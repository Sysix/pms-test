<?php

namespace AddOn\Authorization\Model;

use OAuth2\Request;

class Authorization
{
    public static function addAccessSessionToRequest(\Slim\Http\Request $request)
    {
        if (isset($_SESSION['oAuth2'])) {
            $request = $request->withQueryParams(array_merge($request->getQueryParams(), $_SESSION['oAuth2']));
        }

        return $request;
    }

    public static function createGlobalRequestWithAccessSession()
    {
        $response = Request::createFromGlobals();
        if (isset($_SESSION['oAuth2'])) {
            $response->query = array_merge($response->query, $_SESSION['oAuth2']);
        }

        return $response;
    }

    public static function saveAdminAccessToSession($response)
    {
        $_SESSION['oAuth2'] = [];
        foreach ($response as $key => $value) {
            $_SESSION['oAuth2'][$key] = $value;
        }
    }
}