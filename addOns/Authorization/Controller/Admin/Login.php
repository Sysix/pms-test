<?php

namespace AddOn\Authorization\Controller\Admin;

use OAuth2\Request;
use OAuth2\Server;
use PmsOne\Form\Form;
use PmsOne\Page\Controller;
use AddOn\Authorization\Model\Authorization as AuthorizationModel;
use Slim\Http\Response;

class Login extends Controller
{
    public $langSupport = true;

    public function indexGet(\Slim\Http\Request $request, Response $response)
    {
        /** @var Server $server */
        $server = $this->getAppContainer()->get('oAuth2Server');

        $allowed = $server->verifyResourceRequest(AuthorizationModel::createGlobalRequestWithAccessSession());

        if ($allowed) {
            return $response->withRedirect($this->getRouter()->pathFor('admin.dashboard'));
        }

        $form = new Form($this->getRouter()->pathFor('authorization.admin'));

        $form->addTextElement('username', '')
            ->addAttribute('placeholder', 'Username')
            ->addAttribute('autofocus')
            ->setLabel('Username');

        $form->addPasswordElement('password', '')
            ->addAttribute('placeholder', 'password')
            ->setLabel('Password');

        $form->addHiddenElement('grant_type', 'password');

        $form
            ->removeActionButton('back')
            ->removeActionButton('save-back');

        $form->getActionButton('save')->setValue('Absenden');

        $this->getView()->addVar('form', $form);

        return $this->getView()->setTemplate('@authorization/login.twig')->render();
    }
}