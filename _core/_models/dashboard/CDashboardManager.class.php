<?php
class CDashboardManager {
	private static $_cacheItems = null;
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
}