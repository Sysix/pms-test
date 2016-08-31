<?php

namespace PmsOne\Helper;


use PmsOne\View;

class Bootstrap extends View
{

    public function renderPanel()
    {
        $this->setTemplate('bootstrap/panel.twig');

        return $this->render();
    }
}