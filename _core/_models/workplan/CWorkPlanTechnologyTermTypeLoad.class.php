<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 18.06.15
 * Time: 21:39
 *
 * @property int type_id
 * @property int technology_id
 * @property string value
 *
 * @property CTerm technology
 */
class CWorkPlanTechnologyTermTypeLoad extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TECHNOLOGY_TERM_TYPE_LOADS;

    protected function relations() {
        return array(
            "technology" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "technology_id",
                "targetClass" => "CTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "technology_id" => "Образовательная технология",
            "value" => "Количество часов"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "technology_id"
            ),
            "required" => array(
                "value"
            )
        );
    }


}