<?php

namespace AddOn\Authorization\Controller;

use AddOn\Authorization\Model\Authorization as AuthorizationModel;
use OAuth2\Request;
use PmsOne\Page\Controller;
use Slim\Http\Response;

class Authorization extends Controller
{
    /** @var \OAuth2\Server $server */
    protected $server;

    public function init()
    {
        $this->server = $this->getAppContainer()->get('oAuth2Server');;

        return parent::init();
    }

    public function indexPost()
    {
        return $this->server->handleTokenRequest(Request::createFromGlobals())->getResponseBody();
    }

    public function revokePost()
    {
        return $this->server->handleRevokeRequest(Request::createFromGlobals())->getResponseBody();
    }

    public function adminPost(\Slim\Http\Request $request, Response $response)
    {
        $_POST['client_id'] = 'main_application';
        $_POST['client_secret'] = 'client_secret';

        $responseString = $this->server->handleTokenRequest(Request::createFromGlobals())->getResponseBody();
        $responseObject = json_decode($responseString);

        if (isset($responseObject->error)) {
            $this->getMessages()->addMessage('error', $responseObject->error_description);

            return $response->withRedirect(
                $this->getRouter()->urlFor('admin.login')
            );
        }

        AuthorizationModel::saveAdminAccessToSession($responseObject);

        $this->getMessages()->addMessage('success', 'Erfolgreich eingeloggt');

        return $response->withRedirect(
            $this->getRouter()->urlFor('admin.dashboard')
        );

    }

    public function revokeAdminPost()
    {
        return $this->server->handleRevokeRequest(AuthorizationModel::createGlobalRequestWithAccessSession())->getResponseBody();
    }
}