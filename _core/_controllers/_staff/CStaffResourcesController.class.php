<?php
/**
 * Научные ресурсы сотрудника
 */
class CStaffResourcesController extends CBaseController{

    public function __construct() {
        if (!CSession::isAuth()) {
        	$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Научные ресурсы сотрудника");

        parent::__construct();
    }
    public function actionAdd() {
    	$resource = new CPersonResource();
    	$resource->person_id = CRequest::getInt("id");
    	$this->setData("resource", $resource);
    	$this->addActionsMenuItem(array(
    		"title" => "Назад",
    		"link" => "index.php?action=edit&id=".CRequest::getInt("id"),
    		"icon" => "actions/edit-undo.png"
    	));
    	$this->renderView("_staff/person/resources/add.tpl");
    }
    public function actionEdit() {
    	$resource = CBaseManager::getPersonResource(CRequest::getInt("id"));
    	$person = $resource->person;
    	$this->addActionsMenuItem(array(
    		"title" => "Назад",
    		"link" => "index.php?action=edit&id=".$person->getId(),
    		"icon" => "actions/edit-undo.png"
    	));
    	$this->setData("resource", $resource);
    	$this->renderView("_staff/person/resources/edit.tpl");
    }
    public function actionDelete() {
    	$resource = CBaseManager::getPersonResource(CRequest::getInt("id"));
    	$person = $resource->person;
    	$resource->remove();
    	$this->redirect("index.php?action=edit&id=".$person->getId());
    }
    public function actionSave() {
    	$resource = new CPersonResource();
    	$resource->setAttributes(CRequest::getArray($resource::getClassName()));
    	if ($resource->validate()) {
    		$resource->save();
    		if ($this->continueEdit()) {
    			$this->redirect("resources.php?action=edit&id=".$resource->getId());
    		} else {
    			$this->redirect("index.php?action=edit&id=".$resource->person->getId());
    		}
    		return true;
    	}
    	$this->setData("resource", $resource);
    	$this->renderView("_staff/person/resources/edit.tpl");
    }
}