<?php

/**
 * Class CCorriculumDisciplineCompetention
 *
 * @property int competention_id
 * @property int knowledge_id
 * @property int skill_id
 * @property int experience_id
 */
class CCorriculumDisciplineCompetention extends CActiveModel {
    protected $_table = TABLE_CORRICULUM_DISCIPLINE_COMPETENTIONS;
    protected $_competention = null;
    protected $_knowledge = null;

    protected function relations() {
        return array(
            "competention" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_competention",
                "storageField" => "competention_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "knowledge" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_knowledge",
                "storageField" => "knowledge_id",
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
    			"level_id" => "Уровень освоения"
    	);
    }
}