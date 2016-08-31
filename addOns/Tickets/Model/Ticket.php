<?php

namespace AddOn\Tickets\Model;

use PmsOne\Sql\DataObject;

class Ticket extends DataObject
{
    protected static $table = 'tickets';

    public static function fields()
    {
        return [
            'id' => [
                'type' => 'integer',
                'primary' => true,
                'autoincrement' => true
            ],
            'title' => [
                'type' => 'string'
            ]
        ];
    }
}