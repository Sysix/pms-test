<?php

namespace AddOn\Authorization\Model;

use OAuth2\Request;

class Authorization
{
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
        $data = json_decode($response);
        $_SESSION['oAuth2'] = [];
        foreach ($data as $key => $value) {
            $_SESSION['oAuth2'][$key] = $value;
        }
    }
}