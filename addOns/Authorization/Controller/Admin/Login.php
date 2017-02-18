<?php

namespace AddOn\Authorization\Controller\Admin;

use OAuth2\Request;
use OAuth2\Server;
use PmsOne\Form\Elements\Input;
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

        $form->addElement(new Input('username'))
            ->addAttribute('placeholder', 'Username')
            ->addAttribute('autofocus')
            ->addAttribute('type', 'hidden')
            ->setLabel('Username');

        $form->addElement(new Input('password'))
            ->addAttribute('placeholder', 'password')
            ->addAttribute('type', 'password')
            ->setLabel('Password');

        $form->addElement(new Input('grant_type', 'password'))
            ->addAttribute('type', 'hidden');

        $form
            ->removeActionButton('back')
            ->removeActionButton('save-back');

        $form->getActionButton('save')->setValue('Absenden');

        $this->getView()->addVar('form', $form);
        $this->getView()->addVar('messages', $this->getMessages()->getView());

        return $this->getView()->setTemplate('@authorization/login.twig')->render();
    }
}