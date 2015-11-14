<?php

/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.11.15
 * Time: 11:21
 */
class CStatefullFormBean extends CStatefullBean{
    private $elements = array();

    /**
     * @return array
     */
    public function getElements() {
        return $this->elements;
    }

    /**
     * @param $element
     * @return CStatefullFormElement
     */
    public function getElement($element) {
        if (!array_key_exists($element, $this->elements)) {
            $el = new CStatefullFormElement();
            $el->setName($element);
            $this->elements[$element] = $el;
        }
        return $this->elements[$element];
    }

    /**
     * @param $element
     * @param $state
     */
    public function setElementState($element, $state) {
        $el = $this->getElement($element);
        $el->setState($state);
    }
}