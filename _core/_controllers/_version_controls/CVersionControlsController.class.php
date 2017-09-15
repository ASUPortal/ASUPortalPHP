<?php
/**
 * Управление версиями
 */
class CVersionControlsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }
        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление версиями");

        parent::__construct();
    }
    public function actionIndex() {
    	$module = CRequest::getString("module");
    	$class = CRequest::getString("class");
    	
    	$items = new CArrayList();
    	foreach (CVersionControlService::getVersions(CRequest::getInt("id"), $class) as $item) {
    		if (!is_null($item)) {
    			$item = new $class(new CActiveRecord($item));
    			$items->add($item->id, $item);
    		}
    	}
    	
    	$model = CCoreObjectsManager::getCoreModel($class);
    	if (!is_null($model)) {
    		$modelName = $model->title;
    	} else {
    		$modelName = $class;
    	}
    	$this->setData("modelName", $modelName);
    	
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Удалить выделенные",
    			"icon" => "actions/edit-delete.png",
    			"form" => "#MainView",
    			"link" => "index.php",
    			"action" => "delete"
    		)
    	));
    	
        $this->setData("items", $items);
        $this->setData("itemId", CRequest::getInt("id"));
        $this->setData("class", $class);
        $this->setData("module", $module);
        $this->renderView("_version_controls/index.tpl");
    }
    public function actionDelete() {
    	$itemId = CRequest::getInt("itemId");
    	$module = CRequest::getString("module");
    	$class = CRequest::getString("class");
    	
    	if (CRequest::getInt("id") != 0) {
    		CVersionControlService::delete(CRequest::getInt("id"), $class);
    	} else {
    		$items = CRequest::getArray("selectedInView");
    		foreach ($items as $id){
    			CVersionControlService::delete($id, $class);
    		}
    	}
    	
        $this->redirect("?action=index&id=".$itemId."&module=".$module."&class=".$class);
    }
}