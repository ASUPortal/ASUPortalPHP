<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 10.06.12
 * Time: 20:25
 * To change this template use File | Settings | File Templates.
 */
class CSEBProtocol extends CActiveModel {
    private $_members = null;
    private $_student = null;
    private $_speciality = null;
    private $_chairman = null;
    private $_ticket = null;
    private $_mark = null;
    private $_master = null;
    /**
     * Член комиссии
     *
     * @param CPerson $person
     */
    public function addMember(CPerson $person) {
        if (is_null($this->_members)) {
            $this->_members = new CArrayList();
        }
        $this->_members->add($person->getId(), $person);
    }
    public function setStudent(CStudent $student) {
        $this->_student = $student;
        $this->getRecord()->setItemValue("student_id", $student->getId());
    }
    public function setSpeciality(CTerm $speciality) {
        $this->_speciality = $speciality;
        $this->getRecord()->setItemValue("speciality_id", $speciality->getId());
    }
    public function setChairman(CPerson $person) {
        $this->_chairman = $person;
        $this->getRecord()->setItemValue("chairman_id", $person->getId());
    }
    public function setTicket(CSEBTicket $ticket) {
        $this->_ticket = $ticket;
        $this->getRecord()->setItemValue("ticket_id", $ticket->getId());
    }
    public function setMark(CTerm $mark) {
        $this->_mark = $mark;
        $this->getRecord()->setItemValue("mark_id", $mark->getId());
    }
    public function setBoarMaster(CPerson $person) {
        $this->_master = $person;
    }
    public function setSignDate($date) {
        $this->getRecord()->setItemValue("sign_date", $date);
    }
    public function setQuestions($questions) {
        $this->getRecord()->setItemValue("questions", $questions);
    }
    public function getNumber() {
        return $this->getRecord()->getItemValue("number");
    }
    /**
     * Студент, для которого протокол
     *
     * @return CStudent
     */
    public function getStudent() {
        if (is_null($this->_student)) {
            $this->_student = CStaffManager::getStudent($this->getRecord()->getItemValue("student_id"));
        }
        return $this->_student;
    }
    /**
     * Специальность
     *
     * @return CTerm
     */
    public function getSpeciality() {
        if (is_null($this->_speciality)) {
            $this->_speciality = CTaxonomyManager::getSpeciality($this->getRecord()->getItemValue("speciality_id"));
        }
        return $this->_speciality;
    }
    /**
     * Оценка
     *
     * @return CTerm
     */
    public function getMark() {
        if (is_null($this->_mark)) {
            $this->_mark = CTaxonomyManager::getMark($this->getRecord()->getItemValue("mark_id"));
        }
        return $this->_mark;
    }
    public function getSignDate() {
        return $this->getRecord()->getItemValue("sign_date");
    }
    /**
     * Члены ГАК (кроме председателя)
     *
     * @return CArrayList
     */
    public function getMembers() {
        if (is_null($this->_members)) {
            $this->_members = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_SEB_PROTOCOL_MEMBERS, "protocol_id=".$this->getId()." AND isMaster=0")->getItems() as $ar) {
                $member = CStaffManager::getPersonById($ar->getItemValue("person_id"));
                if (!is_null($member)) {
                    $this->_members->add($member->getId(), $member);
                }
            }
        }
        return $this->_members;
    }
    /**
     * Председатель экзаменационой комиссии
     *
     * @return CPerson
     */
    public function getBoardMaster() {
        if (is_null($this->_master)) {
            $q = "protocol_id=".$this->getId()." AND isMaster=1";
            foreach (CActiveRecordProvider::getWithCondition(TABLE_SEB_PROTOCOL_MEMBERS, $q)->getItems() as $ar) {
                $member = CStaffManager::getPersonById($ar->getItemValue("person_id"));
                if (!is_null($member)) {
                    $this->_master = $member;
                }
            }
        }
        return $this->_master;
    }
    /**
     * Сохранялка новая, групповая.
     */
    public function save() {
        if ($this->getId() != 0) {
            // удаляем старых членов ГАК вместе с председателем
            $q = new CQuery();
            $q->remove(TABLE_SEB_PROTOCOL_MEMBERS)
                ->condition("protocol_id=".$this->getId())
                ->execute();
        }
        parent::save();
        // сохраняем новыех участников
        foreach ($this->getMembers()->getItems() as $member) {
            $q = new CQuery();
            $q->insert(TABLE_SEB_PROTOCOL_MEMBERS, array(
                "protocol_id" => $this->getId(),
                "person_id" => $member->getId(),
                "isMaster" => 0
            ))
                ->execute();
        }
        // председателя тоже сохраняем
        if (!is_null($this->getBoardMaster())) {
            $q = new CQuery();
            $q->insert(TABLE_SEB_PROTOCOL_MEMBERS, array(
                "protocol_id" => $this->getId(),
                "person_id" => $this->getBoardMaster()->getId(),
                "isMaster" => 1
            ))
                ->execute();
        }
    }
    public function setNumber($number) {
        $this->getRecord()->setItemValue("number", $number);
    }
    /**
     * Председатель ГАК
     *
     * @return CPerson
     */
    public function getChairman() {
        if (is_null($this->_chairman)) {
            $this->_chairman = CStaffManager::getPersonById($this->getRecord()->getItemValue("chairman_id"));
        }
        return $this->_chairman;
    }
    /**
     * Билет
     *
     * @return CSEBTicket
     */
    public function getTicket() {
        if (is_null($this->_ticket)) {
            $this->_ticket = CSEBTicketsManager::getTicket($this->getRecord()->getItemValue("ticket_id"));
        }
        return $this->_ticket;
    }
    public function getQuestions() {
        return $this->getRecord()->getItemValue("questions");
    }
}
