<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.06.12
 * Time: 10:10
 * To change this template use File | Settings | File Templates.
 *
 * Менеджер вопросов к ГОСам
 */
class CSEBQuestionsManager {
    private static $_cacheQuestions = null;
    private static $_cacheDisciplinesBySpeciality = null;
    private static $_cacheInit = false;
    private static $_disciplines = null;
    /**
     * Кэш вопросов к ГОСам
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheQuestions() {
        if (is_null(self::$_cacheQuestions)) {
            self::$_cacheQuestions = new CArrayList();
        }
        return self::$_cacheQuestions;
    }
    /**
     * Кэш дисциплин по специальности
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheDisciplinesBySpeciality() {
        if (is_null(self::$_cacheDisciplinesBySpeciality)) {
            self::$_cacheDisciplinesBySpeciality = new CArrayList();
        }
        return self::$_cacheDisciplinesBySpeciality;
    }
    /**
     * Все вопросы к ГОСам, какие есть
     *
     * @static
     * @return CArrayList
     */
    public static function getAllQuestions() {
        if (!self::$_cacheInit) {
            self::$_cacheInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_SEB_QUESTIONS)->getItems() as $i) {
                $q = new CSEBQuestion($i);
                self::getCacheQuestions()->add($q->getId(), $q);
            }
        }
        return self::getCacheQuestions();
    }
    /**
     * Экзаменационный вопрос с учетом кэша
     *
     * @static
     * @param $key
     * @return CSebQuestion
     */
    public static function getQuestion($key) {
        if (!self::getCacheQuestions()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_SEB_QUESTIONS, $key);
            if (!is_null($ar)) {
                $q = new CSEBQuestion($ar);
                self::getCacheQuestions()->add($q->getId(), $q);
            }
        }
        return self::getCacheQuestions()->getItem($key);
    }
    /**
     * Лист дисциплин, по которым есть вопросы
     *
     * @static
     * @return CArrayList
     */
    public static function getDisciplines() {
        if (is_null(self::$_disciplines)) {
            self::$_disciplines = new CArrayList();
            $q = new CQuery();
            $q->select("distinct(discipline_id)")
            ->from(TABLE_SEB_QUESTIONS);
            foreach ($q->execute()->getItems() as $ar) {
                $disc = CTaxonomyManager::getCacheDisciplines()->getItem($ar["discipline_id"]);
                if (!is_null($disc)) {
                    self::$_disciplines->add($disc->getId(), $disc);
                }
            }
        }
        return self::$_disciplines;
    }
    /**
     * Список дисциплин для указанной специальности, по
     * которым есть вопросы
     *
     * @static
     * @param CTerm $speciality
     * @return CArrayList
     */
    public static function getDisciplinesBySpeciality(CTerm $speciality) {
        if (!self::getCacheDisciplinesBySpeciality()->hasElement($speciality->getId())) {
            $res = new CArrayList();
            $q = new CQuery();
            $q->select("distinct(discipline_id)")
                ->from(TABLE_SEB_QUESTIONS)
                ->condition("speciality_id=".$speciality->getId());
            foreach ($q->execute()->getItems() as $ar) {
                $disc = CTaxonomyManager::getCacheDisciplines()->getItem($ar["discipline_id"]);
                if (!is_null($disc)) {
                    $res->add($disc->getId(), $disc);
                }
            }
            self::getCacheDisciplinesBySpeciality()->add($speciality->getId(), $res);
        }
        return self::getCacheDisciplinesBySpeciality()->getItem($speciality->getId());
    }
    /**
     * Лист дисциплин по специальностям для подстановки
     *
     * @static
     * @param CTerm $speciality
     * @return array
     */
    public static function getDisciplinesBySpecialityList(CTerm $speciality) {
        $res = array();
        foreach (self::getDisciplinesBySpeciality($speciality)->getItems() as $i) {
            $res[$i->getId()] = $i->getValue();
        }
        return $res;
    }
    /**
     * Список вопросов по дисциплине
     *
     * @static
     * @param CTerm $discipline
     * @return CArrayList
     */
    public static function getQuestionsByDiscipline(CTerm $discipline) {
        $arr = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_SEB_QUESTIONS, "discipline_id=".$discipline->getId())->getItems() as $i) {
            $q = new CSEBQuestion($i);
            $arr->add($q->getId(), $q);
            self::getCacheQuestions()->add($q->getId(), $q);
        }
        return $arr;
    }
}
