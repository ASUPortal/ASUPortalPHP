<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 23.09.12
 * Time: 15:55
 * To change this template use File | Settings | File Templates.
 */
class COrder extends CActiveModel {
    protected $_person = null;
    protected $_table = TABLE_STAFF_ORDERS;
    public $order_active = 0;
    /**
     * Действует ли сейчас
     *
     * @return bool
     */
    public function isActive() {
        if ((time() > strtotime($this->date_begin)) and strtotime($this->date_end) > time()) {
            if ($this->order_active == 1) {
                return true;
            }
        }
        return false;
    }
    protected function relations() {
        return array(
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "kadri_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
        );
    }
    public static function getClassName() {
        return __CLASS__;
    }
    public  function attributeLabels() {
        return array(
            "type_money" => "Введенные приказы (тип средств)",
            "type_order" => "Тип приказа",
            "order" => "Приказ",
            "order_period" => "Период действия приказа",
            "main_work_place" => "Основное место работы (для совместителей)",
            "prev_order" => "Данные предыдущего приказа",
            "etc" => "Разряд ЕТС, размер ставки",
            "conditions" => "Дополнительные условия работы"
        );
    }
    /**
     * Является ли приказом указанного типа
     * 
     * @param type $type
     * @return type
     */
    public function isOrderType($type) {
        return (strpos(strtolower($this->num_order), strtolower($type)) !== false);
    }    
}
