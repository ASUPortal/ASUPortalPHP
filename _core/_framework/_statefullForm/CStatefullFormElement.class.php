<?php

/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.11.15
 * Time: 13:05
 */
class CStatefullFormElement {
    private $name;
    private $state;

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    public function isEdit() {
        return $this->getState() == 'edit';
    }

    public function isShow() {
        return $this->getState() == 'show';
    }

    public function isHidden() {
        return $this->getState() == 'hide';
    }

    public function setShow($value) {
        if ($value) {
            $this->setState('show');
        } else {
            $this->setState(null);
        }
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state) {
        $this->state = $state;
    }


}