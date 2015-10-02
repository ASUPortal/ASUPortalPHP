<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 02.04.15
 * Time: 17:35
 *
 * @property int sectionIndex
 * @property string name
 * @property CArrayList controls
 * @property int module_id
 * @property CWorkPlan plan
 * @property CWorkPlanContentCategory module
 * @property CArrayList loads
 */
class CWorkPlanContentSection extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_CONTENT_SECTIONS;
    protected $_lectures;
    protected $_controls;

    protected function relations() {
        return array(
            "controls" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_controls",
                "joinTable" => TABLE_WORK_PLAN_CONTENT_CONTROLS,
                "leftCondition" => "section_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "control_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "module" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_module",
                "storageField" => "module_id",
                "managerClass" => "CBaseManager",
                "managerGetObject" => "getWorkPlanContentCategory"
            ),
            "loads" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_CONTENT_LOADS,
                "storageCondition" => "section_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanContentSectionLoad"
            )
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "name",
                "sectionIndex",
                "controls",
                "module_id"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "name" => "Название раздела",
            "sectionIndex" => "Номер раздела",
            "module_id" => "Категория",
            "controls" => "Формы текущего контроля",
            "content" => "Содержание раздела"
        );
    }


}