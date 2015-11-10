<?php
/**
 * 
 * @property int plan_id
 * @property int term_id
 * @property CWorkPlanTerm term
 * @property CTerm controlType
 */
class CWorkPlanFinalControl extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_FINAL_CONTROL;

    protected function relations() {
        return array(
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
            ),
        	"plan" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "plan_id",
        		"targetClass" => "CWorkPlan"
        	)
        );
    }

    public function attributeLabels() {
        return array(
            "control_type_id" => "Вид итогового контроля",
            "term_id" => "Семестр",
            "ordering" => "Порядковый номер"
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