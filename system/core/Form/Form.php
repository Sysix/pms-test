<?php

namespace PmsOne\Form;

use PmsOne\Form\Elements\Button;
use PmsOne\Form\Elements\FormElementInterface;
use PmsOne\Form\Elements\Type\MultiValue;
use PmsOne\Html\Attributes;
use PmsOne\View;

class Form
{
    use Attributes;

    public $formMethod;
    public $formAction;

    /** @var FormElementInterface[] $elements */
    public $elements = [];

    /** @var Button[] $actionButtons */
    public $actionButtons = [];

    /** @var array $elementOptions options for the all elements */
    public $elementOptions = [];

    public $successMessage;
    public $errorMessage;

    public function __construct($action)
    {
        $this
            ->setFormAction($action)
            ->setFormMethod('post')
            ->setDefaultActionButtons();
    }

    public function setFormAction($action)
    {
        $this->formAction = $action;

        $this->addAttribute('action', $action);

        return $this;
    }

    public function getFormAction()
    {
        return $this->formAction;
    }

    public function setFormMethod($method)
    {
        $this->formMethod = $method;

        $this->addAttribute('method', $method);

        return $this;
    }

    public function getFormMethod()
    {
        return $this->formMethod;
    }


    public function setDefaultActionButtons()
    {
        $this->addActionButton((new Button('save', 'Speichern'))->addClass('btn-primary'))
            ->addActionButton((new Button('save-back', 'Übernehmen'))->addClass('btn-secondary'))
            ->addActionButton((new Button('back', 'Zurück'))->addClass('btn-warning')->addClass('back'));

        return $this;
    }

    public function addActionButton(Button $button)
    {
        $this->actionButtons[$button->getName()] = $button;

        return $this;
    }

    public function removeActionButton($name)
    {
        unset($this->actionButtons[$name]);

        return $this;
    }

    public function getActionButton($name)
    {
        if (isset($this->actionButtons[$name])) {
            return $this->actionButtons[$name];
        }

        return null;
    }

    /**
     * @param FormElementInterface $element
     * @return FormElementInterface|MultiValue
     */
    public function addElement(FormElementInterface $element)
    {
        $this->elements[$element->getName()] = $element;
        $this->elementOptions[$element->getName()] = [];

        $this->addElementOption($element->getName(), 'wrapperView', 'form/element-wrapper-default.twig');

        return $element;
    }

    /**
     * @param $name
     * @return FormElementInterface|null
     */
    public function getElement($name)
    {
        if ($this->isElement($name)) {
            return $this->elements[$name];
        }

        return null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isElement($name)
    {
        return isset($this->elements[$name]);
    }

    /**
     * @param $name
     * @return $this
     */
    public function deleteElement($name)
    {
        unset($this->elements[$name]);
        unset($this->elementOptions[$name]);

        return $this;
    }

    /**
     * @param $name
     * @param $option
     * @param $value
     * @return $this
     */
    public function addElementOption($name, $option, $value)
    {
        $this->elementOptions[$name][$option] = $value;

        return $this;
    }

    /**
     * @param $name
     * @param $option
     * @return null
     */
    public function getElementOption($name, $option)
    {
        if ($this->isElementOption($name, $option)) {
            return $this->elementOptions[$name][$option];
        }

        return null;
    }

    /**
     * @param $name
     * @param $option
     * @return bool
     */
    public function isElementOption($name, $option)
    {
        return isset($this->elementOptions[$name][$option]);
    }

    /**
     * @param $name
     * @param $option
     * @return $this
     */
    public function deleteElementOption($name, $option)
    {
        unset($this->elementOptions[$name][$option]);

        return $this;
    }


    /**
     * @param FormElementInterface $element
     * @return string
     */
    public function renderElement(FormElementInterface $element)
    {
        static $count = 0;

        if (!$element->hasAttribute('id')) {
            $element->addAttribute('id', 'form-element-' . $count);
        }

        $count++;

        $view = new View();
        return $view
            ->setTemplate($this->getElementOption($element->getName(), 'wrapperView'))
            ->addVar('element', $element)
            ->addVar('attributes', $element->getAttributes())
            ->render();
    }

    /**
     * @param FormElementInterface[] $elements
     * @return FormElementInterface[]
     */
    protected function getAndRemoveHiddenElements(array &$elements)
    {
        $hiddenElements = [];
        foreach ($elements as $name => $element) {
            if ($element->getAttribute('type') == 'hidden') {
                $hiddenElements[$name] = $element;
                unset($elements[$name]);
            }
        }

        return $hiddenElements;
    }

    /**
     * @return string
     */
    public function render()
    {
        $elementsOutput = $this->elements;
        $hiddenElements = $this->getAndRemoveHiddenElements($elementsOutput);

        $elementsOutput = array_map([$this, 'renderElement'], $elementsOutput);

        $view = new View();
        return $view
            ->setTemplate('form/form.twig')
            ->addVar('elements', $elementsOutput)
            ->addVar('formAttributes', $this->attributes)
            ->addVar('hiddenElements', $hiddenElements)
            ->addVar('actionButtons', $this->actionButtons)
            ->render();
    }
}