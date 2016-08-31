<?php

namespace AddOn\Dashboard\Controller\Admin;

use AddOn\Authorization\Model\Client;
use OAuth2\Request;
use PmsOne\Page\Controller;

class Dashboard extends Controller
{
    public function indexGet()
    {

        $view = $this->getView();

        $view->setTemplate('@dashboard/dashboard.twig');

        $apiKeys = new Client();
        $apiKeys = $apiKeys->getMapper()->all();

        $view->addVar('apiKeys', $apiKeys);

        return $view->render();
    }
}