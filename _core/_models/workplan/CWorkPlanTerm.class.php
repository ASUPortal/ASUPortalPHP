<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 03.04.15
 * Time: 15:05
 *
 * @property CArrayList types
 */
class CWorkPlanTerm extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TERMS;

    protected function relations(){
        return array(
            "types" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_types",
                "storageTable" => TABLE_WORK_PLAN_TERM_LOADS,
                "storageCondition" => "term_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanTermLoad"
            ),
        );
    }


}