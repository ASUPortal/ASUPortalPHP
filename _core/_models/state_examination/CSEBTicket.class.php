<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.06.12
 * Time: 13:06
 * To change this template use File | Settings | File Templates.
 */
class CSEBTicket extends CActiveModel {
    private $_speciality = null;
    private $_protocol = null;
    private $_signer = null;
    private $_year = null;
    private $_questions = null;
    /**
     * Специальность
     *
     * @return CTerm
     */
    public function getSpeciality() {
        if (is_null($this->_speciality)) {
            $this->_speciality = CTaxonomyManager::getCacheSpecialities()->getItem($this->getRecord()->getItemValue("speciality_id"));
        }
        return $this->_speciality;
    }
    /**
     * @param CTerm $value
     */
    public function setSpeciality(CTerm $value) {
        $this->_speciality = $value;
        $this->getRecord()->setItemValue("speciality_id", $value->getId());
    }
    /**
     * @param CTerm $value
     */
    public function setYear(CTerm $value) {
        $this->_year = $value;
        $this->getRecord()->setItemValue("year_id", $value->getId());
    }
    /**
     * @param CDepartmentProtocol $value
     */
    public function setProtocol(CDepartmentProtocol $value) {
        $this->_protocol = $value;
        $this->getRecord()->setItemValue("protocol_id", $value->getId());
    }
    public function setSigner(CPerson $value) {
        $this->_signer = $value;
        $this->getRecord()->setItemValue("signer_id", $value->getId());
    }
    /**
     * Протокол заседния кафедры, на котором билет утвержден
     *
     * @return CDepartmentProtocol
     */
    public function getProtocol() {
        if (is_null($this->_protocol)) {
            $this->_protocol = CProtocolManager::getDepProtocol($this->getRecord()->getItemValue("protocol_id"));
        }
        return $this->_protocol;
    }
    /**
     * Подписант билета
     *
     * @return CPerson
     */
    public function getSigner() {
        if (is_null($this->_signer)) {
            $this->_signer = CStaffManager::getPersonById($this->getRecord()->getItemValue("signer_id"));
        }
        return $this->_signer;
    }
    /**
     * Учебный год
     *
     * @return CTerm
     */
    public function getYear() {
        if (is_null($this->_year)) {
            $this->_year = CTaxonomyManager::getCacheYears()->getItem($this->getRecord()->getItemValue("year_id"));
        }
        return $this->_year;
    }
    /**
     * Вопросы в билете
     *
     * @return CArrayList
     */
    public function getQuestions() {
        if (is_null($this->_questions)) {
            $this->_questions = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_SEB_QUSTIONS_IN_TICKETS, "ticket_id=".$this->getId())->getItems() as $r) {
                $q = CSEBQuestionsManager::getQuestion($r->getItemValue("question_id"));
                if (!is_null($q)) {
                    $this->_questions->add($q->getId(), $q);
                }
            }
        }
        return $this->_questions;
    }
    /**
     * Добавление вопроса в билет
     *
     * @param CSebQuestion $value
     */
    public function addQuestion(CSebQuestion $value) {
        if (is_null($this->_questions)) {
            $this->_questions = new CArrayList();
        }
        $this->_questions->add($value->getId(), $value);
    }
    /**
     * Сохранялка новая, групповая.
     * Блин, как я задолбался уже это писать...
     */
    public function save() {
        if ($this->getId() != 0) {
            // удаляем старые вопросы
            $q = new CQuery();
            $q->remove(TABLE_SEB_QUSTIONS_IN_TICKETS)
                ->condition("ticket_id=".$this->getId())
                ->execute();
        }
        parent::save();
        // сохраняем новые вопросы
        foreach ($this->getQuestions()->getItems() as $i) {
            $q = new CQuery();
            $q->insert(TABLE_SEB_QUSTIONS_IN_TICKETS, array(
                "ticket_id" => $this->getId(),
                "question_id" => $i->getId()
            ))
                ->execute();
        }
    }
    public function setNumber($int) {
        $this->getRecord()->setItemValue("number", $int);
    }
    public function getNumber() {
        return $this->getRecord()->getItemValue("number");
    }
}
