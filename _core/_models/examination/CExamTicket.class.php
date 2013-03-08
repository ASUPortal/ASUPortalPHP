<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.12.12
 * Time: 16:23
 * To change this template use File | Settings | File Templates.
 */
class CExamTicket extends CActiveModel {
    protected $_table = TABLE_EXAMINATION_TICKETS;
    protected $_questions = null;
    protected $_disciplines = null;
    protected $_speciality = null;
    protected $_year = null;
    protected $_ticketQuestions = null;
    protected $_approver = null;
    protected $_protocol = null;

    public function relations() {
        return array(
            "questions" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_questions",
                "joinTable" => TABLE_EXAMINATION_QUESTIONS_IN_TICKETS,
                "leftCondition" => "ticket_id = ". $this->id,
                "rightKey" => "question_id",
                "managerClass" => "CExamManager",
                "managerGetObject" => "getQuestion"
            ),
            "disciplines" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_disciplines",
                "relationFunction" => "getDisciplines"
            ),
            "speciality" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_speciality",
                "storageField" => "speciality_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getSpeciality"
            ),
            "year" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_year",
                "storageField" => "year_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getYear"
            ),
            "approver" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_approver",
                "storageField" => "approver_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "protocol" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_protocol",
                "storageField" => "protocol_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getDepProtocol"
            ),
            "ticketQuestions" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_ticketQuestions",
                "storageTable" => TABLE_EXAMINATION_QUESTIONS_IN_TICKETS,
                "storageCondition" => "ticket_id = " . $this->id,
                "managerClass" => "CExamManager",
                "managerGetObject" => "getTicketQuestion"
            )
        );
    }

    /**
     * Дисциплины
     *
     * @return CArrayList|null
     */
    public function getDisciplines() {
        if (is_null($this->_disciplines)) {
            $this->_disciplines = new CArrayList();
            foreach ($this->questions->getItems() as $q) {
                $discipline = $q->discipline;
                $this->_disciplines->add($discipline->getId(), $discipline);
            }
        }
        return $this->_disciplines;
    }

    /**
     * Дисциплины в виде обычного массива
     *
     * @return array
     */
    public function getDisciplinesList() {
        $res = array();
        foreach ($this->getDisciplines()->getItems() as $discipline) {
            $res[$discipline->getId()] = $discipline->getValue();
        }
        return $res;
    }
}
