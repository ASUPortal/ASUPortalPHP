<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 18:46
 * To change this template use File | Settings | File Templates.
 */
class COrderUsatuController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление приказами УГАТУ и кафедры");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("usatu_order.*")
            ->from(TABLE_USATU_ORDERS." as usatu_order")
            ->order("usatu_order.id desc");
        $set->setQuery($query);
        /**
         * Сортировка приказов в различных направлениях
         */
        $direction = "asc";
        if (CRequest::getString("direction") != "") {
            $direction = CRequest::getString("direction");
        }
        if (CRequest::getString("order") == "date") {
            $query->order("usatu_order.date ".$direction);
        } elseif (CRequest::getString("order") == "number") {
            $query->order("usatu_order.num ".$direction);
        } elseif (CRequest::getString("order") == "type") {
            $query->leftJoin(TABLE_USATU_ORDER_TYPES." as order_type", "order_type.id = usatu_order.orders_type");
            $query->order("order_type.name ".$direction);
        } elseif (CRequest::getString("order") == "title") {
            $query->order("usatu_order.title ".$direction);
        }
        /**
         * Фильтрация приказов
         */
        $selectedOrder = null;
        if (!is_null(CRequest::getFilter("order"))) {
            $query->condition("usatu_order.id = ".CRequest::getFilter("order"));
            $selectedOrder = CStaffManager::getUsatuOrder(CRequest::getFilter("order"));
        }
        /**
         * Выборка приказов
         */
        $orders = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $order = new COrderUsatu($item);
            $orders->add($order->getId(), $order);
        }
        $this->setData("selectedOrder", $selectedOrder);
        $this->setData("orders", $orders);
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_orders_usatu/index.tpl");
    }
    public function actionEdit() {
        $order = CStaffManager::getUsatuOrder(CRequest::getInt("id"));
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("order", $order);
        $this->renderView("_orders_usatu/edit.tpl");
    }
    public function actionAdd() {
        $order = new COrderUsatu();
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("order", $order);
        $this->renderView("_orders_usatu/add.tpl");
    }
    public function actionSave() {
        $order = new COrderUsatu();
        $order->setAttributes(CRequest::getArray($order::getClassName()));
        if ($order->validate()) {
            $order->save();
            $this->redirect("?action=index");
            return true;
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("order", $order);
        $this->renderView("_orders_usatu/add.tpl");
    }
    public function actionDelete() {
        $order = CStaffManager::getUsatuOrder(CRequest::getInt("id"));
        $order->remove();
        $this->redirect("?action=index");
    }
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("term");
        /**
         * Полнотекстовый поиск по текстовым полям
         */
        $fields = array(
            "date",
            "num",
            "title",
            "text",
            "comment"
        );
        $query = new CQuery();
        $query->select("o.*")
            ->from(TABLE_USATU_ORDERS." as o")
            ->condition("MATCH (".implode($fields, ", ").") AGAINST ('".$term."')")
            ->limit(0, 5);
        $objects = new CArrayList();
        foreach ($query->execute()->getItems() as $ar) {
            $object = new COrderUsatu(new CActiveRecord($ar));
            $objects->add($object->getId(), $object);
        }
        foreach ($objects->getItems() as $object) {
            foreach ($fields as $field) {
                if (strpos($object->$field, $term) !== false) {
                    $labels = $object->attributeLabels();
                    if (array_key_exists($field, $labels)) {
                        $label = $labels[$field];
                    } else {
                        $label = $field;
                    }
                    $res[] = array(
                        "label" => $object->getName()." (".$label.": ".$object->$field.")",
                        "value" => $object->getName()." (".$label.": ".$object->$field.")",
                        "object_id" => $object->getId(),
                        "filter" => "order"
                    );
                }
            }
        }
        echo json_encode($res);
    }
}
