<?php

namespace PmsOne\Form\Elements;


use PmsOne\Form\Elements\Type\SingleValue;

class Textarea extends SingleValue
{
    public $viewTemplate = 'form/textarea.twig';

    public function render()
    {
        $this
            ->addAttribute('name', $this->getName());

        return $this->view
            ->setTemplate($this->viewTemplate)
            ->addVar('label', $this->getName())
            ->addVar('value', $this->getValue())
            ->addVar('attributes', $this->getAttributes())
            ->render();
    }
}