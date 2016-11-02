<?php

class CSettingsListSearchController extends CBaseController {
	protected $_isComponent = true;
	
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
		$this->setPageTitle("Список настроек поиска по индексу Solr");
	
		parent::__construct();
	}
    public function actionIndex() {
    	$set = new CRecordSet();
    	$query = new CQuery();
    	$set->setQuery($query);
    	$query->select("t.*")
	    	->from(TABLE_SETTINGS_SOLR_SEARCH." as t")
	    	->order("t.title asc")
	    	->condition("solr_core=".CRequest::getInt("core_id"));
        $settings = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $setting = new CSearchSettingsList($item);
            $settings->add($setting->getId(), $setting);
        }
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Обновить",
        		"link" => "settingsList.php?action=index&core_id=".CRequest::getInt("core_id"),
        		"icon" => "actions/view-refresh.png"
        	),
        	array(
        		"title" => "Добавить настройку",
        		"icon" => "actions/list-add.png",
        		"link" => "settingsList.php?action=add&core_id=".CRequest::getInt("core_id")
        	)
        ));
        $this->setData("settings", $settings);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_search/_settingsList/index.tpl");
    }
    public function actionAdd() {
        $setting = new CSearchSettingsList();
        $setting->solr_core = CRequest::getInt("core_id");
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "settingsList.php?action=index&core_id=".$setting->solr_core,
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->setData("setting", $setting);
        $this->renderView("_search/_settingsList/add.tpl");
    }
    public function actionEdit() {
        $setting = CSearchSettingsManager::getSettingItem(CRequest::getInt("id"));
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "settingsList.php?action=index&core_id=".$setting->solr_core,
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->setData("setting", $setting);
        $this->renderView("_search/_settingsList/edit.tpl");
    }
    public function actionDelete() {
        $setting = CSearchSettingsManager::getSettingItem(CRequest::getInt("id"));
        $core = CSearchSettingsManager::getSetting(CRequest::getInt("core_id"));
        $setting->remove();
        $this->redirect("settingsList.php?action=index&core_id=".$core->getId());
    }
    public function actionSave() {
        $setting = new CSearchSettingsList();
        $setting->setAttributes(CRequest::getArray($setting::getClassName()));
        if ($setting->validate()) {
            $setting->save();
            if ($this->continueEdit()) {
                $this->redirect("settingsList.php?action=edit&id=".$setting->getId());
            } else {
                $this->redirect("settingsList.php?action=index&core_id=".$setting->solr_core);
            }
            return true;
        }
        $this->setData("setting", $setting);
        $this->renderView("_search/_settingsList/edit.tpl");
    }
}
