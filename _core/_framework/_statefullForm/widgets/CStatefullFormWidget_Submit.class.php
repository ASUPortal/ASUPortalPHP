<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 16.11.15
 * Time: 21:49
 */

class CStatefullFormWidget_Submit extends CStatefullFormWidget {
    function __construct($params = array()) {
        /**
         * Проверим, все ли параметры заданы
         */
        if (!array_key_exists('bean', $params)) {
            throw new Exception('Не задан параметр bean');
        }
        if (!is_a($params['bean'], 'CStatefullFormBean')) {
            throw new Exception('Bean не экземпляр класса CStatefullFormBean');
        }
        if (!array_key_exists('element', $params)) {
            throw new Exception('Не задан параметр element, к которому относится элемент формы');
        }
        //
        $this->params = $params;
    }

    protected function getAttributes() {
        $attributes = array();
        $attributes['type'] = 'submit';
        $attributes['name'] = 'element';
        $attributes['class'][] = 'btn';
        $attributes['class'][] = 'btn-link';
        $attributes['value'] = $this->params['element'];
        $attributes['content'] = '<i class="icon-ok"></i>';
        return $attributes;
    }


    function run() {
        return $this->render('button', $this->getAttributes());
    }

}