<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 12.05.13
 * Time: 17:48
 * To change this template use File | Settings | File Templates.
 */

class CGrantMoneyController extends CBaseController{
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
        $this->setPageTitle("Финансирование");

        parent::__construct();
    }
    public function actionAdd() {
        $money = new CGrantMoney();
        $money->period_id = CRequest::getInt("period_id");
        $this->setData("money", $money);
        $this->setData("types", $this->getTypes());
        $this->renderView("_grants/money/add.tpl");
    }
    public function actionEdit() {
        $money = CGrantManager::getMoney(CRequest::getInt("id"));
        $this->setData("money", $money);
        $this->setData("types", $this->getTypes());
        $this->renderView("_grants/money/edit.tpl");
    }
    public function actionDelete() {
        $money = CGrantManager::getMoney(CRequest::getInt("id"));
        $grant_id = $money->period->grant_id;
        $money->remove();
        $this->redirect("admin.php?action=edit&id=".$grant_id);
    }
    public function actionSave() {
        $money = new CGrantMoney();
        $money->setAttributes(CRequest::getArray($money::getClassName()));
        if ($money->validate()) {
            $money->save();
            $this->redirect("admin.php?action=edit&id=".$money->period->grant_id);
            return true;
        }
        $this->setData("money", $money);
        $this->setData("types", $this->getTypes());
        $this->renderView("_grants/money/edit.tpl");
    }
    private function getTypes() {
        return array(
            1 => "Поступление",
            2 => "Расход"
        );
    }
}