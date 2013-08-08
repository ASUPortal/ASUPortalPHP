<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.08.13
 * Time: 12:26
 * To change this template use File | Settings | File Templates.
 */

class CGeneratorController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Генерация кода по шаблону");

        parent::__construct();
    }
    public function actionIndex() {
        $this->renderView("__generator/index.tpl");
    }
}