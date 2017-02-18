<?php

namespace PmsOne\Html;

interface AttributesInterface
{
    /**
     * @param $name
     * @param string $value
     * @return $this
     */
    public function addAttribute($name, $value = '');

    /**
     * @param array $attributes
     * @return $this
     */
    public function addAttributes(array $attributes);

    /**
     * @param $name
     * @return mixed
     */
    public function getAttribute($name);

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param $name
     * @return bool
     */
    public function hasAttribute($name);

    /**
     * @param $name
     * @return $this
     */
    public function deleteAttribute($name);

    /**
     * @param $class
     * @return $this
     */
    public function addClass($class);

    /**
     * @param $class
     * @return $this
     */
    public function removeClass($class);
}