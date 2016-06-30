<?php

/**
 * Class CCorriculumDisciplineCompetention
 * 
 * @property int discipline_id
 * @property int competention_id
 * @property int knowledge_id
 * @property int skill_id
 * @property int experience_id
 */
class CCorriculumDisciplineCompetention extends CActiveModel {
    protected $_table = TABLE_CORRICULUM_DISCIPLINE_COMPETENTIONS;
    protected $_competention = null;
    protected $_level = null;
    protected $_knowledges;
    protected $_skills;
    protected $_experiences;

    protected function relations() {
        return array(
        	"discipline" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_discipline",
        		"storageField" => "discipline_id",
        		"managerClass" => "CCorriculumsManager",
        		"managerGetObject" => "getDiscipline"
        	),
            "competention" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_competention",
                "storageField" => "competention_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
        	"knowledges" => array(
        		"relationPower" => RELATION_MANY_TO_MANY,
        		"storageProperty" => "_knowledges",
        		"joinTable" => TABLE_CORRICULUM_DISCIPLINE_KNOWLEDGES,
        		"leftCondition" => "competention_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
        		"rightKey" => "knowledge_id",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"skills" => array(
        		"relationPower" => RELATION_MANY_TO_MANY,
        		"storageProperty" => "_skills",
        		"joinTable" => TABLE_CORRICULUM_DISCIPLINE_SKILLS,
        		"leftCondition" => "competention_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
        		"rightKey" => "skill_id",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"experiences" => array(
        		"relationPower" => RELATION_MANY_TO_MANY,
        		"storageProperty" => "_experiences",
        		"joinTable" => TABLE_CORRICULUM_DISCIPLINE_EXPERIENCES,
        		"leftCondition" => "competention_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
        		"rightKey" => "experience_id",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"level" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_level",
        		"storageField" => "level_id",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	)
        );
    }
    public function attributeLabels() {
    	return array(
    			"level_id" => "Уровень освоения",
    			"knowledges" => "Знания",
    			"skills" => "Умения",
    			"experiences" => "Владения"
    	);
    }
    protected function validationRules() {
    	return array(
    			"selected" => array(
    				"level_id"
    			)
    	);
    }
}