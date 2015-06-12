<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 05.04.15
 * Time: 16:33
 *
 * @property int type_id
 * @property int section_id
 * @property string value
 * @property CTerm type
 */
class CWorkPlanTermSectionLoad extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TERM_SECTION_LOADS;

    protected function relations() {
        return array(
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "type_id",
                "targetClass" => "CTerm"
            )
        );
    }


    public function attributeLabels() {
        return array(
            "type_id" => "Вид нагрузки",
            "value" => "Количество часов"
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