<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 01.04.13
 * Time: 9:54
 * To change this template use File | Settings | File Templates.
 */

class CGrantManager {
    private static $_cacheGrants = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheGrants() {
        if (is_null(self::$_cacheGrants)) {
            self::$_cacheGrants = new CArrayList();
        }
        return self::$_cacheGrants;
    }

    /**
     * @param $key
     * @return CGrant
     */
    public static function getGrant($key) {
        if (!self::getCacheGrants()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_GRANTS, $key);
            if (!is_null($ar)) {
                $grant = new CGrant($ar);
                self::getCacheGrants()->add($grant->getId(), $grant);
            }
        }
        return self::getCacheGrants()->getItem($key);
    }
}