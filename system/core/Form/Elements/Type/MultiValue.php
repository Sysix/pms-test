<?php

namespace PmsOne\Form\Elements\Type;

abstract class MultiValue extends SingleValue
{
    /** @var array[] $options */
    public $options = [];

    public $valueSeparator = '|';

    /**
     * @param $name
     * @param $value
     */
    public function __construct($name, $value)
    {
        parent::__construct($name, $value);

        $this->formatValue();
    }

    /**
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        parent::setValue($value);

        return $this->formatValue();
    }

    /**
     * @return $this
     */
    protected function formatValue()
    {
        if (!is_array($this->getValue())) {
            $this->setValue(explode(
                $this->valueSeparator,
                trim($this->getValue(), $this->valueSeparator)
            ));
        }

        return $this;
    }

    /**
     * @param $value
     * @return bool
     */
    public function isValue($value)
    {
        return in_array($value, $this->getValue());
    }

    /**
     * @param $key
     * @param $function
     * @return $this
     */
    protected function sortByFunction(&$key, $function)
    {
        if (!is_callable($function)) {
            throw new \InvalidArgumentException('sortFunction need a callable function');
        }

        call_user_func($function, $key);

        return $this;
    }

    /**
     * @param string $function
     * @return $this
     */
    public function sortValues($function = 'asort')
    {
        return $this->sortByFunction($this->value, $function);
    }

    /**
     * @param string $function
     * @return $this
     */
    public function sortOptions($function = 'ksort')
    {
        return $this->sortByFunction($this->options, $function);
    }

    /**
     * @param $name
     * @param $value
     * @param array $attributes
     * @return $this
     */
    public function addOption($name, $value, $attributes = [])
    {
        $this->options[$name] = [
            'name' => $name,
            'value' => $value,
            'attributes' => $attributes
        ];

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isOption($name)
    {
        return isset($this->options[$name]);
    }


    /**
     * @param $name
     * @return array|null
     */
    public function getOption($name)
    {
        if ($this->isOption($name)) {
            return $this->options[$name];
        }

        return null;
    }

    /**
     * @return array[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $name
     * @return $this
     */
    public function deleteOption($name)
    {
        unset($this->options[$name]);

        return $this;
    }
}