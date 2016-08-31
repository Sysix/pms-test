<?php

namespace PmsOne\Form\Elements;


use PmsOne\Form\Elements\Type\SingleValue;

class Input extends SingleValue
{

    public function render()
    {
        $this
            ->addAttribute('name', $this->getName())
            ->addAttribute('value', $this->getValue());

        return $this->view
            ->setTemplate('form/input.twig')
            ->addVar('label', $this->getName())
            ->addVar('attributes' , $this->getAttributes())
            ->render();
    }
}