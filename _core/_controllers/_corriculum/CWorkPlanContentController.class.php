<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 07.09.15
 * Time: 22:15
 */

class CWorkPlanContentController extends CBaseController{
    protected $_isComponent = true;

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
        $this->setPageTitle("Управление модулями");

        parent::__construct();
    }
    public function actionPractices() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontent.php?action=practices&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
        ));
        $this->setData("objects", $plan->getPractices());
        $this->renderView("_corriculum/_workplan/content/practices.tpl");
    }
    public function actionLabWorks() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontent.php?action=labworks&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
        ));
        $this->setData("objects", $plan->getLabWorks());
        $this->renderView("_corriculum/_workplan/content/labworks.tpl");
    }
    public function actionTechnologies() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontent.php?action=technologies&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
        ));
        $this->setData("objects", $plan->getTechnologies());
        $this->renderView("_corriculum/_workplan/content/technologies.tpl");
    }
}