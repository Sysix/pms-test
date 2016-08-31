<?php

namespace AddOn\Authorization\Model;

use PmsOne\Sql\DataObject;

class Scope extends DataObject
{
    protected static $table = 'oauth_scopes';

    public static function fields()
    {
        return [
            'scope' => [
                'type' => 'text'
            ],
            'is_default' => [
                'type' => 'boolean'
            ]
        ];
    }
}