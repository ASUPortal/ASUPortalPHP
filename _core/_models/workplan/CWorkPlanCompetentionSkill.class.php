<?php
/**
 * 
 * @property int competention_id
 * @property int skill_id
 * @property string type_task
 * @property string procedure_eval
 * @property string criteria_eval
 */
class CWorkPlanCompetentionSkill extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_SKILLS;
    
    protected function relations() {
    	return array(
    		"competention" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageField" => "competention_id",
    			"targetClass" => "CWorkPlanCompetention"
    		),
        	"skill" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "skill_id",
        		"targetClass" => "CTerm"
        	)
    	);
    }

    public function attributeLabels() {
        return array(
        	"skill_id" => "Умение",
            "term.name" => "Умение",
        	"type_task" => "Типовое задание из ФОС, позволяющее проверить сформированность образовательного результата",
        	"procedure_eval" => "Процедура оценивания образовательного результата",
        	"criteria_eval" => "Критерии оценки",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "skill_id"
            )
        );
    }

}