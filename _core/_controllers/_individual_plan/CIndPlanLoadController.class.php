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
        $set = new CRecordSet(true);
        $query = new CQuery();
        $set->setQuery($query);

        $query->select("p.*")
            ->from(TABLE_PERSON." as p")
            ->order("p.fio asc");

        $persons = new CArrayList();

        foreach ($set->getPaginated()->getItems() as $ar) {
            $person = new CPerson($ar);
            $persons->add($person->getId(), $person);
        }

        $this->setData("paginator", $set->getPaginator());
        $this->setData("persons", $persons);
        $this->renderView("_individual_plan/load/index.tpl");
    }
    public function actionView() {
        $person = CStaffManager::getPerson(CRequest::getInt("id"));

        $this->setData("person", $person);
        $this->renderView("_individual_plan/load/view.tpl");
    }
    public function actionAdd() {
        $load = new CIndPlanPersonLoad();
        $load->person_id = CRequest::getInt("id");

        $this->setData("load", $load);
        $this->renderView("_individual_plan/load/add.tpl");
    }
    public function actionEdit() {
        $load = CIndPlanManager::getLoad(CRequest::getInt("id"));
        $this->setData("load", $load);
        $this->renderView("_individual_plan/load/add.tpl");
    }
    public function actionSave() {
        $load = new CIndPlanPersonLoad();
        $load->setAttributes(CRequest::getArray($load::getClassName()));
        if ($load->validate()) {
            $load->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$load->getId());
            } else {
                $this->redirect("?action=view&id=".$load->person_id);
            }
            return true;
        }
        $this->setData("load", $load);
        $this->renderView("_individual_plan/load/add.tpl");
    }
}