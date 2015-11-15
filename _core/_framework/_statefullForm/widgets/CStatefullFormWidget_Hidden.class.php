<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 15.11.15
 * Time: 21:47
 */

class CStatefullFormWidget_Hidden extends CStatefullFormWidget_Input{
    protected function getAttributes() {
        $attributes = parent::getAttributes();
        $attributes['type'] = 'hidden';
        return $attributes;
    }
}