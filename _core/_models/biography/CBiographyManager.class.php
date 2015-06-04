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
}