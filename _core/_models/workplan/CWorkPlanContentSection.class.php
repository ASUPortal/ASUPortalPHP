<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 02.04.15
 * Time: 17:35
 *
 * @property int sectionIndex
 * @property string name
 * @property CArrayList lectures
 * @property CArrayList controls
 * @property int plan_id
 * @property CWorkPlan plan
 */
class CWorkPlanContentSection extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_CONTENT_SECTIONS;
    protected $_lectures;
    protected $_controls;

    protected function relations() {
        return array(
            "lectures" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_lectures",
                "storageTable" => TABLE_WORK_PLAN_CONTENT_LECTURES,
                "storageCondition" => "section_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanContentLecture"
            ),
            "controls" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_controls",
                "joinTable" => TABLE_WORK_PLAN_CONTENT_CONTROLS,
                "leftCondition" => "section_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "control_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "plan" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "plan_id",
                "targetClass" => "CWorkPlan"
            )
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "name",
                "sectionIndex"
            )
        );
    }


}