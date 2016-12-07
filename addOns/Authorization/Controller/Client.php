<?php

namespace AddOn\Authorization\Controller;

use AddOn\Authorization\Model\Client as ClientModel;
use PmsOne\Page\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class Client extends Controller
{
    public function get(Request $request, Response $response, $args)
    {
        $clients = new ClientModel();

        try {
            if (isset($args['client_id'])) {
                $entity = $clients->getMapper()->where([
                    'client_id' => $args['client_id']
                ])->first();

                if (!$entity) {
                    throw new \Exception('no client with client_id ' . $args['client_id'] . ' found');
                }
            } else {
                $entity = $clients->getMapper()->all();
            }


            return $entity;
        } catch (\Exception $e) {

            return $response->withStatus(400)->withJson([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, Response $response, $args)
    {
        $clients = new ClientModel();

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

    public function create(Request $request, Response $response)
    {
        $clients = new ClientModel();

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

    public function delete(Request $request, Response $response, $args)
    {
        $clients = new ClientModel();

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