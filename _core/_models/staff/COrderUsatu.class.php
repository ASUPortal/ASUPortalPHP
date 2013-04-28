<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 18:58
 * To change this template use File | Settings | File Templates.
 */
class COrderUsatu extends CActiveModel {
    protected $_table = TABLE_USATU_ORDERS;
    protected $_type = null;

    public $order_for_seb = 0;

    protected function relations() {
        return array(
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_type",
                "storageField" => "orders_type",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getUsatuOrderType"
            ),
        );
    }
    public function attributeLabels() {
        return array(
            "orders_type" => "Тип приказа",
            "date" => "Дата",
            "num" => "Номер",
            "comment" => "Комментарий",
            "text" => "Текст приказа",
            "title" => "Заголовок приказа",
            "order_num_date" => "Дата и номер",
            "order_for_seb" => "Приказ по ГАК"
        );
    }
    protected function validationRules() {
        return array(
            "required" => array(
                "title",
                "date",
                "num",
                "text"
            ),
            "selected" => array(
                "orders_type"
            )
        );
    }
    public function getName() {
        return "№".$this->num." от ".date("d.m.Y", strtotime($this->date));
    }
}
