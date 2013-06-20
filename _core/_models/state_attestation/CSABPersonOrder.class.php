<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 18.05.13
 * Time: 19:38
 * To change this template use File | Settings | File Templates.
 */

class CSABPersonOrder extends CActiveModel{
    protected $_table = TABLE_SAB_PERSON_ORDERS;

    public $year_id;
    public $person_id;
    public $order_id;

    protected $_order = null;
    protected $_year = null;
    protected $_type = null;

    public function relations() {
        return array(
            "order" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_order",
                "storageField" => "order_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getUsatuOrder"
            ),
            "year" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_year",
                "storageField" => "year_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getYear"
            ),
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_type",
                "storageField" => "type_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "order_id" => "Приказ",
            "year_id" => "Год",
            "type_id" => "Тип приказа"
        );
    }

    public function validationRules() {
        return array(
            "selected" => array(
                "order_id",
                "year_id",
                "type_id"
            )
        );
    }
}