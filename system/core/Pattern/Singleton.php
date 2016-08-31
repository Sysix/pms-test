<?php

namespace PmsOne\Pattern;

/**
 * Class Singleton
 * @package PmsOne\Pattern
 */
trait Singleton
{
    protected static $instance = null;

    protected function __construct()
    {
    }

    private final function __clone()
    {
    }

    protected function init()
    {
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
            static::$instance->init();
        }

        return static::$instance;
    }
}