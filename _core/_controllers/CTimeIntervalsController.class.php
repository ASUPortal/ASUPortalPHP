<?php

class CTimeIntervalsController extends CBaseController{
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
        $this->setPageTitle("Учебный год");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("year.*")
            ->from(TABLE_YEARS." as year")
            ->order("year.name desc");
        if (CRequest::getString("order") == "year.date_start") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("year.date_start ".$direction);
        }
        $years = new CArrayList();
        $set->setQuery($query);
        foreach ($set->getPaginated()->getItems() as $ar) {
            $year = new CTimeIntervals($ar);
            $years->add($year->getId(), $year);
        }
        $this->setData("paginator", $set->getPaginator());
        $this->setData("years", $years);
        $this->renderView("_time_intervals/index.tpl");
    }
    public function actionAdd() {
        $year = new CTimeIntervals();
        $this->setData("year", $year);
        $this->renderView("_time_intervals/add.tpl");
    }
    public function actionEdit() {
        $year = CTaxonomyManager::getTimeInterval(CRequest::getInt("id"));
        $this->setData("year", $year);
        $this->renderView("_time_intervals/edit.tpl");
    }
    public function actionDelete() {
        $year = CTaxonomyManager::getTimeInterval(CRequest::getInt("id"));
        $year->remove();
        $this->redirect("index.php?action=index");
    }
    public function actionSave() {
        $year = new CTimeIntervals();
        $year->setAttributes(CRequest::getArray($year::getClassName()));
        if ($year->validate()) {
            $year->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$year->getId());
            } else {
                $this->redirect("index.php?action=index");
            }
            return true;
        }
        $this->setData("year", $year);
        $this->renderView("_time_intervals/edit.tpl");
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по названию
    	 */
    	$query = new CQuery();
    	$query->select("distinct(year.id) as id, year.name as name")
    	->from(TABLE_YEARS." as year")
    	->condition("year.name like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "year.id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CTimeIntervals"
    		);
    	}
    	/**
    	 * Поиск по дате начала года
    	 */
    	$query = new CQuery();
    	$query->select("distinct(year.id) as id, year.date_start as name")
    	->from(TABLE_YEARS." as year")
    	->condition("year.date_start like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "year.id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CTimeIntervals"
    		);
    	}
    	/**
    	 * Поиск по дате окончания года
    	 */
    	$query = new CQuery();
    	$query->select("distinct(year.id) as id, year.date_end as name")
    	->from(TABLE_YEARS." as year")
    	->condition("year.date_end like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "year.id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CTimeIntervals"
    		);
    	}
    	/**
    	 * Поиск по комментарию
    	 */
    	$query = new CQuery();
    	$query->select("distinct(year.id) as id, year.comment as name")
    	->from(TABLE_YEARS." as year")
    	->condition("year.comment like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "year.id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CTimeIntervals"
    		);
    	}
    	echo json_encode($res);
    }
}