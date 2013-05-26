<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 29.04.13
 * Time: 21:20
 * To change this template use File | Settings | File Templates.
 */

class CGrantEventsController extends CBaseController{
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
        $this->setPageTitle("Мероприятия");

        parent::__construct();
    }
    public function actionAdd() {
        $event = new CGrantEvent();
        $event->grant_id = CRequest::getInt("grant_id");
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("event", $event);
        $this->renderView("_grants/event/add.tpl");
    }
    public function actionEdit() {
        $event = CGrantManager::getEvent(CRequest::getInt("id"));
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("event", $event);
        $this->renderView("_grants/event/edit.tpl");
    }
    public function actionSave() {
        $event = new CGrantEvent();
        $event->setAttributes(CRequest::getArray($event::getClassName()));
        if ($event->validate()) {
            $event->save();
            $this->redirect("admin.php?action=edit&id=".$event->grant_id);
            return true;
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("event", $event);
        $this->renderView("_grants/event/edit.tpl");
    }
    public function actionDelete() {
        $event = CGrantManager::getEvent(CRequest::getInt("id"));
        $id = $event->grant_id;
        $event->remove();
        $this->redirect("admin.php?action=edit&id=".$id);
    }
}