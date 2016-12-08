<?php

namespace PmsOne\Form\Elements;


use PmsOne\Form\Elements\Type\SingleValue;

class Input extends SingleValue
{
    public $viewTemplate = 'form/input.twig';

    public function render()
    {
        $this
            ->addAttribute('name', $this->getName())
            ->addAttribute('value', $this->getValue());

        return $this->view
            ->setTemplate($this->viewTemplate)
            ->addVar('label', $this->getName())
            ->addVar('attributes', $this->getAttributes())
            ->render();
    }
}