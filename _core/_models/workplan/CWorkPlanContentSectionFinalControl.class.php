<?php
/**
 * 
 * @property int section_id
 * @property int term_id
 * @property CWorkPlanContentSection section
 * @property CWorkPlanTerm term
 * @property CTerm controlType
 */
class CWorkPlanContentSectionFinalControl extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_CONTENT_FINAL_CONTROL;

    protected function relations() {
        return array(
            "section" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_section",
                "storageField" => "section_id",
                "targetClass" => "CWorkPlanContentSection"
            ),
            "term" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_term",
                "storageField" => "term_id",
                "targetClass" => "CWorkPlanTerm"
            ),
            "controlType" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_controlType",
                "storageField" => "control_type_id",
                "targetClass" => "CTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "control_type_id" => "Вид итогового контроля",
            "term_id" => "Семестр"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "term_id",
                "control_type_id"
            )
        );
    }

}