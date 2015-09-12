<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 12.09.15
 * Time: 23:24
 *
 * @property CTerm supply
 * @property int plan_id
 */
class CWorkPlanAdditionalSupply extends CActiveModel {
    protected $_table = TABLE_WORK_PLAN_ADDITIONAL_SUPPLY;

    protected function relations() {
        return array(
            "supply" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "supply_id",
                "targetClass" => "CTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "supply_id" => "Дополнительное обеспечение"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "supply_id"
            )
        );
    }
}