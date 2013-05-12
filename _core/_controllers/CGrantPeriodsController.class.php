<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 12.05.13
 * Time: 15:09
 * To change this template use File | Settings | File Templates.
 */

class CGrantPeriodsController extends CBaseController{
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
        $this->setPageTitle("Мероприятия");

        parent::__construct();
    }
    public function actionAdd() {
        $period = new CGrantPeriod();
        $period->grant_id = CRequest::getInt("grant_id");
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("period", $period);
        $this->renderView("_grants/period/add.tpl");
    }
    public function actionDelete() {
        $perid = CGrantManager::getPeriod(CRequest::getInt("id"));
        $grant_id = $perid->grant_id;
        $perid->remove();
        $this->redirect("index.php?action=edit&id=".$grant_id);
    }
    public function actionEdit() {
        $period = CGrantManager::getPeriod(CRequest::getInt("id"));
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("period", $period);
        $this->renderView("_grants/period/edit.tpl");
    }
    public function actionSave() {
        $period = new CGrantPeriod();
        $period->setAttributes(CRequest::getArray($period::getClassName()));
        if ($period->validate()) {
            $period->save();
            $this->redirect("index.php?action=edit&id=".$period->grant_id);
            return true;
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("period", $period);
        $this->renderView("_grants/period/edit.tpl");
    }
}