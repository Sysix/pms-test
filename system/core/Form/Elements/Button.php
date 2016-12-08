<?php

namespace PmsOne\Form\Elements;

use PmsOne\Form\Elements\Type\SingleValue;

class Button extends SingleValue
{
    public $viewTemplate = 'form/button.twig';

    public function __construct($name, $value)
    {
        parent::__construct($name, $value);

        $this
            ->removeClass('form-control')
            ->addClass('btn');
    }

    public function render()
    {
        $this->addAttribute('name', $this->getName());

        return $this->view
            ->setTemplate($this->viewTemplate)
            ->addVar('label', $this->getName())
            ->addVar('value', $this->getValue())
            ->addVar('attributes', $this->getAttributes())
            ->render();
    }
}