<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 10.06.12
 * Time: 20:28
 * To change this template use File | Settings | File Templates.
 */
class CSEBProtocolsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Протоколы ГОС экзаменов");

        parent::__construct();
    }
    public function actionIndex() {
        $protocols = CProtocolManager::getAllSebProtocols()->getItems();
        CStaffManager::getAllStudents();
        $this->extendTable("dataTable");

        $this->setData("protocols", $protocols);
        $this->renderView("_state_exam/_protocols/index.tpl");
    }
    public function actionWizard() {
        $this->addDatePicker("sign_date");
        $this->addJSInclude("_modules/_protocols/_core.js");
        $this->renderView("_state_exam/_protocols/wizard.step1.tpl");
    }
    public function actionWizardStep2() {
        $this->setData("year_id", CRequest::getInt("year_id"));
        $this->setData("sign_date", CRequest::getString("sign_date"));
        $this->setData("group_id", CRequest::getInt("group_id"));
        $this->setData("chairman_id", CRequest::getInt("chairman_id"));
        $this->setData("master_id", CRequest::getInt("master_id"));
        $this->setData("members", CRequest::getArray("member"));

        $group = CStaffManager::getStudentGroup(CRequest::getInt("group_id"));
        $this->setData("students", $group->getStudents()->getItems());

        $year = CTaxonomyManager::getYear(CRequest::getInt("year_id"));
        $this->setData("year", $year);
        $this->setData("speciality", $group->getSpeciality());

        $this->renderView("_state_exam/_protocols/wizard.step2.tpl");
    }
    public function actionWizardCompleted() {
        $sign_date = CRequest::getString("sign_date");
        $chairman = CStaffManager::getPersonById(CRequest::getInt("chairman_id"));
        $master = CStaffManager::getPersonById(CRequest::getInt("master_id"));
        $members = new CArrayList();
        foreach (CRequest::getArray("members") as $m) {
            $member = CStaffManager::getPersonById($m);
            $members->add($member->getId(), $member);
        }

        CProtocolManager::getAllSebProtocols();

        // на студента по протоколу
        foreach (CRequest::getArray("student") as $key=>$value) {
            $student = CStaffManager::getStudent($key);
            $ticket = CSEBTicketsManager::getTicket($value['ticket_id']);
            $mark = CTaxonomyManager::getMark($value['mark_id']);
            $questions = $value['questions'];

            $protocol = CFactory::createSebProtocol();
            $protocol->setSignDate($sign_date);
            $protocol->setStudent($student);
            $protocol->setChairman($chairman);
            $protocol->setTicket($ticket);
            $protocol->setMark($mark);
            $protocol->setQuestions($questions);
            $protocol->setBoarMaster($master);
            $protocol->setSpeciality($student->getSpeciality());
            foreach ($members->getItems() as $member) {
                $protocol->addMember($member);
            }
            $protocol->setNumber(CProtocolManager::getAllSebProtocols()->getCount() + 1);
            $protocol->save();

            CProtocolManager::getCacheSebProtocols()->add($protocol->getId(), $protocol);
        }
        $this->redirect("?action=index");
    }
    public function actionStudentGroupsByYearJSON() {
        // сильно замучиваться не буду, ибо уже надоедать начало
        $res = array();
        foreach (CStaffManager::getStudentGroupsByYear(CTaxonomyManager::getYear(CRequest::getInt("id")))->getItems() as $group) {
            $res[] = $group->toArrayForJSON();
        }
        echo json_encode($res);
    }
    public function actionView() {
        $protocol = CProtocolManager::getSebProtocol(CRequest::getInt("id"));
        $this->setData("protocol", $protocol);
        $this->renderView("_state_exam/_protocols/view.tpl");
    }
}
