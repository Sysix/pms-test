<?php

namespace PmsOne\Form\Elements;


use PmsOne\Form\Elements\Type\MultiValue;

class Radio extends MultiValue
{
    /**
     * @param $name
     * @param $value
     * @param array $attributes
     * @return $this
     */
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
            ->setTemplate('form/radio.twig')
            ->addVar('options', $this->getOptions())
            ->render();
    }
}