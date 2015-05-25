<?php

class CAspirantsViewController extends CBaseController{
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
        $this->setPageTitle("Данные об аспирантах");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet(false);
        $query = new CQuery();
        $selectedPerson = null;
        $query->select("disser.*")
            ->from(TABLE_PERSON_DISSER." as disser")
            ->innerJoin(TABLE_PERSON." as person", "disser.kadri_id = person.id")
            ->condition("disser.disser_type = 'кандидат'")
            ->order("person.fio asc");
        $set->setQuery($query);
        if (CRequest::getString("order") == "person.fio") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_PERSON." as person", "disser.kadri_id = person.id");
        		$query->order("person.fio ".$direction);
        }   
        elseif (CRequest::getString("order") == "science_spec_id") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("science_spec_id ".$direction);
        }
        elseif (CRequest::getString("order") == "study_form_id") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("study_form_id ".$direction);
        }
        elseif (CRequest::getString("order") == "scinceMan") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_PERSON." as person", "disser.scinceMan = person.id");
        		$query->order("person.fio ".$direction);
        }
        elseif (CRequest::getString("order") == "tema") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("tema ".$direction);
        }
        elseif (CRequest::getString("order") == "god_zach") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("god_zach ".$direction);
        }
        elseif (CRequest::getString("order") == "date_end") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("date_end ".$direction);
        }
        elseif (CRequest::getString("order") == "comment") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("comment ".$direction);
        }
        // фильтр по руководителю
        if (!is_null(CRequest::getFilter("person"))) {
        	$query->innerJoin(TABLE_PERSON." as person", "disser.scinceMan = person.id and person.id = ".CRequest::getFilter("person"));
        	$selectedPerson = CRequest::getFilter("person");
        }
        // фильтр по ФИО
        if (!is_null(CRequest::getFilter("fio"))) {
        	$query->innerJoin(TABLE_PERSON." as person", "disser.kadri_id=person.id and person.id = ".CRequest::getFilter("fio"));
        }
        // фильтр по теме
        if (!is_null(CRequest::getFilter("tema"))) {
        	$query->condition("disser.id = ".CRequest::getFilter("tema"));
        }
        // фильтр по комментарию
        if (!is_null(CRequest::getFilter("comment"))) {
        	$query->condition("disser.id = ".CRequest::getFilter("comment"));
        }
        $isArchive = (CRequest::getString("isArchive") == "1");
        if (!$isArchive) {
			$query->condition('disser.date_end > "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'"');
        }
        if ($isArchive) {
        	$requestParams = array();
        	foreach (CRequest::getGlobalRequestVariables()->getItems() as $key=>$value) {
        		if ($key != "isArchive") {
        			$requestParams[] = $key."=".$value;
        		}
        	}
        	$this->addActionsMenuItem(array(
        			array(
        					"title" => "Текущий год",
        					"link" => "?".implode("&", $requestParams),
        					"icon" => "mimetypes/x-office-calendar.png"
        			),
        	));
        } else {
        	$requestParams = array();
        	foreach (CRequest::getGlobalRequestVariables()->getItems() as $key=>$value) {
        		$requestParams[] = $key."=".$value;
        	}
        	$requestParams[] = "isArchive=1";
        	$this->addActionsMenuItem(array(
        			array(
        					"title" => "Архив",
        					"link" => "?".implode("&", $requestParams),
        					"icon" => "devices/media-floppy.png"
        			),
        	));
        }
        $managersQuery = new CQuery();
        $managersQuery->select("person.*")
        ->from(TABLE_PERSON." as person")
        ->order("person.fio asc")
		->innerJoin(TABLE_PERSON_DISSER." as disser", "person.id = disser.scinceMan");
        $managers = array();
        foreach ($managersQuery->execute()->getItems() as $ar) {
        	$person = new CPerson(new CActiveRecord($ar));
        	$managers[$person->getId()] = $person->getName();
        }
        $dissers = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
        	$disser = new CPersonPaper($ar);
        	$dissers->add($disser->getId(), $disser);
        }
        $this->setData("isArchive", $isArchive);
        $this->setData("dissers", $dissers);
        $this->setData("managers", $managers);
        $this->setData("selectedPerson", $selectedPerson);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_aspirants_view/index.tpl");
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по ФИО
    	*/
    	$query = new CQuery();
    	$query->select("distinct(disser.kadri_id) as id, person.fio as name")
    	->from(TABLE_PERSON_DISSER." as disser")
    	->innerJoin(TABLE_PERSON." as person", "disser.kadri_id = person.id")
    	->condition("person.fio like '%".$term."%'")
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
    	 * Поиск по теме
    	 */
    	$query = new CQuery();
    	$query->select("distinct(disser.id) as id, disser.tema as tema")
    	->from(TABLE_PERSON_DISSER." as disser")
    	->condition("disser.tema like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"label" => $item["tema"],
    				"value" => $item["tema"],
    				"object_id" => $item["id"],
    				"type" => 2
    		);
    	}
    	/**
    	 * Поиск по комментарию
    	 */
    	$query = new CQuery();
    	$query->select("distinct(disser.id) as id, disser.comment as comment")
    	->from(TABLE_PERSON_DISSER." as disser")
    	->condition("disser.comment like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"label" => $item["comment"],
    				"value" => $item["comment"],
    				"object_id" => $item["id"],
    				"type" => 3
    		);
    	}
    	echo json_encode($res);
    }
}