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
    private static $_cacheLoads;
    private static $_cacheWorks;

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
     * @return CArrayList
     */
    private static function getCacheLoads() {
        if (is_null(self::$_cacheLoads)) {
            self::$_cacheLoads = new CArrayList();
        }
        return self::$_cacheLoads;
    }

    /**
     * @return CArrayList
     */
    private static function getCacheWorks() {
        if (is_null(self::$_cacheWorks)) {
            self::$_cacheWorks = new CArrayList();
        }
        return self::$_cacheWorks;
    }

    /**
     * Разные виды работ из индивидуального плана
     *
     * @param $key
     * @return CIndPlanPersonWork
     */
    public static function getWork($key) {
        if (!self::getCacheWorks()->hasElement($key)) {
            $ar = null;
            if (is_numeric($key)) {
                $ar = CActiveRecordProvider::getById(TABLE_IND_PLAN_WORKS, $key);
            }
            if (!is_null($ar)) {
                $load = new CIndPlanPersonWork($ar);
                self::getCacheWorks()->add($load->getId(), $load);
            }
        }
        return self::getCacheWorks()->getItem($key);
    }

    /**
     * Нагрузка (на ставку, на полставки и т.п.) одного сотрудника
     * в каком-нибудь году
     *
     * @param $key
     * @return CIndPlanPersonLoad
     */
    public static function getLoad($key) {
        if (!self::getCacheLoads()->hasElement($key)) {
            $ar = null;
            if (is_numeric($key)) {
                $ar = CActiveRecordProvider::getById(TABLE_IND_PLAN_LOADS, $key);
            }
            if (!is_null($ar)) {
                $load = new CIndPlanPersonLoad($ar);
                self::getCacheLoads()->add($load->getId(), $load);
            }
        }
        return self::getCacheLoads()->getItem($key);
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
     * @param $category
     * @return array
     */
    public static function getWorklistByCategory($category) {
        $result = array();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_WORKTYPES, "id_razdel=".$category)->getItems() as $ar) {
            $w = new CIndPlanWorktype($ar);
            self::getCacheWorktypes()->add($w->getId(), $w);
            $result[$w->getId()] = $w->name;
        }
        return $result;
    }
    
    /**
     * Получить нагрузки выбранного года и преподавателя
     * @param CTerm $year
     * @param CPerson $person
     * @return array
     */
    public static function getLoadsByYearAndPerson(CTerm $year, CPerson $person) {
        $loads = array();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_LOADS, "year_id =".$year->getId()." and person_id =".$person->getId())->getItems() as $item) {
            $load = new CIndPlanPersonLoad($item);
            $loads[$load->getId()] = $load->getType();
        }
        return $loads;
    }
}