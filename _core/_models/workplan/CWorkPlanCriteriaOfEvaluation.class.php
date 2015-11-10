<?php
/**
 * 
 * @property int plan_id
 * 
 * @property int type
 * @property string criteria
 */
class CWorkPlanCriteriaOfEvaluation extends CActiveModel {
    protected $_table = TABLE_WORK_PLAN_CRITERIA_OF_EVALUATION;
    
    protected function relations() {
    	return array(
    		"plan" => array(
    				"relationPower" => RELATION_HAS_ONE,
    				"storageField" => "plan_id",
    				"targetClass" => "CWorkPlan"
    		)
    	);
    }

    public function attributeLabels() {
        return array(
            "type" => "Тип",
            "criteria" => "Критерий оценки",
            "ordering" => "Порядковый номер"
        );
    }
    protected function validationRules() {
        return array(
            "required" => array(
                "criteria"
            )
        );
    }

}