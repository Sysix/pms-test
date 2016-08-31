<?php

namespace PmsOne;

use Spot\Locator;

/**
 * Class Sql
 * @package PmsOne
 */
class Sql extends Locator
{
    public function __construct()
    {
        $config = new \Spot\Config();

        parent::__construct($config);
    }

    const DEFAULT_DATABASE = 'default';

    public function setDatabases(array $databases)
    {
        foreach ($databases as $name => $config) {
            $default = false;
            if ($name == static::DEFAULT_DATABASE) {
                $default = true;
            }


            $this->config()->addConnection($name, $config, $default);
        }

        return $this;
    }
}