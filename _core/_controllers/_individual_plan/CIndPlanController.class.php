<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 29.07.13
 * Time: 19:24
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanController extends CBaseController{
    public function __construct() {
        if (!CSession::isAuth()) {
            //$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Индивидуальный план");

        parent::__construct();
    }
    public function actionIndex() {
        $this->renderView("_individual_plan/index.tpl");
    }
}