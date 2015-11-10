<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 20.06.15
 * Time: 20:58
 *
 * @property int plan_id
 * @property software_id
 *
 * @property CTerm software
 */
class CWorkPlanSoftware extends CActiveModel {
    protected $_table = TABLE_WORK_PLAN_SOFTWARE;

    protected function relations() {
        return array(
            "software" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "software_id",
                "targetClass" => "CTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "software_id" => "Программное обеспечение",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "software_id"
            )
        );
    }


}