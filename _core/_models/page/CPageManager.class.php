<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 13.04.13
 * Time: 11:16
 * To change this template use File | Settings | File Templates.
 */

class CPageManager {
    private static $_cachePages = null;

    /**
     * @return CArrayList|null
     */
    private static function getCachePages() {
        if (is_null(self::$_cachePages)) {
            self::$_cachePages = new CArrayList();
        }
        return self::$_cachePages;
    }
    public static function getPage($key) {
        if (!self::getCachePages()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_PAGES, $key);
            if (!is_null($ar)) {
                $page = new CPage($ar);
                self::getCachePages()->add($page->getId(), $page);
            }
        }
        return self::getCachePages()->getItem($key);
    }
}