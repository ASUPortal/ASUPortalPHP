<?php
/**
 * 
 * @property int plan_id
 * @property int section_id
 * 
 * Расчётные задания по разделу дисциплины для пункта 
 * 4. Учебно-методическое обеспечение самостоятельной работы студентов
 * 
 */
class CWorkPlanCalculationTask extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_CALCULATION_TASKS;

    protected function relations() {
        return array(
            "section" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_section",
                "storageField" => "section_id",
                "managerClass" => "CBaseManager",
                "managerGetObject" => "getWorkPlanContentSection"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "section_id" => "Контролируемый раздел (тема) дисциплины",
            "task" => "Расчётное задание (задача и пр.)",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "section_id"
            )
        );
    }

}