<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 07.09.15
 * Time: 21:55
 *
 * @property int load_id
 * @property CTerm technology
 * @property CWorkPlanContentSectionLoad load
 */
class CWorkPlanContentSectionLoadTechnology extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_CONTENT_TECHNOLOGIES;

    protected function relations() {
        return array(
            "load" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_load",
                "storageField" => "load_id",
                "targetClass" => "CWorkPlanContentSectionLoad"
            ),
            "technology" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_technology",
                "storageField" => "technology_id",
                "targetClass" => "CTerm"
            )
        );
    }


    public function attributeLabels() {
        return array(
            "technology_id" => "Технология",
            "value" => "Число часов",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "value"
            ),
            "selected" => array(
                "technology_id"
            )
        );
    }


}