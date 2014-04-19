<?php
class CDashboardManager {
	private static $_cacheItems = null;
    private static $_cacheDashboardReports = null;
	/**
	 * 
	 * @return CArrayList
	 */
	private static function getCacheItems() {
		if (is_null(self::$_cacheItems)) {
			self::$_cacheItems = new CArrayList();
		}
		return self::$_cacheItems;
	}

    /**
     * @return CArrayList|null
     */
    private static function getCacheDashboardReports() {
        if (is_null(self::$_cacheDashboardReports)) {
            self::$_cacheDashboardReports = new CArrayList();
        }
        return self::$_cacheDashboardReports;
    }
	/**
	 * Получить Item 
	 * 
	 * @param unknown $key
	 * @return CDashboardItem
	 */
	public static function getDashboardItem($key) {
		if (!self::getCacheItems()->hasElement($key)) {
			$item = CActiveRecordProvider::getById(TABLE_DASHBOARD, $key);
			if (!is_null($item)) {
				$dbItem = new CDashboardItem($item);
				self::getCacheItems()->add($dbItem->getId(), $dbItem);
			}
		}
		return self::getCacheItems()->getItem($key);
	}

    /**
     * @param $key
     * @return CDashboardReport
     */
    public static function getDashboardReport($key) {
        if (!self::getCacheDashboardReports()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_DASHBOARD_REPORTS, $key);
            if (!is_null($ar)) {
                $obj = new CDashboardReport($ar);
                self::getCacheDashboardReports()->add($key, $obj);
            }
        }
        return self::getCacheDashboardReports()->getItem($key);
    }
}