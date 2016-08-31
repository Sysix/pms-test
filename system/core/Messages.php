<?php

namespace PmsOne;

class Messages extends \Slim\Flash\Messages
{
    public function getView()
    {
        $view = new View();
        $view->setTemplate('bootstrap/messages.twig');
        $view->addVar('success', $this->getMessage('success'));
        $view->addVar('error', $this->getMessage('error'));
        $view->addVar('info', $this->getMessage('info'));

        return $view->render();
    }
}