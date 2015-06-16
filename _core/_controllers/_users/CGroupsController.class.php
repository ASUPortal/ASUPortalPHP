<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 13.04.13
 * Time: 20:07
 * To change this template use File | Settings | File Templates.
 */

class CGroupsController extends CBaseController {
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
        $this->setPageTitle("Управление группами пользователей");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("gr.*")
            ->from(TABLE_USER_GROUPS." as gr")
            ->order("gr.comment asc");
        $groups = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $group = new CUserGroup($ar);
            $groups->add($group->getId(), $group);
        }
        $this->setData("paginator", $set->getPaginator());
        $this->setData("groups", $groups);
        $this->renderView("_users/groups/index.tpl");
    }
    public function actionEdit() {
        $group = CStaffManager::getUserGroup(CRequest::getInt("id"));
        $form = new CUserGroupForm();
        $form->group = $group;
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("form", $form);
        $this->renderView("_users/groups/edit.tpl");
    }
    public function actionAdd() {
        $group = new CUserGroup();
        $form = new CUserGroupForm();
        $form->group = $group;
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("form", $form);
        $this->renderView("_users/groups/add.tpl");
    }
    public function actionSave() {
        $form = new CUserGroupForm();
        $form->setAttributes(CRequest::getArray($form::getClassName()));
        if ($form->validate()) {
            $form->save();
            if ($this->continueEdit()) {
                $this->redirect("groups.php?action=edit&id=".$form->group["id"]);
            } else {
                $this->redirect("groups.php?action=index");
            }
            return true;
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("form", $form);
        $this->renderView("_users/groups/add.tpl");
    }
    public function actionDelete() {
        $group = CStaffManager::getUserGroup(CRequest::getInt("id"));
        $group->remove();
        $this->redirect("groups.php?action=index");
    }
}