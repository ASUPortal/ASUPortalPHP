<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 21.03.15
 * Time: 23:56
 *
 * @property int plan_id
 * @property CArrayList knowledges
 * @property CArrayList skills
 * @property CArrayList experiences
 */
class CWorkPlanCompetention extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_COMPETENTIONS;
    protected $_knowledges;
    protected $_skills;
    protected $_experiences;

    protected function relations() {
        return array(
            "knowledges" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_knowledges",
                "joinTable" => TABLE_WORK_PLAN_KNOWLEDGES,
                "leftCondition" => "competention_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "knowledge_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "skills" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_skills",
                "joinTable" => TABLE_WORK_PLAN_SKILLS,
                "leftCondition" => "competention_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "skill_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "experiences" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_experiences",
                "joinTable" => TABLE_WORK_PLAN_EXPERIENCES,
                "leftCondition" => "competention_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "experience_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            )
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "competention_id"
            )
        );
    }


}