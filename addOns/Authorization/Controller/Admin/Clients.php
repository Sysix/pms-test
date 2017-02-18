<?php

namespace AddOn\Authorization\Controller\Admin;

use AddOn\Authorization\Model\Client;
use AddOn\Authorization\Model\Scope;
use PmsOne\Form\Elements\Input;
use PmsOne\Form\Elements\Select;
use PmsOne\Form\Form;
use PmsOne\Page\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class Clients extends Controller
{
    public function index()
    {
        $view = $this->getView();
        $view->setTemplate('@authorization/oauth2/clients.twig');

        $clientsModel = new Client();
        $clients = $clientsModel->getMapper()->all()->with('user')->limit(10);

        $view->addVar('messages', $this->getMessages()->getView());
        $view->addVar('clients', $clients);

        return $view->render();
    }

    public function create(Request $request, Response $response)
    {
        global $app;

        $params = $request->getParams();
        unset($params['save']);

        $subResponse = $app->subRequest('PUT', '/oauth2/clients', http_build_query($params), [], [], '', new Response());

        if ($subResponse->getStatusCode() != 200) {
            $this->getMessages()->addMessage('error', 'Client could not be created');
        } else {
            $this->getMessages()->addMessage('success', 'Client was successful created');
        }

        return $response->withRedirect($this->getRouter()->pathFor('admin.oauth2.clients'), 302);
    }

    public function delete(Request $request, Response $response, $args)
    {
        global $app;

        $subResponse = $app->subRequest('DELETE', 'oauth2/clients/' . $args['client_id'], '', [], [], '', new Response());

        if ($subResponse->getStatusCode() != 200) {
            $this->getMessages()->addMessage('error', 'Client could not be deleted');
        } else {
            $this->getMessages()->addMessage('success', 'Client was successful deleted');
        }

        return $response->withRedirect($this->getRouter()->pathFor('admin.oauth2.clients'), 302);

    }

    public function update(Request $request, Response $response, $args)
    {
        global $app;

        $params = $request->getParams();
        unset($params['save']);

        $subResponse = $app->subRequest('POST', 'oauth2/clients/' . $args['client_id'], http_build_query($params), [], [], '', new Response());

        if ($subResponse->getStatusCode() != 200) {
            $this->getMessages()->addMessage('error', 'Client could not be updated');
        } else {
            $this->getMessages()->addMessage('success', 'Client was successful updated');
        }

        return $response->withRedirect($this->getRouter()->pathFor('admin.oauth2.clients'), 302);

    }

    public function add()
    {
        $form = new Form($this->getRouter()->pathFor('admin.oauth2.clients'));

        $form->addElement(new Select('user_id'))
            ->addOption('admin', '1')
            ->setLabel('User');

        $form->addElement(new Input('client_id'))
            ->addAttribute('type', 'text')
            ->setLabel('Client ID');

        $form->addElement(new Input('client_secret'))
            ->addAttribute('type', 'password')
            ->setLabel('Password');

        $form->addElement(new Input('redirect_uri'))
            ->addAttribute('type', 'url')
            ->setLabel('Redirect URL');

        $form->addElement(new Input('grant_types'))
            ->addAttribute('type', 'text')
            ->setLabel('Grant Types');

        $scopeModel = new Scope();

        $scopeElement = $form->addElement(new Select('scope'))
            ->setMultiple()
            ->setViewTemplate('@authorization/form/scope-select.twig')
            ->setLabel('Access');

        $scopes = $scopeModel->getMapper()->select();
        foreach ($scopes as $scope) {
            $scopeElement->addOption($scope->scope, $scope->scope);
        }

        $view = $this->getView();
        $view->setTemplate('@authorization/oauth2/clients.form.twig');
        $view->addVar('form', $form);

        return $view->render();
    }

    public function edit(Request $request, Response $response, $args)
    {
        $clientMapper = new Client();

        $client = $clientMapper->getMapper()->first([
            'client_id' => $args['client_id']
        ]);

        $form = new Form($this->getRouter()->pathFor('admin.oauth2.clients.update', [
            'client_id' => $args['client_id']
        ]));

        $form->addElement(new Select('user_id'))
            ->addOption('admin', '1')
            ->setLabel('User');

        $form->addElement(new Input('client_id', $client->client_id))
            ->addAttribute('type', 'text')
            ->addAttribute('disabled')
            ->setLabel('Client ID');

        $form->addElement(new Input('client_secret', $client->client_secret))
            ->addAttribute('type', 'password')
            ->setLabel('Password');

        $form->addElement(new Input('redirect_uri', $client->redirect_uri))
            ->addAttribute('type', 'url')
            ->setLabel('Redirect URL');

        $form->addElement(new Input('grant_types', $client->grant_types))
            ->addAttribute('type', 'text')
            ->setLabel('Grant Types');

        $scopeModel = new Scope();

        $scopeElement = $form->addElement(new Select('scope', explode(' ', $client->scope)))
            ->setMultiple()
            ->setViewTemplate('@authorization/form/scope-select.twig')
            ->setLabel('Access');

        $scopes = $scopeModel->getMapper()->select();
        foreach ($scopes as $scope) {
            $scopeElement->addOption($scope->scope, $scope->scope);
        }

        $view = $this->getView();
        $view->setTemplate('@authorization/oauth2/clients.form.twig');
        $view->addVar('form', $form);

        return $view->render();
    }
}