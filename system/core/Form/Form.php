<?php

namespace PmsOne\Form;

use PmsOne\Form\Elements\Button;
use PmsOne\Form\Elements\Checkbox;
use PmsOne\Form\Elements\Input;
use PmsOne\Form\Elements\Radio;
use PmsOne\Form\Elements\Raw;
use PmsOne\Form\Elements\Select;
use PmsOne\Form\Elements\Textarea;
use PmsOne\Form\Elements\Type\SingleValue;
use PmsOne\Html\Attributes;
use PmsOne\View;

class Form
{
    use Attributes;

    public $formMethod;
    public $formAction;

    /** @var SingleValue[] $elements */
    public $elements = [];

    /** @var Input[] $hiddenElements */
    public $hiddenElements = [];

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
     * @param SingleValue $element
     * @return SingleValue
     */
    public function addElement(SingleValue $element)
    {
        $this->elementOptions[$element->getName()] = [];

        if ($element->getAttribute('type') == 'hidden') {
            $this->hiddenElements[$element->getName()] = $element;
        } else {
            $this->elements[$element->getName()] = $element;
        }

        return $element;
    }

    /**
     * @param $name
     * @return SingleValue|null
     */
    public function getElement($name)
    {
        if ($this->isElement($name)) {
            return $this->elements[$name];
        }

        if (isset($this->hiddenElements[$name])) {
            return $this->hiddenElements[$name];
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
     * @param $name
     * @param $value
     * @param $type
     * @return Input
     */
    protected function addInputElement($name, $value, $type)
    {
        $element = new Input($name, $value);
        $element->addAttribute('type', $type);

        return $this->addElement($element);
    }

    /**
     * @param $name
     * @param $value
     * @return Input
     */
    public function addTextElement($name, $value)
    {
        return $this->addInputElement($name, $value, 'text');
    }

    /**
     * @param $name
     * @param $value
     * @return Input
     */
    public function addPasswordElement($name, $value)
    {
        return $this->addInputElement($name, $value, 'password');
    }

    /**
     * @param $name
     * @param $value
     * @return Input
     */
    public function addUrlElement($name, $value)
    {
        return $this->addInputElement($name, $value, 'url');
    }

    /**
     * @param $name
     * @param $value
     * @return Input
     */
    public function addDateElement($name, $value)
    {
        return $this->addInputElement($name, $value, 'date');
    }

    /**
     * @param $name
     * @param $value
     * @return Input
     */
    public function addNumberElement($name, $value)
    {
        return $this->addInputElement($name, $value, 'number');
    }

    /**
     * @param $name
     * @param $value
     * @return Input
     */
    public function addEmailElement($name, $value)
    {
        return $this->addInputElement($name, $value, 'email');
    }

    /**
     * @param $name
     * @param $value
     * @return Input
     */
    public function addHiddenElement($name, $value)
    {
        return $this->addInputElement($name, $value, 'hidden');
    }

    /**
     * @param $name
     * @param $value
     * @return Textarea
     */
    public function addTextareaElement($name, $value)
    {
        return $this->addElement(new Textarea($name, $value));
    }

    /**
     * @param $name
     * @param $value
     * @return Select
     */
    public function addSelectElement($name, $value)
    {
        return $this->addElement(new Select($name, $value));
    }

    /**
     * @param $name
     * @param $value
     * @return Radio
     */
    public function addRadioElement($name, $value)
    {
        return $this->addElement(new Radio($name, $value));
    }

    /**
     * @param $name
     * @param $value
     * @return Checkbox
     */
    public function addCheckboxElement($name, $value)
    {
        return $this->addElement(new Checkbox($name, $value));
    }

    /**
     * @param $value
     * @return Raw
     */
    public function addRawElement($value)
    {
        return $this->addElement(new Raw($value));
    }


    /**
     * @param SingleValue $element
     * @return string
     */
    public function renderElement(SingleValue $element)
    {
        static $count = 0;

        if (!$element->hasAttribute('id')) {
            $element->addAttribute('id', 'form-element-' . $count);
        }

        $count++;

        $view = new View();
        return $view
            ->setTemplate($element->wrapperView)
            ->addVar('element', $element)
            ->addVar('attributes', $element->getAttributes())
            ->render();
    }

    /**
     * @return string
     */
    public function render()
    {
        $elementsOutput = $this->elements;
        $elementsOutput = array_map([$this, 'renderElement'], $elementsOutput);

        $view = new View();
        return $view
            ->setTemplate('form/form.twig')
            ->addVar('elements', $elementsOutput)
            ->addVar('formAttributes', $this->attributes)
            ->addVar('hiddenElements', $this->hiddenElements)
            ->addVar('actionButtons', $this->actionButtons)
            ->render();
    }
}