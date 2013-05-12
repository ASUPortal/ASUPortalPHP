<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 12.05.13
 * Time: 17:43
 * To change this template use File | Settings | File Templates.
 */

class CGrantMoney extends CActiveModel{
    protected $_table = TABLE_GRANT_MONEY;
    public $type_id = 1;
    public $period_id = null;
    protected $_period = null;
    protected $_category = null;
    public function validationRules() {
        $arr = array();
        $arr["required"] = array(
            "value"
        );
        $arr["selected"] = array(
            "type_id"
        );
        if ($this->type_id == 2) {
            $arr["selected"][] = "category_id";
        }
        return $arr;
    }
    public function attributeLabels() {
        return array(
            "type_id" => "Тип",
            "value" => "Сумма",
            "comment" => "Комментарий",
            "category_id" => "Статья расхода"
        );
    }
    public function relations() {
        return array(
            "period" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_period",
                "storageField" => "period_id",
                "managerClass" => "CGrantManager",
                "managerGetObject" => "getPeriod"
            ),
            "category" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_category",
                "storageField" => "category_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            )
        );
    }
}