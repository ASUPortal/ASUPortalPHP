<?php
/**
 *
 * @property int type_study_activity_id
 *
 * @property CTerm type
 * @property CTerm form
 */
class CWorkPlanControlTypes extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TYPES_CONTROL;

    protected function relations() {
        return array(
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "type_study_activity_id",
                "targetClass" => "CTerm"
            ),
        	"section" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_section",
                "storageField" => "section_id",
                "targetClass" => "CWorkPlanContentSection"
            ),
        	"control" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "control_id",
        		"targetClass" => "CTerm"
        	)
        );
    }

    public function attributeLabels() {
        return array(
            "type_study_activity_id" => "Вид учебной деятельности",
        	"section_id" => "Раздел",
        	"control_id" => "Вид контроля",
            "mark" => "Балл за конкретное задание",
            "amount_labors" => "Число заданий",
            "min" => "Минимальный",
        	"max" => "Максимальный"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "type_study_activity_id",
            	"section_id",
            	"control_id"
            ),
        	"numeric" => array(
        		"mark",
        		"amount_labors",
        		"min",
        		"max"
        	)
        );
    }

}