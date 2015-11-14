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