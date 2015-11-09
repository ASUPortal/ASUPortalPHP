<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 07.09.15
 * Time: 21:41
 *
 * @property int load_id
 * @property CWorkPlanContentSectionLoad load
 */
class CWorkPlanContentSectionLoadTopic extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_CONTENT_TOPICS;

    protected function relations() {
        return array(
            "load" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_load",
                "storageField" => "load_id",
                "targetClass" => "CWorkPlanContentSectionLoad"
            )
        );
    }


    public function attributeLabels() {
        return array(
            "title" => "Тема",
            "value" => "Число часов",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "title",
                "value"
            )
        );
    }


}