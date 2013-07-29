<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 19:25
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelFieldValidatorsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            //$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление валидаторами полей");

        parent::__construct();
    }
    public function actionAdd() {
        $validator = new CCoreModelFieldValidator();
        $validator->field_id = CRequest::getInt("id");
        $this->setData("validator", $validator);
        $this->renderView("_core/fieldvalidator/add.tpl");
    }
    public function actionEdit() {
        $validator = CCoreObjectsManager::getCoreModelFieldValidator(CRequest::getInt("id"));
        $this->setData("validator", $validator);
        $this->renderView("_core/fieldvalidator/edit.tpl");
    }
    public function actionSave() {
        $validator = new CCoreModelFieldValidator();
        $validator->setAttributes(CRequest::getArray($validator::getClassName()));
        if ($validator->validate()) {
            $validator->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$validator->getId());
            } else {
                $this->redirect("fields.php?action=edit&id=".$validator->field_id);
            }
            return true;
        }
        $this->setData("validator", $validator);
        $this->renderView("_core/fieldvalidator/edit.tpl");
    }
    public function actionDelete() {
        $validator = CCoreObjectsManager::getCoreModelFieldValidator(CRequest::getInt("id"));
        $field = $validator->field_id;
        $validator->remove();
        $this->redirect("fields.php?action=edit&id=".$field);
    }
}