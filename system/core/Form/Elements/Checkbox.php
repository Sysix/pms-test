<?php
/**
 * Created by PhpStorm.
 * User: Sysix
 * Date: 12.09.2015
 * Time: 23:41
 */

namespace PmsOne\Form\Elements;


use PmsOne\Form\Elements\Type\MultiValue;

class Checkbox extends MultiValue
{
    public $viewTemplate = 'form/checkbox.twig';

    public function addOption($name, $value, $attributes = [])
    {
        $attributes['value'] = $value;
        $attributes['type'] = 'checkbox';
        $attributes['name'] = $this->getName();

        if ($this->isValue($value)) {
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