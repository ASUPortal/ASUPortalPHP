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
         * Основная, связанная сущность
         */
        $config["_tasks_"] = '<field name="_tasks_" type="int" indexed="true" stored="false" multiValued="true" />';
        $config["_class_"] = '<field name="_class_" type="text_general" indexed="true" stored="true" />';
        $config["_is_main_"] = '<field name="_is_main_" type="text_general" indexed="true" stored="true" />';
        $config["_parent_class_"] = '<field name="_parent_class_" type="text_general" indexed="true" stored="true" />';
        $config["_parent_field_"] = '<field name="_parent_field_" type="text_general" indexed="true" stored="true" />';
        $config["_doc_id_"] = '<field name="_doc_id_" type="int" indexed="true" stored="true" />';

        $this->setData("config", $config);
        $this->renderView("_search/index.tpl");
    }
    public function actionSearch() {
        $userQuery = mb_strtolower($_GET["query"]);
        $params = array(
            "_is_main_" => 1
        );
        /**
         * Получаем доп. параметры
         */
        if (array_key_exists("params", $_GET)) {
            foreach ($_GET["params"] as $key=>$value) {
                if ($key == "__task") {
                    $key = "_tasks_";
                }
                $params[$key] = $value;
            }
        }
        $result = array();
        /**
         * Выполняем поиск
         */
        $docs = CSolr::search($userQuery, $params);
        /**
         * В зависимости от класса модели берем данные только
         * из полей, которые описаны в метаданных модели
         */
        if (is_array($docs)) {
            foreach ($docs as $doc) {
                if (property_exists($doc, "_class_")) {
                    $class = $doc->_class_;
                    /**
                     * Получаем модель по наименованию
                     */
                    $model = CCoreObjectsManager::getCoreModel($class);
                    if (!is_null($model)) {
                        foreach ($model->fields->getItems() as $field) {
                            if (property_exists($doc, $field->field_name)) {
                                $fieldName = $field->field_name;
                                $fieldValue = mb_strtolower($doc->$fieldName);
                                if (mb_strpos($fieldValue, $userQuery) !== false) {
                                    $result[] = array(
                                        "field" => $fieldName,
                                        "value" => $doc->$fieldName,
                                        "class" => $doc->_class_,
                                        "label" => $doc->$fieldName
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
        /**
         * Если определена задача, то попробуем поискать
         * и в связанных с ее моделями
         */
        if (array_key_exists("_tasks_", $params)) {
            foreach (CCoreObjectsManager::getModelsByTask($params["_tasks_"])->getItems() as $coreModel) {
                $newParams = array(
                    "_is_main_" => "0",
                    "_parent_class_" => $coreModel->class_name
                );
                $docs = CSolr::search($userQuery, $newParams);
                if (is_array($docs)) {
                    foreach ($docs as $doc) {
                        if (property_exists($doc, "_class_")) {
                            $class = $doc->_class_;
                            /**
                             * Получаем модель по наименованию
                             */
                            $model = CCoreObjectsManager::getCoreModel($class);
                            if (!is_null($model)) {
                                foreach ($model->fields->getItems() as $field) {
                                    if (property_exists($doc, $field->field_name)) {
                                        $fieldName = $field->field_name;
                                        $fieldValue = strtolower($doc->$fieldName);
                                        if (mb_strpos($fieldValue, $userQuery) !== false) {
                                            $result[] = array(
                                                "field" => $doc->_parent_field_,
                                                "value" => $doc->_doc_id_,
                                                "class" => $doc->_parent_class_,
                                                "label" => $doc->$fieldName
                                            );
                                        }
                                    }
                                }
                            }
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
    public function actionLookupTypeAhead() {
        $catalog = CRequest::getString("catalog");
        $lookup = CRequest::getString("query");

        $result = array();
        if ($catalog == "staff") {
            // выбор сотрудников
            $query = new CQuery();
            $query->select("person.id as id, person.fio as name")
                ->from(TABLE_PERSON." as person")
                ->condition("person.fio like '%".$lookup."%'")
                ->limit(0, 10);
            foreach ($query->execute()->getItems() as $item) {
                $result[$item["id"]] = $item["name"];
            }
        } elseif ($catalog == "student") {
            // выбор студентов
            $query = new CQuery();
            $query->select("distinct(student.id) as id, student.fio as name")
                ->from(TABLE_STUDENTS." as student")
                ->condition("student.fio like '%".$lookup."%'")
                ->limit(0, 10);
            foreach ($query->execute()->getItems() as $item) {
                $result[$item["id"]] = $item["name"];
            }
        } elseif (!is_null(CTaxonomyManager::getLegacyTaxonomy($catalog))) {
            // унаследованная таксономия
            $taxonomy = CTaxonomyManager::getLegacyTaxonomy($catalog);
            $query = new CQuery();
            $query->select("distinct(taxonomy.id) as id, taxonomy.name as name")
                ->from($taxonomy->getTableName()." as taxonomy")
                ->condition("taxonomy.name like '%".$lookup."%'")
                ->limit(0, 10);
            foreach ($query->execute()->getItems() as $item) {
                $result[$item["id"]] = $item["name"];
            }
        } else {

        }

        echo json_encode($result);
    }
    public function actionLookupGetItem() {
        $catalog = CRequest::getString("catalog");
        $id = CRequest::getInt("id");

        $result = array();
        if ($catalog == "staff") {
            // выбор сотрудников
            $person = CStaffManager::getPerson($id);
            if (!is_null($person)) {
                $result[$person->getId()] = $person->getName();
            }
        } elseif($catalog == "student") {
            // выбор студентов
            $student = CStaffManager::getStudent($id);
            if (!is_null($student)) {
                $result[$student->getId()] = $student->getName();
            }
        } elseif (!is_null(CTaxonomyManager::getLegacyTaxonomy($catalog))) {
            // унаследованная таксономия
            $taxonomy = CTaxonomyManager::getLegacyTaxonomy($catalog);
            $term = $taxonomy->getTerm($id);
            if (!is_null($term)) {
                $result[$term->getId()] = $term->getValue();
            }
        }
        echo json_encode($result);
    }
    public function actionLookupGetDialog() {
        $this->renderView("_search/subform.lookupdialog.tpl");
    }
    public function actionLookupViewData() {
        $catalog = CRequest::getString("catalog");
        $result = array();
        if ($catalog == "staff") {
            // выбор сотрудников
            foreach (CStaffManager::getAllPersons()->getItems() as $person) {
                $result[$person->getId()] = $person->getName();
            }
        } elseif($catalog == "student") {
            // выбор студентов
            foreach (CStaffManager::getAllStudents()->getItems() as $student) {
                $result[$student->getId()] = $student->getName();
            }
        } elseif (!is_null(CTaxonomyManager::getLegacyTaxonomy($catalog))) {
            // унаследованная таксономия
            $taxonomy = CTaxonomyManager::getLegacyTaxonomy($catalog);
            foreach ($taxonomy->getTerms()->getItems() as $term) {
                $result[$term->getId()] = $term->getValue();
            }
        }
        echo json_encode($result);
    }
}