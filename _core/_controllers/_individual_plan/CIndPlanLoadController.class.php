<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 05.08.13
 * Time: 20:23
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanLoadController extends CBaseController{
    public function __construct() {
        if (!CSession::isAuth()) {
            //$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Учебная нагрузка");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);

        $query->select("p.*")
            ->from(TABLE_PERSON." as p")
            ->order("p.fio asc");

        $selectedYear = null;
        $years = CTaxonomyManager::getYearsList();
        if (!is_null(CRequest::getFilter("year"))) {
            $selectedYear = CRequest::getFilter("year");
        }

        $persons = new CArrayList();

        foreach ($set->getPaginated()->getItems() as $ar) {
            $person = new CPerson($ar);
            if (!is_null($selectedYear)) {
                $person->setIndPlanYearFilter(CTaxonomyManager::getYear($selectedYear));
            }
            $persons->add($person->getId(), $person);
        }

        $this->setData("years", $years);
        $this->setData("selectedYear", $selectedYear);
        $this->setData("paginator", $set->getPaginator());
        $this->setData("persons", $persons);
        $this->renderView("_individual_plan/load/index.tpl");
    }
    public function actionView() {
        $selectedYear = null;
        $years = array();
        if (!is_null(CRequest::getFilter("year"))) {
            $selectedYear = CRequest::getFilter("year");
        }

        $person = CStaffManager::getPerson(CRequest::getInt("id"));
        foreach ($person->getIndPlansByYears()->getItems() as $load) {
            if ($load->haveValues()) {
                $years[$load->year->getId()] = $load->year->getValue();
            }
        }

        if (!is_null($selectedYear)) {
            $person->setIndPlanYearFilter(CTaxonomyManager::getYear($selectedYear));
        }

        $this->setData("years", $years);
        $this->setData("selectedYear", $selectedYear);
        $this->setData("person", $person);
        $this->renderView("_individual_plan/load/view.tpl");
    }
}