<?php
class CCoreModelTasksController extends CBaseController{
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
        $this->setPageTitle("Управление задачами модели");

        parent::__construct();
    }
    public function actionAdd() {
        $object = new CCoreModelTask();
        $object->model_id = CRequest::getInt("id");
        $this->setData("object", $object);
        $this->renderView("_core/task/add.tpl");
    }
    public function actionEdit() {
        $object = CCoreObjectsManager::getCoreModelTask(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->renderView("_core/task/edit.tpl");
    }
    public function actionDelete() {
        $object = CCoreObjectsManager::getCoreModelTask(CRequest::getInt("id"));
        $model = $object->model_id;
        $object->remove();
        $this->redirect("models.php?action=edit&id=".$model);
    }
    public function actionSave() {
        $object = new CCoreModelTask();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("tasks.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("models.php?action=edit&id=".$object->model_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_core/task/edit.tpl");
    }
}