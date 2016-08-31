<?php

namespace PmsOne\Helper;


class Dir
{
    const DS = DIRECTORY_SEPARATOR;

    static $path;

    public static function setUpPath($path)
    {
        static::$path = realpath($path);
    }

    public static function root($file = '')
    {
        return static::$path . static::DS . $file;
    }

    public static function system($file = '')
    {
        return static::root('system' . static::DS . $file);
    }

    public static function cache($file = '')
    {
        return static::system('cache' . static::DS . $file);
    }

    public static function lang($file = '')
    {
        return static::system('langs' . static::DS . $file);
    }

    public static function view($file = '')
    {
        return static::system('views' . static::DS . $file);
    }

    public static function page($file = '')
    {
        return static::system('pages' . static::DS . $file);
    }

    public static function vendor($file = '')
    {
        return static::system('vendor' . static::DS . $file);
    }

    public static function addOn($file = '')
    {
        return static::root('addOns' . static::DS . $file);
    }

    public static function template($file = '')
    {
        return static::root('templates' . static::DS . $file);
    }
}