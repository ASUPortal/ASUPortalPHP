<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.06.12
 * Time: 10:06
 * To change this template use File | Settings | File Templates.
 */
class CSEBQuestionsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Вопросы к ГОС экзаменам");

        parent::__construct();
    }
    public function actionIndex() {
        $this->extendTable("dataTable");
        $this->setData("questions", CSEBQuestionsManager::getAllQuestions()->getItems());
        $this->renderView("_state_exam/_questions/index.tpl");
    }
    public function actionAdd() {
        $this->renderView("_state_exam/_questions/add.tpl");
    }
    public function actionSave() {
        if (CRequest::getInt("id") == 0) {
            $q = CFactory::createSebQuestion();
        } else {
            $q = CSEBQuestionsManager::getQuestion(CRequest::getInt("id"));
        }

        $discipline = CTaxonomyManager::getCacheDisciplines()->getItem(CRequest::getInt("discipline_id"));
        $speciality = CTaxonomyManager::getCacheSpecialities()->getItem(CRequest::getInt("speciality_id"));
        $q->setDiscipline($discipline);
        $q->setSpeciality($speciality);
        $q->setText(CRequest::getString("question"));
        $q->save();

        $this->redirect("?action=index");
    }
    public function actionView() {
        $q = CSEBQuestionsManager::getQuestion(CRequest::getInt("id"));

        $this->setData("question", $q);
        $this->renderView("_state_exam/_questions/view.tpl");
    }
    public function actionRemove() {
        $q = CSEBQuestionsManager::getQuestion(CRequest::getInt("id"));

        $q->remove();
        $this->redirect("?action=index");
    }
    public function actionEdit() {
        $q = CSEBQuestionsManager::getQuestion(CRequest::getInt("id"));

        $this->setData("question", $q);
        $this->renderView("_state_exam/_questions/edit.tpl");
    }
    public function actionGetDisciplinesJSON() {
        $arr = array();
        foreach (CSEBQuestionsManager::getDisciplines()->getItems() as $i) {
            $arr[] = $i->toArrayForJSON();
        }
        echo json_encode($arr);
    }
    public function actionGetQuestionsJSON() {
        $arr = array();
        $disc = CTaxonomyManager::getCacheDisciplines()->getItem(CRequest::getInt("id"));
        foreach (CSEBQuestionsManager::getQuestionsByDiscipline($disc)->getItems() as $i) {
            $arr[] = $i->toArrayForJSON();
        }
        echo json_encode($arr);
    }
}
