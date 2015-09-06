<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 06.09.15
 * Time: 18:31
 *
 * @property string title
 * @property int plan_id
 * @property int order
 */
class CWorkPlanContentModule extends CActiveModel {
    protected $_table = TABLE_WORK_PLAN_CONTENT_MODULES;

    public function attributeLabels() {
        return array(
            "title" => "Название модуля",
            "order" => "Порядковый номер"
        );
    }

    public function getValidationRules() {
        return array(
            "required" => array(
                "title",
                "order"
            )
        );
    }


}