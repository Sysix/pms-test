<?php

namespace PmsOne\Navigation;

use PmsOne\View;

class Point
{
    protected $label;

    protected $link;

    protected $subPoints = [];

    protected $wrapperAttributes;

    protected $attributes;

    public function __construct($label, $link)
    {
        $this->label = $label;

        $this->wrapperAttributes = new Attributes();
        $this->attributes = new Attributes();

        $this->setLink($link);
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;

        $this->getAttributes()->addAttribute('href', $link);

        return $this;
    }

    public function addSubPoint(Point $point)
    {
        $this->subPoints[] = $point;
    }

    public function getWrapperAttributes()
    {
        return $this->wrapperAttributes;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function render()
    {
        $view = new View();

        $view->addVar('attributes', $this->getAttributes()->getAttributes());
        $view->addVar('wrapperAttributes', $this->getAttributes()->getAttributes());
        $view->addVar('label', $this->label);
        $view->addVar('subPoints', $this->subPoints);
        
        $view->setTemplate('navigation/point.twig');

        return $view->render();
    }
}