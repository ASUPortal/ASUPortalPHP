<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 21.03.13
 * Time: 21:13
 * To change this template use File | Settings | File Templates.
 */

class CMessagesController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Личные сообщения");

        parent::__construct();
    }
    public function actionIndex() {
        /**
         * Считаем, что по умолчанию пользователь хочет
         * посмотреть свои входящие
         */
        $this->actionInbox();
    }
    public function actionInbox() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("mail.*")
            ->from(TABLE_MESSAGES." as mail")
            ->condition("mail.to_user_id = ".CSession::getCurrentUser()->getId())
            ->order("mail.date_send desc");
        $messages = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $mail = new CMessage($ar);
            $messages->add($mail->getId(), $mail);
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $message = new CMessage();
        $message->from_user_id = CSession::getCurrentUser()->getId();
        $this->setData("message", $message);
        $this->setData("messages", $messages);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_messages/inbox.tpl");
    }
    public function actionOutbox() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("mail.*")
            ->from(TABLE_MESSAGES." as mail")
            ->condition("mail.from_user_id = ".CSession::getCurrentUser()->getId())
            ->order("mail.date_send desc");
        $messages = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $mail = new CMessage($ar);
            $messages->add($mail->getId(), $mail);
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");
        $message = new CMessage();
        $message->from_user_id = CSession::getCurrentUser()->getId();
        $this->setData("message", $message);
        $this->setData("messages", $messages);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_messages/outbox.tpl");
    }
    public function actionView() {
        $mail = CStaffManager::getMessage(CRequest::getInt("id"));
        $isMy = false;
        if (!is_null($mail->getSender())) {
            if ($mail->getSender()->getId() == CSession::getCurrentPerson()->getId()) {
                $isMy = true;
            }
        }
        if (!is_null($mail->getRecipient())) {
            if ($mail->getRecipient()->getId() == CSession::getCurrentPerson()->getId()) {
                $isMy = true;
            }
        }
        if (!$isMy) {
            exit;
        }
        if (!$mail->isRead()) {
            if (!is_null($mail->getRecipient())) {
                if ($mail->getRecipient()->getId() == CSession::getCurrentPerson()->getId()) {
                    $mail->read_status = 1;
                    $mail->save();
                }
            }
        }
        $this->setData("mail", $mail);
        $this->renderView("_messages/view.tpl");
    }
    public function actionSend() {
        $mail = new CMessage();
        $mail->setAttributes(CRequest::getArray($mail::getClassName()));
        $mail->date_send = date("Y-m-d H:i:s");
        if ($mail->validate()) {
            $mail->save();
            $this->redirect("?action=outbox#tab-outbox");
            return true;
        }
        $this->setData("message", $mail);
        $this->renderView("_messages/edit.tpl");
    }
}