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
 *
 * @property CArrayList sections
 */
class CWorkPlanContentModule extends CActiveModel {
    protected $_table = TABLE_WORK_PLAN_CONTENT_MODULES;

    protected function relations() {
        return array(
            "sections" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_CONTENT_SECTIONS,
                "storageCondition" => "module_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanContentSection"
            )
        );
    }

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