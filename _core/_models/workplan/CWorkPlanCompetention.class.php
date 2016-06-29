<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 21.03.15
 * Time: 23:56
 *
 * @property int plan_id
 * @property int allow_delete
 * @property int competention_id
 * @property CArrayList knowledges
 * @property CArrayList skills
 * @property CArrayList experiences
 * @property CArrayList canUse
 * @property CTerm competention
 */
class CWorkPlanCompetention extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_COMPETENTIONS;
    protected $_knowledges;
    protected $_skills;
    protected $_experiences;

    public $allow_delete = 1;

    protected function relations() {
        return array(
            "competention" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "competention_id",
                "targetClass" => "CTerm"
            ),
        	"knowledges" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_KNOWLEDGES,
        		"storageCondition" => "competention_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanCompetentionKnowledge"
        	),
        	"skills" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_SKILLS,
        		"storageCondition" => "competention_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanCompetentionSkill"
        	),
        	"experiences" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_EXPERIENCES,
        		"storageCondition" => "competention_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanCompetentionExperience"
        	),
            "canUse" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_canUse",
                "joinTable" => TABLE_WORK_PLAN_COMPETENTION_CAN_USE,
                "leftCondition" => "competention_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "term_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
        	"level" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_level",
        		"storageField" => "level_id",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"discipline" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_discipline",
        		"storageField" => "discipline_id",
        		"managerClass" => "CCorriculumsManager",
        		"managerGetObject" => "getDiscipline"
        	)
        );
    }

    protected function validationRules() {
    	if ($this->type != 0) {
    		return array(
    				"selected" => array(
    						"competention_id",
    						"level_id",
    						"discipline_id"
    				)
    		);
    	} else {
    		return array(
    				"selected" => array(
    						"competention_id"
    				)
    		);
    	}
    }

    public function attributeLabels() {
        return array(
            "competention_id" => "Компетенция",
            "knowledges" => "Знания",
            "skills" => "Умения",
            "experiences" => "Владения",
            "canUse" => "Умеет использовать",
            "level_id" => "Уровень освоения",
            "discipline_id" => "Дисциплина, сформировавшая компетенцию",
        	"type_task" => "Типовое задание из ФОС, позволяющее проверить сформированность образовательного результата",
        	"procedure_eval" => "Процедура оценивания образовательного результата",
        	"criteria_eval" => "Критерии оценки"
        );
    }


}