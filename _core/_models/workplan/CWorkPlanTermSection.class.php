<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 03.04.15
 * Time: 18:32
 *
 * @property CArrayList loads
 * @property int term_id
 * @property string title
 * @property CWorkPlanTerm term
 */
class CWorkPlanTermSection extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TERM_SECTIONS;

    protected function relations(){
        return array(
            "loads" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_TERM_SECTION_LOADS,
                "storageCondition" => "section_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanTermSectionLoad"
            ),
            "term" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "term_id",
                "targetClass" => "CWorkPlanTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "title" => "Наименование раздела"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "title"
            )
        );
    }


}