<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 16.12.12
 * Time: 12:03
 * To change this template use File | Settings | File Templates.
 */
class CACLGroupController extends CBaseController {
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
        $set = CActiveRecordProvider::getAllFromTable(TABLE_USER_GROUPS);
        $groups = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $r) {
            $group = new CUserGroup($r);
            $groups->add($group->getId(), $group);
        }
        $this->setData("groups", $groups);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_acl_manager/groups/index.tpl");
    }
    public function actionAdd() {
        $group = new CUserGroup();
        $this->setData("group", $group);
        $this->renderView("_acl_manager/groups/add.tpl");
    }
    public function actionSave() {
        $group = new CUserGroup();
        $group->setAttributes(CRequest::getArray($group::getClassName()));
        if ($group->validate()) {
            $group->save();
            $this->redirect("groups.php?action=index");
            return true;
        }
        $this->setData("group", $group);
        $this->renderView("_acl_manager/groups/edit.tpl");
    }
    public function actionDelete() {
        $group = CStaffManager::getUserGroup(CRequest::getInt("id"));
        $group->remove();
        $this->redirect("?action=index");
    }
    public function actionView() {
        $group = CStaffManager::getUserGroup(CRequest::getInt("id"));
        $this->setData("group", $group);
        $this->renderView("_acl_manager/groups/view.tpl");
    }
    public function actionEdit() {
        $group = CStaffManager::getUserGroup(CRequest::getInt("id"));
        $this->setData("group", $group);
        $this->renderView("_acl_manager/groups/edit.tpl");
    }
    public function actionManageMembers() {
        $group = CStaffManager::getUserGroup(CRequest::getInt("id"));
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/dialogs/personSelector.js");
        $this->setData("group", $group);
        $this->renderView("_acl_manager/groups/manageMembers.tpl");
    }
    public function actionSaveMembers() {
        $aclItems = CRequest::getArray("members");
        $group = new CUserGroup();
        $group->setAttributes(CRequest::getArray($group::getClassName()));
        // удаляем все старые записи и создаем новые
        $transaction = new CTransaction();
        foreach ($group->getACLRelations()->getItems() as $item) {
            $item->remove();
        }
        // создаем новый записи
        foreach ($aclItems["id"] as $key=>$value) {
            $entry = new CACLGroupEntry();
            $entry->group_id = $group->getId();
            $entry->setType($aclItems["type"][$key]);
            $entry->setValue($value);
            $entry->save();
        }
        $transaction->commit();
        $this->redirect("?action=index");
    }
}
