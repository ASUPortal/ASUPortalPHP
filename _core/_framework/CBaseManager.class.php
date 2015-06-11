<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 11.06.15
 * Time: 21:35
 */

class CBaseManager {
    /**
     * Магический метод получения любых классов
     *
     * @param $name
     * @param array $params
     * @throws Exception
     */
    public static function __callStatic($name, $params = array()) {
        /**
         * Получаем имя класса из имени функции
         */
        $className = "C".CUtils::strRight($name, "get");
        if (!class_exists($className)) {
            throw new Exception("В приложении не объявлен класс ".$className);
        }
        /**
         * @var CActiveModel $simpleClass
         */
        $simpleClass = new $className();
        $table = $simpleClass->getRecord()->getTable();
        $id = $params[0];
        /**
         * Попробуем сначала получить из кэша
         */
        $keySeek = $table . "_" . $id;
        if (CApp::getApp()->cache->hasCache($keySeek)) {
            return CApp::getApp()->cache->get($keySeek);
        }
        $ar = CActiveRecordProvider::getById($table, $id);
        if (!is_null($ar)) {
            $obj = new $className($ar);
            CApp::getApp()->cache->set($keySeek, $obj, 60);
            return $obj;
        }
    }
}