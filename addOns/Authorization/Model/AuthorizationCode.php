<?php

namespace AddOn\Authorization\Model;


use PmsOne\Sql\DataObject;
use Spot\EntityInterface;
use Spot\MapperInterface;

class AuthorizationCode extends DataObject
{
    protected static $table = 'oauth_authorization_codes';

    public static function fields()
    {
        return [
            'authorization_code' => [
                'type' => 'string',
                'length' => 40,
                'primary' => true
            ],
            'client_id' => [
                'type' => 'string',
                'length' => 80
            ],
            'user_id' => [
                'type' => 'integer',
                'length' => 80
            ],
            'redirect_uri' => [
                'type' => 'string',
                'length' => 200,
            ],
            'expires' => [
                'type' => 'datetime'
            ],
            'scope' => [
                'type' => 'string',
                'length' => 2000
            ]
        ];
    }

    public static function relations(MapperInterface $mapper, EntityInterface $entity)
    {
        return [
            'client' => $mapper->hasOne($entity, 'AddOn\Authorization\Model\Client', 'client_id'),
            'user' => $mapper->hasOne($entity, 'AddOn\Authorization\Model\User', 'user_id')
        ];
    }
}