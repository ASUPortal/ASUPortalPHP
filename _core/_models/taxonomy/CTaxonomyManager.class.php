<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 08.05.12
 * Time: 21:10
 * To change this template use File | Settings | File Templates.
 *
 * Менеджер по работе со словарями и справочниками
 */

    define("TAXONOMY_DEPARTMENT_ROLES", "department_roles");

class CTaxonomyManager {
    private static $_cachePosts = null;
    private static $_cacheTerms = null;
    private static $_cacheDisciplines = null;
    private static $_cacheTaxonomy = null;
    private static $_cacheTypes = null;
    private static $_fullInit = false;
    private static $_cacheSpecialities = null;
    private static $_cacheYears = null;
    private static $_cacheMarks = null;
    private static $_cacheEdForms = null;
    private static $_cacheTitles = null;
    private static $_cacheDegrees = null;
    private static $_cacheOrderTypes = null;
    private static $_cacheOrderMoneys = null;
    private static $_cacheControlTypes = null;
    private static $_cacheYearParts = null;
    private static $_cacheGenders = null;
    private static $_cacheUsatyOrderTypes = null;
    private static $_cacheLanguages = null;
    private static $_cacheDiplomConfirmations = null;
    private static $_cachePracticePlaces = null;
    private static $_cacheLegacyTaxonomies = null;
    private static $_cacheLegacyTerms = null;
    /**
     * Кэш должностей
     *
     * @static
     * @return CArrayList
     */
    public static function getCachePosts() {
        if (is_null(self::$_cachePosts)) {
            self::$_cachePosts = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_POSTS)->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cachePosts->add($term->getId(), $term);
            }
        }
        return self::$_cachePosts;
    }
    /**
     * Кэш ученых степеней
     *
     * @return CArrayList|null
     */
    public static function getCacheDegrees() {
        if (is_null(self::$_cacheDegrees)) {
            self::$_cacheDegrees = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_DEGREES)->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheDegrees->add($term->getId(), $term);
            }
        }
        return self::$_cacheDegrees;
    }
    /**
     * @static
     * @return CArrayList
     */
    public static function getCacheTitles() {
        if (is_null(self::$_cacheTitles)) {
            self::$_cacheTitles = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_TITLES)->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheTitles->add($term->getId(), $term);
            }
        }
        return self::$_cacheTitles;
    }
    /**
     * Звание
     *
     * @param $key
     * @return CTerm
     */
    public static function getTitle($key) {
        return self::getCacheTitles()->getItem($key);
    }

    /**
     * Ученая степень из словаря
     *
     * @param $key
     * @return CTerm
     */
    public static function getDegree($key) {
        return self::getCacheDegrees()->getItem($key);
    }
    /**
     * Кэш оценок
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheMarks() {
        if (is_null(self::$_cacheMarks)) {
            self::$_cacheMarks = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_MARKS)->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheMarks->add($term->getId(), $term);
            }
        }
        return self::$_cacheMarks;
    }
    /**
     * Оценки для подстановки
     *
     * @static
     * @return array
     */
    public static function getMarksList() {
        $arr = array();
        foreach (self::getCacheMarks()->getItems() as $item) {
            $arr[$item->getId()] = $item->getValue();
        }
        return $arr;
    }
    /**
     * Кэш учебных годов
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheYears() {
        if (is_null(self::$_cacheYears)) {
            self::$_cacheYears = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_YEARS)->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheYears->add($term->getId(), $term);
                self::$_cacheYears->add($term->getValue(), $term);
            }
        }
        return self::$_cacheYears;
    }
    /**
     * Учебный год по идентификатору
     *
     * @static
     * @param $key
     * @return CTerm
     */
    public static function getYear($key) {
        return self::getCacheYears()->getItem($key);
    }
    /**
     * Список учебных годов для подстановки
     *
     * @static
     * @return array
     */
    public static function getYearsList() {
        $arr = array();
        foreach (self::getCacheYears()->getItems() as $i) {
            $arr[$i->getId()] = $i->getValue();
        }
        asort($arr);
        return $arr;
    }
    /**
     * Кэш специальностей
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheSpecialities() {
        if (is_null(self::$_cacheSpecialities)) {
            self::$_cacheSpecialities = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_SPECIALITIES)->getItems() as $item) {
                $term = new CSpeciality($item);
                self::$_cacheSpecialities->add($term->getId(), $term);
            }
        }
        return self::$_cacheSpecialities;
    }
    /**
     * Специальность
     *
     * @static
     * @param $key
     * @return CSpeciality
     */
    public static function getSpeciality($key) {
        return self::getCacheSpecialities()->getItem($key);
    }
    /**
     * Специальности для подстановки
     *
     * @static
     * @return array
     */
    public static function getSpecialitiesList() {
        $arr = array();
        foreach (self::getCacheSpecialities()->getItems() as $i) {
            $arr[$i->getId()] = $i->getValue();
        }
        return $arr;
    }
    /**
     * Кэш дисциплин
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheDisciplines() {
        if (is_null(self::$_cacheDisciplines)) {
            self::$_cacheDisciplines = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_DISCIPLINES, "name asc")->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheDisciplines->add($term->getId(), $term);
            }
        }
        return self::$_cacheDisciplines;
    }
    /**
     * Кэш форм обучения
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheEducationForms() {
        if (is_null(self::$_cacheEdForms)) {
            self::$_cacheEdForms = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_EDUCATION_FORMS)->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheEdForms->add($term->getId(), $term);
            }
        }
        return self::$_cacheEdForms;
    }
    /**
     * Форма обучения
     *
     * @static
     * @param $key
     * @return CTerm
     */
    public static function getEductionForm($key) {
        return self::getCacheEducationForms()->getItem($key);
    }
    /**
     * Список дисциплин для подстановки
     *
     * @static
     * @return array
     */
    public static function getDisciplinesList() {
        $arr = array();
        foreach (self::getCacheDisciplines()->getItems() as $i) {
            $arr[$i->getId()] = $i->getValue();
        }
        return $arr;
    }
    /**
     * Дисциплина из кэша
     *
     * @static
     * @param $key
     * @return CTerm
     */
    public static function getDiscipline($key) {
        return self::getCacheDisciplines()->getItem($key);
    }
    /**
     * Кэш типов участия на кафедре
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheTypes() {
        if (is_null(self::$_cacheTypes)) {
            self::$_cacheTypes = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_TYPES)->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheTypes->add($term->getId(), $term);
            }
        }
        return self::$_cacheTypes;
    }
    /**
     * Кэш словарей таксономии
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheTaxonomy() {
        if (is_null(self::$_cacheTaxonomy)) {
            self::$_cacheTaxonomy = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_TAXONOMY, "name asc")->getItems() as $item) {
                $taxonomy = new CTaxonomy($item);
                self::$_cacheTaxonomy->add($taxonomy->getAlias(), $taxonomy);
                self::$_cacheTaxonomy->add($taxonomy->getId(), $taxonomy);
            }
        }
        return self::$_cacheTaxonomy;
    }
    /**
     * Лист таксономий в виде объектов
     *
     * @static
     * @return CArrayList
     */
    public static function getTaxonomiesObjectList() {
        $res = new CArrayList();
        foreach (self::getCacheTaxonomy()->getItems() as $key=>$value) {
            if (is_int($key)) {
                $res->add($res->getCount(), $value);
            }
        }
        return $res;
    }
    /**
     * Массив названий таксономий для подстановки
     *
     * @static
     * @return array
     */
    public static function getTaxonomiesList() {
        $res = array();
        foreach (self::getCacheTaxonomy()->getItems() as $key=>$value) {
            if (is_int($key)) {
                $res[$key] = $value->getName();
            }
        }
        return $res;
    }
    /**
     * Должность по идентификатору
     *
     * @static
     * @param $id
     * @return CTerm
     */
    public static function getPostById($id) {
        if (self::getCachePosts()->hasElement($id)) {
            return self::getCachePosts()->getItem($id);
        }
        return null;
    }
    /**
     * Тип участия на кафедре по идентификатору
     *
     * @static
     * @param $id
     * @return CTerm
     */
    public static function getTypeById($id) {
        if (self::getCacheTypes()->hasElement($id)) {
            return self::getCacheTypes()->getItem($id);
        }
        return null;
    }

    /**
     * Типы участия на кафедре
     *
     * @return array
     */
    public static function getTypesList() {
        $result = array();
        foreach (self::getCacheTypes()->getItems() as $type) {
            $result[$type->getId()] = $type->getValue();
        }
        return $result;
    }
    /**
     * Объект таксономии по псевдониму или id
     *
     * @static
     * @param $id
     * @return CTaxonomy
     */
    public static function  getTaxonomy($id) {
        return self::getCacheTaxonomy()->getItem($id);
    }
    /**
     * Полная инициализация всех словарей таксономии
     *
     * @static
     */
    public static function fullInit() {
        if (!self::$_fullInit) {
            foreach (self::getCacheTaxonomy()->getItems() as $item) {
                $item->initTerms();
            }
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_TAXONOMY_TERMS)->getItems() as $item) {
                $term = new CTerm($item);
                $taxonomy = $term->getParentTaxonomy();
                $taxonomy->addTerm($term);
            }
            self::getCachePosts();
            self::getCacheTypes();
            self::getCacheDisciplines();
        }
    }
    /**
     * Кэш терминов (на всякий случай)
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheTerms() {
        if (is_null(self::$_cacheTerms)) {
            self::$_cacheTerms = new CArrayList();
        }
        return self::$_cacheTerms;
    }
    /**
     * CTerm по id
     *
     * @static
     * @param $id
     * @return CTerm
     */
    public static function getTerm($id) {
        if (!self::getCacheTerms()->hasElement($id)) {
            $rec = CActiveRecordProvider::getById(TABLE_TAXONOMY_TERMS, $id);
            if (!is_null($rec)) {
                $term = new CTerm($rec);
                self::getCacheTerms()->add($term->getId(), $term);
            }
        }
        if (!self::getCacheTerms()->hasElement($id)) {
            self::getCacheTerms()->add($id, null);
        }
        return self::getCacheTerms()->getItem($id);
    }
    /**
     * Оценка
     *
     * @static
     * @param $key
     * @return CTerm
     */
    public static function getMark($key) {
        return self::getCacheMarks()->getItem($key);
    }
    /**
     * Все должности
     *
     * @static
     * @return CArrayList
     */
    public static function getPosts() {
        return self::getCachePosts();
    }
    /**
     * Все звания
     *
     * @static
     * @return CArrayList
     */
    public static function getTitles() {
        return self::getCacheTitles();
    }

    /**
     * Типы приказов (основной, совместительноство, дополнительный)
     *
     * @return CArrayList|null
     */
    public static function getCacheOrderTypes() {
        if (is_null(self::$_cacheOrderTypes)) {
            self::$_cacheOrderTypes = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_ORDER_TYPES)->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheOrderTypes->add($term->getId(), $term);
            }
        }
        return self::$_cacheOrderTypes;
    }

    /**
     * Типы приказов (бюджет, внебюджет)
     *
     * @return CArrayList|null
     */
    public static function getCacheOrderMoneyTypes() {
        if (is_null(self::$_cacheOrderMoneys)) {
            self::$_cacheOrderMoneys = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_ORDER_MONEY_TYPES)->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheOrderMoneys->add($term->getId(), $term);
            }
        }
        return self::$_cacheOrderMoneys;
    }

    /**
     * @return CArrayList
     */
    private static function getCacheControlTypes() {
        if (is_null(self::$_cacheControlTypes)) {
            self::$_cacheControlTypes = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_STUDENTS_CONTROL_TYPES, "1=1", "name asc")->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheControlTypes->add($term->getId(), $term);
            }
        }
        return self::$_cacheControlTypes;
    }

    /**
     * Вид контроля (в журнале успеваемости)
     *
     * @param $key
     * @return CTerm
     */
    public static function getControlType($key) {
        return self::getCacheControlTypes()->getItem($key);
    }

    /**
     * @return array
     */
    public static function getControlTypesList() {
        $res = array();
        foreach (self::getCacheControlTypes()->getItems() as $type) {
            $res[$type->getId()] = $type->getValue();
        }
        return $res;
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheYearParts() {
        if (is_null(self::$_cacheYearParts)) {
            self::$_cacheYearParts = new CArrayList();
        }
        return self::$_cacheYearParts;
    }

    /**
     * Семестр
     *
     * @param $key
     * @return CTerm
     */
    public static function getYearPart($key) {
        if (!self::getCacheYearParts()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_YEAR_PARTS, $key);
            if (!is_null($item)) {
                $term = new CTerm($item);
                self::getCacheYearParts()->add($key, $term);
            }
        }
        return self::getCacheYearParts()->getItem($key);
    }

    /**
     * Список семестров для автоподстановки
     *
     * @return array
     */
    public static function getYearPartsList() {
        $res = array();
        foreach (CActiveRecordProvider::getAllFromTable(TABLE_YEAR_PARTS)->getItems() as $item) {
            $term = new CTerm($item);
            self::getCacheYearParts()->add($term->getId(), $term);
            $res[$term->getId()] = $term->getValue();
        }
        return $res;
    }

    /**
     *  Кэш полов
     *
     * @return CArrayList|null
     */
    public static function getCacheGenders() {
        if (is_null(self::$_cacheGenders)) {
            self::$_cacheGenders = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_GENDERS)->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheGenders->add($term->getId(), $term);
            }
        }
        return self::$_cacheGenders;
    }

    /**
     * Список полов для подстановки.
     * Шикарно просто, отдельная таблица для этого
     *
     * @return array
     */
    public static function getGendersList() {
        $res = array();
        foreach (self::getCacheGenders()->getItems() as $term) {
            $res[$term->getId()] = $term->getValue();
        }
        return $res;
    }

    /**
     * Получить пол
     *
     * @param $key
     * @return CTerm
     */
    public static function getGender($key) {
        return self::getCacheGenders()->getItem($key);
    }

    /**
     * Кэш иностранных языков
     *
     * @return CArrayList|null
     */
    public static function getCacheLanguages() {
        if (is_null(self::$_cacheLanguages)) {
            self::$_cacheLanguages = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_LANGUAGES)->getItems() as $item) {
                $lang = new CTerm($item);
                self::$_cacheLanguages->add($lang->getId(), $lang);
            }
        }
        return self::$_cacheLanguages;
    }

    /**
     * Иностранный язык
     *
     * @param $key
     * @return CTerm
     */
    public static function getLanguage($key) {
        return self::getCacheLanguages()->getItem($key);
    }

    /**
     * @return CArrayList|null
     */
    public static function getCacheDiplomConfirmations() {
        if (is_null(self::$_cacheDiplomConfirmations)) {
            self::$_cacheDiplomConfirmations = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_DIPLOM_CONFIRMATIONS)->getItems() as $item) {
                $term = new CTerm($item);
                self::$_cacheDiplomConfirmations->add($term->getId(), $term);
            }
        }
        return self::$_cacheDiplomConfirmations;
    }

    /**
     * Кэш мест практики
     *
     * @return CArrayList|null
     */
    private static function getCachePracticePlaces() {
        if (is_null(self::$_cachePracticePlaces)) {
            self::$_cachePracticePlaces = new CArrayList();
        }
        return self::$_cachePracticePlaces;
    }

    /**
     * Место практики
     *
     * @param $key
     * @return CPracticePlace
     */
    public static function getPracticePlace($key) {
        if (!self::getCachePracticePlaces()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_PRACTICE_PLACES, $key);
            if (!is_null($item)) {
                $place = new CPracticePlace($item);
                self::getCachePracticePlaces()->add($place->getId(), $place);
            }
        }
        return self::getCachePracticePlaces()->getItem($key);
    }

    /**
     * Все места практики для подстановки
     *
     * @return array
     */
    public static function getPracticePlacesList() {
        $res = array();
        foreach (CActiveRecordProvider::getAllFromTable(TABLE_PRACTICE_PLACES." as place", "place.name asc")->getItems() as $item) {
            $place = new CPracticePlace($item);
            self::getCachePracticePlaces()->add($place->getId(), $place);
            $res[$place->getId()] = $place->getValue();
        }
        return $res;
    }

    /**
     * Статусу утверждения диплома
     *
     * @param $key
     * @return CTerm
     */
    public static function getDiplomConfirmation($key) {
        return self::getCacheDiplomConfirmations()->getItem($key);
    }

    /**
     * Типы приказов УГАТУ и кафедры
     *
     * @return CArrayList
     */
    private static function getCacheUsatuOrderTypes() {
        if (is_null(self::$_cacheUsatyOrderTypes)) {
            self::$_cacheUsatyOrderTypes = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_USATU_ORDER_TYPES)->getItems() as $ar) {
                $type = new CTerm($ar);
                self::$_cacheUsatyOrderTypes->add($type->getId(), $type);
            }
        }
        return self::$_cacheUsatyOrderTypes;
    }

    /**
     * Тип приказа УГАТУ/кафедры
     *
     * @param $key
     * @return CTerm
     */
    public static function getUsatuOrderType($key) {
        return self::getCacheUsatuOrderTypes()->getItem($key);
    }

    /**
     * Типы приказов для подстановки
     *
     * @return array
     */
    public static function getUsatuOrderTypesList() {
        $res = array();
        foreach (self::getCacheUsatuOrderTypes()->getItems() as $type) {
            $res[$type->getId()] = $type->getValue();
        }
        return $res;
    }

    /**
     * Кэш унаследованных таксономий
     *
     * @return CArrayList
     */
    private static function getCacheLegacyTaxonomies() {
        if (is_null(self::$_cacheLegacyTaxonomies)) {
            self::$_cacheLegacyTaxonomies = new CArrayList();
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_TAXONOMIES_LEGACY, "comment asc")->getItems() as $ar) {
                $legacy = new CTaxonomyLegacy($ar);
                self::$_cacheLegacyTaxonomies->add($legacy->getId(), $legacy);
                self::$_cacheLegacyTaxonomies->add($legacy->getAlias(), $legacy);
            }
        }
        return self::$_cacheLegacyTaxonomies;
    }

    /**
     * Все унаследованные таксономии
     *
     * @return CArrayList
     */
    public static function getLegacyTaxonomiesObjects() {
        $res = new CArrayList();
        foreach (self::getCacheLegacyTaxonomies()->getItems() as $t) {
            $res->add($t->getId(), $t);
        }
        return $res;
    }

    /**
     * @return array
     */
    public static function getLegacyTaxonomiesObjectsList() {
        $res = array();
        foreach (self::getLegacyTaxonomiesObjects()->getItems() as $taxonomy) {
            $res[$taxonomy->getId()] = $taxonomy->getName();
        }
        return $res;
    }

    /**
     * Унаследованная таксономию по ключу
     *
     * @param $id
     * @return CTaxonomyLegacy
     */
    public static function getLegacyTaxonomy($id) {
        return self::getCacheLegacyTaxonomies()->getItem($id);
    }

    /**
     * Кэш терминов
     *
     * @return CArrayList|null
     */
    private static function getCacheLegacyTerms() {
        if (is_null(self::$_cacheLegacyTerms)) {
            self::$_cacheLegacyTerms = new CArrayList();
        }
        return self::$_cacheLegacyTerms;
    }

    /**
     * Получить термин из унаследованной таксономии
     *
     * @param $termId
     * @param $taxonomyId
     * @return CTerm
     */
    public static function getLegacyTerm($termId, $taxonomyId) {
        if (!self::getCacheLegacyTerms()->hasElement($termId."_".$taxonomyId)) {
            $taxonomy = self::getLegacyTaxonomy($taxonomyId);
            foreach ($taxonomy->getTerms()->getItems() as $term) {
                self::getCacheLegacyTerms()->add($term->getId()."_".$taxonomyId, $term);
            }
        }
        return self::getCacheLegacyTerms()->getItem($termId."_".$taxonomyId);
    }
}
