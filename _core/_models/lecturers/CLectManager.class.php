<?php

class CLectManager {
    private static $_cacheLects = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheLects() {
        if (is_null(self::$_cacheLects)) {
            self::$_cacheLects = new CArrayList();
        }
        return self::$_cacheLects;
    }
    public static function getLect($key) {
        if (!self::getCacheLects()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_USERS, $key);
            if (!is_null($ar)) {
                $lect = new CLect($ar);
                self::getCacheLects()->add($lect->getId(), $lect);
            }
        }
        return self::getCacheLects()->getItem($key);
    }
}