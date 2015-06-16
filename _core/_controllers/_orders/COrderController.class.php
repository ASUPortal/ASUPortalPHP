<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 18.11.12
 * Time: 18:36
 * To change this template use File | Settings | File Templates.
 */
class COrderController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление приказами");

        parent::__construct();
    }
    public function actionIndex() {
        $persons = new CRecordSet();
        if (array_key_exists("rated", $_GET)) {
            $rated = $_GET["rated"];
        } else {
            $rated = 1;
        }
        /*
        $roles = new CArrayList();
        if (array_key_exists("types", $_GET)) {
            if (is_array($_GET["types"])) {
                foreach ($_GET["types"] as $key=>$value) {
                    $types[$value] = $value;
                    $roles->add(CTaxonomyManager::getTypeById($value)->getId(), CTaxonomyManager::getTypeById($value));
                }
            } else {
                foreach (explode(";", $_GET["types"]) as $value) {
                    $types[$value] = $value;
                    $roles->add(CTaxonomyManager::getTypeById($value)->getId(), CTaxonomyManager::getTypeById($value));
                }
            }
        } else {
            foreach (CTaxonomyManager::getCacheTypes()->getItems() as $type) {
                $types[$type->getId()] = $type->getId();
                $roles->add($type->getId(), $type);
            }
        }
        foreach (CStaffManager::getPersonsWithTypes($roles)->getItems() as $person) {
            if ($rated == 1) {
                if ($person->getActiveOrders()->getCount() > 0) {
                    $persons->add($person->getId(), $person);
                }
            } else {
                $persons->add($person->getId(), $person);
            }
        }
        $this->setData("types", $types);
        $this->setData("types_url", implode(";", $types));
        $this->addJSInclude("_modules/_orders/filter.js");
        */
        foreach (CStaffManager::getAllPersons()->getItems() as $person) {
            if ($rated == 1) {
                if ($person->getActiveOrders()->getCount() > 0) {
                    $persons->add($person->getId(), $person);
                }
            } else {
                $persons->add($person->getId(), $person);
            }
        }
        $this->setData("rated", $rated);
        $this->setData("persons", $persons);
        $this->renderView("_orders/index.tpl");
    }
    public function actionView() {
        $person = CStaffManager::getPerson(CRequest::getInt("id"));
        $this->setData("person", $person);
        $this->renderView("_orders/view.tpl");
    }
    public function actionAdd() {
        $order = new COrder();
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $order->kadri_id = CRequest::getInt("id");
        $this->setData("order", $order);
        $this->setData("type_money", array(
            2 => "Бюджет",
            3 => "Внебюджет"
        ));
        $this->setData("type_order", array(
            2 => "Основной",
            3 => "Совместительство",
            4 => "Дополнительно"
        ));
        $this->renderView("_orders/edit.tpl");
    }
    public function actionViewOrder() {
        $order = CStaffManager::getOrder(CRequest::getInt("id"));
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->setData("order", $order);
        $this->setData("type_money", array(
            2 => "Бюджет",
            3 => "Внебюджет"
        ));
        $this->setData("type_order", array(
            2 => "Основной",
            3 => "Совместительство",
            4 => "Дополнительно"
        ));
        $this->renderView("_orders/edit.tpl");
    }
    public function actionSave() {
        $order = new COrder();
        $order->setAttributes(CRequest::getArray($order::getClassName()));
        if ($order->validate()) {
            $order->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=viewOrder&id=".$order->getId());
            } else {
                $this->redirect("?action=view&id=".$order->person->getId());
            }
            return true;
        }
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->setData("order", $order);
        $this->renderView("_orders/edit.tpl");
    }
}
