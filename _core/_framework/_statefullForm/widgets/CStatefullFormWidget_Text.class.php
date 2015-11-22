<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 16.11.15
 * Time: 22:18
 */

class CStatefullFormWidget_Text extends CStatefullFormWidget {
    function __construct($params = array()) {
        /**
         * Проверим, все ли параметры заданы
         */
        if (!array_key_exists('model', $params) || is_null($params['model'])) {
            $params['model'] = new CModel();
        }
        if (!is_a($params['model'], 'CModel')) {
            throw new Exception('Model не экземпляр класса CModel');
        }
        if (!array_key_exists('attribute', $params)) {
            throw new Exception('Не задан параметр attribute, не знаю значение какого поля выводить');
        }
        //
        $this->params = $params;
    }

    private function isDeleted() {
        /* @var $model CModel */
        $model = $this->params['model'];
        return $model->isMarkDeleted();
    }

    protected function getAttributes() {
        $attributes = array();
        $attributes['content'] = $this->getValue();
        return $attributes;
    }

    protected function getValue() {
        $attribute = $this->params['attribute'];
        $model = $this->params['model'];
        //
        return $model->$attribute;
    }

    function run() {
        if ($this->isDeleted()) {
            return $this->render('s', $this->getAttributes());
        } else {
            return $this->getValue();
        }
    }
}