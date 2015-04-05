<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 03.04.15
 * Time: 18:32
 *
 * @property CArrayList types
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
            )
        );
    }


}