<?php

class CHoursRateController extends CBaseController{
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
        $this->setPageTitle("Справочник ставок");

        parent::__construct();
    }
    public function actionIndex() {
        $selectedYear = null;
        $set = new CRecordSet(false);
        $query = new CQuery();
        $query->select("rate.*")
            ->from(TABLE_HOURS_RATE." as rate")
            ->leftJoin(TABLE_POSTS." as post", "rate.dolgnost_id = post.id")
            ->order("post.name asc");
        
        // фильтр по году
        if (!is_null(CRequest::getFilter("year.id"))) {
        	$query->condition("rate.year_id =".CRequest::getFilter("year.id"));
        	$selectedYear = CRequest::getFilter("year.id");
        }
        
        $isAll = false;
        if (CRequest::getInt("isAll") == "1") {
        	$isAll = true;
        }
        if (!$isAll and CRequest::getFilter("year.id") == "") {
        	$query->condition("rate.year_id =".CUtils::getCurrentYear()->getId());
        }
        
        $set->setQuery($query);
        $rates = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
        	$rate = new CHoursRate($ar);
        	$rates->add($rate->getId(), $rate);
        }

        $this->setData("paginator", $set->getPaginator());
        $this->setData("rates", $rates);
        $this->setData("selectedYear", $selectedYear);
        $this->setData("isAll", $isAll);
        $this->renderView("_hrs_rate/index.tpl");
    }
    public function actionAdd() {
        $rate = new CHoursRate();
        $this->setData("rate", $rate);
        $this->renderView("_hrs_rate/add.tpl");
    }
    public function actionEdit() {
        $rate = CTaxonomyManager::getHoursRate(CRequest::getInt("id"));
        $this->setData("rate", $rate);
        $this->renderView("_hrs_rate/edit.tpl");
    }
    public function actionDelete() {
        $rate = CTaxonomyManager::getHoursRate(CRequest::getInt("id"));
        $rate->remove();
        $this->redirect("index.php?action=index");
    }
    public function actionSave() {
        $rate = new CHoursRate();
        $rate->setAttributes(CRequest::getArray($rate::getClassName()));
        if ($rate->validate()) {
            $rate->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$rate->getId());
            } else {
                $this->redirect("index.php?action=index");
            }
            return true;
        }
        $this->setData("rate", $rate);
        $this->renderView("_hrs_rate/edit.tpl");
    }
}