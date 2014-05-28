<?php
class CNMSProtocolAgendaPointsController extends CBaseController{
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
        $this->setPageTitle("Управление пунктами повестки");

        parent::__construct();
    }
    public function actionAdd() {
        $object = new CNMSProtocolAgendaPoint();
        $object->protocol_id = CRequest::getInt("id");
        $object->section_id = $object->protocol->agenda->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=edit&id=".$object->protocol_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_protocols_nms/agendaPoint/add.tpl");
    }
    public function actionEdit() {
        $object = CProtocolManager::getNMSProtocolAgendaPoint(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=edit&id=".$object->protocol_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_protocols_nms/agendaPoint/edit.tpl");
    }
    public function actionDelete() {
        $object = CProtocolManager::getNMSProtocolAgendaPoint(CRequest::getInt("id"));
        $porotocol_id = $object->protocol_id;
        $object->remove();
        $this->redirect("index.php?action=edit&id=".$porotocol_id);
    }
    public function actionSave() {
        $object = new CNMSProtocolAgendaPoint();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("point.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("index.php?action=edit&id=".$object->protocol_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_protocols_nms/agendaPoint/edit.tpl");
    }
}