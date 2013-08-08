<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.08.13
 * Time: 11:35
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanLoadConclusionController extends CBaseController{
    public function __construct() {
        if (!CSession::isAuth()) {
            //$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Заключения заведующего кафедрой");

        parent::__construct();
    }
    public function actionAdd() {
        $c = new CIndPlanPersonLoadConclusion();
        $c->id_kadri = CRequest::getInt("id");
        $this->setData("conclusion", $c);
        $this->renderView("_individual_plan/conclusion/add.tpl");
    }
    public function actionEdit() {
        $c = CIndPlanManager::getConclusion(CRequest::getInt("id"));
        $this->setData("conclusion", $c);
        $this->renderView("_individual_plan/conclusion/edit.tpl");
    }
    public function actionSave() {
        $c = new CIndPlanPersonLoadConclusion();
        $c->setAttributes(CRequest::getArray($c::getClassName()));
        if ($c->validate()) {
            $c->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$c->getId());
            } else {
                $this->redirect("../load.php?action=view&id=".$c->id_kadri);
            }
            return true;
        }
        $this->setData("conclusion", $c);
        $this->renderView("_individual_plan/conclusion/edit.tpl");
    }
    public function actionDelete() {
        $c = CIndPlanManager::getConclusion(CRequest::getInt("id"));
        $person_id = $c->id_kadri;
        $c->remove();
        $this->redirect("../load.php?action=view&id=".$person_id);
    }
}