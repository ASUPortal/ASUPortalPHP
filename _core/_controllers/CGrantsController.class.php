<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 01.04.13
 * Time: 9:48
 * To change this template use File | Settings | File Templates.
 */

class CGrantsController extends CBaseController{
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
        $this->setPageTitle("Гранты и все такое");

        parent::__construct();
    }
    public function actionIndex() {

    }
    public function actionAdd() {

    }
    public function actionEdit() {

    }
    public function actionDelete() {

    }
    public function actionSearch() {

    }
}