<?php
class CNMSProtocolsController extends CBaseController{
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
        $this->setPageTitle("Управление протоколами НМС");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet(false);
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_NMS_PROTOCOL." as t")
            ->order('STR_TO_DATE(date_text, "%d.%m.%Y") desc');
        if (CRequest::getString("order") == "date_text") {
        	$direction = "asc";
        	if (CRequest::getString("direction") == "desc") {
        		$direction = "desc";
        	}
        	$query->order('STR_TO_DATE(date_text, "%d.%m.%Y") '.$direction);
        }
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CNMSProtocol($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить протокол",
            "link" => "index.php?action=add",
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_protocols_nms/protocol/index.tpl");
    }
    public function actionAdd() {
        $object = new CNMSProtocol();
        $object->date_text = date("d.m.Y");
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
        $this->renderView("_protocols_nms/protocol/add.tpl");
    }
    public function actionEdit() {
        $object = CProtocolManager::getNMSProtocol(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "index.php?action=index",
                "icon" => "actions/edit-undo.png"
            ),
            array(
                "title" => "Добавить решение",
                "link" => "point.php?action=add&id=".$object->getId(),
                "icon" => "actions/list-add.png"
            )
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_protocols_nms/protocol/edit.tpl");
    }
    public function actionDelete() {
        $object = CProtocolManager::getNMSProtocol(CRequest::getInt("id"));
        $object->remove();
        $this->redirect("index.php?action=index");
    }
    public function actionSave() {
        $object = new CNMSProtocol();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("index.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("index.php?action=index");
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_protocols_nms/protocol/edit.tpl");
    }
    public function actionConvert() {
        foreach (CActiveRecordProvider::getAllFromTable(TABLE_NMS_PROTOCOL)->getItems() as $ar) {
            $protocol = new CNMSProtocol($ar);
            $protocol->date_text = date("d.m.Y", strtotime($protocol->date_text));
            $protocol->save();
            foreach ($protocol->agenda->getItems() as $point) {
                $person = CStaffManager::getPerson($point->kadri_id);
                if (!is_null($person)) {
                    $members = $point->members;
                    $members->add($person->getId(), $person);
                    $point->save();
                }
            }
        }
        $this->redirect("?action=index", "Конвертация завершена");
    }
}