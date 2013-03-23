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
        $messages = new CRecordSet();
        /**
         * В зависимости от того, какая у пользователя открыта закладка,
         * ту папку и листаем. Дефолтная - входящие
         */
        $folder = "in";
        if (CRequest::getString("folder") !== "") {
            $folder = CRequest::getString("folder");
        }
        /**
         * Запрос от открытой вкладки пагинируем нужные записи
         */
        if ($folder = "in") {
            $inQuery = new CQuery();
            $inQuery->select("mail.*")
                ->from(TABLE_MESSAGES." as mail")
                ->condition("to_user_id = ".CSession::getCurrentUser()->getId()." AND mail_type = 'in'");
            $messages->setQuery($inQuery);
        } elseif ($folder = "out") {
            $outQuery = new CQuery();
            $outQuery->select("mail.*")
                ->from(TABLE_MESSAGES." as mail")
                ->condition("from_user_id = ".CSession::getCurrentUser()->getId()." AND mail_type = 'out'");
            $messages->setQuery($outQuery);
        } elseif ($folder = "draft") {
            $draftQuery = new CQuery();
            $draftQuery->select("mail.*")
                ->from(TABLE_MESSAGES." as mail")
                ->condition("from_user_id = ".CSession::getCurrentUser()->getId()." AND mail_type = 'draft'");
            $messages->setQuery($draftQuery);
        }
        /**
         * Получаем реальные сообщения
         */
        $mails = new CArrayList();
        foreach ($messages->getPaginated()->getItems() as $record) {
            $mail = new CMessage(new CActiveRecord($record));
            $mails->add($mail->getId(), $mail);
        }
        /**
         * Все отдаем в рисовалку
         */
        $this->setData("folder", $folder);
        $this->setData("messages", $mails);
        $this->setData("paginator", $messages->getPaginator());
        /**
         * Рисуем
         */
        $this->renderView("_messages/index.tpl");
    }
}