<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 24.09.12
 * Time: 21:29
 * To change this template use File | Settings | File Templates.
 */
class CRatingManager {
    private static $_cacheIndexes = null;
    private static $_cacheIndexesInit = false;
    private static $_cachePersonIndexes = null;
    private static $_cacheIndexValues = null;
    /**
     * @static
     * @return CArrayList
     */
    private static function getCacheIndexValues() {
        if (is_null(self::$_cacheIndexValues)) {
            self::$_cacheIndexValues = new CArrayList();
        }
        return self::$_cacheIndexValues;
    }
    /**
     * @static
     * @return CArrayList
     */
    private static function getCacheIndexes() {
        if (is_null(self::$_cacheIndexes)) {
            self::$_cacheIndexes = new CArrayList();
        }
        return self::$_cacheIndexes;
    }
    /**
     * @static
     * @return CArrayList
     */
    private static function getCachePersonIndexes() {
        if (is_null(self::$_cachePersonIndexes)) {
            self::$_cachePersonIndexes = new CArrayList();
        }
        return self::$_cachePersonIndexes;
    }
    /**
     * @static
     * @param $key
     * @return CPersonRatingIndex
     */
    public static function getPersonIndex($key) {
        if (!self::getCachePersonIndexes()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_PERSON_RATINGS, $key);
            if (!is_null($item)) {
                $obj = new CPersonRatingIndex($item);
                self::getCachePersonIndexes()->add($obj->getId(), $obj);
                self::getCachePersonIndexes()->add($obj->person_id."|".$obj->index_id, $obj);
            }
        }
        return self::getCachePersonIndexes()->getItem($key);
    }
    /**
     * @static
     * @param $key
     * @return CRatingIndex
     */
    public static function getRatingIndex($key) {
        if (!self::getCacheIndexes()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_RATING_INDEXES, $key);
            if (!is_null($item)) {
                $obj = new CRatingIndex($item);
                self::getCacheIndexes()->add($obj->getId(), $obj);
            }
        }
        return self::getCacheIndexes()->getItem($key);
    }
    /**
     * Все показатели с указанным названием.
     * (В разных годах могут быть показатели с одинаковым названием,
     * например, один и тот же за несколько лет)
     *
     * @static
     * @param $key
     * @return CArrayList
     */
    public static function getRatingIndexesByName($key) {
        $res = new CArrayList();
        foreach (self::getAllRatingIndexes()->getItems() as $index) {
            if (strtoupper($index->title) == strtoupper($key)) {
                $res->add($index->getId(), $index);
            }
        }
        return $res;
    }
    /**
     * @static
     * @return CArrayList
     */
    public static function getAllRatingIndexes() {
        if (!self::$_cacheIndexesInit) {
            self::$_cacheIndexesInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_RATING_INDEXES)->getItems() as $item) {
                $obj = new CRatingIndex($item);
                self::getCacheIndexes()->add($obj->getId(), $obj);
            }
        }
        return self::getCacheIndexes();
    }
    /**
     * @static
     * @return array
     */
    public static function getRatingIndexesList() {
        $res = array();
        foreach (self::getAllRatingIndexes()->getItems() as $item) {
            $res[$item->getId()] = $item->title;
        }
        return $res;
    }
    /**
     * Все показатели в указанном году
     *
     * @static
     * @param CTerm $year
     * @return CArrayList
     */
    public static function getRatingIndexesByYear(CTerm $year) {
        $res = new CArrayList();
        foreach (self::getAllRatingIndexes()->getItems() as $item) {
            if ($item->year_id == $year->getId()) {
                $res->add($item->getId(), $item);
            }
        }
        return $res;
    }
    /**
     * @static
     * @param $key
     * @return CRatingIndexValue
     */
    public static function getRatingIndexValue($key) {
        if (!self::getCacheIndexValues()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_RATING_INDEX_VALUES, $key);
            if (!is_null($item)) {
                $obj = new CRatingIndexValue($item);
                self::getCacheIndexValues()->add($key, $obj);
            }
        }
        return self::getCacheIndexValues()->getItem($key);
    }
}
