<?php
class CDashboardSettingsController extends CBaseController {
	public function __construct() {
		if (!CSession::isAuth()) {
			$this->redirectNoAccess();
		}
	
		$this->_smartyEnabled = true;
		$this->setPageTitle("Настройка рабочего стола");
	
		parent::__construct();
	}
	public function actionIndex() {
		$set = new CRecordSet(false);
		$query = new CQuery();
		$set->setQuery($query);
		$query->select("dash.*")
			->from(TABLE_DASHBOARD." as dash")
			->condition("dash.parent_id = 0");
		$selectedUser = null;
		$selectedGroup = null;
		$groupsQuery = new CQuery();
		$groupsQuery->select("groups.*")
			->from(TABLE_USER_GROUPS." as groups")
			->order("groups.comment asc")
			->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "groups.id = userGroup.group_id")
			->innerJoin(TABLE_DASHBOARD." as dash", "userGroup.user_id = dash.user_id");
		$usersQuery = new CQuery();
		$usersQuery->select("user.*")
			->from(TABLE_USERS." as user")
			->order("user.fio asc")
			->innerJoin(TABLE_DASHBOARD." as dash", "user.id = dash.user_id");
		if (CRequest::getString("order") == "user.FIO") {
			$direction = "asc";
			if (CRequest::getString("direction") != "") {
				$direction = CRequest::getString("direction");}
				$query->innerJoin(TABLE_USERS." as user", "dash.user_id = user.id");
				$query->order("user.FIO ".$direction);
		}
		if (!is_null(CRequest::getFilter("group"))) {
			$selectedGroup = CRequest::getFilter("group");
			$query->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "dash.user_id=userGroup.user_id");
			$query->innerJoin(TABLE_USER_GROUPS." as groups", "userGroup.group_id = groups.id and groups.id = ".CRequest::getFilter("group"));
			$usersQuery->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "dash.user_id=userGroup.user_id");
			$usersQuery->innerJoin(TABLE_USER_GROUPS." as groups", "userGroup.group_id = groups.id and groups.id = ".CRequest::getFilter("group"));
		}
		if (!is_null(CRequest::getFilter("user"))) {
			$query->innerJoin(TABLE_USERS." as user", "user.id = dash.user_id and user.id = ".CRequest::getFilter("user"));
			$selectedUser = CRequest::getFilter("user");
			$groupsQuery->innerJoin(TABLE_USERS." as user", "user.id = dash.user_id and user.id = ".CRequest::getFilter("user"));
		}
		if (is_null(CRequest::getFilter("user")) and is_null(CRequest::getFilter("group"))) {
			$query->condition("dash.user_id = ".CSession::getCurrentUser()->getId()." and dash.parent_id = 0");
		}
		$usersGroups = array();
		foreach ($groupsQuery->execute()->getItems() as $ar) {
			$group = new CUserGroup(new CActiveRecord($ar));
			$usersGroups[$group->getId()] = $group->comment;
		}
		$users = array();
		foreach ($usersQuery->execute()->getItems() as $ar) {
			$user = new CUser(new CActiveRecord($ar));
			$users[$user->getId()] = $user->getName();
		}
		$items = new CArrayList();
		foreach ($set->getPaginated()->getItems() as $ar) {
			$item = new CDashboardItem($ar);
			$items->add($item->getId(), $item);
		}
		$this->addActionsMenuItem(array(
			array(
				"title" => "Удалить выделенные",
				"link" => "settings.php",
				"form" => "#MainView",
				"icon" => "actions/edit-delete.png",
				"action" => "delete"
			)	
		));
		$this->setData("usersGroups", $usersGroups);
		$this->setData("users", $users);
		$this->setData("selectedGroup", $selectedGroup);
		$this->setData("selectedUser", $selectedUser);
		$this->setData("paginator", $set->getPaginator());
		$this->setData("items", $items);
		$this->renderView("_dashboard/settings/index.tpl");
	}
	public function actionAdd() {
		$parents = new CArrayList();
		foreach (CActiveRecordProvider::getWithCondition(TABLE_DASHBOARD, "user_id = ".CRequest::getInt("id")." and parent_id = 0")->getItems() as $ar) {
			$item = new CDashboardItem($ar);
			$parents->add($item->getId(), $item->title);
			foreach ($item->children->getItems() as $child) {
				$parents->add($child->getId(), " - ".$child->title);
			}
		}
		$icons = new CArrayList();
		$iconSources = new CArrayList();
		$dirs = array(
				"actions",
				"apps",
				"categories",
				"devices",
				"emblems",
				"emotes",
				"mimetypes",
				"places",
				"status"
		);
		foreach ($dirs as $dir) {
			if ($h = opendir(CORE_CWD."/images/tango/16x16/".$dir."/")) {
				while ($file = readdir($h)) {
					if (strpos($file, ".png")) {
						$iconSources->add($dir."/".$file, $dir."/".$file);
					}
				}
				closedir($h);
			}
		}
		/**
		 * Теперь исключаем те, которых нет в фаензе
		 */
		foreach ($dirs as $dir) {
			if (file_exists(CORE_CWD."/images/tango/64x64/".$dir)) {
				if ($h = opendir(CORE_CWD."/images/tango/64x64/".$dir."/")) {
					while ($file = readdir($h)) {
						if ($iconSources->hasElement($dir."/".$file)) {
							$icons->add($dir."/".$file, $dir."/".$file);
						}
					}
					closedir($h);
				}
			}
		}
		$item = new CDashboardItem();
		$item->user_id = CRequest::getInt("id");
		$this->addActionsMenuItem(array(
			array(
				"title" => "Назад",
				"link" => WEB_ROOT."_modules/_dashboard/settings.php?action=index",
				"icon" => "actions/edit-undo.png"
			)
		));
		$this->addJSInclude("_core/jDropdown/jquery.dd.js");
		$this->addCSSInclude("_core/jDropdown/dd.css");
		$this->setData("icons", $icons);
		$this->setData("parents", $parents);
		$this->setData("item", $item);
		$this->renderView("_dashboard/settings/add.tpl");
	}
	public function actionEdit() {
		$parents = new CArrayList();
		foreach (CActiveRecordProvider::getWithCondition(TABLE_DASHBOARD, "user_id = ".CRequest::getInt("user_id")." and parent_id = 0")->getItems() as $ar) {
			$item = new CDashboardItem($ar);
			$parents->add($item->getId(), $item->title);
			foreach ($item->children->getItems() as $child) {
				$parents->add($child->getId(), " - ".$child->title);
			}
		}
        $icons = new CArrayList();
        $iconSources = new CArrayList();
        $dirs = array(
            "actions",
            "apps",
            "categories",
            "devices",
            "emblems",
            "emotes",
            "mimetypes",
            "places",
            "status"
        );
        foreach ($dirs as $dir) {
            if ($h = opendir(CORE_CWD."/images/tango/16x16/".$dir."/")) {
                while ($file = readdir($h)) {
                    if (strpos($file, ".png")) {
                        $iconSources->add($dir."/".$file, $dir."/".$file);
                    }
                }
                closedir($h);
            }
        }
        /**
         * Теперь исключаем те, которых нет в фаензе
         */
        foreach ($dirs as $dir) {
            if (file_exists(CORE_CWD."/images/tango/64x64/".$dir)) {
                if ($h = opendir(CORE_CWD."/images/tango/64x64/".$dir."/")) {
                    while ($file = readdir($h)) {
                        if ($iconSources->hasElement($dir."/".$file)) {
                            $icons->add($dir."/".$file, $dir."/".$file);
                        }
                    }
                    closedir($h);
                }
            }
        }
        $item = CDashboardManager::getDashboardItem(CRequest::getInt("id"));
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => WEB_ROOT."_modules/_dashboard/settings.php?action=index",
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->addJSInclude("_core/jDropdown/jquery.dd.js");
        $this->addCSSInclude("_core/jDropdown/dd.css");
        $this->setData("icons", $icons);
		$this->setData("parents", $parents);
		$this->setData("item", $item);
		$this->renderView("_dashboard/settings/edit.tpl");		
	}
	public function actionSave() {
		$item = new CDashboardItem();
		$item->setAttributes(CRequest::getArray($item::getClassName()));
		if ($item->validate()) {
			$item->save();
			$this->redirect("?action=index");
			return true;
		}
		$parents = new CArrayList();
		foreach (CActiveRecordProvider::getWithCondition(TABLE_DASHBOARD, "user_id = ".CSession::getCurrentUser()->getId()." and parent_id = 0")->getItems() as $ar) {
			$item = new CDashboardItem($ar);
			$parents->add($item->getId(), $item->title);
			foreach ($item->children->getItems() as $child) {
				$parents->add($child->getId(), " - ".$child->title);
			}
		}
		$this->setData("parents", $item);		
		$this->setData("item", $item);
		$this->renderView("_dashboard/settings/edit.tpl");		
	}
	public function actionDelete() {
		$item = CDashboardManager::getDashboardItem(CRequest::getInt("id"));
		if (!is_null($item)) {
			$item->remove();
		}
		$items = CRequest::getArray("selectedDoc");
		foreach ($items as $id){
			$item = CDashboardManager::getDashboardItem($id);
			$item->remove();
		}
		$this->redirect("?action=index");
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
    	$this->renderView("_dashboard/settings/usersGroups.tpl");
    }
    public function actionItemsGroups() {
    	$userGroup = CRequest::getInt("id");
    	$query = new CQuery();
    	$query->select("dash.*")
	    	->from(TABLE_DASHBOARD." as dash")
	    	->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=dash.user_id")
	    	->condition("userGroup.group_id=".$userGroup." and dash.parent_id = 0");
    	$items = array();
    	foreach ($query->execute()->getItems() as $ar) {
    		$item = new CDashboardItem(new CActiveRecord($ar));
    		$items[] = $item->getId();
    	}
    	$parents = array();
    	foreach ($items as $id) {
    		$parents[] = CDashboardManager::getDashboardItem($id)->title;
    	}

    	$this->setData("parents", $parents);
    	$this->renderView("_dashboard/settings/itemsGroups.tpl");
    }
    public function actionAddItemsForGroups() {
    	$icons = new CArrayList();
    	$iconSources = new CArrayList();
    	$dirs = array(
    			"actions",
    			"apps",
    			"categories",
    			"devices",
    			"emblems",
    			"emotes",
    			"mimetypes",
    			"places",
    			"status"
    	);
    	foreach ($dirs as $dir) {
    		if ($h = opendir(CORE_CWD."/images/tango/16x16/".$dir."/")) {
    			while ($file = readdir($h)) {
    				if (strpos($file, ".png")) {
    					$iconSources->add($dir."/".$file, $dir."/".$file);
    				}
    			}
    			closedir($h);
    		}
    	}
    	/**
    	 * Теперь исключаем те, которых нет в фаензе
    	 */
    	foreach ($dirs as $dir) {
    		if (file_exists(CORE_CWD."/images/tango/64x64/".$dir)) {
    			if ($h = opendir(CORE_CWD."/images/tango/64x64/".$dir."/")) {
    				while ($file = readdir($h)) {
    					if ($iconSources->hasElement($dir."/".$file)) {
    						$icons->add($dir."/".$file, $dir."/".$file);
    					}
    				}
    				closedir($h);
    			}
    		}
    	}
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
    	$form = new CDashboardItemForm();
    	$form->users = $users;
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_dashboard/settings.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->addJSInclude("_core/jDropdown/jquery.dd.js");
    	$this->addCSSInclude("_core/jDropdown/dd.css");
    	$this->setData("icons", $icons);
    	$this->setData("form", $form);
    	$this->renderView("_dashboard/settings/addItemsForGroups.tpl");
    }
    public function actionAddItemsForGroupsProcess() {
    	$form = new CDashboardItemForm();
    	$form->setAttributes(CRequest::getArray($form::getClassName()));
    	if ($form->validate()) {
    		foreach ($form->users as $id) {
    			$items = new CDashboardItem();
    			$items->user_id = $id;
    			$items->title = $form->title;
    			$items->link = $form->link;
    			$items->icon = $form->icon;
    			$items->save();
    		}
    		$this->redirect("?action=index");
    		return false;
    	}
    	$this->setData("form", $form);
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_dashboard/settings.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->renderView("_dashboard/settings/addItemsForGroups.tpl");
    }
    /*public function actionAddChildrenForGroups() {
    	$icons = new CArrayList();
    	$iconSources = new CArrayList();
    	$dirs = array(
    			"actions",
    			"apps",
    			"categories",
    			"devices",
    			"emblems",
    			"emotes",
    			"mimetypes",
    			"places",
    			"status"
    	);
    	foreach ($dirs as $dir) {
    		if ($h = opendir(CORE_CWD."/images/tango/16x16/".$dir."/")) {
    			while ($file = readdir($h)) {
    				if (strpos($file, ".png")) {
    					$iconSources->add($dir."/".$file, $dir."/".$file);
    				}
    			}
    			closedir($h);
    		}
    	}
    	/**
    	 * Теперь исключаем те, которых нет в фаензе
    	 */
    	/*foreach ($dirs as $dir) {
    		if (file_exists(CORE_CWD."/images/tango/64x64/".$dir)) {
    			if ($h = opendir(CORE_CWD."/images/tango/64x64/".$dir."/")) {
    				while ($file = readdir($h)) {
    					if ($iconSources->hasElement($dir."/".$file)) {
    						$icons->add($dir."/".$file, $dir."/".$file);
    					}
    				}
    				closedir($h);
    			}
    		}
    	}
    	$userGroup = CRequest::getInt("id");
    	$usersQuery = new CQuery();
		$usersQuery->select("users.*")
			->from(TABLE_USERS." as users")
			->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
			->condition("userGroup.group_id=".$userGroup." and users.id in (select `user_id` from `dashboard`)");
    	$users = array();
    	foreach ($usersQuery->execute()->getItems() as $ar) {
    		$user = new CUser(new CActiveRecord($ar));
    		$users[] = $user->getId();
    	}
    	$query = new CQuery();
    	$query->select("dash.*")
	    	->from(TABLE_DASHBOARD." as dash")
	    	->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=dash.user_id")
	    	->condition("userGroup.group_id=".$userGroup." and dash.parent_id = 0");
    	$items = array();
    	foreach ($query->execute()->getItems() as $ar) {
    		$item = new CDashboardItem(new CActiveRecord($ar));
    		$items[] = $item->getId();
    	}
    	$parents = array();
    	foreach ($items as $id) {
    		$parents[CDashboardManager::getDashboardItem($id)->id] = CDashboardManager::getDashboardItem($id)->title." - ".CDashboardManager::getDashboardItem($id)->link;
    	}
    	$form = new CDashboardItemForm();
    	$form->users = $users;
    	$form->parent_id = $parents;
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_dashboard/settings.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->addJSInclude("_core/jDropdown/jquery.dd.js");
    	$this->addCSSInclude("_core/jDropdown/dd.css");
    	$this->setData("icons", $icons);
    	$this->setData("parents", $parents);
    	$this->setData("form", $form);
    	$this->renderView("_dashboard/settings/addChildrenForGroups.tpl");
    }
    public function actionAddChildrenForGroupsProcess() {
		$form = new CDashboardItemForm();
    	$form->setAttributes(CRequest::getArray($form::getClassName()));
    	if ($form->validate()) {
    		foreach ($form->users as $id) {
    			$items = new CDashboardItem();
    			$items->user_id = $id;
    			$items->title = $form->title;
    			$items->link = $form->link;
    			$items->icon = $form->icon;
    			$items->parent_id = $form->parent_id;
    			$items->save();
    		}
    		$this->redirect("?action=index");
    		return false;
    	}
    	$this->setData("form", $form);
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_dashboard/settings.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->renderView("_dashboard/settings/addItemsForGroups.tpl");
    }*/
}