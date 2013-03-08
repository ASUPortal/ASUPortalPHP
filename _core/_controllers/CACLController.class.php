<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 11.06.12
 * Time: 17:40
 * To change this template use File | Settings | File Templates.
 */
class CACLController extends CBaseController {
    private $allowedAnonymous = array(
        "restorePassword",
        "savePasswordRecoveryRequest",
        "requestSent",
        "getNewPassword"
    );
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление доступом пользователей");

        parent::__construct();
    }
    public function actionIndex() {
        $this->renderView("_acl_manager/index.tpl");
    }
    public function actionPersonSelectDialog() {
        $this->setData("items", CRequest::getArray("items"));
        $this->setData("fieldName", CRequest::getString("field"));
        $this->renderView("_acl_manager/dialog/personSelect.tpl");
    }
    public function actionUserSelectDialog() {
        $this->setData("items", CStaffManager::getAllUsers());
        $this->renderView("_acl_manager/dialog/userSelect.tpl");
    }
    public function actionGroupSelectDialog() {
        $this->setData("items", CStaffManager::getAllUserGroups());
        $this->renderView("_acl_manager/dialog/groupSelect.tpl");
    }

    /**
     * Автоподстановка значений
     */
    public function actionLookupNames() {
        $text = CRequest::getString("term");
        echo json_encode($text);
    }

    /**
     * Весь код дальше никуда не девать, он используется для восстановления доступа. Пусть
     * будет, его не жалко, напишу новый рядом
     */
    public function actionRestorePassword() {
        $request = CFactory::createPasswordRecoveryRequest();
        $this->setData("request", $request);
        $this->renderView("_acl_manager/restore_password.tpl");
    }
    public function actionSavePasswordRecoveryRequest() {
        $request = CFactory::createPasswordRecoveryRequest();
        $request->setAttributes(CRequest::getArray(CPasswordRecoveryRequest::getClassName()));
        if ($request->validate()) {
            $request->active = 1;
            $request->hash = md5($request->credential.time());
            $request->save();
            // отправляем письмо электронной почтой
            if (!is_null(CNotificationManager::getTemplate("newPasswordRequest"))) {
                $message = CNotificationManager::getTemplate("newPasswordRequest")->createNotification();
                $message->appendLine(WEB_ROOT."_modules/_acl_manager/?action=getNewPassword&id=".$request->hash);
                $message->email(CStaffManager::getUser($request->credential)->getPerson());
            }
            $this->redirect("?action=requestSent");
        }
        $this->setData("request", $request);
        $this->renderView("_acl_manager/restore_password.tpl");
    }
    public function actionRequestSent() {
        $this->renderView("_acl_manager/request_sent.tpl");
    }
    public function actionGetNewPassword() {
        $request = CStaffManager::getPasswordRecoveryRequest(CRequest::getString("id"));
        if (is_null($request)) {
            $this->renderView("_acl_manager/no_recovery_request.tpl");
            return true;
        }
        if (!$request->isActive()) {
            $this->renderView("_acl_manager/request_used.tpl");
            return true;
        }
        $user = CStaffManager::getUser($request->credential);
        if (is_null($user)) {
            $this->renderView("_acl_manager/no_recovery_request.tpl");
            return true;
        }
        // новый пароль
        $password = substr(md5(time()), 0, 7);
        $user->password = md5($password);
        $user->save();
        // запрос использован
        $request->active = 0;
        $request->save();
        // уведомляем пользователя
        if (!is_null(CNotificationManager::getTemplate("newPasswordSent"))) {
            $message = CNotificationManager::getTemplate("newPasswordSent")->createNotification();
            $message->appendLine("Логин: ".$user->getLogin());
            $message->appendLine("Пароль: ".$password);
            $message->email($user->getPerson());
        }
        $this->renderView("_acl_manager/request_processed.tpl");
    }
}
