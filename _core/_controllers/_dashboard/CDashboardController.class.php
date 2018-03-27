<?php
class CDashboardController extends CBaseController {
	public $allowedAnonymous = array(
		"tasks"
	);
	public function __construct() {
		if (!CSession::isAuth()) {
			$this->redirectNoAccess();
		}
	
		$this->_smartyEnabled = true;
		$this->setPageTitle("Рабочий стол");
	
		parent::__construct();
	}
	public function actionIndex() {
        $this->addCSSInclude("_modules/_dashboard/style.css");
        $items = new CArrayList();
        /**
         * Показываем пункты рабочего стола в зависимости от
         * личных настроек пользователя
         */
        if (!is_null(CSession::getCurrentUser()->getPersonalSettings())) {
            $settings = CSession::getCurrentUser()->getPersonalSettings();
            /**
             * Показ дней рождения на этой недел
             */
            if ($settings->isShowBirthdays()) {
                if (CStaffManager::getBirthdaysThisWeek()->getCount() > 0) {
                    $cnt = 0;
                    $item = new CDashboardItem();
                    $item->id = "birthdays";
                    $item->title = "Дни рождения";
                    $item->icon = "mimetypes/text-x-java.png";
                    $items->add("_".$items->getCount(), $item);
                    foreach (CStaffManager::getBirthdaysThisWeek()->getItems() as $person) {
                        $child = new CDashboardItem();
                        $child->id = "person_".$person->getId();
                        $child->title = $person->getName()." (".$person->getBirthday().")";
                        $item->addChild($child);
                        $cnt++;
                        if ($cnt == 2) {
                            $child = new CDashboardItem();
                            $child->id = "person_q";
                            $child->title = "Всего ".CStaffManager::getBirthdaysThisWeek()->getCount();
                            $item->addChild($child);
                            break;
                        }
                    }
                }
            }
            /**
             * Показываем сообщения
             */
            if ($settings->isShowMessages()) {
                $item = new CDashboardItem();
                $item->title = "Сообщения";
                $item->icon = "apps/evolution.png";
                $item->addChild(null);
                if (CSession::getCurrentUser()->getUnreadMessages()->getCount() > 0) {
                    $child = new CDashboardItem();
                    $child->id = "inbox";
                    $child->title = "Входящие (".CSession::getCurrentUser()->getUnreadMessages()->getCount().")";
                    $child->link = "_modules/_mail";
                    $item->addChild($child);
                } else {
                    $child = new CDashboardItem();
                    $child->id = "inbox";
                    $child->title = "Нет непрочитанных сообщений";
                    $child->link = "_modules/_mail";
                    $item->addChild($child);
                }
                $child = new CDashboardItem();
                $child->id = "new";
                $child->title = "Написать сообщение";
                $child->link = "_modules/_mail";
                $item->addChild($child);
                $items->add("_".$items->getCount(), $item);
            }
        }
        $set = CActiveRecordProvider::getWithCondition(TABLE_DASHBOARD, "user_id = ".CSession::getCurrentUser()->getId()." and parent_id = 0");
        foreach ($set->getItems() as $ar) {
            $item = new CDashboardItem($ar);
            $items->add($item->getId(), $item);
        }
        /**
         * Личный рабочий стол
         */
        $dashboards = new CArrayList();
        $dashboards->add("Личный рабочий стол", $items);
        /**
         * Соберем рабочие столы групп, в которые входит пользователь
         */
        $groups = CSession::getCurrentUser()->getGroups();
        foreach ($groups->getItems() as $group) {
            $dashboards->add($group->comment, $group->dashboardItems);
        }
        $roles = new CArrayList();
        foreach (CSession::getCurrentUser()->getRoles()->getItems() as $role) {
			if ($role->hidden!=1) {
				$roles->add($role->getId(), $role);
			}
        }
        $sort = new CArrayList();
        foreach ($roles->getItems() as $i) {
			$sort->add($i->name, $i->getId());
        }
        $tasks = new CArrayList();
        foreach ($sort->getSortedByKey(true)->getItems() as $i) {
			$item = $roles->getItem($i);
			$tasks->add($item->getId(), $item);
        }
        
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Управление",
        		"link" => "index.php?action=list",
        		"icon" => "apps/preferences-desktop-wallpaper.png"
        	),
        	array(
        		"title" => "Личные настройки",
        		"link" => WEB_ROOT."_modules/_settings/index.php",
        		"icon" => "categories/applications-accessories.png"
        	)
        ));
        
        if (CSessionService::hasAnyUserGroup([ADMINISTRATORS])) {
        	$this->addActionsMenuItem(array(
        		array(
        			"title" => "Настройки для групп пользователей",
        			"link" => "index.php?action=settingsForGroups",
        			"icon" => "apps/preferences-desktop-wallpaper.png"
        		)
        	));
        }
        
        $this->setData("tasks", $tasks);
		$this->setData("dashboards", $dashboards);
        $this->setData("settings", $settings);
        $this->addJSInclude("_modules/_dashboard/script.js");
		$this->renderView("_dashboard/index.tpl");
	}
	public function actionTasks() {
		$roles = new CArrayList();
		foreach (CSession::getCurrentUser()->getRoles()->getItems() as $role) {
			if ($role->hidden!=1) {
				$roles->add($role->getId(), $role);
			}
		}
		$sort = new CArrayList();
		foreach ($roles->getItems() as $i) {
			$sort->add($i->name, $i->getId());
		}
		$tasks = new CArrayList();
		foreach ($sort->getSortedByKey(true)->getItems() as $i) {
			$item = $roles->getItem($i);
			$tasks->add($item->getId(), $item);
		}
		if (CSession::getCurrentUser()->getLevelForCurrentTask() != ACCESS_LEVEL_NO_ACCESS) {
			$this->addActionsMenuItem(array(
				array(
					"title" => "Личные настройки",
					"link" => WEB_ROOT."_modules/_settings/index.php",
					"icon" => "categories/applications-accessories.png"
				)
			));
		}
		$this->setData("tasks", $tasks);
		$this->renderView("_dashboard/tasks.tpl");
	}
	public function actionList() {
		$set = CActiveRecordProvider::getWithCondition(TABLE_DASHBOARD, "user_id = ".CSession::getCurrentUser()->getId()." and parent_id = 0");
        $items = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $item = new CDashboardItem($ar);
            $items->add($item->getId(), $item);
        }
        
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "index.php?action=index",
        		"icon" => "actions/edit-undo.png"
        	),
        	array(
        		"title" => "Добавить",
        		"link" => "index.php?action=add",
        		"icon" => "actions/list-add.png"
        	)
        ));
        
        $this->setData("paginator", $set->getPaginator());
        $this->setData("items", $items);
		$this->renderView("_dashboard/list.tpl");		
	}
	public function actionSettingsForGroups() {
		$set = CActiveRecordProvider::getWithCondition(TABLE_DASHBOARD, "parent_id = 0 and group_id != 0");
		$items = new CArrayList();
		foreach ($set->getPaginated()->getItems() as $ar) {
			$item = new CDashboardItem($ar);
			$items->add($item->getId(), $item);
		}
		
		$this->addActionsMenuItem(array(
			array(
				"title" => "Назад",
				"link" => "index.php?action=index",
				"icon" => "actions/edit-undo.png"
			),
			array(
				"title" => "Добавить",
				"link" => "index.php?action=add&forGroups=1",
				"icon" => "actions/list-add.png"
			)
		));
		
		$this->setData("paginator", $set->getPaginator());
		$this->setData("items", $items);
		$this->renderView("_dashboard/listForGroups.tpl");
	}
	public function actionAdd() {
		$parents = new CArrayList();
		foreach (CActiveRecordProvider::getWithCondition(TABLE_DASHBOARD, "user_id = ".CSession::getCurrentUser()->getId()." and parent_id = 0")->getItems() as $ar) {
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
        if (CRequest::getInt("forGroups") != 1) {
        	$item->user_id = CSession::getCurrentUser()->getId();
        }
        
        $this->addJSInclude("_core/jDropdown/jquery.dd.js");
        $this->addCSSInclude("_core/jDropdown/dd.css");
        
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "index.php?action=list",
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        
        $groups = array();
        foreach (CStaffManager::getAllUserGroups()->getItems() as $group) {
        	$groups[$group->getId()] = $group->comment;
        }
        $this->setData("groups", $groups);
        
        $accessLevel = false;
        if (CSessionService::hasAnyUserGroup([ADMINISTRATORS])) {
        	$accessLevel = true;
        }
        $this->setData("accessLevel", $accessLevel);
        
        $this->setData("icons", $icons);
		$this->setData("parents", $parents);
		$this->setData("item", $item);
		if (CRequest::getInt("forGroups") == 1) {
			$this->renderView("_dashboard/addForGroups.tpl");
		} else {
			$this->renderView("_dashboard/add.tpl");
		}
	}
	public function actionEdit() {
		$parents = new CArrayList();
		foreach (CActiveRecordProvider::getWithCondition(TABLE_DASHBOARD, "user_id = ".CSession::getCurrentUser()->getId()." and parent_id = 0")->getItems() as $ar) {
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
        $groups = array();
        foreach (CStaffManager::getAllUserGroups()->getItems() as $group) {
        	$groups[$group->getId()] = $group->comment;
        }
        $this->setData("groups", $groups);
        
        $accessLevel = false;
        if (CSessionService::hasAnyUserGroup([ADMINISTRATORS])) {
        	$accessLevel = true;
        }
        $this->setData("accessLevel", $accessLevel);
        
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "index.php?action=list",
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        
        $this->addJSInclude("_core/jDropdown/jquery.dd.js");
        $this->addCSSInclude("_core/jDropdown/dd.css");
        $this->setData("icons", $icons);
		$this->setData("parents", $parents);
		$this->setData("item", $item);
		if (CRequest::getInt("forGroups") == 1) {
			$this->renderView("_dashboard/editForGroups.tpl");
		} else {
			$this->renderView("_dashboard/edit.tpl");
		}
	}
	public function actionSave() {
		$item = new CDashboardItem();
		$item->setAttributes(CRequest::getArray($item::getClassName()));
		if ($item->validate()) {
			$item->save();
			if (CRequest::getInt("forGroups") == 1) {
				$this->redirect("?action=settingsForGroups");
			} else {
				$this->redirect("?action=list");
			}
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
		$this->renderView("_dashboard/edit.tpl");		
	}
	public function actionDelete() {
		$item = CDashboardManager::getDashboardItem(CRequest::getInt("id"));
		$item->remove();
		$this->redirect("?action=list");
	}
    /**
     * Показываем окошко с ближайшими днями рождения
     */
    public function actionShowBirthdayDialog() {
        $this->setData("persons", CStaffManager::getBirthdaysThisWeek());
        $this->renderView("_dashboard/subform.birthdays.tpl");
    }
}