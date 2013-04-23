<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 23.04.13
 * Time: 19:45
 * To change this template use File | Settings | File Templates.
 */

class CStaffController extends CBaseController{
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
        $this->setPageTitle("Управление сотрудниками кафедры");

        parent::__construct();
    }
    public function actionIndex() {

    }
    public function actionAdd() {

    }
    public function actionEdit() {

    }
    public function actionSave() {

    }
    public function actionDelete() {

    }
    public function actionSearch() {

    }
}