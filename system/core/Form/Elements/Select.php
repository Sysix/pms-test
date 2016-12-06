<?php

namespace PmsOne\Form\Elements;


use PmsOne\Form\Elements\Type\MultiValue;

class Select extends MultiValue
{
    /**
     * @param bool $multiple
     * @return $this
     */
    public function setMultiple($multiple = true)
    {
        if ($multiple) {
            $this->addAttribute('multiple');
        } else {
            $this->deleteAttribute('multiple');
        }

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @param array $attributes
     * @return $this
     */
    public function addOption($name, $value, $attributes = [])
    {
        $attributes['value'] = $value;

        if ($this->isValue($value)) {
            $attributes['selected'] = '';
        }

        return parent::addOption($name, $value, $attributes);
    }

    /**
     * @return string
     */
    public function render()
    {
        if ($this->getAttribute('multiple')) {
            $this->addAttribute('name', $this->getAttribute('name') . '[]');
        }

        $this
            ->addAttribute('name', $this->getName());

        return $this->view
            ->setTemplate('form/select.twig')
            ->addVar('label', $this->getName())
            ->addVar('options', $this->getOptions())
            ->addVar('attributes', $this->getAttributes())
            ->render();
    }
}