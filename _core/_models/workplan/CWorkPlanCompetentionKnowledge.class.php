<?php
/**
 * 
 * @property int competention_id
 * @property int knowledge_id
 * @property string type_task
 * @property string procedure_eval
 * @property string criteria_eval
 */
class CWorkPlanCompetentionKnowledge extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_KNOWLEDGES;
    
    protected function relations() {
    	return array(
    		"competention" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageField" => "competention_id",
    			"targetClass" => "CWorkPlanCompetention"
    		),
        	"knowledge" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "knowledge_id",
        		"targetClass" => "CTerm"
        	)
    	);
    }

    public function attributeLabels() {
        return array(
        	"knowledge_id" => "Знание",
            "term.name" => "Знание",
        	"type_task" => "Типовое задание из ФОС, позволяющее проверить сформированность образовательного результата",
        	"procedure_eval" => "Процедура оценивания образовательного результата",
        	"criteria_eval" => "Критерии оценки",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "knowledge_id"
            )
        );
    }

}