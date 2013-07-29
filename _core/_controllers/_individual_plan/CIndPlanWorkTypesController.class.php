<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 29.07.13
 * Time: 19:53
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanWorkTypesController extends CBaseController{
    public function __construct() {
        if (!CSession::isAuth()) {
            //$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Справочник видов работ");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        /**
         * Исходный запрос
         */
        $query->select("w.*")
            ->from(TABLE_IND_PLAN_WORKTYPES." as w")
            ->order("w.name asc");
        /**
         * Фильтр по категории
         */
        $selectedCategory = null;
        if (!is_null(CRequest::getFilter("category"))) {
            $selectedCategory = CRequest::getFilter("category");
            $query->condition("w.id_razdel = ".$selectedCategory);
        }
        $works = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $work = new CIndPlanWorktype($ar);
            $works->add($work->getId(), $work);
        }

        $this->setData("selectedCategory", $selectedCategory);
        $this->setData("works", $works);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_individual_plan/worktypes/index.tpl");
    }
    public function actionAdd() {
        $work = new CIndPlanWorktype();
        $this->setData("work", $work);
        $this->renderView("_individual_plan/worktypes/add.tpl");
    }
    public function actionEdit() {
        $work = CIndPlanManager::getWorktype(CRequest::getInt("id"));
        $this->setData("work", $work);
        $this->renderView("_individual_plan/worktypes/edit.tpl");
    }
    public function actionSave() {
        $work = new CIndPlanWorktype();
        $work->setAttributes(CRequest::getArray($work::getClassName()));
        if ($work->validate()) {
            $work->save();
            if ($this->continueEdit()) {
                $this->redirect("worktypes.php?action=edit&id=".$work->getId());
            } else {
                $this->redirect("worktypes.php?action=index");
            }
            return true;
        }
        $this->setData("work", $work);
        $this->renderView("_individual_plan/worktypes/edit.tpl");
    }
    public function actionDelete() {
        $work = CIndPlanManager::getWorktype(CRequest::getInt("id"));
        $work->remove();
        $this->redirect("worktypes.php?action=index");
    }
}