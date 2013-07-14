<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 14:48
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelFieldsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            //$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление полями моделей данных");

        parent::__construct();
    }
    public function actionAdd() {
        $field = new CCoreModelField();
        $field->model_id = CRequest::getInt("id");
        $this->setData("field", $field);
        $this->renderView("_core/field/add.tpl");
    }
    public function actionEdit() {
        $field = CCoreObjectsManager::getCoreModelField(CRequest::getInt("id"));
        $this->setData("field", $field);
        $this->renderView("_core/field/edit.tpl");
    }
    public function actionSave() {
        $field = new CCoreModelField();
        $field->setAttributes(CRequest::getArray($field::getClassName()));
        if ($field->validate()) {
            $field->save();
            if ($this->continueEdit()) {
                $this->redirect("fields.php?action=edit&id=".$field->getId());
            } else {
                $this->redirect("models.php?action=edit&id=".$field->model_id);
            }
            return true;
        }
        $this->setData("field", $field);
        $this->renderView("_core/field/edit.tpl");
    }
    public function actionDelete() {
        $field = CCoreObjectsManager::getCoreModelField(CRequest::getInt("id"));
        $model = $field->model_id;
        $field->remove();
        $this->redirect("models.php?action=edit&id=".$model);
    }
}