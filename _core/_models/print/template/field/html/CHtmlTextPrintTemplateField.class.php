<?php

/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.11.16
 * Time: 13:51
 */
class CHtmlTextPrintTemplateField extends CHtmlPrintTemplateField {
    public function getName() {
        return $this->_field->alias;
    }

    public function setValue($value) {
        $this->_domNode->textContent = $value;
    }

}