<?php
class CCoreModelValidatorsController extends CBaseController{
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
        $this->setPageTitle("Управление валидаторами моделей");

        parent::__construct();
    }
    public function actionAdd() {
        $object = new CCoreModelValidator();
        $object->model_id = CRequest::getInt("id");
        $this->setData("object", $object);
        $this->renderView("_core/modelvalidator/add.tpl");
    }
    public function actionEdit() {
        $object = CCoreObjectsManager::getCoreModelValidator(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->renderView("_core/modelvalidator/edit.tpl");
    }
    public function actionDelete() {
        $object = CCoreObjectsManager::getCoreModelValidator(CRequest::getInt("id"));
        $model = $object->model_id;
        $object->remove();
        $this->redirect("models.php?action=edit&id=".$model);
    }
    public function actionSave() {
        $object = new CCoreModelValidator();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("modelvalidators.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("models.php?action=edit&id=".$object->model_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_core/modelvalidator/edit.tpl");
    }
}