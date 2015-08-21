<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 22.12.12
 * Time: 11:29
 * To change this template use File | Settings | File Templates.
 */
class CSettingsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Личные настройки");

        parent::__construct();
    }
    public function actionIndex() {
        $settings = new CUserSettings();
        $settings->user_id = CSession::getCurrentUser()->getId();
        if (!is_null(CSession::getCurrentUser()->getPersonalSettings())) {
            $settings = CSession::getCurrentUser()->getPersonalSettings();
        }
        $sizes = array(
            5, 6, 7, 8
        );
        $this->setData("sizes", $sizes);
        $this->setData("settings", $settings);
        $this->addActionsMenuItem(array(
            "title" => "Добавить инфографику",
            "link" => "reports.php?action=add&id=".$settings->getId(),
            "icon" => "actions/list-add.png"
        ));
        $this->renderView("_settings/index.tpl");
    }
    public function actionUsersGroups() {
    	$set = new CRecordSet();
    	$query = new CQuery();
    	$set->setQuery($query);
    	$query->select("gr.*")
	    	->from(TABLE_USER_GROUPS." as gr")
	    	->order("gr.comment asc");
    	$groups = new CArrayList();
    	foreach ($set->getItems() as $ar) {
    		$group = new CUserGroup($ar);
    		$groups->add($group->getId(), $group);
    	}
    	$this->setData("groups", $groups);
    	$this->renderView("_settings/usersGroups.tpl");
    }
    public function actionAddUsersSettings() {
    	$userGroup = CRequest::getInt("id");
    	$query = new CQuery();
    	$query->select("users.*")
	    	->from(TABLE_USERS." as users")
	    	->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
    		->leftJoin(TABLE_USER_SETTINGS." as userSettings", "users.id=userSettings.user_id")
	    	->condition("userGroup.group_id=".$userGroup." and userSettings.user_id is null");
    	$users = array();
    	foreach ($query->execute()->getItems() as $ar) {
    		$user = new CUser(new CActiveRecord($ar));
    		$users[] = $user->getId();
    	}
    	$options = array(0 => "Нет", 1 => "Да");
    	$form = new CUsersSettingsForm();
    	$form->users = $users;
    	$this->setData("form", $form);
    	$this->setData("options", $options);
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_dashboard/index.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->renderView("_settings/addUsersSettings.tpl");
    }
    public function actionAddUsersSettingsProcess() {
    	$checkboxes = array(
    			"dashboard_enabled_groups",
    			"dashboard_show_birthdays_groups",
    			"dashboard_show_messages_groups",
    			"dashboard_show_all_tasks_groups",
    			"dashboard_check_messages_groups"
    	);
    	$form = new CUsersSettingsForm();
    	$form->setAttributes(CRequest::getArray($form::getClassName()));
    	if ($form->validate()) {
    		foreach ($form->users as $id) {
    			$settings = new CUserSettings();
	    		foreach ($checkboxes as $box) {
	    			if (!array_key_exists($box, CRequest::getArray($settings::getClassName()))) {
	    				$settings->$box = 0;
	    			}
    			}
    			$settings->user_id = $id;
    			$settings->dashboard_enabled_groups = $form->dashboard_enabled_groups;
    			$settings->dashboard_show_birthdays_groups = $form->dashboard_show_birthdays_groups;
    			$settings->dashboard_show_messages_groups = $form->dashboard_show_messages_groups;
    			$settings->dashboard_show_all_tasks_groups = $form->dashboard_show_all_tasks_groups;
    			$settings->dashboard_check_messages_groups = $form->dashboard_check_messages_groups;
    			$settings->save();
    		}
    		$this->redirect(WEB_ROOT."_modules/_dashboard/index.php?action=index");
    		return false;
    	}
    	$this->setData("form", $form);
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_dashboard/index.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->renderView("_settings/addUsersSettings.tpl");
    }
    public function actionChangeUsersSettings() {
    	$userGroup = CRequest::getInt("id");
    	$query = new CQuery();
    	$query->select("users.*")
	    	->from(TABLE_USERS." as users")
	    	->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
	    	->condition("userGroup.group_id=".$userGroup);
    	$users = array();
    	foreach ($query->execute()->getItems() as $ar) {
    		$user = new CUser(new CActiveRecord($ar));
    		$users[] = $user->getId();
    	}
    	$options = array(0 => "Нет", 1 => "Да");
    	$form = new CUsersSettingsForm();
    	$form->users = $users;
    	$this->setData("form", $form);
    	$this->setData("options", $options);
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_dashboard/index.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->renderView("_settings/changeUsersSettings.tpl");
    }
    public function actionChangeUsersSettingsProcess() {
    	$checkboxes = array(
    			"dashboard_enabled_groups",
    			"dashboard_show_birthdays_groups",
    			"dashboard_show_messages_groups",
    			"dashboard_show_all_tasks_groups",
    			"dashboard_check_messages_groups"
    	);
    	$form = new CUsersSettingsForm();
    	$form->setAttributes(CRequest::getArray($form::getClassName()));
    	if ($form->validate()) {
    		foreach ($form->users as $id) {
    			$settings = CStaffManager::getUserSettingsByUser($id);
    			if (!is_null($settings)) {
    				$settings->dashboard_enabled_groups = $form->dashboard_enabled_groups;
    				$settings->dashboard_show_birthdays_groups = $form->dashboard_show_birthdays_groups;
    				$settings->dashboard_show_messages_groups = $form->dashboard_show_messages_groups;
    				$settings->dashboard_show_all_tasks_groups = $form->dashboard_show_all_tasks_groups;
    				$settings->dashboard_check_messages_groups = $form->dashboard_check_messages_groups;
    				$settings->save();
    			}
    		}
    		$this->redirect(WEB_ROOT."_modules/_dashboard/index.php?action=index");
    		return false;
    	}
    	$this->setData("form", $form);
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_dashboard/index.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->renderView("_settings/changeUsersSettings.tpl");
    }
    public function actionSave() {
        $checkboxes = array(
            "dashboard_enabled",
            "dashboard_show_birthdays",
            "dashboard_show_messages",
            "dashboard_show_all_tasks",
            "dashboard_check_messages"
        );
        $settings = new CUserSettings();
        $settings->setAttributes(CRequest::getArray($settings::getClassName()));
        foreach ($checkboxes as $box) {
            if (!array_key_exists($box, CRequest::getArray($settings::getClassName()))) {
                $settings->$box = 0;
            }
        }
        if ($settings->validate()) {
            $settings->save();
            $this->redirect("?action=index");
        }
    }
}
