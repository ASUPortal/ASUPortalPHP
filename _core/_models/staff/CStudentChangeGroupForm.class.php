<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 29.01.14
 * Time: 21:58
 * To change this template use File | Settings | File Templates.
 */

class CStudentChangeGroupForm extends CFormModel{
    public $students;

    public function attributeLabels() {
        return array(
            "group_id" => "Группа для переноса"
        );
    }
    public function validationRules() {
        return array(
            "selected" => array(
                "group_id"
            )
        );
    }
}