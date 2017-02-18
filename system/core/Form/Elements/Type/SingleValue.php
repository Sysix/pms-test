<?php

namespace PmsOne\Form\Elements\Type;


use PmsOne\Form\Elements\FormElementInterface;
use PmsOne\Html\Attributes;
use PmsOne\View;

/**
 * Class SingleValue
 */
abstract class SingleValue implements FormElementInterface
{
    use Attributes;

    public $name;
    public $defaultValue;

    public $value;

    public $label;

    /** @var View $view */
    protected $view;

    public $viewTemplate = null;

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value = '')
    {
        $this->setName($name)
            ->setValue($value);

        $this->view = new View();

        $this->addClass('form-control');
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        if ($this->value) {
            return $this->value;
        }

        return null;
    }


    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @param $view
     * @return $this
     */
    public function setViewTemplate($view)
    {
        $this->viewTemplate = $view;

        return $this;
    }

    public function getViewTemplate()
    {
        return $this->viewTemplate;
    }


    /**
     * @return string
     */
    abstract function render();
}