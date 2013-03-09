<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 11.11.12
 * Time: 16:06
 * To change this template use File | Settings | File Templates.
 */
class CExaminationController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление доступом пользователей");

        parent::__construct();
    }
    public function actionIndex() {
        $set = CActiveRecordProvider::getAllFromTable(TABLE_EXAMINATION_QUESTIONS);
        $questions = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $q = new CExamQuestion($item);
            $questions->add($q->getId(), $q);
        }
        $this->setData("questions", $questions);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_examination/index.tpl");
    }
    public function actionAdd() {
        $question = new CExamQuestion();
        $this->setData("cources", array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5
        ));
        $this->setData("question", $question);
        $this->renderView("_examination/edit.tpl");
    }
    public function actionEdit() {
        $question = new CExamQuestion();
        if (CRequest::getInt("id") != 0) {
            $question = CExamManager::getQuestion(CRequest::getInt("id"));
        }
        $this->setData("cources", array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5
        ));
        $this->setData("question", $question);
        $this->renderView("_examination/edit.tpl");
    }
    public function actionSave() {
        $question = new CExamQuestion();
        $question->setAttributes(CRequest::getArray(CExamQuestion::getClassName()));
        if ($question->validate()) {
            $question->save();
            $this->redirect("?action=index");
        }
        $this->setData("question", $question);
        $this->renderView("_examination/edit.tpl");
    }
    public function actionAddGroup() {
        $group = new CExamGroupAdd();
        $this->setData("group", $group);
        $this->setData("cources", array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5
        ));
        $this->renderView("_examination/groupadd.tpl");
    }
    public function actionSaveGroup() {
        $group = new CExamGroupAdd();
        $group->setAttributes(CRequest::getArray($group::getClassName()));
        $texts = explode(chr(13), $group->text);
        foreach ($texts as $text) {
            $q = new CExamQuestion();
            $q->speciality_id = $group->speciality_id;
            $q->course = $group->course;
            $q->year_id = $group->year_id;
            $q->category_id = $group->category_id;
            $q->discipline_id = $group->discipline_id;
            $q->text = trim($text);
            $q->save();
        }
        $this->redirect("?action=index");
    }
    public function actionGenerate() {
        $generate = new CExamGenerate();
        $generate->number = 20;
        $generate->protocol_id = CProtocolManager::getAllDepProtocols()->getLastItem()->getId();
        $this->setData("cources", array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5
        ));
        $this->setData("generate", $generate);
        $this->addJSInclude("_modules/_examination/script.js");
        $this->renderView("_examination/generate.tpl");
    }
    public function actionGenerateTickets() {
        $generate = new CExamGenerate();
        $generate->setAttributes(CRequest::getArray(CExamGenerate::getClassName()));
        if ($generate->validate()) {
            // генерируем указанное количество билетов
            // для начала нужно получить по каждой из указанных дисциплин
            //asu_debug($generate);
            $questions = array();
            foreach ($generate->discipline_id as $key=>$value) {
                $questions[] = $this->getQuestionsByParams(
                    $generate->speciality_id,
                    $generate->course,
                    $generate->year_id,
                    $value,
                    $generate->category_id[$key],
                    $generate->number
                );
            }
            // нужное количество вопросов
            $session = time();
            for ($i = 0; $i < $generate->number; $i++) {
                $ticket = new CExamTicket();
                $ticket->session_id = $session;
                $ticket->speciality_id = $generate->speciality_id;
                $ticket->course = $generate->course;
                $ticket->year_id = $generate->year_id;
                $ticket->approver_id = $generate->approver_id;
                $ticket->protocol_id = $generate->protocol_id;
                $ticket->person_id = CSession::getCurrentPerson()->getId();
                $ticket->save();
                // в билете генерируем указанное количество вопросов
                foreach ($questions as $k=>$list) {
                    $ticketQuestion = new CExamTicketQuestion();
                    $ticketQuestion->ticket_id = $ticket->getId();
                    $q = $list->getFirstItem();
                    $key = $list->getFirstItemKey();
                    $list->removeItem($key);
                    $ticketQuestion->question_id = $q->getId();
                    $ticketQuestion->order = ($k + 1);
                    $ticketQuestion->save();
                }
            }
            // перекидываем на просмотр
            $this->redirect("?action=view&id=".$session);
            return true;
        }
        $this->setData("generate", $generate);
        $this->setData("cources", array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5
        ));
        $this->setData("generate", $generate);
        $this->addJSInclude("_modules/_examination/script.js");
        $this->renderView("_examination/generate.tpl");
    }

    /**
     * Сгенерировать указанное количество вопросов по указанным параметрам
     *
     * @param $speciality_id
     * @param $course
     * @param $year_id
     * @param $discipline_id
     * @param $category_id
     * @param $count
     * @return CArrayList
     */
    private function getQuestionsByParams(
        $speciality_id,
        $course,
        $year_id,
        $discipline_id,
        $category_id,
        $count
    ) {
        $res = new CArrayList();
        // получим все вопросы по указанной дисциплине
        $questions = new CArrayList();
        foreach (CExamManager::getAllQuestions()->getItems() as $q) {
            if ($q->speciality_id == $speciality_id) {
                if ($q->course == $course) {
                    if ($q->year_id == $year_id) {
                        if ($q->discipline_id == $discipline_id) {
                            if ($q->category_id == $category_id) {
                                $questions->add($q->getId(), $q);
                            }
                        }
                    }
                }
            }
        }
        // если ничего не нашлось, то выходим
        if ($questions->getCount() == 0) {
            return $res;
        }
        // если полученное количество вопросов больше требуемого, то берем
        // случайные значения, если меньше, то тоже берем случайные значения
        // до тех пор, пока не наберем столько, сколько надо
        if ($questions->getCount() >= $count) {
            for ($i = 0; $i < $count; $i++) {
                $q = $questions->getShuffled()->getFirstItem();
                $res->add($res->getCount(), $q);
                $questions->removeItem($q->getId());
            }
        } else {
            $backup = $questions->getCopy();
            $i = 0;
            while ($i < $count) {
                $q = $questions->getShuffled()->getFirstItem();
                $res->add($res->getCount(), $q);
                $questions->removeItem($q->getId());
                if ($questions->getCount() == 0) {
                    $questions = $backup->getCopy();
                }
                $i++;
            }
        }
        return $res;
    }
    public function actionView() {
        $set = CActiveRecordProvider::getWithCondition(TABLE_EXAMINATION_TICKETS, "session_id = ".CRequest::getInt("id"));
        $tickets = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $ticket = CExamManager::getTicket($item->getId());
            $tickets->add($ticket->getId(), $ticket);
        }
        $paginator = $set->getPaginator();
        $this->setData("tickets", $tickets);
        $this->setData("paginator", $paginator);
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->renderView("_examination/view.tpl");
    }
    public function actionMy() {
        $set = CActiveRecordProvider::getDistinctWithCondition(TABLE_EXAMINATION_TICKETS,"person_id = ".CSession::getCurrentPerson()->getId(), "session_id");
        $tickets = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $ticket = new CExamTicket($item);
            $tickets->add($ticket->getId(), $ticket);
        }
        $this->setData("tickets", $tickets);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_examination/my.tpl");
    }

    /**
     * Удаление одного билета
     */
    public function actionDel() {
        $question = CExamManager::getQuestion(CRequest::getInt("id"));
        if (!is_null($question)) {
            $question->remove();
        }
        $this->redirect("?action=index");
    }

    /**
     * Удаление группы билетов
     */
    public function actionDelete() {
        foreach (CExamManager::getTicketsBySession(CRequest::getInt("id"))->getItems() as $ticket) {
            foreach ($ticket->ticketQuestions->getItems() as $q) {
                $q->remove();
            }
            $ticket->remove();
        }
        $this->redirect("?action=my");
    }
}
