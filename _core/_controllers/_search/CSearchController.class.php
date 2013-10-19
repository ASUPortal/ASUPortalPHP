<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 19.10.13
 * Time: 18:05
 * To change this template use File | Settings | File Templates.
 */

class CSearchController extends CBaseController{
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
        $this->setPageTitle("Системный поиск");

        parent::__construct();
    }
    public function actionIndex() {
        $config = array();
        /**
         * Формируем конфиг выгрузки на основе списка выгружаемых полей
         */
        foreach (CCoreObjectsManager::getAllExportableModels()->getItems() as $model) {
            $fields = array();
            $modelName = $model->class_name;
            $modelObj = new $modelName();
            foreach ($modelObj->getDbTableFields()->getItems() as $name=>$field) {
                $fields[$name] = $field->isTextField();
            }
            foreach ($model->fields->getItems() as $field) {
                if ($field->isExportable()) {
                    if (CUtils::strRight($field->field_name, "_") !== "id") {
                        if ($fields[$field->field_name]) {
                            $config[$field->field_name] = '<field name="'.$field->field_name.'" type="text_general" indexed="true" stored="true" />';
                        } else {
                            $config[$field->field_name] = '<field name="'.$field->field_name.'" type="int" indexed="true" stored="true" />';
                        }
                    }
                }
            }
        }
        /**
         * Список задач модели
         * Класс модели
         */
        $config["_tasks_"] = '<field name="_tasks_" type="int" indexed="true" stored="true" multiValued="true" />';
        $config["_class_"] = '<field name="_class_" type="text_general" indexed="true" stored="true" />';

        $this->setData("config", $config);
        $this->renderView("_search/index.tpl");
    }
}