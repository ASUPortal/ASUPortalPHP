<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 18.05.13
 * Time: 21:17
 * To change this template use File | Settings | File Templates.
 */

class CSABOrdersController extends CBaseController{
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
        $this->setPageTitle("Приказы сотрудников по ГЭК");

        parent::__construct();
    }
    public function actionAdd() {
        $order = new CSABPersonOrder();
        $order->person_id = CRequest::getInt("id");
        $this->setData("order", $order);
        $this->renderView("_staff/order_sab/add.tpl");
    }
    public function actionEdit() {
        $order = CSABManager::getSABPersonOrder(CRequest::getInt("id"));
        $this->setData("order", $order);
        $this->renderView("_staff/order_sab/edit.tpl");
    }
    public function actionSave() {
        $order = new CSABPersonOrder();
        $order->setAttributes(CRequest::getArray($order::getClassName()));
        if ($order->validate()) {
            $order->save();
            $this->redirect("index.php?action=edit&id=".$order->person_id);
            return true;
        }
        $this->setData("order", $order);
        $this->renderView("_staff/order_sab/edit.tpl");
    }
    public function actionDelete() {
        $order = CSABManager::getSABPersonOrder(CRequest::getInt("id"));
        $person = $order->person_id;
        $order->remove();
        $this->redirect("index.php?action=edit&id=".$person);
    }
}