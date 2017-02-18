<?php

namespace PmsOne\Form\Elements;


use PmsOne\Form\Elements\Type\MultiValue;

class Radio extends MultiValue
{
    public $viewTemplate = 'form/radio.twig';

    public function addOption($name, $value, $attributes = [])
    {
        $attributes['value'] = $value;
        $attributes['type'] = 'radio';
        $attributes['name'] = $this->getName();

        if ($value == $this->getValue()) {
            $attributes['checked'] = '';
        }

        return parent::addOption($name, $value, $attributes);
    }

    public function render()
    {
        return $this->view
            ->setTemplate($this->viewTemplate)
            ->addVar('options', $this->getOptions())
            ->render();
    }
}