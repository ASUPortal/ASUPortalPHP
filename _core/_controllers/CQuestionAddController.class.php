<?php

class CQuestionAddController extends CBaseController{
    public function __construct() {
        if (!CSession::isAuth()) {
            $action = CRequest::getString("action");
            if ($action == "") {
                $action = "index";
            }
            if (!in_array($action, $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Вопросы преподавателям и другим пользователям портала");

        parent::__construct();
    }
    public function actionIndex() {
    	$quest = new CQuestion();
    	$quest->user_id = CRequest::getInt("user_id");
    	$this->setData("quest", $quest);
    	$this->renderView("_question_add/add.tpl");
    }
    public function actionEdit() {
    	$quest = CQuestionManager::getQuestion(CRequest::getInt("id"));
    	$this->setData("quest", $quest);
    	$this->renderView("_question_add/edit.tpl");
    }
    public function actionSave() {
    	$quest = new CQuestion();
    	$quest->setAttributes(CRequest::getArray($quest::getClassName()));
    	if ($quest->validate()) {
    		$quest->contact_info .= "; ".CStaffManager::getUser(CSession::getCurrentUser()->getId())->getName().'; ip '.$_SERVER["REMOTE_ADDR"];
    		if ($quest->answer_text != '') {
    			$quest->datetime_answ=date("Y-d-m H:i:s",time());
    		}
    		$quest->save();
    		if ($this->continueEdit()) {
    			$this->redirect("?action=edit&id=".$quest->getId());
    		} else {
    			$this->redirect(WEB_ROOT);
    		}
    		return true;
    	}
    	$this->setData("quest", $quest);
    	$this->renderView("_question_add/edit.tpl");
    }
}