<?php

namespace AddOn\Authorization\Controller\Admin;

use AddOn\Authorization\Model\Scope;
use PmsOne\Form\Elements\Input;
use PmsOne\Form\Elements\Radio;
use PmsOne\Form\Form;
use PmsOne\Page\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class Scopes extends Controller
{
    public function index()
    {
        $view = $this->getView();
        $view->setTemplate('@authorization/oauth2/scopes.twig');

        $scopes = Scope::getMapper()->all();

        $view->addVar('messages', $this->getMessages()->getView());
        $view->addVar('scopes', $scopes);

        return $view->render();
    }

    public function create(Request $request, Response $response)
    {
        global $app;

        $params = $request->getParams();
        unset($params['save']);

        $subResponse = $app->subRequest('PUT', '/oauth2/scopes', http_build_query($params), [], [], '', new Response());

        if ($subResponse->getStatusCode() != 200) {
            $this->getMessages()->addMessage('error', 'Scope could not be created');
        } else {
            $this->getMessages()->addMessage('success', 'Scope was successful created');
        }

        return $response->withRedirect($this->getRouter()->pathFor('admin.oauth2.scopes'), 302);
    }

    public function delete(Request $request, Response $response, $args)
    {
        global $app;

        $subResponse = $app->subRequest('DELETE', 'oauth2/scopes/' . $args['scope'], '', [], [], '', new Response());

        if ($subResponse->getStatusCode() != 200) {
            $this->getMessages()->addMessage('error', 'Scope could not be deleted');
        } else {
            $this->getMessages()->addMessage('success', 'Scope was successful deleted');
        }

        return $response->withRedirect($this->getRouter()->pathFor('admin.oauth2.scopes'), 302);

    }

    public function update(Request $request, Response $response, $args)
    {
        global $app;

        $params = $request->getParams();
        unset($params['save']);

        $subResponse = $app->subRequest('POST', 'oauth2/scopes/' . $args['scope'], http_build_query($params), [], [], '', new Response());

        if ($subResponse->getStatusCode() != 200) {
            $this->getMessages()->addMessage('error', 'Scope could not be updated');
        } else {
            $this->getMessages()->addMessage('success', 'Scope was successful updated');
        }

        return $response->withRedirect($this->getRouter()->pathFor('admin.oauth2.scopes'), 302);

    }

    public function add()
    {
        $form = new Form($this->getRouter()->pathFor('admin.oauth2.scopes'));

        $form->addElement(new Input('scope'))
            ->addAttribute('type', 'text')
            ->setLabel('Name');

        $form->addElement(new Radio('is_default', 0))
            ->addOption('Ja', 1)
            ->addOption('Nein', 0)
            ->setLabel('Default');

        $view = $this->getView();
        $view->setTemplate('@authorization/oauth2/scopes.form.twig');
        $view->addVar('form', $form);

        return $view->render();
    }

    public function edit(Request $request, Response $response, $args)
    {
        $scope = Scope::getMapper()->first([
            'scope' => $args['scope']
        ]);

        $form = new Form($this->getRouter()->pathFor('admin.oauth2.scopes.update', [
            'scope' => $args['scope']
        ]));

        $form->addElement(new Input('scope', $scope->scope))
            ->addAttribute('type', 'text')
            ->setLabel('Name');

        $form->addElement(new Radio('is_default', $scope->is_default))
            ->addOption('Ja', 1)
            ->addOption('Nein', 0)
            ->setLabel('Default');

        $view = $this->getView();
        $view->setTemplate('@authorization/oauth2/scopes.form.twig');
        $view->addVar('form', $form);
        return $view->render();
    }
}