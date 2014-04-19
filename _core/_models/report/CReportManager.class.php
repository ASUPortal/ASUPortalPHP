<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 19.04.14
 * Time: 15:50
 * To change this template use File | Settings | File Templates.
 */

class CReportManager {
    private static $_cacheReports = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheReports() {
        if (is_null(self::$_cacheReports)) {
            self::$_cacheReports = new CArrayList();
        }
        return self::$_cacheReports;
    }

    /**
     * @param $key
     * @return CReport
     */
    public static function getReport($key) {
        if (!self::getCacheReports()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_REPORTS, $key);
            if (!is_null($ar)) {
                $obj = new CReport($ar);
                self::getCacheReports()->add($key, $obj);
            }
        }
        return self::getCacheReports()->getItem($key);
    }
}