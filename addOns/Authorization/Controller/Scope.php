<?php

namespace AddOn\Authorization\Controller;

use AddOn\Authorization\Model\Scope as ScopeModel;
use PmsOne\Page\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class Scope extends Controller
{
    public function get(Request $request, Response $response, $args)
    {
        $scopes = new ScopeModel();

        try {
            if ($request->getParam('scope')) {
                $entity = $scopes->getMapper()->where([
                    'scope :like' => '%' . $request->getParam('scope') . '%'
                ]);

                if (!$entity) {
                    throw new \Exception('no scope with name ' . $request->getParam('scope') . ' found');
                }
            } else {
                $entity = $scopes->getMapper()->all();
            }

            return $response->withJson($entity->jsonSerialize());
        } catch (\Exception $e) {

            return $response->withStatus(400)->withJson([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, Response $response, $args)
    {
        $scopes = new ScopeModel();

        try {
            $entity = $scopes->getMapper()->where([
                'scope' => $args['scope']
            ])->first();

            if (!$entity) {
                throw new \Exception('no scope with name ' . $args['scope'] . ' found');
            }

            /** @var \Spot\EntityInterface $entity */
            $entity->data($request->getParams());

            $scopes->getMapper()->update($entity);

            return $entity;
        } catch (\Exception $e) {

            return $response->withStatus(400)->withJson([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function create(Request $request, Response $response)
    {
        $clients = new ScopeModel();

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
        $clients = new ScopeModel();

        try {
            $entity = $clients->getMapper()->delete([
                'scope' => $args['scope']
            ]);

            return $entity;
        } catch (\Exception $e) {

            return $response->withStatus(400)->withJson([
                'error' => $e->getMessage()
            ]);
        }
    }
}