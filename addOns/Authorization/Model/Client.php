<?php

namespace AddOn\Authorization\Model;

use PmsOne\Sql\DataObject;
use Spot\EntityInterface;
use Spot\MapperInterface;

class Client extends DataObject
{
    protected static $table = 'oauth_clients';

    public static function fields()
    {
        return [
            'client_id' => [
                'type' => 'string',
                'length' => 80,
                'primary' => true
            ],
            'client_secret' => [
                'type' => 'string',
                'length' => 80
            ],
            'redirect_uri' => [
                'type' => 'string',
                'length' => 2000
            ],
            'grant_types' => [
                'type' => 'string',
                'length' => 80
            ],
            'scope' => [
                'type' => 'string',
                'length' => 100
            ],
            'user_id' => [
                'type' => 'integer',
                'length' => 80
            ]
        ];
    }

    public static function relations(MapperInterface $mapper, EntityInterface $entity)
    {
        return [
            'user' => $mapper->belongsTo($entity, 'AddOn\Authorization\Model\User', 'user_id')
        ];
    }
}