<?php

namespace AddOn\Authorization\Model;

use PmsOne\Sql\DataObject;
use Spot\EntityInterface;
use Spot\MapperInterface;

class Jwt extends DataObject
{
    protected static $table = 'oauth_jwt';

    public static function fields()
    {
        return [
            'client_id' => [
                'type' => 'string',
                'length' => 80,
                'primary' => true
            ],
            'subject' => [
                'type' => 'string',
                'length' => 80
            ],
            'public_key' => [
                'type' => 'string',
                'length' => 2000
            ]
        ];
    }

    public static function relations(MapperInterface $mapper, EntityInterface $entity)
    {
        return [
            'client' => $mapper->hasOne($entity, 'AddOn\Authorization\Model\Client', 'client_id'),
        ];
    }
}