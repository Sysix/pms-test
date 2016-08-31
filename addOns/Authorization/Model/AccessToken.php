<?php

namespace AddOn\Authorization\Model;

use PmsOne\Sql\DataObject;
use Spot\EntityInterface;
use Spot\MapperInterface;

class AccessToken extends DataObject
{
    protected static $table = 'oauth_access_tokens';

    public static function fields()
    {
        return [
            'access_token' => [
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
            'client' => $mapper->belongsTo($entity, 'AddOn\Authorization\Model\Client', 'client_id'),
            'user' => $mapper->belongsTo($entity, 'AddOn\Authorization\Model\User', 'user_id')
        ];
    }
}