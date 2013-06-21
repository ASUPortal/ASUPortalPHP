<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.04.13
 * Time: 12:40
 * To change this template use File | Settings | File Templates.
 */

class CSABManager {
    private static $_cachePreviewCommission = null;
    private static $_cacheCommission = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheCommission() {
        if (is_null(self::$_cacheCommission)) {
            self::$_cacheCommission = new CArrayList();
        }
        return self::$_cacheCommission;
    }
    /**
     * @param $key
     * @return CSABCommission|null
     */
    public static function getCommission($key) {
        if (!self::getCacheCommission()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_SAB_COMMISSIONS, $key);
            if (!is_null($ar)) {
                $commission = new CSABCommission($ar);
                self::getCacheCommission()->add($commission->getId(), $commission);
            }
        }
        return self::getCacheCommission()->getItem($key);
    }

    /**
     * @return CArrayList
     */
    public static function getCommissions() {
        $res = new CArrayList();
        $ids = CRequest::getString("id");
        $ids = explode(":", $ids);
        foreach ($ids as $id) {
            $comm = self::getCommission($id);
            if (!is_null($comm)) {
                $res->add($comm->getId(), $comm);
            }
        }
        return $res;
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

    /**
     * @return CArrayList|null
     */
    private static function getCachePreviewCommission() {
        if (is_null(self::$_cachePreviewCommission)) {
            self::$_cachePreviewCommission = new CArrayList();
        }
        return self::$_cachePreviewCommission;
    }

    /**
     * @param $key
     * @return CDiplomPreviewComission
     */
    public static function getPreviewCommission($key) {
        if (!self::getCachePreviewCommission()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_DIPLOM_PREVIEW_COMISSIONS, $key);
            if (!is_null($ar)) {
                $comm = new CDiplomPreviewComission($ar);
                self::getCachePreviewCommission()->add($comm->getId(), $comm);
            }
        }
        return self::getCachePreviewCommission()->getItem($key);
    }
}