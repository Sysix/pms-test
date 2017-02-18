<?php

namespace PmsOne\Form\Elements;


interface FormElementInterface extends ElementInterface
{
    public function setLabel($label);
    public function getLabel();

}