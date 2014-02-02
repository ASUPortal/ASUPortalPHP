<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 19.10.13
 * Time: 18:05
 * To change this template use File | Settings | File Templates.
 */

class CSearchController extends CBaseController{
    protected $allowedAnonymous = array(
        "updateIndex"
    );
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
        $this->addActionsMenuItem(array(
            array(
                "title" => "Настройки",
                "link" => "index.php?action=settings",
                "icon" => "places/network-workgroup.png"
            )
        ));
        $this->renderView("_search/index.tpl");
    }
    public function actionSettings() {
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
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "index.php?action=index",
                "icon" => "actions/edit-undo.png"
            ),
            array(
                "title" => "Обновить индекс",
                "icon" => "actions/document-print-preview.png",
                "link" => "index.php?action=updateIndex&redirect=index"
            )
        ));
        $this->renderView("_search/settings.tpl");
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
        $resultObj = CSolr::search($userQuery, $params);
        $docs = $resultObj->getDocuments();
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
    public function actionUpdateIndex() {
        $models = CCoreObjectsManager::getAllExportableModels();
        foreach ($models->getItems() as $metaModel) {
            if ($metaModel->isExportable()) {
                $modelClass = $metaModel->class_name;
                $model = new $modelClass();
                $records = CActiveRecordProvider::getAllFromTable($model->getRecord()->getTable());
                foreach ($records->getItems() as $record) {
                    $model = new $modelClass($record);
                    CSolr::addObject($model);
                }
                CSolr::commit();
            }
        }
        if (CRequest::getString("redirect") != "") {
            $this->redirect("?action=".CRequest::getString("redirect"));
        }
    }
    protected function onActionBeforeExecute() {
        if ($this->getAction() == "updateIndex") {
            if (CRequest::getString("key") == CSettingsManager::getSettingValue("solr_key")) {
                return true;
            }
        }
        parent::onActionBeforeExecute();
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
        } elseif ($catalog == "studentgroup") {
            // выбор студенческих групп
            $query = new CQuery();
            $query->select("distinct(gr.id) as id, gr.name as name")
                ->from(TABLE_STUDENT_GROUPS." as gr")
                ->condition("gr.name like '%".$lookup."%'")
                ->limit(0, 10);
            foreach ($query->execute()->getItems() as $item) {
                $result[$item["id"]] = $item["name"];
            }
        } elseif ($catalog == "sab_commissions") {
            // комиссии по защите дипломов. показываем только комиссии этого года
            $query = new CQuery();
            $query->select("distinct(comm.id) as id, comm.title as name")
                ->from(TABLE_SAB_COMMISSIONS." as comm")
                ->condition("comm.title like '%".$lookup."%' and year_id=".CUtils::getCurrentYear()->getId())
                ->limit(0, 10);
            foreach ($query->execute()->getItems() as $item) {
                $comm = new CSABCommission(new CActiveRecord($item));
                $value = $comm->title;
                if (!is_null($comm->manager)) {
                    $value .= " ".$comm->manager->getName();
                }
                if (!is_null($comm->secretar)) {
                    $value .= " ".$comm->secretar->getName();
                }
                $result[$comm->getId()] = $value;
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
        } elseif ($catalog == "studentgroup") {
            // группы студентов
            $group = CStaffManager::getStudentGroup($id);
            if (!is_null($group)) {
                $result[$group->getId()] = $group->getName();
            }
        } elseif ($catalog == "sab_commissions") {
            // комиссии по защите дипломов
            $commission = CSABManager::getCommission($id);
            if (!is_null($commission)) {
                $value = $commission->title;
                if (!is_null($commission->manager)) {
                    $value .= " ".$commission->manager->getName();
                }
                if (!is_null($commission->secretar)) {
                    $value .= " (".$commission->secretar->getName().")";
                }
                $result[$commission->getId()] = $value;
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
        } elseif ($catalog == "studentgroup") {
            // выбор студенческих групп
            foreach (CStaffManager::getAllStudentGroups()->getItems() as $group) {
                $result[$group->getId()] = $group->getName();
            }
        } elseif ($catalog == "sab_commissions") {
            // комиссии по защите дипломов. показываем только комиссии этого года
            foreach (CActiveRecordProvider::getWithCondition(TABLE_SAB_COMMISSIONS, "year_id=".CUtils::getCurrentYear()->getId())->getItems() as $ar) {
                $comm = new CSABCommission($ar);
                $value = $comm->title;
                if (!is_null($comm->manager)) {
                    $value .= " ".$comm->manager->getName();
                }
                if (!is_null($comm->secretar)) {
                    $value .= " ".$comm->secretar->getName();
                }
                $result[$comm->getId()] = $value;
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
    public function actionGlobalSearch() {
        /**
         * Запрос, который отправил пользователь
         */
        $userQuery = CRequest::getString("keyword");
        /**
         * Задачи, в которые у него есть доступ
         */
        $tasks = array();
        foreach (CSession::getCurrentUser()->getRoles()->getItems() as $role) {
            $tasks[] = $role->getId();
        }
        /**
         * Непосредственно, поиск
         */
        $params = array(
            "_is_main_" => 1,
            "_highlight_" => "doc_body"
        );
        $resultObj = CSolr::search($userQuery, $params);
        /**
         * Формируем модель данных для рисования результата
         */
        $result = array();
        $userQuery = mb_strtolower($userQuery);
        foreach ($resultObj->getDocuments() as $doc) {
            $hl = $resultObj->getHighlighingByDocument($doc);
            $res = array(
                "text" => implode(", ", $hl)
            );
            /**
             * Получаем метамодель для этого типа объекта
             * Смотрим, какие задачи с ней связны
             */
            $model = CCoreObjectsManager::getCoreModel($doc->_class_);
            $tasks = array();
            if (!is_null($model)) {
                foreach ($model->tasks->getItems() as $task) {
                    /**
                     * Для каждой задачи формируем URL с фильтром
                     */
                    $title = $task->name;
                    $url = $task->url;

                    $urlParams = array();
                    $urlParams[] = "filterClass=".$doc->_class_;
                    $urlParams[] = "filterLabel=".str_replace("</em>", "", str_replace("<em>", "", implode(", ", $hl)));

                    foreach ($model->fields->getItems() as $field) {
                        if (property_exists($doc, $field->field_name)) {
                            $fieldName = $field->field_name;
                            $fieldValue = mb_strtolower($doc->$fieldName);
                            if (mb_strpos($fieldValue, $userQuery) !== false) {
                                if (mb_strlen($fieldValue) < 100) {
                                    $urlParams[] = "filter=".$fieldName.":".$fieldValue;
                                } else {
                                    $urlParams[] = "filter=".$fieldName.":".$userQuery;
                                }
                            }
                        }
                    }

                    $url .= "?".implode("&", $urlParams);

                    $tasks[$url] = $title;
                }
            }
            $res["tasks"] = $tasks;
            $result[] = $res;
        }
        /**
         * Склеиваем результаты с одинаковым текстом
         */
        $outResults = array();
        foreach ($result as $res) {
            $key = $res["text"];
            if (array_key_exists($key, $outResults)) {
                $obj = $outResults[$key];
                $tasks = $obj["tasks"];
                foreach ($res["tasks"] as $url=>$title) {
                    $tasks[$url] = $title;
                }
                $obj["tasks"] = $tasks;
            } else {
                $obj = $res;
            }
            $outResults[$key] = $obj;
        }
        /**
         * Отображением
         */
        $this->setData("results", $outResults);
        $this->renderView("_search/results.tpl");
    }
}