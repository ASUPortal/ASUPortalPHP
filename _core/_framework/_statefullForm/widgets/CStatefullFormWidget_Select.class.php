<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 15.11.15
 * Time: 22:00
 */

class CStatefullFormWidget_Select extends CStatefullFormWidget_Input {
    function __construct($params = array()) {
        if (!(array_key_exists('source', $params) || array_key_exists('values', $params))) {
            throw new Exception('Не заданы параметр source или values. Не знаю, откуда брать данные для подстановки');
        }

        parent::__construct($params);
    }

    private function getValues() {
        $values = array();
        if (array_key_exists('source', $this->params)) {
            $taxonomy = CTaxonomyManager::getTaxonomy($this->params['source']);
            $values = $taxonomy->getTermsList();
        } elseif (array_key_exists('values', $this->params)) {
            $values = $this->params['values'];
        }
        return $values;
    }

    private function getContent() {
        $options = array();
        foreach ($this->getValues() as $key=>$value) {
            $attributes = array();
            $attributes['value'] = $key;
            $attributes['content'] = $value;
            if ($key == $this->getValue()) {
                $attributes['selected'] = true;
            }
            $options[] = $this->render('option', $attributes);
        }
        return $options;
    }

    protected function getAttributes() {
        $attributes = parent::getAttributes();
        unset($attributes['type']);
        $attributes['class'][] = 'select2';
        $attributes['content'] = $this->getContent();
        return $attributes;
    }

    function run() {
        return $this->render('select', $this->getAttributes());
    }


}