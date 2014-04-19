<?php
class CDashboardReportsController extends CBaseController{
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
        $this->setPageTitle("Управление отчетами");

        parent::__construct();
    }
    public function actionAdd() {
        $object = new CDashboardReport();
        $object->settings_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=index",
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_dashboard/report/add.tpl");
    }
    public function actionEdit() {
        $object = CDashboardManager::getDashboardReport(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=index",
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_dashboard/report/edit.tpl");
    }
    public function actionDelete() {
        $object = CDashboardManager::getDashboardReport(CRequest::getInt("id"));
        $object->remove();
        $this->redirect("index.php?action=index");
    }
    public function actionSave() {
        $object = new CDashboardReport();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("reports.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("index.php?action=index");
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_dashboard/report/edit.tpl");
    }
}