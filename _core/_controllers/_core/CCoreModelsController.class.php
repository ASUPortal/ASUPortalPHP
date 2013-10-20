<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 13:59
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление моделями данных");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("m.*")
            ->from(TABLE_CORE_MODELS." as m");
        $models = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $model = new CCoreModel($ar);
            $models->add($model->getId(), $model);
        }
        $this->setData("models", $models);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_core/model/index.tpl");
    }
    public function actionAdd() {
        $model = new CCoreModel();
        $this->setData("model", $model);
        $this->renderView("_core/model/add.tpl");
    }
    public function actionEdit() {
        $model = CCoreObjectsManager::getCoreModel(CRequest::getInt("id"));
        $this->setData("model", $model);
        $this->renderView("_core/model/edit.tpl");
    }
    public function actionSave() {
        $model = new CCoreModel();
        $model->setAttributes(CRequest::getArray($model::getClassName()));
        if ($model->validate()) {
            $model->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$model->getId());
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        $this->setData("model", $model);
        $this->renderView("_core/model/add.tpl");
    }
    public function actionDelete() {
        $model = CCoreObjectsManager::getCoreModel(CRequest::getInt("id"));
        $model->remove();
        $this->redirect("?action=index");
    }
    public function actionImport() {
        /**
         * Берем папку моделей и ищем все подпапки
         */
        $models = array();
        $modelsDir = opendir(CORE_CWD.CORE_DS."_core".CORE_DS."_models");
        while (false !== ($dir = readdir($modelsDir))) {
            if ($dir != "." && $dir != "..") {
                if (is_dir(CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS.$dir)) {
                    /**
                     * Ищем файлы
                     */
                    $modelDir = opendir(CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS.$dir);
                    while (($file = readdir($modelDir)) !== false) {
                        if ($file != "." && $file != "..") {
                            $models[] = $file;
                        }
                    }
                }
            }
        }
        /**
         * Последовательно создаем классы и смотрим, являются ли они наследниками
         * CModel или CActiveModel
         */
        foreach ($models as $model) {
            $model = substr($model, 0, strpos($model, "."));
            $obj = new $model();
            if (is_a($obj, "CModel")) {
                /**
                 * Это модельный класс, отсюда берем названия полей
                 */
                $persistedObj = CCoreObjectsManager::getCoreModel(get_class($obj));
                if (is_null($persistedObj)) {
                    /**
                     * Создаем сам класс
                     */
                    $persistedObj = new CCoreModel();
                    $persistedObj->title = get_class($obj);
                    $persistedObj->class_name = get_class($obj);
                    $persistedObj->save();
                    $fields = $obj->attributeLabels();
                    foreach ($fields as $key=>$value) {
                        $field = new CCoreModelField();
                        $field->model_id = $persistedObj->getId();
                        $field->field_name = $key;
                        $field->save();
                        $t = new CCoreModelFieldTranslation();
                        $t->field_id = $field->getId();
                        $t->language_id = CSettingsManager::getSettingValue("system_language_default");
                        $t->value = $value;
                        $t->save();
                    }
                }
            }
        }
    }
    public function actionImportFields() {
        $model = CCoreObjectsManager::getCoreModel(CRequest::getInt("id"));
        $className = $model->class_name;
        $obj = new $className();
        $fields = array();
        foreach ($obj->getDbTableFields()->getItems() as $field) {
            if (mb_strtolower($field->name) !== "id") {
                $fields[] = $field->name;
            }
        }
        $form = new CCoreModelForm();
        $form->id = $model->getId();
        foreach ($fields as $field) {
            $form->fields[] = array(
                "name" => $field,
                "translation" => $field,
                "validator" => 0,
            );
        }
        $this->setData("form", $form);
        $this->renderView("_core/model/import.tpl");
    }
    public function actionSaveImported() {
        $form = new CCoreModelForm();
        $form->setAttributes(CRequest::getArray($form::getClassName()));
        $form->save();

        $this->redirect("?action=edit&id=".$form->id);
    }
    public function actionExport() {
        $metaModel = CCoreObjectsManager::getCoreModel(CRequest::getInt("id"));
        if (!$metaModel->isExportable()) {
            $this->redirect("models.php?action=edit&id=".$metaModel->getId());
            echo 1;
            return false;
        }
        $modelClass = $metaModel->class_name;
        $model = new $modelClass();
        $records = CActiveRecordProvider::getAllFromTable($model->getRecord()->getTable());
        foreach ($records->getItems() as $record) {
            $model = new $modelClass($record);
            CSolr::addObject($model);
        }
        CSolr::commit();
        echo 1;
    }
}