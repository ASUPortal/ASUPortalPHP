<?php
/**
 * 
 * @property int competention_id
 * @property int experience_id
 * @property string type_task
 * @property string procedure_eval
 * @property string criteria_eval
 */
class CWorkPlanCompetentionExperience extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_EXPERIENCES;
    
    protected function relations() {
    	return array(
    		"competention" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageField" => "competention_id",
    			"targetClass" => "CWorkPlanCompetention"
    		),
        	"experience" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "experience_id",
        		"targetClass" => "CTerm"
        	)
    	);
    }

    public function attributeLabels() {
        return array(
        	"experience_id" => "Владение",
            "term.name" => "Владение",
        	"type_task" => "Типовое задание из ФОС, позволяющее проверить сформированность образовательного результата",
        	"procedure_eval" => "Процедура оценивания образовательного результата",
        	"criteria_eval" => "Критерии оценки",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "experience_id"
            )
        );
    }

}