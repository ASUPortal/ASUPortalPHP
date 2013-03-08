<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 28.05.12
 * Time: 9:11
 * To change this template use File | Settings | File Templates.
 */

class CResourcesManager {
    private static $_cacheResources = null;
    private static $_cacheCalendars = null;
    private static $_cacheInit = false;

    /**
     * Кэш
     *
     * @return CArrayList
     */
    private static function getCacheResources(){
        if (is_null(self::$_cacheResources)) {
            self::$_cacheResources = new CArrayList();
        }
        return self::$_cacheResources;
    }
    /**
     * Типы ресурсов для подстановки
     *
     * @static
     * @return array
     */
    public static function getTypesList() {
        $r = array(
            "kadri" => "Сотрудник"
        );
        return $r;
    }
    /**
     * Все ресурсы, с полной инициализацией кэша
     *
     * @static
     * @return CArrayList
     */
    public static function getAllResources() {
        if (!self::$_cacheInit) {
            self::$_cacheInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_RESOURCES)->getItems() as $item) {
                $resource = new CResource($item);
                self::getCacheResources()->add($resource->getId(), $resource);
            }
        }
        return self::getCacheResources();
    }
    /**
     * Ресурс по идентификатору
     *
     * @static
     * @param $id
     * @return CResource
     */
    public static function getResourceById($id) {
        if (!self::getCacheResources()->hasElement($id)) {
            $ar = CActiveRecordProvider::getById(TABLE_RESOURCES, $id);
            if (!is_null($ar)) {
                $res = new CResource($ar);
                self::getCacheResources()->add($res->getId(), $res);
            }
        }
        return self::getCacheResources()->getItem($id);
    }
    public static function getResourcesList() {
        $r = array();
        foreach (self::getAllResources()->getItems() as $i) {
            $r[$i->getId()] = $i->getName();
        }
        return $r;
    }
    /**
     * Кэш календарей
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheCalendars() {
        if (is_null(self::$_cacheCalendars)) {
            self::$_cacheCalendars = new CArrayList();
        }
        return self::$_cacheCalendars;
    }
    /**
     * @static
     * @param $id
     * @return CCalendar
     */
    public static function getCalendarById($id) {
        if (!self::getCacheCalendars()->hasElement($id)) {
            $ar = CActiveRecordProvider::getById(TABLE_CALENDARS, $id);
            if (!is_null($ar)) {
                $c = new CCalendar($ar);
                self::getCacheCalendars()->add($c->getId(), $c);
            }
        }
        return self::getCacheCalendars()->getItem($id);
    }
}
