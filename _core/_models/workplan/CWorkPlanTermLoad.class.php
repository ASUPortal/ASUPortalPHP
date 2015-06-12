<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 03.04.15
 * Time: 15:24
 *
 * @property int term_id
 * @property int type_id
 * @property int value
 */
class CWorkPlanTermLoad extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TERM_LOADS;

    protected function relations() {
        return array(
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "type_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "type_id" => "Тип",
            "value" => "Значение"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "value"
            ),
            "selected" => array(
                "type_id"
            )
        );
    }


}