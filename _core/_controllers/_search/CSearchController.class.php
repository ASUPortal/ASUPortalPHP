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
            ),
            array(
                "title" => "Поиск по файлам",
                "icon" => "actions/document-print-preview.png",
                "link" => "index.php?action=searchFiles"
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
            ),
        	array(
        		"title" => "Обновить файловый индекс",
        		"icon" => "actions/document-print-preview.png",
        		"link" => "index.php?action=updateIndexFiles"
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
    public function actionUpdateIndexFiles() {
    	$this->setData("messages", CApp::getApp()->search->updateIndex());
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => "index.php?action=settings",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->renderView("_search/updateIndexFiles.tpl");
    }
    public function actionSearchFiles() {
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => "index.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->renderView("_search/searchFiles.tpl");
    }
    public function actionSearchByFiles() {
    	$userQuery = CRequest::getString("stringSearch");
    	$params = array(
    		"_is_file_" => 1,
    		"_highlight_" => "content"
    	);
    	$resultObj = CSolr::search($userQuery, $params);
    	$result = array();
    	foreach ($resultObj->getDocuments() as $doc) {
    		$hl = $resultObj->getHighlighingByDocument($doc);
    		try {
    			$file = CApp::getApp()->search->getFile($doc->id);
    		} catch (Exception $e) {
    			echo "<font color='#FF0000'>".$e->getMessage()."</font><br>";
    		}
    		if (!empty($file)) {
    			$fileName = CFileUtils::getFileName($file->getRealFilePath());
    			$res = array();
    			$res["hl"] = implode(", ", $hl);
    			$res["filepath"] = $file->getRealFilePath();
    			$res["location"] = $file->getFileLocation();
    			$res["filename"] = $fileName;
    			$result[] = $res;
    		}
    	}
    	$this->setData("result", $result);
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => "index.php?action=searchFiles",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->renderView("_search/searchByFiles.tpl");
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

    /**
     * @param $catalog
     * @return ISearchCatalogInterface
     * @throws Exception
     */
    private function searchObjectsFactory($catalog, $properties = array()) {
        if ($catalog == "staff") {
            return new CSearchCatalogStaff(array(
                "properties" => $properties
            ));
        } elseif ($catalog == "student") {
            return new CSearchCatalogStudent(array(
                "properties" => $properties
            ));
        } elseif ($catalog == "studentgroup") {
            return new CSearchCatalogStudentGroup(array(
                "properties" => $properties
            ));
        } elseif ($catalog == "sab_commissions") {
            return new CSearchCatalogSABCommission(array());
        } elseif (CUtils::strLeft($catalog, ".") == "class") {
            $class = CUtils::strRight($catalog, ".");
            return new $class(array(
                "properties" => $properties
            ));
        } elseif (!is_null(CTaxonomyManager::getTaxonomy($catalog))) {
            return new CSearchCatalogTaxonomy(array(
                "taxonomy" => $catalog,
                "properties" => $properties
            ));
        } elseif (!is_null(CTaxonomyManager::getLegacyTaxonomy($catalog))) {
            return new CSearchCatalogTaxonomyLegacy(array(
                "taxonomy" => $catalog,
                "properties" => $properties
            ));
        } else {
            throw new Exception("Не могу найти каталог для поиска ".$catalog);
        }
    }
    public function actionLookupTypeAhead() {
        $catalog = CRequest::getString("catalog");
        $lookup = CRequest::getString("query");

        $obj = $this->searchObjectsFactory($catalog);
        $result = $obj->actionTypeAhead($lookup);

        echo json_encode($result);
    }
    public function actionLookupGetItem() {
        $catalog = CRequest::getString("catalog");
        $id = CRequest::getInt("id");

        $obj = $this->searchObjectsFactory($catalog);
        $result = $obj->actionGetItem($id);

        echo json_encode($result);
    }
    public function actionLookupGetDialog() {
        $this->setData("allowCreation", CRequest::getString("allowCreation"));
        $this->renderView("_search/subform.lookupdialog.tpl");
    }
    public function actionLookupGetCatalogProperties() {
        $catalog = CRequest::getString("catalog");
        $obj = $this->searchObjectsFactory($catalog);

        $properties = array();
        foreach ($obj->actionGetCatalogProperties() as $key=>$value) {
            $property = array(
                "key" => $key,
                "label" => $value,
                "checked" => false
            );
            $properties[$key] = $property;
        }
        foreach ($obj->actionGetDefaultCatalogProperties() as $value) {
            if (array_key_exists($value, $properties)) {
                $properties[$value]["checked"] = true;
            }
        }
        echo json_encode($properties);
    }
    public function actionLookupViewData() {
        $catalog = CRequest::getString("catalog");
        $properties = CRequest::getArray("properties");
        $properties = array_unique($properties);

        $obj = $this->searchObjectsFactory($catalog, $properties);
        $result = $obj->actionGetViewData();

        echo json_encode($result);
    }
    public function actionNgLookupViewData() {
        $catalog = CRequest::getString("catalog");
        $properties = CRequest::getArray("properties");
        $properties = array_unique($properties);

        $obj = $this->searchObjectsFactory($catalog, $properties);
        $data = $obj->actionGetViewData();

        $result = array();
        foreach ($data as $key=>$value) {
            /**
             * @var IJSONSerializable $term
             * @var ISearchCatalogInterface $obj
             */
            $term = $obj->actionGetObject($key);
            $result[] = $term->toJsonObject(false);
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
    public function actionGetGlobalSearchSubform() {
        $this->renderView("_search/subform.globalsearch.tpl");
    }

    /**
     * Получаем адрес диалога создания нового элемента справочника
     */
    public function actionLookupGetCreationDialog() {
        $catalog = CRequest::getString("catalog");
        $obj = $this->searchObjectsFactory($catalog);

        echo $obj->actionGetCreationActionUrl();
    }
}
