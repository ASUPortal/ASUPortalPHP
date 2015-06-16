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
        $set = new CRecordSet(false);
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
        $selectedType = null;
        if (!is_null(CRequest::getFilter("type"))) {
            $query->condition("orders_type = ".CRequest::getFilter("type"));
            $selectedType = CTaxonomyManager::getUsatuOrderType(CRequest::getFilter("type"))->getId();
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
        $this->setData("selectedType", $selectedType);
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
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "index.php?action=index",
                "icon" => "actions/edit-undo.png"
            ),
            array(
                "title" => "Добавить новость",
                "link" => "index.php?action=addNewsItem&id=".$order->getId(),
                "icon" => "actions/list-add.png"
            )
        ));
        $this->renderView("_orders_usatu/edit.tpl");
    }
    public function actionAddNewsItem() {
        $order = CStaffManager::getUsatuOrder(CRequest::getInt("id"));

        $newsItem = new CNewsItem();
        $newsItem->user_id_insert = CSession::getCurrentUser()->getId();
        $newsItem->date_time = date("Y-m-d H:i:s");
        $newsItem->news_type = "notice";
        $newsItem->related_id = $order->getId();
        $newsItem->related_type_name = get_class($order);
        $newsItem->title = "Добавлен приказ №".$order->num." от ".$order->date.": ".$order->title;
        $newsItem->file = $order->text;
        // скопируем файл, если он есть
        if ($order->attachment != "") {
            $propOrder = $order->fieldsProperty();
            $propNews = $newsItem->fieldsProperty();
            copy($propOrder["attachment"]["upload_dir"].$order->attachment, $propNews["file_attach"]["upload_dir"].$order->attachment);
            $newsItem->file_attach = $order->attachment;
        }
        $newsItem->save();

        $this->redirect("?action=edit&id=".$order->getId());
    }
    public function actionDeleteNewsItem() {
        $item = CNewsManager::getNewsItem(CRequest::getInt("id"));
        $order = $item->related_id;
        $item->remove();
        $this->redirect("?action=edit&id=".$order);
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
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$order->getId());
            } else {
                $this->redirect("?action=index");
            }
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
