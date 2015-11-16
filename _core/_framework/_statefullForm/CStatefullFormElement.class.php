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
    private $validationErrors = array();
    private $formElementValues = array();

    /**
     * @return array
     */
    public function getFormElementValues() {
        return $this->formElementValues;
    }

    /**
     * @param CActiveModel|array $formElementValues
     */
    public function setFormElementValues($formElementValues) {
        if (is_a($formElementValues, 'CModel')) {
            /* @var $model CActiveModel */
            $model = $formElementValues;
            foreach ($model->getRecord()->getItems() as $key=>$value) {
                $this->formElementValues[$key] = $value;
            }
        } else {
            $this->formElementValues = $formElementValues;
        }
    }

    /**
     * @return array
     */
    public function getValidationErrors() {
        return $this->validationErrors;
    }

    /**
     * @param CArrayList|array $validationErrors
     */
    public function setValidationErrors($validationErrors){
        if (is_a($validationErrors, 'CArrayList')) {
            $this->validationErrors = array();
            foreach ($validationErrors as $key=>$value) {
                $this->validationErrors[$key] = $value;
            }
        } else {
            $this->validationErrors = $validationErrors;
        }
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    public function isEdit() {
        return $this->getState() == 'edit';
    }

    public function isStateNotSet() {
        return is_null($this->getState());
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