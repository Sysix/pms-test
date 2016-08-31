<?php

namespace AddOn\Authorization\Controller;

use AddOn\Authorization\Model\Client;
use PmsOne\Page\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class OAuth2 extends Controller
{
    public function clientsUpdate(Request $request, Response $response, $args)
    {
        $clients = new Client();

        try {
            $entity = $clients->getMapper()->where([
                'client_id' => $args['client_id']
            ])->first();

            if (!$entity) {
                throw new \Exception('no client with client_id ' . $args['client_id'] . ' found');
            }

            /** @var \Spot\EntityInterface $entity */
            $entity->data($request->getParams());

            $clients->getMapper()->update($entity);

            return $entity;
        } catch (\Exception $e) {

            return $response->withStatus(400)->withJson([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function clientsCreate(Request $request, Response $response)
    {
        $clients = new Client();

        try {
            $params = $request->getParams();
            $entity = $clients->getMapper()->create($params);

            return $entity;
        } catch (\Exception $e) {

            return $response->withStatus(400)->withJson([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function clientsDelete(Request $request, Response $response, $args)
    {
        $clients = new Client();

        try {
            $entity = $clients->getMapper()->delete([
                'client_id' => $args['client_id']
            ]);

            return $entity;
        } catch (\Exception $e) {

            return $response->withStatus(400)->withJson([
                'error' => $e->getMessage()
            ]);
        }
    }
}