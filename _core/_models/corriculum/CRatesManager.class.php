<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 15.06.13
 * Time: 21:14
 * To change this template use File | Settings | File Templates.
 */

class CRatesManager {
    private static $_cacheRates;

    /**
     * @return CArrayList
     */
    private static function getCacheRates() {
        if (is_null(self::$_cacheRates)) {
            self::$_cacheRates = new CArrayList();
        }
        return self::$_cacheRates;
    }
    private static function addToCache(CRate $rate) {
        self::getCacheRates()->add($rate->getId(), $rate);
        if (!is_null($rate->year)) {
            self::getCacheRates()->add($rate->year->getId()."_".$rate->alias, $rate);
        }
    }
    /**
     * @param $key
     * @return CRate
     */
    public static function getRate($key) {
        if (!self::getCacheRates()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_RATES, $key);
            if (!is_null($ar)) {
                $rate = new CRate($ar);
                self::addToCache($rate);
            }
        }
        return self::getCacheRates()->getItem($key);
    }

    /**
     * @param CTerm $year
     * @param $alias
     * @return CRate
     */
    public static function getRateByAliasAndYear(CTerm $year, $alias) {
        if (!self::getCacheRates()->hasElement($year->getId()."_".$alias)) {
            $q = new CQuery();
            $q->select("r.*")
                ->from(TABLE_RATES." as r")
                ->condition("r.year_id = ".$year->getId()." AND r.alias='".$alias."'");
            foreach ($q->execute()->getItems() as $ar) {
                $rate = new CRate($ar);
                self::addToCache($rate);
            }
        }
        return self::getCacheRates()->getItem($year->getId()."_".$alias);
    }
}