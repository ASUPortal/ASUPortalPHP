<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 31.03.13
 * Time: 15:44
 * To change this template use File | Settings | File Templates.
 */

class CNewsManager {
    private static $_cacheNews = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheNews() {
        if (is_null(self::$_cacheNews)) {
            self::$_cacheNews = new CArrayList();
        }
        return self::$_cacheNews;
    }

    /**
     * @param $key
     * @return CNewsItem
     */
    public static function getNewsItem($key) {
        if (!self::getCacheNews()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_NEWS, $key);
            if (!is_null($ar)) {
                $newsItem = new CNewsItem($ar);
                self::getCacheNews()->add($newsItem->getId(), $newsItem);
            }
        }
        return self::getCacheNews()->getItem($key);
    }
}