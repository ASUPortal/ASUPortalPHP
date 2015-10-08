<?php

class CWorkPlanProjectThemeGroupAdd extends CFormModel {
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "project_title" => "Темы (по одной на строке)",
        	"type" => "Тип"
        );
    }
}
