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
        $this->setPageTitle("Управление приказами УГАТУ");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("order.*")
            ->from(TABLE_USATU_ORDERS)
            ->order("order.id desc");
        $set->setQuery($query);
        /**
         * Сортировка приказов в различных направлениях
         */
        $direction = "asc";
        if (CRequest::getString("direction") != "") {
            $direction = CRequest::getString("direction");
        }
        if (CRequest::getString("order") == "date") {
            $query->order("order.date ".$direction);
        } elseif (CRequest::getString("order") == "number") {
            $query->order("order.num ".$direction);
        } elseif (CRequest::getString("order") == "type") {
            $query->leftJoin(TABLE_USATU_ORDER_TYPES." as order_type", "order_type.id = order.orders_type");
            $query->order("order_type.name ".$direction);
        } elseif (CRequest::getString("order") == "title") {
            $query->order("order.title ".$direction);
        }
        /**
         * Фильтрация приказов
         */
        /**
         * Выборка приказов
         */
        $orders = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $order = new COrderUsatu($item);
            $orders->add($order->getId(), $order);
        }
        $this->setData("orders", $orders);
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_orders_usatu/index.tpl");
    }
}
