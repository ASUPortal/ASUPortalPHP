<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 10.06.12
 * Time: 10:51
 * To change this template use File | Settings | File Templates.
 */
class CDisciplinesManager{
    private static $_cacheDisciplines = null;
    /**
     * Кэш предметов
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheDisciplines() {
        if (is_null(self::$_cacheDisciplines)) {
            self::$_cacheDisciplines = new CArrayList();
        }
        return self::$_cacheDisciplines;
    }
    /**
     * Дисциплина с поиском и кэшем
     *
     * @static
     * @param $key
     * @return CDiscipline
     */
    public static function getDiscipline($key) {
        if (!self::getCacheDisciplines()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_DISCIPLINES, $key);
            if (!is_null($ar)) {
                $disc = new CDiscipline($ar);
                self::getCacheDisciplines()->add($disc->getId(), $disc);
            }
        }
        return self::getCacheDisciplines()->getItem($key);
    }
}
