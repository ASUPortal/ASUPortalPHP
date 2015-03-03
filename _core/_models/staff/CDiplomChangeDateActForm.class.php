<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 29.01.14
 * Time: 21:58
 * To change this template use File | Settings | File Templates.
 */

class CDiplomChangeDateActForm extends CFormModel{
    public $diploms;
    public $date_act;

    public function attributeLabels() {
        return array(
            "date_act" => "Дата защиты"
        );
    }
    public function validationRules() {
        return array(
            "checkdate" => array(
                "date_act"
            )
        );
    }
}