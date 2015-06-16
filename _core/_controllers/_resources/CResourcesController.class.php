<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 27.05.12
 * Time: 19:49
 * To change this template use File | Settings | File Templates.
 */
class CResourcesController extends CBaseController {

    public function __construct() {
        $this->_smartyEnabled = true;

        parent::__construct();
    }

    /**
     * Главная страница контроллера ресурсов
     */
    public function actionIndex() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->setPageTitle("Ресурсы");

        $resources = CResourcesManager::getAllResources()->getItems();
        $this->setData("resources", $resources);

        $this->renderView("_resources/index.tpl");
    }

    /**
     * Добавление ресурса
     */
    public function actionAdd() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->renderView("_resources/add.tpl");
    }

    /**
     * Сохранение ресурса
     */
    public function actionSave() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }
        if (CRequest::getInt("id") == 0) {
            // создаем новый ресурс
            $res = CFactory::createResource();
        } else {
            $res = CResourcesmanager::getResourceById(CRequest::getInt("id"));
        }
        $res->setName(CRequest::getString("name"));
        $res->setType(CRequest::getString("type"));
        $res->setResourceId(CRequest::getInt("resource_id"));
        $res->save();

        if (CRequest::getInt("id") == 0) {
            // создаем календарь по умолчанию к нему
            $calendar = CFactory::createCalendar();
            $calendar->setResourceId($res->getId());
            $calendar->setDefault(true);
            $calendar->setPublic(true);
            $calendar->setShowNoDetails(true);
            $calendar->setName("Календарь по умолчанию");
            $calendar->save();
        }

        $this->redirect("?action=index");
    }
    /**
     * Просмотр ресурса
     */
    public function actionView() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $res = CResourcesManager::getResourceById(CRequest::getInt("id"));
        $this->setData("resource", $res);

        $this->renderView("_resources/view.tpl");
    }
    public function actionEdit() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $res = CResourcesManager::getResourceById(CRequest::getInt("id"));
        $this->setData("resource", $res);

        $this->renderView("_resources/edit.tpl");
    }
    public function actionAddCalendar() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $res = CSession::getCurrentPerson()->getResource();

        $this->setData("resource", $res);
        $this->renderView("_resources/addCalendar.tpl");
    }
    public function actionSaveCalendar() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $cal = CFactory::createCalendar();
        $cal->setName(CRequest::getString("name"));
        $cal->setDescription(CRequest::getString("description"));
        $cal->setDefault(false);
        $cal->setResource(CSession::getCurrentPerson()->getResource());
        $cal->save();

        $this->redirect(WEB_ROOT."_modules/_calendar/");
    }
    /**
     * Список доступных ресурсов в виде JSON-ответа
     */
    public function actionGetResourcesJSON() {
        $q = new CQuery();
        $r = $q->select("*")
        ->from(TABLE_RESOURCES)
        ->condition("name LIKE '%".CRequest::getString("term")."%'  ")
        ->execute();

        $records = array();
        foreach ($r->getItems() as $ar) {
            $records[] = new CResource(new CActiveRecord($ar));
        }
        $jRes = array();
        foreach ($records as $r) {
            $jRes[] = array(
                "id" => $r->getId(),
                "label" => $r->getName(),
                "value" => $r->getId()
            );
        }
        echo json_encode($jRes);
    }
}
