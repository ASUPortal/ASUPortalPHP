<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.04.13
 * Time: 12:40
 * To change this template use File | Settings | File Templates.
 */

class CSABManager {
    /**
     * @param $key
     * @return CSABCommission|null
     */
    public static function getCommission($key) {
        $commission = null;
        $ar = CActiveRecordProvider::getById(TABLE_SAB_COMMISSIONS, $key);
        if (!is_null($ar)) {
            $commission = new CSABCommission($ar);
        }
        return $commission;
    }

    /**
     * @param $key
     * @return CSABPersonOrder|null
     */
    public static function getSABPersonOrder($key) {
        $order = null;
        $ar = CActiveRecordProvider::getById(TABLE_SAB_PERSON_ORDERS, $key);
        if (!is_null($ar)) {
            $order = new CSABPersonOrder($ar);
        }
        return $order;
    }

    /**
     * @return array
     */
    public static function getCommissionsList() {
        $result = array();
        $query = new CQuery();
        $query->select("c.*")
            ->from(TABLE_SAB_COMMISSIONS." as c")
            ->innerJoin(TABLE_YEARS." as y", "c.year_id = y.id")
            ->order("y.name desc");
        foreach ($query->execute()->getItems() as $ar) {
            $commission = new CSABCommission(new CActiveRecord($ar));
            $nv = $commission->title." (".$commission->year->getValue().")";
            $result[$commission->getId()] = $nv;
        }
        return $result;
    }
}