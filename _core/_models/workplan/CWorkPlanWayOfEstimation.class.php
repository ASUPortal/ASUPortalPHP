<?php
/**
 * 
 * @property int plan_id
 * @property int type_id
 *
 * @property CTerm type
 * @property CArrayList criteria
 */
class CWorkPlanWayOfEstimation extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_WAY_OF_ESTIMATION;

    protected function relations() {
        return array(
        	"type" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "type_id",
        		"targetClass" => "CTerm"
        	),
            "criteria" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "joinTable" => TABLE_WORK_PLAN_CRITERIA_OF_ESTIMATION,
                "leftCondition" => "way_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "criteria_id",
                "targetClass" => "CTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "type_id" => "Способ оценивания",
            "criteria" => "Критерии оценки"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "type_id"
            )
        );
    }

}