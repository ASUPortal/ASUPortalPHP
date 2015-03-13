<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.03.15
 * Time: 21:49
 */

class CWorkPlanController extends CBaseController{
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
        $this->setPageTitle("Рабочие программы");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("wp.*")
            ->from(TABLE_WORK_PLANS." as wp");
        $paginated = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $plan = new CWorkPlan($ar);
            $paginated->add($plan->getId(), $plan);
        }
        $this->addActionsMenuItem(array(
            array(
                "title" => "Добавить",
                "link" => "?action=add",
                "icon" => "actions/list-add.png"
            ),
        ));
        $this->setData("plans", $paginated);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_workplan/workplan/index.tpl");
    }
    public function actionAdd() {
        $plan = new CWorkPlan();
        $plan->title = "Наименование не указано";
        $plan->save();
        $this->redirect("?action=edit&id=".$plan->getId());
    }
    public function actionEdit() {
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "?action=index",
                "icon" => "actions/edit-undo.png"
            ),
        ));
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $this->setData("plan", $plan);
        $this->renderView("_workplan/workplan/edit.tpl");
    }
}