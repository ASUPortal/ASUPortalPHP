<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 18:59
 * To change this template use File | Settings | File Templates.
 */

class CCoreValidatorsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление валидаторами");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("*")
            ->from(TABLE_CORE_VALIDATORS);
        $items = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $v = new CCoreValidator($ar);
            $items->add($v->getId(), $v);
        }
        $this->setData("validators", $items);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_core/validator/index.tpl");
    }
    public function actionAdd() {
        $validator = new CCoreValidator();
        $this->setData("validator", $validator);
        $this->renderView("_core/validator/add.tpl");
    }
    public function actionEdit() {
        $validator = CCoreObjectsManager::getCoreValidator(CRequest::getInt("id"));
        $this->setData("validator", $validator);
        $this->renderView("_core/validator/edit.tpl");
    }
    public function actionSave() {
        $validator = new CCoreValidator();
        $validator->setAttributes(CRequest::getArray($validator::getClassName()));
        if ($validator->validate()) {
            $validator->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$validator->getId());
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        $this->setData("validator", $validator);
        $this->renderView("_core/validator/edit.tpl");
    }
    public function actionDelete() {
        $validator = CCoreObjectsManager::getCoreValidator(CRequest::getInt("id"));
        $validator->remove();
        $this->redirect("?action=index");
    }
    public function actionImport() {
        // валидаторы моделей
        $modelValidators = CUtils::getAllClassesWithInterface("IModelValidator");
        foreach ($modelValidators as $class=>$object) {
            $validator = CCoreObjectsManager::getCoreValidator($class);
            if (is_null($validator)) {
                $validator = new CCoreValidator();
                $validator->title = $class;
                $validator->class_name = $class;
                $validator->type_id = 2;
                $validator->save();
            }
        }
        // опциональные проверялки
        $optionalValidators = CUtils::getAllClassesWithInterface("IValidatorOptional");
        foreach ($optionalValidators as $class=>$object) {
            $validator = CCoreObjectsManager::getCoreValidator($class);
            if (is_null($validator)) {
                $validator = new CCoreValidator();
                $validator->title = $class;
                $validator->class_name = $class;
                $validator->type_id = 3;
                $validator->save();
            }
        }
        // валидаторы полей
        $fieldValidators = CUtils::getAllClassesWithInterface("IValidatorOptional");
        foreach ($fieldValidators as $class=>$object) {
            $validator = CCoreObjectsManager::getCoreValidator($class);
            if (is_null($validator)) {
                $validator = new CCoreValidator();
                $validator->title = $class;
                $validator->class_name = $class;
                $validator->type_id = 1;
                $validator->save();
            }
        }
        $this->redirect("?action=index");
    }
    public static function getTypesList() {
        return array(
            1 => "Валидатор поля",
            2 => "Валидатор модели",
            3 => "Валидатор опциональный"
        );
    }
}