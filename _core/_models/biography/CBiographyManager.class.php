<?php

class CBiographyManager {
    private static $_cacheBiographys = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheBiographys() {
        if (is_null(self::$_cacheBiographys)) {
            self::$_cacheBiographys = new CArrayList();
        }
        return self::$_cacheBiographys;
    }
    public static function getBiography($key) {
        if (!self::getCacheBiographys()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_BIOGRAPHY, $key);
            if (!is_null($ar)) {
                $biography = new CBiography($ar);
                self::getCacheBiographys()->add($biography->getId(), $biography);
            }
        }
        return self::getCacheBiographys()->getItem($key);
    }
    public static function getBiographyByUser($key) {
    	if (!self::getCacheBiographys()->hasElement($key)) {
    		$item = CActiveRecordProvider::getById(TABLE_BIOGRAPHY, $key);
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_BIOGRAPHY, "user_id = '".$key."'")->getItems() as $item) {
    			$biography = new CBiography($item);
    			self::getCacheBiographys()->add($biography->id, $biography);
    			self::getCacheBiographys()->add($biography->user_id, $biography);
    		}
    	}
    	return self::getCacheBiographys()->getItem($key);
    }
}