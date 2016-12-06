<?php

$container = $app->getContainer();
$sql = $container['db'];

$storage = new \AddOn\Authorization\Storage\Pdo($sql->config()->defaultConnection()->getWrappedConnection());

$server = new \OAuth2\Server(
    $storage,
    [],
    [
        new \OAuth2\GrantType\ClientCredentials($storage)
    ]
);

// set oAuth2 Storage
$container['oAuth2Storage'] = function () use ($storage) {
    return $storage;
};

// set oAuth2 Server
$container['oAuth2Server'] = function () use ($server) {
    return $server;
};


$server->addGrantType(new \OAuth2\GrantType\ClientCredentials($storage));
$server->addGrantType(new \OAuth2\GrantType\AuthorizationCode($storage));
$server->addGrantType(new \OAuth2\GrantType\UserCredentials($storage));

// create the authorization middleware
$authMiddleware = new \AddOn\Authorization\Middleware\Authorization($server, $container);

$container['oAuthMiddleware'] = function () use ($authMiddleware) {
    return $authMiddleware;
};
/*
$users = new \AddOn\Authorization\Model\User();
$users->createUser([
    'username' => 'admin',
    'password' => 'password',
    'first_name' => 'Alexander',
    'last_name' => 'Schlegel',
    'email' => 'sysix@sysix-coding.de',
]);
*/

/*
$clients = new \AddOn\Authorization\Model\Client();
$clients->getMapper()->create([
    'user_id' => 1,
    'client_id' => 'main_application',
    'client_secret' => 'client_secret'
]);
*/

$navigation = \PmsOne\Navigation::getNavigation('main-navigation');

$oauth2 = new \PmsOne\Navigation\Point('oAuth2', '/admin/oauth2');
$oauth2->addSubPoint(new \PmsOne\Navigation\Point('Clients', '/admin/oauth2/clients'));
$oauth2->addSubPoint(new \PmsOne\Navigation\Point('Scopes', '/admin/oauth2/scopes'));
$oauth2->addSubPoint(new \PmsOne\Navigation\Point('Users', '/admin/oauth2/users'));

$navigation->addPoint($oauth2);


$app
    ->get('/admin/login', 'AddOn\\Authorization\\Controller\\Admin\\Login:indexGet')
    ->setName('admin.login');

$app
    ->get('/admin/oauth2', 'AddOn\\Authorization\\Controller\\Admin\\OAuth2:indexGet')
    ->setName('admin.oauth2')
    ->add($authMiddleware->withRequiredScope(['loggedIn']));

$app
    ->get('/admin/oauth2/clients', 'AddOn\\Authorization\\Controller\\Admin\\OAuth2:clientsGet')
    ->setName('admin.oauth2.clients')
    ;

$app
    ->get('/admin/oauth2/clients/add', 'AddOn\\Authorization\\Controller\\Admin\\OAuth2:clientsAddGet')
    ->setName('admin.oauth2.clients.add')
    ;

$app->get('/admin/oauth2/clients/delete/{client_id}', 'AddOn\\Authorization\\Controller\\Admin\\OAuth2:clientsDelete')->setName('admin.oauth2.clients.delete')->add($authMiddleware->withRequiredScope(['loggedIn']));
$app->get('/admin/oauth2/clients/edit/{client_id}', 'AddOn\\Authorization\\Controller\\Admin\\OAuth2:clientsEditGet')->setName('admin.oauth2.clients.edit')->add($authMiddleware->withRequiredScope(['loggedIn']));

$app->post('/admin/oauth2/clients', 'AddOn\\Authorization\\Controller\\Admin\\OAuth2:clientsCreate');
$app->post('/admin/oauth2/clients/{client_id}', 'AddOn\\Authorization\\Controller\\Admin\\OAuth2:clientsUpdate')->setName('admin.oauth2.clients.update');


$app->post('/authorization', 'AddOn\\Authorization\\Controller\\Authorization:indexPost')->setName('authorization');
$app->post('/authorization/admin', 'AddOn\\Authorization\\Controller\\Authorization:adminPost')->setName('authorization.admin');
$app->post('/authorization/revoke', 'AddOn\\Authorization\\Controller\\Authorization:revokePost')->setName('authorization.revoke');
$app->post('/admin/logout', 'AddOn\\Authorization\\Controller\\Authorization:revokeAdminPost')->setName('admin.logout');

$app->put('/oauth2/clients', 'AddOn\\Authorization\\Controller\\Client:create')->setName('oauth2.clients.create');
$app->delete('/oauth2/clients/{client_id}', 'AddOn\\Authorization\\Controller\\Client:delete')->setName('oauth2.clients.delete');
$app->post('/oauth2/clients/{client_id}', 'AddOn\\Authorization\\Controller\\Client:update')->setName('oauth2.clients.update');