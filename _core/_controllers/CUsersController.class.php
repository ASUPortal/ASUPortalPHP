<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 13.04.13
 * Time: 13:12
 * To change this template use File | Settings | File Templates.
 */

class CUsersController extends CBaseController{
    protected $allowedAnonymous = array(
        "login"
    );
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
        $this->setPageTitle("Управление пользователями");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("user.*")
            ->from(TABLE_USERS." as user")
            ->order("user.fio asc");
        $users = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $user = new CUser($ar);
            $users->add($user->getId(), $user);
        }
        $this->setData("users", $users);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_users/users/index.tpl");
    }
    public function actionAdd() {
        $user = new CUser();
        $form = new CUserForm();
        $form->user = $user;
        /**
         * Получаем список групп
         */
        $groups = array();
        foreach (CStaffManager::getAllUserGroups()->getItems() as $group) {
            $groups[$group->getId()] = $group->comment;
        }
        /**
         * Получаем список ролей, полученных от участия
         * в группах
         */
        $fromGroups = array();
        foreach ($user->getGroups()->getItems() as $group) {
            foreach ($group->getRoles()->getItems() as $role) {
                $fromGroups[$role->getId()] = $group->comment;
            }
        }
        /**
         * Подключаем скрипты красивости
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        /**
         * Все передаем в представление
         */
        $this->setData("fromGroups", json_encode($fromGroups));
        $this->setData("groups", $groups);
        $this->setData("form", $form);
        $this->renderView("_users/users/add.tpl");
    }
    public function actionEdit() {
        /**
         * Собираем форму для редактирования
         */
        $form = new CUserForm();
        $user = CStaffManager::getUser(CRequest::getInt("id"));
        $form->user = $user;
        /**
         * Получаем список групп
         */
        $groups = array();
        foreach (CStaffManager::getAllUserGroups()->getItems() as $group) {
            $groups[$group->getId()] = $group->comment;
        }
        /**
         * Получаем список ролей, полученных от участия
         * в группах
         */
        $fromGroups = array();
        foreach ($user->getGroups()->getItems() as $group) {
            foreach ($group->getRoles()->getItems() as $role) {
                $fromGroups[$role->getId()] = $group->comment;
            }
        }
        /**
         * Подключаем скрипты красивости
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        /**
         * Все передаем в представление
         */
        $this->setData("fromGroups", json_encode($fromGroups));
        $this->setData("groups", $groups);
        $this->setData("form", $form);
        $this->renderView("_users/users/edit.tpl");
    }
    public function actionSave() {
        $form = new CUserForm();
        $form->setAttributes(CRequest::getArray($form::getClassName()));
        /**
         * Здесь у нас слегка раздутая модель,
         */
        if ($form->validate()) {
            $form->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$form->user["id"]);
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        /**
         * Получаем список групп
         */
        $groups = array();
        foreach (CStaffManager::getAllUserGroups()->getItems() as $group) {
            $groups[$group->getId()] = $group->comment;
        }
        $user = $form->user;
        /**
         * Получаем список ролей, полученных от участия
         * в группах
         */
        $fromGroups = array();
        foreach ($user->getGroups()->getItems() as $group) {
            foreach ($group->getRoles()->getItems() as $role) {
                $fromGroups[$role->getId()] = $group->comment;
            }
        }
        /**
         * Подключаем скрипты красивости
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        /**
         * Все передаем в представление
         */
        $this->setData("fromGroups", json_encode($fromGroups));
        $this->setData("groups", $groups);
        $this->setData("form", $form);
        $this->renderView("_users/users/edit.tpl");
    }
    public function actionDelete() {
        $user = CStaffManager::getUser(CRequest::getInt("id"));
        $user->remove();
        $this->redirect("?action=index");
    }
}
