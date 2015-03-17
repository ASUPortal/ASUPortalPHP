<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 29.01.14
 * Time: 21:58
 * To change this template use File | Settings | File Templates.
 */

class CDiplomChangePractPlaceForm extends CFormModel{
    public $diploms;
    public $pract_place_id;

    public function attributeLabels() {
        return array(
            "pract_place_id" => "Место практики"
        );
    }
    public function validationRules() {
        return array(
            "selected" => array(
                "pract_place_id"
            )
        );
    }
}