<?php

namespace PmsOne;

use PmsOne\Html\Attributes;
use PmsOne\Navigation\Point;

class Navigation
{
    use Attributes;

    protected static $navigations = [];

    protected $name;

    protected $options = [];

    protected $points = [];

    public function __construct($name, array $options = [])
    {
        $this->name = $name;

        $this->options = $options;

        static::addToGlobal($this);
    }

    public function getName()
    {
        return $this->name;
    }


    public function addPoint(Point $point)
    {
        $this->points[] = $point;

        return $this;
    }

    public function render()
    {
        $view = new View();

        $view->setTemplate('navigation/navigation.twig');
        $view->addVar('points', $this->points);
        $view->addVar('attributes', $this->attributes);

        return $view->render();
    }

    protected static function addToGlobal(Navigation $navigation)
    {
        static::$navigations[$navigation->getName()] = $navigation;
    }

    /**
     * @param $name
     * @return self
     * @throws \Exception
     */
    public static function getNavigation($name)
    {
        if (!isset(static::$navigations[$name])) {
            throw new \Exception('navigation ' . $name . ' not found');
        }

        return static::$navigations[$name];
    }
}