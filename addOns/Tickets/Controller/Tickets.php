<?php

namespace AddOn\Tickets\Controller;

use PmsOne\Form\Elements\Input;
use PmsOne\Form\Elements\Select;
use PmsOne\Form\Form;
use PmsOne\Page\Controller;
use AddOn\Tickets\Model\Ticket as TicketModel;
use Slim\Http\Request;
use Slim\Http\Response;

class Tickets extends Controller
{
    public function indexGet(Request $request, Response $response)
    {
        $ticketsModel = new TicketModel();

        $tickets = $ticketsModel->getMapper()->all();

        if ($tickets->count()) {
            return $response->withJson($tickets, 200);
        } else {
            return $response->withJson(array(
                'status' => false,
                'message' => 'no tickets found'
            ), 404);
        }
    }

    public function add()
    {
        $form = new Form($this->getUri()->withPath('/tickets/save'));

        $form->addElement(new Input('title'))
            ->addAttribute('type', 'text')
            ->setLabel('Titel');

        $form->addElement(new Select('milestone'))
            ->addOption('option1', 'option1')
            ->addOption('option2', 'option2')
            ->setLabel('Milestone');


        return $form->render();
    }
}