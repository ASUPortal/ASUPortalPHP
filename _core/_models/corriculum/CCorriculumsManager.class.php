<?php
/**
 * Менеджер учебных планов
 *
 * @author TERRAN
 */
class CCorriculumsManager {
    private static $_cacheCorriculums = null;
    private static $_cacheCorriculumsInit = false;
    private static $_cacheDisciplines = null;
    private static $_cacheCycles = null;
    private static $_cacheLabors = null;
    private static $_cacheControls = null;
    private static $_cacheHours = null;
    private static $_cachePractices = null;
    /**
     * Кэш учебных планов
     * @return CArrayList 
     */
    public static function getCacheCorriculums() {
        if (is_null(self::$_cacheCorriculums)) {
            self::$_cacheCorriculums = new CArrayList();
        }
        return self::$_cacheCorriculums;
    }
    /**
     * Кэш дисциплин в учебных планах
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
     * Учебный план из кэша
     * @param type $key
     * @return CCorriculum 
     */
    public static function getCorriculum($key) {
        if (!self::getCacheCorriculums()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_CORRICULUMS, $key);
            if (!is_null($ar)) {
                $corriculum = new CCorriculum($ar);
                self::getCacheCorriculums()->add($corriculum->getId(), $corriculum);
            }
        }
        return self::getCacheCorriculums()->getItem($key);
    }
    public static function getCorriculumsList() {
        $res = array();
        foreach (self::getAllCorriculums()->getItems() as $c) {
            $title = "";
            if ($c->title == "") {
                if (!is_null($c->direction)) {
                    $title .= $c->direction->getValue();
                }
                if (!is_null($c->profile)) {
                    $title .= " (".$c->profile->getValue().")";
                }
            } else {
                $title = $c->title;
            }
            $res[$c->getId()] = $title;
        }
        return $res;
    }
    /**
     * Дисциплина из учебного плана
     *
     * @static
     * @param $key
     * @return CCorriculumDiscipline
     */
    public static function getDiscipline($key) {
        if (!self::getCacheDisciplines()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_CORRICULUM_DISCIPLINES, $key);
            if (!is_null($ar)) {
                $discipline = new CCorriculumDiscipline($ar);
                self::getCacheDisciplines()->add($discipline->getId(), $discipline);
            }
        }
        return self::getCacheDisciplines()->getItem($key);
    }
    /**
     * Все учебные планы
     * @return CArrayList 
     */
    public static function getAllCorriculums() {
        if (!self::$_cacheCorriculumsInit) {
            self::$_cacheCorriculumsInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_CORRICULUMS)->getItems() as $ar) {
                $cor = new CCorriculum($ar);
                self::getCacheCorriculums()->add($cor->getId(), $cor);
            }
        }
        return self::getCacheCorriculums();
    }
    /**
     * Кэш циклов
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheCycles() {
        if (is_null(self::$_cacheCycles)) {
            self::$_cacheCycles = new CArrayList();
        }
        return self::$_cacheCycles;
    }
    /**
     * Цикл учебного плана
     *
     * @static
     * @param $key
     * @return CCorriculumCycle
     */
    public static function getCycle($key) {
        if (!self::getCacheCycles()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_CORRICULUM_CYCLES, $key);
            if (!is_null($ar)) {
                $obj = new CCorriculumCycle($ar);
                self::getCacheCycles()->add($obj->getId(), $obj);
            }
        }
        return self::getCacheCycles()->getItem($key);
    }
    /**
     * Кэш трудоемкости
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheLabors() {
        if (is_null(self::$_cacheLabors)) {
            self::$_cacheLabors = new CArrayList();
        }
        return self::$_cacheLabors;
    }
    /**
     * Трудоемоксть дисциплины из кэша
     *
     * @static
     * @param $key
     * @return CCorriculumDisciplineLabor
     */
    public static function getLabor($key) {
        if (!self::getCacheLabors()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_CORRICULUM_DISCIPLINE_LABORS, $key);
            if (!is_null($ar)) {
                $obj = new CCorriculumDisciplineLabor($ar);
                self::getCacheLabors()->add($obj->id, $obj);
            }
        }
        return self::getCacheLabors()->getItem($key);
    }
    /**
     * Кэш форм контроля
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheControls() {
        if (is_null(self::$_cacheControls)) {
            self::$_cacheControls = new CArrayList();
        }
        return self::$_cacheControls;
    }
    /**
     * Форма контроля для дисциплины по коду из кэша
     *
     * @static
     * @param $key
     * @return CCorriculumDisciplineControl
     */
    public static function getControl($key) {
        if (!self::getCacheControls()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_CORRICULUM_DISCIPLINE_CONTROLS, $key);
            if (!is_null($ar)) {
                $obj = new CCorriculumDisciplineControl($ar);
                self::getCacheControls()->add($obj->id, $obj);
            }
        }
        return self::getCacheControls()->getItem($key);
    }
    /**
     * Кэш распределения нагрузки по семестрам
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheHours() {
        if (is_null(self::$_cacheHours)) {
            self::$_cacheHours = new CArrayList();
        }
        return self::$_cacheHours;
    }

    /**
     * @param $key
     * @return CCorriculumDisciplineHour
     */
    public static function getHour($key) {
        if (!self::getCacheHours()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_CORRICULUM_DISCIPLINE_HOURS, $key);
            if (!is_null($ar)) {
                $obj = new CCorriculumDisciplineHour($ar);
                self::getCacheHours()->add($obj->id, $obj);
            }
        }
        return self::getCacheHours()->getItem($key);
    }

    /**
     * Кэш практик
     *
     * @return CArrayList
     */
    private static function getCachePractices() {
        if (is_null(self::$_cachePractices)) {
            self::$_cachePractices = new CArrayList();
        }
        return self::$_cachePractices;
    }

    /**
     * Практика в учебном плане
     *
     * @param $key
     * @return CCorriculumPractice
     */
    public static function getPractice($key) {
        if (!self::getCachePractices()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_CORRICULUM_PRACTICES, $key);
            if (!is_null($ar)) {
                $obj = new CCorriculumPractice($ar);
                self::getCachePractices()->add($key, $obj);
            }
        }
        return self::getCachePractices()->getItem($key);
    }
}

?>
