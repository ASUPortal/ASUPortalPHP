<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.06.12
 * Time: 13:02
 * To change this template use File | Settings | File Templates.
 */
class CSEBTicketsManager {
    private static $_cacheTickets = null;
    private static $_cacheInit = false;
    /**
     * Кэш билетов
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheTickets() {
        if (is_null(self::$_cacheTickets)) {
            self::$_cacheTickets = new CArrayList();
        }
        return self::$_cacheTickets;
    }
    /**
     * Все зарегистрированные билеты
     *
     * @static
     * @return CArrayList
     */
    public static function getAllTickets() {
        if (!self::$_cacheInit) {
            self::$_cacheInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_SEB_TICKETS)->getItems() as $i) {
                $ticket = new CSEBTicket($i);
                self::getCacheTickets()->add($ticket->getId(), $ticket);
            }
        }
        return self::getCacheTickets();
    }
    /**
     * @static
     * @param $key
     * @return CSEBTicket
     */
    public static function getTicket($key) {
        if (!self::getCacheTickets()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_SEB_TICKETS, $key);
            if (!is_null($ar)) {
                $ticket = new CSEBTicket($ar);
                self::getCacheTickets()->add($ticket->getId(), $ticket);
            }
        }
        return self::getCacheTickets()->getItem($key);
    }
    /**
     * Инициализация вопросов для всех билетов, которые есть в базе
     *
     * @static
     */
    public static function initQuestions() {
        foreach (CActiveRecordProvider::getAllFromTable(TABLE_SEB_QUSTIONS_IN_TICKETS)->getItems() as $i) {
            if (self::getCacheTickets()->hasElement($i->getItemValue("ticket_id"))) {
                $question = CSEBQuestionsManager::getQuestion($i->getItemValue("question_id"));
                $ticket = self::getCacheTickets()->getItem($i->getItemValue("ticket_id"));
                $ticket->addQuestion($question);
            }
        }
    }
    /**
     * Билеты по определенной специальности и за определенный год
     *
     * @static
     * @param CTerm $year
     * @param CTerm $speciality
     * @return array
     */
    public static function getTicketsByYearAndSpecialityList(CTerm $year, CTerm $speciality) {
        $q = "speciality_id=".$speciality->getId()." AND year_id=".$year->getId();
        if (!self::getCacheTickets()->hasElement($q)) {
            $arr = array();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_SEB_TICKETS, $q)->getItems() as $ar) {
                $ticket = new CSEBTicket($ar);
                $arr[$ticket->getId()] = $ticket->getNumber();
                self::getCacheTickets()->add($ticket->getId(), $ticket);
            }
            self::getCacheTickets()->add($q, $arr);
        }
        return self::getCacheTickets()->getItem($q);
    }
}
