<?php

namespace AddOn\Authorization\Controller;

use AddOn\Authorization\Model\Client as ClientModel;
use PmsOne\Page\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class Client extends Controller
{
    public function get(Request $request, Response $response)
    {
        try {
            if ($request->getParam('client_id')) {
                $entity = ClientModel::getMapper()->where([
                    'client_id' => $request->getParam('client_id')
                ])->first();

                if (!$entity) {
                    throw new \Exception('no client with client_id ' . $request->getParam('client_id') . ' found');
                }
            } else {
                $entity = ClientModel::getMapper()->all();
            }


            return $entity;
        } catch (\Exception $e) {

            return $response->withStatus(400)->withJson([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function update(Request $request, Response $response, array $args)
    {
        try {
            $entity = ClientModel::getMapper()->where([
                'client_id' => $args['client_id']
            ])->first();

            if (!$entity) {
                throw new \Exception('no client with client_id ' . $args['client_id'] . ' found');
            }

            /** @var \Spot\EntityInterface $entity */
            $params = $request->getParams();
            if (is_array($params['scope'])) {
                $params['scope'] = implode(' ', $params['scope']);
            }
            $entity->data($params);

            ClientModel::getMapper()->update($entity);

            return $response->withStatus(200)->withJson($entity);
        } catch (\Exception $e) {

            return $response->withStatus(400)->withJson([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function create(Request $request, Response $response)
    {
        try {
            $params = $request->getParams();
            if (is_array($params['scope'])) {
                $params['scope'] = implode(' ', $params['scope']);
            }
            $entity = ClientModel::getMapper()->create($params);

            return $response->withStatus(200)->withJson($entity);
        } catch (\Exception $e) {

            return $response->withStatus(400)->withJson([
                'error' => $e->getMessage()
            ]);
        }
    }


    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     *
     * @noinspection PhpUnusedParameterInspection
     */
    public function delete(Request $request, Response $response, array $args)
    {
        try {
            $entity = ClientModel::getMapper()->delete([
                'client_id' => $args['client_id']
            ]);

            return $response->withStatus(200)->withJson($entity);
        } catch (\Exception $e) {

            return $response->withStatus(400)->withJson([
                'error' => $e->getMessage()
            ]);
        }
    }
}