<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 16.06.15
 * Time: 22:58
 *
 * @property int plan_id
 * @property int term_id
 *
 * @property CWorkPlanTerm term
 * @property CArrayList types
 */
class CWorkPlanTechnologyTerm extends CActiveModel{
    protected $_table =  TABLE_WORK_PLAN_TECHNOLOGY_TERMS;

    protected function relations() {
        return array(
            "term" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "term_id",
                "targetClass" => "CWorkPlanTerm"
            ),
            "types" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_TECHNOLOGY_TERM_TYPES,
                "storageCondition" => "technology_term_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanTechnologyTermType"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "term_id" => "Семестр"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "term_id"
            )
        );
    }


}