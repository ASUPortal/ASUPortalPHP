<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 15.11.15
 * Time: 21:37
 */

abstract class CStatefullFormWidget implements IStatefullFormWidget {
    protected $params = array();

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
        if (!array_key_exists('model', $params)) {
            throw new Exception('Не задан параметр model, не знаю к какой модели данных идет обращение');
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

    protected function getValue() {
        $bean = $this->params['bean'];
        $elementName = $this->params['element'];
        $attribute = $this->params['attribute'];
        $element = $bean->getElement($elementName);
        $model = $this->params['model'];
        //
        $value = null;
        if (array_key_exists($attribute, $element->getFormElementValues())) {
            $values = $element->getFormElementValues();
            $value = $values[$attribute];
        } else {
            $value = $model->$attribute;
        }
        return $value;
    }

    protected function getAttributes() {
        $elementName = $this->params['element'];
        $attribute = $this->params['attribute'];
        $model = $this->params['model'];
        //
        $attributes = array();
        $attributes['type'] = 'text';
        $attributes['name'] = $elementName . '['. $attribute .']';
        // если в бине есть значение - берем его
        $attributes['value'] = $this->getValue();
        // обязательность поля
        $validators = CCoreObjectsManager::getFieldValidators($model);
        if (array_key_exists($attribute, $validators)) {
            $attributes['required'] = 'required';
        }
        $attributes['class'] = array();
        if (array_key_exists('class', $this->params)) {
            $attributes['class'][] = $this->params['class'];
        }
        return $attributes;
    }

    protected function render($type = '', $attributes = array()) {
        $hasContent = array_key_exists('content', $attributes);
        $result = '<' . $type;
        foreach ($attributes as $key=>$value) {
            if ($key != 'content') {
                if (is_array($value)) {
                    $value = implode(' ', $value);
                }
                $result .= ' ' . $key . '="' . $value . '"';
            }
        }
        if ($hasContent) {
            $result .= '>';
            if (is_array($attributes['content'])) {
                $result .= implode($attributes['content']);
            } else {
                $result .= $attributes['content'];
            }
            $result .= '</'. $type .'>';
        } else {
            $result .= ' />';
        }
        return $result;
    }

    function run() {
        return $this->render('input', $this->getAttributes());
    }
}