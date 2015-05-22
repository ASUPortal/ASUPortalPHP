<?php

class CPractPlacesController extends CBaseController{
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
        $this->setPageTitle("Базы практики студентов");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet(false);
        $query = new CQuery();
        $selectedTown = null;
        $query->select("pract.*")
            ->from(TABLE_PRACTICE_PLACES." as pract")
            ->order("pract.name asc");
        $practics = new CArrayList();
        $set->setQuery($query);
        if (CRequest::getString("order") == "towns.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->leftJoin(TABLE_TOWNS." as towns", "pract.town_id=towns.id");
        		$query->order("towns.name ".$direction);
        }   
        elseif (CRequest::getString("order") == "name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("name ".$direction);
        }
        elseif (CRequest::getString("order") == "comment") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("comment ".$direction);
        }
        $townQuery = new CQuery();
        $townQuery->select("distinct(town.id) as id, town.name as name")
        	->from(TABLE_TOWNS." as town")
        	->innerJoin(TABLE_PRACTICE_PLACES." as pract", "pract.town_id = town.id")
        	->order("town.name asc");
        // фильтр по городу
        if (!is_null(CRequest::getFilter("town"))) {
        	$query->innerJoin(TABLE_TOWNS." as town", "pract.town_id = town.id and town.id = ".CRequest::getFilter("town"));
        	$selectedTown = CRequest::getFilter("town");
        }
        // фильтр по наименованию
        if (!is_null(CRequest::getFilter("name"))) {
        	$query->condition("pract.id = ".CRequest::getFilter("name"));
        }
        // фильтр по комментарию
        if (!is_null(CRequest::getFilter("comment"))) {
        	$query->condition("pract.id = ".CRequest::getFilter("comment"));
        }
        $towns = array();
        foreach ($townQuery->execute()->getItems() as $item) {
        	$towns[$item["id"]] = $item["name"];
        }
        foreach ($set->getPaginated()->getItems() as $ar) {
        	$practic = new CPracticePlace($ar);
        	$practics->add($practic->getId(), $practic);
        }
        $this->setData("paginator", $set->getPaginator());
        $this->setData("practics", $practics);
        $this->setData("towns", $towns);
        $this->setData("selectedTown", $selectedTown);
        $this->renderView("_pract_bases/index.tpl");
    }
    public function actionAdd() {
        $practic = new CPracticePlace();
        $this->setData("practic", $practic);
        $this->renderView("_pract_bases/add.tpl");
    }
    public function actionEdit() {
		$practic = CTaxonomyManager::getPracticePlace(CRequest::getInt("id"));
        $this->setData("practic", $practic);
        $this->renderView("_pract_bases/edit.tpl");
    }
    public function actionDelete() {
        $practic = CTaxonomyManager::getPracticePlace(CRequest::getInt("id"));
        $practic->remove();
        $this->redirect("index.php?action=index");
    }
    public function actionSave() {
        $practic = new CPracticePlace();
        $practic->setAttributes(CRequest::getArray(CPracticePlace::getClassName()));
        if ($practic->validate()) {
        	$practic->save();
        	if ($this->continueEdit()) {
        		$this->redirect("?action=edit&id=".$practic->getId());
        	} else {
        		$this->redirect("?action=index");
        	}
        	return true;
        }
        $this->setData("practic", $practic);
        $this->renderView("_pract_bases/edit.tpl");
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по наименованию практики
    	*/
    	$query = new CQuery();
    	$query->select("distinct(pract.id) as id, pract.name as name")
    	->from(TABLE_PRACTICE_PLACES." as pract")
    	->condition("pract.name like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"label" => $item["name"],
    				"value" => $item["name"],
    				"object_id" => $item["id"],
    				"type" => 1
    		);
    	}
    	/**
    	 * Поиск по комментарию
    	 */
    	$query = new CQuery();
    	$query->select("distinct(pract.id) as id, pract.comment as comment")
    	->from(TABLE_PRACTICE_PLACES." as pract")
    	->condition("pract.comment like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"label" => $item["comment"],
    				"value" => $item["comment"],
    				"object_id" => $item["id"],
    				"type" => 2
    		);
    	}
    	echo json_encode($res);
    }
}