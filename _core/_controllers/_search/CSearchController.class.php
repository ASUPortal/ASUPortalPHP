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
                        $config[$field->field_name] = '<field name="'.$field->field_name.'" type="text_general" indexed="true" stored="true" />';
                        /**
                        if ($fields[$field->field_name]) {
                            $config[$field->field_name] = '<field name="'.$field->field_name.'" type="text_general" indexed="true" stored="true" />';
                        } else {
                            $config[$field->field_name] = '<field name="'.$field->field_name.'" type="int" indexed="true" stored="true" />';
                        }
                         */
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
    public function actionSearch() {
        $userQuery = $_GET["query"];
        $result = array();
        /**
         * Выполняем поиск
         */
        $docs = CSolr::search($userQuery);
        /**
         * В зависимости от класса модели берем данные только
         * из полей, которые описаны в метаданных модели
         */
        foreach ($docs as $doc) {
            $class = $doc->_class_;
            /**
             * Получаем модель по наименованию
             */
            $model = CCoreObjectsManager::getCoreModel($class);
            if (!is_null($model)) {
                foreach ($model->fields->getItems() as $field) {
                    if (property_exists($doc, $field->field_name)) {
                        $fieldName = $field->field_name;
                        if (strpos($doc->$fieldName, $userQuery) !== false) {
                            $result[] = array(
                                "field" => $fieldName,
                                "value" => $doc->$fieldName,
                                "class" => $doc->_class_
                            );
                        }
                    }
                }
            }
        }
        echo json_encode($result);
    }
    public function actionGetExportableModels() {
        $result = array();
        foreach (CCoreObjectsManager::getAllExportableModels()->getItems() as $model) {
            $result[] = $model->getId();
        }
        echo json_encode($result);
    }
}