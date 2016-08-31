<?php

namespace PmsOne\Html;

use PmsOne\Helper\Formatter;

trait Attributes
{
    public $attributes;

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function addAttribute($key, $value = '')
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function addAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->addAttribute($key, $value);
        }

        return $this;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if ($this->hasAttribute($key)) {
            return $this->attributes[$key];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasAttribute($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * @param $key
     * @return $this
     */
    public function deleteAttribute($key)
    {
        unset($this->attributes[$key]);

        return $this;
    }


    /**
     * @param $class
     * @return $this
     */
    public function addClass($class)
    {
        if (!$this->hasAttribute('class')) {
            $this->addAttribute('class', []);
        }

        $class = explode(' ', $class);

        $this->addAttribute('class',
            array_merge($this->getAttribute('class'), $class)
        );

        return $this;
    }

    /**
     * @param $class
     * @return $this
     */
    public function removeClass($class)
    {
        if (!$this->hasAttribute('class')) {
            $this->addAttribute('class', []);
        }

        if (($key = array_search($class, $this->getAttribute('class'))) !== false) {
            unset($this->attributes['class'][$key]);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function formatAttributes()
    {
        return Formatter::formatAttributes($this->attributes);
    }
}