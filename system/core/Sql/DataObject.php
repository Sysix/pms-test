<?php

namespace PmsOne\Sql;

use PmsOne\Sql;
use Spot\Entity;
use Spot\Mapper;

class DataObject extends Entity
{
    /** @var  Sql $db */
    protected static $db;

    public static function getMapper()
    {
        return new Mapper(static::getDb(), get_called_class());
    }

    public static function getDb()
    {
        return static::$db;
    }

    public static function setDb($db)
    {
        static::$db = $db;
    }
}