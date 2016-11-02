<?php

class CSettingsSearchController extends CBaseController {
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
		$this->setPageTitle("Настройки поиска по индексу Solr");
	
		parent::__construct();
	}
    public function actionIndex() {
        $set = CActiveRecordProvider::getAllFromTable(TABLE_SETTINGS_SOLR_CORES, "title asc");
        $settings = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $setting = new CSearchSettings($item);
            $settings->add($setting->getId(), $setting);
        }
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "index.php?action=index",
        		"icon" => "actions/edit-undo.png"
        	),
        	array(
        		"title" => "Добавить коллекцию",
        		"icon" => "actions/list-add.png",
        		"link" => "settings.php?action=add"
        	)
        ));
        $this->setData("settings", $settings);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_search/_settings/index.tpl");
    }
    public function actionAdd() {
        $setting = new CSearchSettings();
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "settings.php?action=index",
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->setData("setting", $setting);
        $this->renderView("_search/_settings/add.tpl");
    }
    public function actionEdit() {
        $setting = CSearchSettingsManager::getSetting(CRequest::getInt("id"));
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "settings.php?action=index",
        		"icon" => "actions/edit-undo.png"
        	),
        	array(
        		"title" => "Обновить файловый индекс для коллекции",
        		"icon" => "actions/document-print-preview.png",
        		"link" => "index.php?action=updateIndexFiles&core_id=".CRequest::getInt("id")
        	)
        ));
        $this->setData("setting", $setting);
        $this->renderView("_search/_settings/edit.tpl");
    }
    public function actionDelete() {
        $setting = CSearchSettingsManager::getSetting(CRequest::getInt("id"));
        $setting->remove();
        $this->redirect("?action=index");
    }
    public function actionSave() {
        $setting = new CSearchSettings();
        $setting->setAttributes(CRequest::getArray($setting::getClassName()));
        if ($setting->validate()) {
            $setting->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$setting->getId());
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        $this->setData("setting", $setting);
        $this->renderView("_search/_settings/edit.tpl");
    }
}
