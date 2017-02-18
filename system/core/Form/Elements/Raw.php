<?php

namespace PmsOne\Form\Elements;

use PmsOne\Form\Elements\Type\SingleValue;

class Raw extends SingleValue
{
    static $count = 0;

    public function __construct($name, $value = '')
    {
        parent::__construct('raw-element-' . self::$count, $value);

        self::$count++;
    }

    public function render()
    {
        return $this->getValue();
    }
}