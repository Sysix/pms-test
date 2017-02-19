<?php

namespace PmsOne\Form\Elements;


use PmsOne\Form\Elements\Type\MultiValue;

class Select extends MultiValue
{
    public $viewTemplate = 'form/select.twig';

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
        $this->addAttribute('name', $this->getName());

        if ($this->hasAttribute('multiple')) {
            $this->addAttribute('name', $this->getName() . '[]');
        }

        return $this->view
            ->setTemplate($this->viewTemplate)
            ->addVar('label', $this->getName())
            ->addVar('options', $this->getOptions())
            ->addVar('attributes', $this->getAttributes())
            ->render();
    }
}