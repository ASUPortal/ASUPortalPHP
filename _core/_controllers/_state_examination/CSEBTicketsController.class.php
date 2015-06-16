<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.06.12
 * Time: 12:55
 * To change this template use File | Settings | File Templates.
 */
class CSEBTicketsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Билеты к ГОС экзаменам");

        parent::__construct();
    }
    public function actionIndex() {
        $this->extendTable("dataTable");

        CSEBTicketsManager::getAllTickets();
        CSEBQuestionsManager::getAllQuestions();
        CSEBTicketsManager::initQuestions();

        $this->setData("tickets", CSEBTicketsManager::getAllTickets()->getItems());
        $this->renderView("_state_exam/_tickets/index.tpl");
    }
    public function actionAdd() {
        $this->addJSInclude("_modules/_tickets/_core.js");
        $this->renderView("_state_exam/_tickets/add.tpl");
    }
    public function actionSave() {
        if (CRequest::getInt("id") == 0) {
            $ticket = CFactory::createSebTicket();
        } else {
            $ticket = CSEBTicketsManager::getTicket(CRequest::getInt("id"));
        }
        foreach (CRequest::getArray("question") as $i) {
            $q = CSEBQuestionsManager::getQuestion($i);
            if (!is_null($q)) {
                $ticket->addQuestion($q);
            }
        }
        $ticket->setSpeciality(CTaxonomyManager::getCacheSpecialities()->getItem(CRequest::getInt("speciality_id")));
        $ticket->setYear(CTaxonomyManager::getCacheYears()->getItem(CRequest::getInt("year_id")));
        $ticket->setProtocol(CProtocolManager::getDepProtocol(CRequest::getInt("protocol_id")));
        $ticket->setSigner(CStaffManager::getPersonById(CRequest::getInt("signer_id")));
        $ticket->setNumber(CRequest::getInt("number"));
        $ticket->save();

        $this->redirect("?action=index");
    }
    public function actionWizard() {
        $this->renderView("_state_exam/_tickets/wizard.step1.tpl");
    }
    public function actionWizardStep2() {
        $this->setData("speciality_id", CRequest::getInt("speciality_id"));
        $this->setData("year_id", CRequest::getInt("year_id"));
        $this->setData("protocol_id", CRequest::getInt("protocol_id"));
        $this->setData("signer_id", CRequest::getInt("signer_id"));
        $this->setData("speciality", CTaxonomyManager::getSpeciality(CRequest::getInt("speciality_id")));

        $this->renderView("_state_exam/_tickets/wizard.step2.tpl");
    }
    public function actionWizardCompleted() {
        $speciality = CTaxonomyManager::getCacheSpecialities()->getItem(CRequest::getInt("speciality_id"));
        $year = CTaxonomyManager::getCacheYears()->getItem(CRequest::getInt("year_id"));
        $protocol = CProtocolManager::getDepProtocol(CRequest::getInt("protocol_id"));
        $signer = CStaffManager::getPersonById(CRequest::getInt("signer_id"));

        $disciplines = new CArrayList();
        foreach (CRequest::getArray("discipline") as $i) {
            $disciplines->add($disciplines->getCount(), CDisciplinesManager::getDiscipline($i));
        }

        // бегаем по циклу столько раз, сколько нам билетов нужно
        for ($i = 1; $i <= CRequest::getInt("count"); $i++) {
            $ticket = CFactory::createSebTicket();
            $ticket->setSpeciality($speciality);
            $ticket->setYear($year);
            $ticket->setProtocol($protocol);
            $ticket->setSigner($signer);
            $ticket->setNumber($i);

            foreach($disciplines->getItems() as $disc) {
                if ($disc->getQuestions()->getCount() == 0) {
                    break;
                }

                $question = $disc->getQuestions()->getShuffled()->getFirstItem();
                $disc->getQuestions()->removeItem($question->getId());

                $ticket->addQuestion($question);
            }

            $ticket->save();
        }

        $this->redirect("?action=index");
    }
    public function actionView() {
        $ticket = CSEBTicketsManager::getTicket(CRequest::getInt("id"));
        $this->setData("ticket", $ticket);
        $this->renderView("_state_exam/_tickets/view.tpl");
    }
}
