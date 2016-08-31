<?php

namespace PmsOne\Form\Elements;


use PmsOne\Form\Elements\Type\SingleValue;

class Textarea extends SingleValue
{

    public function render()
    {
        $this
            ->addAttribute('name', $this->getName());

        return $this->view
            ->setTemplate('form/textarea.twig')
            ->addVar('label', $this->getName())
            ->addVar('value', $this->getValue())
            ->addVar('attributes' , $this->getAttributes())
            ->render();
    }
}