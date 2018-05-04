<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 15.06.13
 * Time: 21:20
 * To change this template use File | Settings | File Templates.
 */

class CRatesController extends CBaseController{
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

        $this->_useDojo = true;
        $this->_smartyEnabled = true;
        $this->setPageTitle("Справочник ставок");

        parent::__construct();
    }
    public function actionIndex() {
        $selectedYear = null;
        $set = new CRecordSet(false);
        $query = new CQuery();
        $query->select("r.*")
            ->from(TABLE_RATES." as r")
            ->order("r.id desc");
        
        // фильтр по году
        if (!is_null(CRequest::getFilter("year.id"))) {
            $query->condition("r.year_id =".CRequest::getFilter("year.id"));
            $selectedYear = CRequest::getFilter("year.id");
        }
        
        $isAll = false;
        if (CRequest::getInt("isAll") == "1") {
            $isAll = true;
        }
        if (!$isAll and CRequest::getFilter("year.id") == "") {
            $query->condition("r.year_id =".CUtils::getCurrentYear()->getId());
        }
        
        $set->setQuery($query);
        $rates = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $rate = new CRate($ar);
            $rates->add($rate->getId(), $rate);
        }
        $this->setData("rates", $rates);
        $this->setData("selectedYear", $selectedYear);
        $this->setData("isAll", $isAll);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_rates/rate/index.tpl");
    }
    public function actionAdd() {
        $rate = new CRate();
        $this->setData("rate", $rate);
        $this->renderView("_rates/rate/add.tpl");
    }
    public function actionEdit() {
        $rate = CRatesManager::getRate(CRequest::getInt("id"));
        $this->setData("rate", $rate);
        $this->renderView("_rates/rate/edit.tpl");
    }
    public function actionSave() {
        $rate = new CRate();
        $rate->setAttributes(CRequest::getArray($rate::getClassName()));
        if ($rate->validate()) {
            $rate->save();
            $this->redirect("?action=index");
            return true;
        }
        $this->setData("rate", $rate);
        $this->renderView("_rates/rate/edit.tpl");
    }
    public function actionDelete() {
        $rate = CRatesManager::getRate(CRequest::getInt("id"));
        $rate->remove();
        $this->redirect("?action=index");
    }
}