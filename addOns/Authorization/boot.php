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
    ->post('/admin/logout', 'AddOn\\Authorization\\Controller\\Authorization:revokeAdminPost')
    ->setName('admin.logout');


$app->group('/admin/oauth2/clients', function () {
    $controller = 'AddOn\\Authorization\\Controller\\Admin\\Clients';

    $this
        ->map(['GET'], '', $controller . ':index')
        ->setName('admin.oauth2.clients');

    $this
        ->get('/add', $controller . ':add')
        ->setName('admin.oauth2.clients.add');

    $this
        ->get('/delete/{client_id}', $controller . ':delete')
        ->setName('admin.oauth2.clients.delete');

    $this
        ->get('/edit/{client_id}', $controller . ':edit')
        ->setName('admin.oauth2.clients.edit');

    $this
        ->map(['POST'], '', $controller . ':create')
        ->setName('admin.oauth2.clients.create');

    $this
        ->post('/{client_id}', $controller . ':update')
        ->setName('admin.oauth2.clients.update');
});

$app->group('/authorization', function () {
    $controller = 'AddOn\\Authorization\\Controller\\Authorization';

    $this
        ->map(['POST'], '', $controller . ':indexPost')
        ->setName('authorization');

    $this
        ->post('/admin', $controller . ':adminPost')
        ->setName('authorization.admin');

    $this
        ->post('/revoke', $controller . ':revokePost')
        ->setName('authorization.revoke');
});

$app->group('/oauth2/clients', function () {
    $controller = 'AddOn\\Authorization\\Controller\\Client';

    $this
        ->map(['GET'], '', $controller . ':get')
        ->setName('oauth2.clients.get');

    $this
        ->map(['PUT'], '', $controller . ':create')
        ->setName('oauth2.clients.create');

    $this
        ->delete('/{client_id}', $controller . ':delete')
        ->setName('oauth2.clients.delete');

    $this
        ->post('/{client_id}', $controller . ':update')
        ->setName('oauth2.clients.update');
});

$app->group('/oauth2/scopes', function () {
    $controller = 'AddOn\\Authorization\\Controller\\Scope';

    $this
        ->map(['GET'], '', $controller . ':get')
        ->setName('oauth2.scopes.get');

    $this
        ->map(['PUT'], '', $controller . ':create')
        ->setName('oauth2.scopes.create');

    $this
        ->delete('/{scope}', $controller . ':delete')
        ->setName('oauth2.scopes.delete');

    $this
        ->post('/{scope}', $controller . ':update')
        ->setName('oauth2.scopes.update');
});