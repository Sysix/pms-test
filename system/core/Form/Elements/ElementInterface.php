<?php

namespace PmsOne\Form\Elements;

use PmsOne\Html\AttributesInterface;

interface ElementInterface extends AttributesInterface
{

    public function __construct($name, $value = '');

    public function setName($name);
    public function getName();

    public function setValue($value);
    public function getValue();

    public function setViewTemplate($view);
    public function getViewTemplate();

    public function render();
}