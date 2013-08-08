<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 29.07.13
 * Time: 20:23
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanManager {
    private static $_cacheWorktypes;

    /**
     * @return CArrayList
     */
    private static function getCacheWorktypes() {
        if (is_null(self::$_cacheWorktypes)) {
            self::$_cacheWorktypes = new CArrayList();
        }
        return self::$_cacheWorktypes;
    }

    /**
     * @param $key
     * @return CIndPlanWorktype
     */
    public static function getWorktype($key) {
        if (!self::getCacheWorktypes()->hasElement($key)) {
            $ar = null;
            if (is_numeric($key)) {
                $ar = CActiveRecordProvider::getById(TABLE_IND_PLAN_WORKTYPES, $key);
            }
            if (!is_null($ar)) {
                $worktype = new CIndPlanWorktype($ar);
                self::getCacheWorktypes()->add($worktype->getId(), $worktype);
            }
        }
        return self::getCacheWorktypes()->getItem($key);
    }

    /**
     * @param CPerson $person
     * @param CTerm $year
     * @return CIndPlanPersonLoad
     */
    public static function getLoadByPersonAndYear(CPerson $person, CTerm $year) {
        /**
         * Честно говоря, не вижу большого смысла кешировать эту лажу, поэтому
         * данные каждый раз извлекаются заново. Пусть так и будет
         */
        $load = new CIndPlanPersonLoad();
        $load->person = $person;
        $load->year = $year;
        return $load;
    }

    /**
     * @param $key
     * @return CIndPlanPersonLoadConclusion|null
     */
    public static function getConclusion($key) {
        $c = null;
        $ar = CActiveRecordProvider::getById(TABLE_IND_PLAN_CONCLUSTIONS, $key);
        if (!is_null($ar)) {
            $c = new CIndPlanPersonConclusion($ar);
        }
        return $c;
    }

    /**
     * @param $key
     * @return CIndPlanPersonChange|null
     */
    public static function getChange($key) {
        $c = null;
        $ar = CActiveRecordProvider::getById(TABLE_IND_PLAN_CHANGES, $key);
        if (!is_null($ar)) {
            $c = new CIndPlanPersonChange($ar);
        }
        return $c;
    }

    /**
     * @param $key
     * @return CIndPlanPersonPublication|null
     */
    public static function getPublication($key) {
        $c = null;
        $ar = CActiveRecordProvider::getById(TABLE_IND_PLAN_PUBLICATIONS, $key);
        if (!is_null($ar)) {
            $c = new CIndPlanPersonPublication($ar);
        }
        return $c;
    }
}