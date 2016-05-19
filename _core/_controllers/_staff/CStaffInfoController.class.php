<?php
class CStaffInfoController extends CBaseController{

    public function __construct() {
        if (!CSession::isAuth()) {
        	$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление информацией о сотруднике");

        parent::__construct();
    }
    public function actionAdd() {
    	$person = CStaffManager::getPerson(CRequest::getInt("id"));
    	$obj = new CPerson();
    	$fields = array();
    	foreach ($obj->getDbTableFields()->getItems() as $field) {
    		if (mb_strtolower($field->name) !== "id") {
    			$fields[] = $field->name;
    		}
    	}
    	foreach ($fields as $field) {
    		$labels = CCoreObjectsManager::getAttributeLabels($person);
    		$columnLabels = CCoreObjectsManager::getAttributeTableLabels($person);
    		if (array_key_exists($field, $columnLabels)) {
    			$label = $columnLabels[$field];
    		} elseif (array_key_exists($field, $labels)) {
    			$label = $labels[$field];
    		} else {
    			$label = $field;
    		}
    		$form[] = array(
    			"name" => $label,
    			"value" => $person->$field
    		);
    	}
    	$pageContent = array();
    	foreach ($form as $items) {
    		$pageContent[] = "<b>".$items["name"]."</b>: ".$items["value"];
    	}
    	$page = new CPage();
    	$page->title = CStaffManager::getPerson(CRequest::getInt("id"))->getName();
    	$page->user_id_insert = CStaffManager::getPerson(CRequest::getInt("id"))->getUserId();
    	$page->pg_cat = "1";
    	$page->page_content = implode("<br>", $pageContent);
    	$this->addActionsMenuItem(array(
    		"title" => "Назад",
    		"link" => "index.php?action=edit&id=".CRequest::getInt("id"),
    		"icon" => "actions/edit-undo.png"
    	));
    	$this->addJSInclude(JQUERY_UI_JS_PATH);
    	$this->addCSSInclude(JQUERY_UI_CSS_PATH);
    	$this->addCSSInclude("_modules/_redactor/redactor.css");
    	$this->addJSInclude("_modules/_redactor/redactor.min.js");
    	$this->setData("page", $page);
    	$this->renderView("_staff/person/staffInfo/add.tpl");
    }
    public function actionAddGroup() {
    	$pageContent = array();
    	foreach (CRequest::getArray("selectedDoc") as $id) {
    		$person = CStaffManager::getPerson($id);
    		$attributes = $person->fieldsProperty();
    		$display = false;
    		if (array_key_exists("photo", $attributes)) {
    			$field = $attributes["photo"];
    			if ($field["type"] == FIELD_UPLOADABLE) {
    				$storage = $field["upload_dir"];
    				$file = $person->photo;
    				if ($file !== "") {
    					if (file_exists($storage.$file)) {
    						$display = true;
    					}
    				}
    			}
    		}
    		if ($person->work_place != "") {
    			$pageContent[] = "<b>".$person->getName()."</b><br><br>";
    			if ($display) {
    				$pageContent[] = '<a href="../../images/lects/'.$person->photo.'" target="_blank" class="image_clearboxy cboxElement">
    									<img src="../../_modules/_thumbnails/?src=/images/lects/'.$person->photo.'&amp;w=200"></a><br>';
    			}
    			if ($person->getPost() != "") {
    				$pageContent[] = "Должность на кафедре: ".$person->getPost()."<br>";
    			}
    			$pageContent[] = "Основное место работы: ".$person->work_place."<br>";
    			if ($person->getManuals()->getCount() != 0) {
    				$pageContent[] = "Дисциплины:<br>";
    				foreach ($person->getManuals()->getItems() as $manual) {
    					$pageContent[] = '<li><a href="../../_modules/_library/index.php?action=publicView&id='.$manual->nameFolder.'">'.$manual->name.' ('.$manual->f_cnt.')</a></li>';
    				}
    			}
    			$pageContent[] = "<hr>";
    		}
    	}
    	$page = new CPage();
    	$page->title = "Наши работодатели";
    	$page->user_id_insert = CSession::getCurrentUser()->getId();
    	$page->pg_cat = "2";
    	$page->page_content = implode("", $pageContent);
    	$this->addActionsMenuItem(array(
    		"title" => "Назад",
    		"link" => "index.php?action=index",
    		"icon" => "actions/edit-undo.png"
    	));
    	$this->addJSInclude(JQUERY_UI_JS_PATH);
    	$this->addCSSInclude(JQUERY_UI_CSS_PATH);
    	$this->addCSSInclude("_modules/_redactor/redactor.css");
    	$this->addJSInclude("_modules/_redactor/redactor.min.js");
    	$this->setData("page", $page);
    	$this->renderView("_staff/person/staffInfo/add.tpl");
    }
    public function actionEdit() {
    	$page = CPageManager::getPage(CRequest::getInt("id"));
    	$personId = CStaffManager::getUserById($page->user_id_insert)->getPerson()->getId();
    	if ($page->pg_cat = "2") {
    		$this->addActionsMenuItem(array(
    			"title" => "Назад",
    			"link" => "index.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		));
    	} else {
    		$this->addActionsMenuItem(array(
    			"title" => "Назад",
    			"link" => "index.php?action=edit&id=".$personId,
    			"icon" => "actions/edit-undo.png"
    		));
    	}
    	$this->addJSInclude(JQUERY_UI_JS_PATH);
    	$this->addCSSInclude(JQUERY_UI_CSS_PATH);
    	$this->addCSSInclude("_modules/_redactor/redactor.css");
    	$this->addJSInclude("_modules/_redactor/redactor.min.js");
    	$this->setData("page", $page);
    	$this->renderView("_staff/person/staffInfo/edit.tpl");
    }
    public function actionDelete() {
    	$page = CPageManager::getPage(CRequest::getInt("id"));
    	$personId = CStaffManager::getUserById($page->user_id_insert)->getPerson()->getId();
    	$page->remove();
    	$this->redirect("index.php?action=edit&id=".$personId);
    }
    public function actionSave() {
    	$page = new CPage();
    	$page->setAttributes(CRequest::getArray($page::getClassName()));
    	if ($page->validate()) {
    		$page->save();
    		if ($this->continueEdit()) {
    			$this->redirect("?action=edit&id=".$page->getId());
    		} else {
    			$this->redirect("index.php?action=edit&id=".CStaffManager::getUserById($page->user_id_insert)->getPerson()->getId());
    		}
    		return true;
    	}
    	$this->addJSInclude(JQUERY_UI_JS_PATH);
    	$this->addCSSInclude(JQUERY_UI_CSS_PATH);
    	$this->addCSSInclude("_modules/_redactor/redactor.css");
    	$this->addJSInclude("_modules/_redactor/redactor.min.js");
    	$this->setData("page", $page);
    	$this->renderView("_staff/person/staffInfo/edit.tpl");
    }
}