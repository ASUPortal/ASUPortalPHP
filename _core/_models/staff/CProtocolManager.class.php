<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.06.12
 * Time: 13:17
 * To change this template use File | Settings | File Templates.
 *
 * Менеджер протоколов разного вида
 */
class CProtocolManager {
    private static $_cacheDepProtocols = null;
    private static $_cacheSebProtocols = null;
    private static $_cacheSebProtocolsInit = false;
    private static $_cacheDepProtocolsInit = false;
    /**
     * Кэш протоколов заседния кафедры
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheDepProtocols() {
        if (is_null(self::$_cacheDepProtocols)) {
            self::$_cacheDepProtocols = new CArrayList();
        }
        return self::$_cacheDepProtocols;
    }
    /**
     * Все протоколы заседаний кафедры
     *
     * @static
     * @return CArrayList
     */
    public static function getAllDepProtocols() {
        if (!self::$_cacheDepProtocolsInit) {
            self::$_cacheDepProtocolsInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_DEPARTMENT_PROTOCOLS)->getItems() as $i) {
                $protocol = new CDepartmentProtocol($i);
                self::getCacheDepProtocols()->add($protocol->getId(), $protocol);
            }
        }
        return self::getCacheDepProtocols();
    }
    /**
     * Список протоколов для подстановки
     *
     * @static
     * @return array
     */
    public static function getAllDepProtocolsList() {
        $arr = array();
        $res = array();
        foreach (self::getAllDepProtocols()->getItems() as $i) {
            $arr[$i->getId()] = date("Ymd", strtotime($i->getDate()));
        }
        asort($arr, false);
        foreach ($arr as $key=>$value) {
            $protocol = self::getDepProtocol($key);
            $res[$protocol->getId()] = "Протокол №".$protocol->getNumber()." от ".$protocol->getDate();
        }
        return $res;
    }
    /**
     * Протокол по ключу
     *
     * @static
     * @param $key
     * @return CDepartmentProtocol
     */
    public static function getDepProtocol($key) {
        if (!self::getCacheDepProtocols()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_DEPARTMENT_PROTOCOLS, $key);
            if (!is_null($ar)) {
                $protocol = new CDepartmentProtocol($ar);
                self::getCacheDepProtocols()->add($protocol->getId(), $protocol);
            }
        }
        return self::getCacheDepProtocols()->getItem($key);
    }
    /**
     * Кэш протоколов ГАК
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheSebProtocols() {
        if (is_null(self::$_cacheSebProtocols)) {
            self::$_cacheSebProtocols = new CArrayList();
        }
        return self::$_cacheSebProtocols;
    }
    /**
     * Все протоколы ГАК
     *
     * @static
     * @return CArrayList
     */
    public static function getAllSebProtocols() {
        if (!self::$_cacheSebProtocolsInit) {
            self::$_cacheSebProtocolsInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_SEB_PROTOCOLS)->getItems() as $i) {
                $protocol = new CSEBProtocol($i);
                self::getCacheSebProtocols()->add($protocol->getId(), $protocol);
            }
        }
        return self::getCacheSebProtocols();
    }
    /**
     * Протокол ГАК
     *
     * @static
     * @param $key
     * @return CSEBProtocol
     */
    public static function getSebProtocol($key) {
        if (!self::getCacheSebProtocols()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_SEB_PROTOCOLS, $key);
            if (!is_null($ar)) {
                $protocol = new CSEBProtocol($ar);
                self::getCacheSebProtocols()->add($protocol->getId(), $protocol);
            }
        }
        return self::getCacheSebProtocols()->getItem($key);
    }
}
