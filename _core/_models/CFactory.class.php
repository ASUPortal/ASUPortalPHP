<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 15.05.12
 * Time: 10:18
 * To change this template use File | Settings | File Templates.
 *
 * Фабрика всех типов документов. Обычная фабрика, не абрстрактная и не фабричный метод
 */
class CFactory {
    /**
     * Создает пустой термин таксономии
     *
     * @static
     * @return CTerm
     */
    public static function createTerm() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_TAXONOMY_TERMS);
        $term = new CTerm($ar);
        return $term;
    }
    /**
     * Создает пустой ресурс
     *
     * @static
     * @return CResource
     */
    public static function createResource() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_RESOURCES);
        $resource = new CResource($ar);
        return $resource;
    }
    /**
     * Создает пустой календарь
     *
     * @static
     * @return CCalendar
     */
    public static function createCalendar() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_CALENDARS);
        $calendar = new CCalendar($ar);
        return $calendar;
    }
    /**
     * Создает пустое событие
     *
     * @static
     * @return CEvent
     */
    public static function createEvent() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_EVENTS);
        $event = new CEvent($ar);
        return $event;
    }
    /**
     * Создает пустое меню
     *
     * @static
     * @return CMenu
     */
    public static function createMenu() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_MENUS);
        $event = new CMenu($ar);
        return $event;
    }
    /**
     * Создает пустой пункт меню
     *
     * @static
     * @return CMenuItem
     */
    public static function createMenuItem() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_MENU_ITEMS);
        $event = new CMenuItem($ar);
        return $event;
    }
    /**
     * Создает пустой вопрос к ГОСам
     *
     * @static
     * @return CSebQuestion
     */
    public static function createSebQuestion() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_SEB_QUESTIONS);
        $event = new CSEBQuestion($ar);
        return $event;
    }
    /**
     * Пустой билет ГАК
     *
     * @static
     * @return CSEBTicket
     */
    public static function createSebTicket() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_SEB_TICKETS);
        $event = new CSEBTicket($ar);
        return $event;
    }
    /**
     * Пустой протокол ГАК
     *
     * @static
     * @return CSEBProtocol
     */
    public static function createSebProtocol() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_SEB_PROTOCOLS);
        $event = new CSEBProtocol($ar);
        return $event;
    }
    /**
     * Пустой учебный план
     * @return \CCorriculum 
     */
    public static function createCorriculum() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_CORRICULUMS);
        $event = new CCorriculum($ar);
        return $event;        
    }
    /**
     * Пустой объект таксономии
     *
     * @static
     * @return CTaxonomy
     */
    public static function createTaxonomy() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_TAXONOMY);
        $event = new CTaxonomy($ar);
        return $event;
    }
    /**
     * Цикл учебного плана
     *
     * @static
     * @return CCorriculumCycle
     */
    public static function createCorriculumCycle() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_CORRICULUM_CYCLES);
        $event = new CCorriculumCycle($ar);
        return $event;
    }
    /**
     * Дисциплина цикла учебного плана
     * О_о
     *
     * @static
     * @return CCorriculumDiscipline
     */
    public static function createCorriculumDiscipline() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_CORRICULUM_DISCIPLINES);
        $event = new CCorriculumDiscipline($ar);
        return $event;
    }
    /**
     * Трудоемкость дисциплины цикла учебного плана
     * О_о о_О
     *
     * @static
     * @return CCorriculumDisciplineLabor
     */
    public static function createCorriculumDisciplineLabor() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_CORRICULUM_DISCIPLINE_LABORS);
        $event = new CCorriculumDisciplineLabor($ar);
        return $event;
    }
    /**
     * @static
     * @return CCorriculumDisciplineControl
     */
    public static function createCorriculumDisciplineControl() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_CORRICULUM_DISCIPLINE_CONTROLS);
        $event = new CCorriculumDisciplineControl($ar);
        return $event;
    }
    /**
     * @static
     * @return CCorriculumDisciplineHour
     */
    public static function createCorriculumDisciplineHour() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_CORRICULUM_DISCIPLINE_HOURS);
        $event = new CCorriculumDisciplineHour($ar);
        return $event;
    }
    /**
     * @static
     * @return CRatingIndex
     */
    public static function createRatingIndex() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_RATING_INDEXES);
        $event = new CRatingIndex($ar);
        return $event;
    }
    /**
     * @static
     * @return CPersonRatingIndex
     */
    public static function createPersonRatingIndex() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_PERSON_RATINGS);
        $event = new CPersonRatingIndex($ar);
        return $event;
    }
    /**
     * @static
     * @return CPasswordRecoveryRequest
     */
    public static function createPasswordRecoveryRequest() {
        $ar = self::createActiveRecord();
        $ar->setTable(TABLE_PASSWORD_RECOVERY_REQUESTS);
        $event = new CPasswordRecoveryRequest($ar);
        return $event;
    }
    /**
     * @static
     * @return CNotification
     */
    public static function createNotification() {
        $ar = self::createActiveRecord();
        $ar->setTable(null);
        $event = new CNotification($ar);
        return $event;
    }
    /**
     * Создание ActiveRecord-а с нужными инициализированными параметрами
     *
     * @static
     * @return CActiveRecord
     */
    private static function createActiveRecord() {
        $arr = array(
            "id" => null
        );

        $ar = new CActiveRecord($arr);
        return $ar;
    }
}
