<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 11.11.12
 * Time: 16:11
 * To change this template use File | Settings | File Templates.
 */
class CExamManager {
    private static $_cacheQuestions = null;
    private static $_cacheQuestionsInit = false;
    private static $_cacheTickets = null;
    private static $_cacheTicketQuestions = null;

    /**
     * @return CArrayList|null
     */
    private static function getCacheQuestions() {
        if (is_null(self::$_cacheQuestions)) {
            self::$_cacheQuestions = new CArrayList();
        }
        return self::$_cacheQuestions;
    }

    /**
     * @param $key
     * @return CExamQuestion
     */
    public static function getQuestion($key) {
        if (!self::getCacheQuestions()->hasElement($key)) {
            $obj = CActiveRecordProvider::getById(TABLE_EXAMINATION_QUESTIONS, $key);
            if (!is_null($obj)) {
                $q = new CExamQuestion($obj);
                self::getCacheQuestions()->add($q->getId(), $q);
            }
        }
        return self::getCacheQuestions()->getItem($key);
    }

    /**
     * @return CArrayList|null
     */
    public static function getAllQuestions() {
        if (!self::$_cacheQuestionsInit) {
            self::$_cacheQuestionsInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_EXAMINATION_QUESTIONS)->getItems() as $obj) {
                $q = new CExamQuestion($obj);
                self::getCacheQuestions()->add($q->getId(), $q);
            }
        }
        return self::getCacheQuestions();
    }

    /**
     * Массив ключ-значение специальностей, для которых есть вопросы
     *
     * @return array
     */
    public static function getSpecialitiesWithQuestionsList() {
        $res = array();
        foreach (self::getAllQuestions()->getItems() as $q) {
            if (!is_null($q->speciality)) {
                $res[$q->speciality_id] = $q->speciality->getValue();
            }
        }
        return $res;
    }

    /**
     * Кэш билетов
     *
     * @return CArrayList|null
     */
    private static function getCacheTickets() {
        if (is_null(self::$_cacheTickets)) {
            self::$_cacheTickets = new CArrayList();
        }
        return self::$_cacheTickets;
    }

    /**
     * Получить билет по идентификатору
     *
     * @param $key
     * @return CExamTicket
     */
    public static function getTicket($key) {
        if (!self::getCacheTickets()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_EXAMINATION_TICKETS, $key);
            if (!is_null($item)) {
                $ticket = new CExamTicket($item);
                self::getCacheTickets()->add($ticket->getId(), $ticket);
            }
        }
        return self::getCacheTickets()->getItem($key);
    }

    /**
     * Билеты по сессии
     *
     * @param $session
     * @return CArrayList
     */
    public static function getTicketsBySession($session) {
        $res = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_EXAMINATION_TICKETS, "session_id = ".$session)->getItems() as $item) {
            $ticket = self::getTicket($item->getId());
            $res->add($ticket->getId(), $ticket);
        }
        return $res;
    }

    /**
     * Кэш записей из связанной таблицы
     *
     * @return CArrayList|null
     */
    private static function getCacheTicketQuestions() {
        if (is_null(self::$_cacheTicketQuestions)) {
            self::$_cacheTicketQuestions = new CArrayList();
        }
        return self::$_cacheTicketQuestions;
    }

    /**
     * @param $key
     * @return CExamTicketQuestion
     */
    public static function getTicketQuestion($key) {
        if (!self::getCacheTicketQuestions()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_EXAMINATION_QUESTIONS_IN_TICKETS, $key);
            if (!is_null($item)) {
                $obj = new CExamTicketQuestion($item);
                self::getCacheTicketQuestions()->add($obj->getId(), $obj);
            }
        }
        return self::getCacheTicketQuestions()->getItem($key);
    }
}
