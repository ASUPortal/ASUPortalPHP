<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 03.04.15
 * Time: 15:05
 *
 * @property CArrayList types
 * @property CArrayList sections
 * @property CArrayList labs
 * @property CArrayList practices
 * @property int plan_id
 * @property String number
 * @property CWorkPlan plan
 */
class CWorkPlanTerm extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TERMS;

    protected function relations(){
        return array(
            "types" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_TERM_LOADS,
                "storageCondition" => "term_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanTermLoad"
            ),
            "sections" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_TERM_SECTIONS,
                "storageCondition" => "term_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanTermSection"
            ),
            "labs" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_TERM_LABS,
                "storageCondition" => "term_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanTermLab"
            ),
            "practices" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_TERM_PRACTICES,
                "storageCondition" => "term_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanTermPractice"
            ),
            "plan" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "plan_id",
                "targetClass" => "CWorkPlan"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "number" => "Номер семестра"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "number"
            )
        );
    }


}