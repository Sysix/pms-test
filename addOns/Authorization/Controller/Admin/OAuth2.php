<?php

namespace AddOn\Authorization\Controller\Admin;

use PmsOne\Page\Controller;

class OAuth2 extends Controller
{
    public function indexGet()
    {
        $view = $this->getView();
        $view->setTemplate('@authorization/oauth2/index.twig');

        return $view->render();
    }

}