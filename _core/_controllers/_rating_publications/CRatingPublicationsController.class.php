<?php
class CRatingPublicationsController extends CBaseController{
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
        $this->setPageTitle("Рейтинг преподавателей по публикациям");

        parent::__construct();
    }
    public function actionIndex() {
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Показатели преподавателей",
    			"link" => "persons.php",
    			"icon" => "apps/system-users.png"
    		),
    		array(
    			"title" => "Публикации",
    			"link" => WEB_ROOT."_modules/_staff/publications.php",
    			"icon" => "actions/document-open.png"
    		)
    	));
    	if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_ALL) {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "Типы изданий",
    				"link" => "?action=types",
    				"icon" => "actions/address-book-new.png"
    			)
    		));
    	}
    	$this->addJSInclude("_core/HighCharts/highcharts.src.js");
    	$this->addJSInclude("_core/HighCharts/modules/exporting.src.js");
    	$this->addJSInclude("_modules/_rating/ratingPublications.js");
    	$this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
    	$this->addJSInclude("_core/jTagIt/tag-it.js");
    	$this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
    	$this->addCSSInclude("_core/jTagIt/jquery.tagit.css");
    	$this->renderView("_rating_publications/index.tpl");
    }
    public function actionTypes() {
    	$set = new CRecordSet();
    	$query = new CQuery();
    	$query->select("types.*")
	    	->from(TABLE_PUBLICATIONS_TYPES." as types")
	    	->order("types.name asc");
    	$types = new CArrayList();
    	$set->setQuery($query);
    	foreach ($set->getPaginated()->getItems() as $ar) {
    		$type = new CPublicationByTypes($ar);
    		$types->add($type->getId(), $type);
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => "index.php",
    			"icon" => "actions/edit-undo.png"
    		),
    		array(
    			"title" => "Добавить",
    			"link" => "index.php?action=add",
    			"icon" => "actions/list-add.png"
    		)
    	));
    	$this->setData("paginator", $set->getPaginator());
    	$this->setData("types", $types);
    	$this->renderView("_rating_publications/types.tpl");
    }
    public function actionAdd() {
    	$type = new CPublicationByTypes();
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => "index.php?action=types",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->setData("type", $type);
    	$this->renderView("_rating_publications/add.tpl");
    }
    public function actionEdit() {
    	$type = CBaseManager::getPublicationByTypes(CRequest::getInt("id"));
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => "index.php?action=types",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->setData("type", $type);
    	$this->renderView("_rating_publications/edit.tpl");
    }
    public function actionDelete() {
    	$type = CBaseManager::getPublicationByTypes(CRequest::getInt("id"));
    	$type->remove();
    	$this->redirect("index.php?action=types");
    }
    public function actionSave() {
    	$type = new CPublicationByTypes();
    	$type->setAttributes(CRequest::getArray($type::getClassName()));
    	if ($type->validate()) {
    		$type->save();
    		if ($this->continueEdit()) {
    			$this->redirect("index.php?action=edit&id=".$type->getId());
    		} else {
    			$this->redirect("index.php?action=types");
    		}
    		return true;
    	}
    	$this->setData("type", $type);
    	$this->renderView("_rating_publications/edit.tpl");
    }
}