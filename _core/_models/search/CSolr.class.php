<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 06.10.13
 * Time: 17:57
 * To change this template use File | Settings | File Templates.
 */

class CSolr {
    private static $_client = null;

    /**
     * @return CSolrClient
     */
    private static function getClient() {
        if (is_null(self::$_client)) {
            $params = array(
                "hostname" => CSettingsManager::getSettingValue("solr_server"),
                "port" => CSettingsManager::getSettingValue("solr_port"),
                "path" => "solr/PortalASU"
            );
            self::$_client = new SolrClient($params);
        }
        return self::$_client;
    }

    /**
     * @param CActiveModel $model
     * @param bool $exportTasks
     * @param bool $isMain
     * @return SolrInputDocument
     */
    private static function createSolrInputDocument(CActiveModel $model, $exportTasks = false, $isMain = false) {
        $doc = new SolrInputDocument();
        $doc->addField("id", $model->getRecord()->getTable()."_".$model->getId());
        $doc->addField("_doc_id_", $model->getId());
        /**
         * Выгружаем дополнительные выгружаемые поля
         */
        $metaModel = CCoreObjectsManager::getCoreModel(get_class($model));
        if (!is_null($metaModel)) {
            foreach ($metaModel->fields->getItems() as $field) {
                if ($field->isExportable()) {
                    $name = $field->field_name;
                    $doc->addField($name, $model->$name);
                }
            }
        }
        /**
         * Выгружаем список задач, с которыми связана модель
         */
        if ($exportTasks) {
            foreach ($metaModel->tasks->getItems() as $task) {
                $doc->addField("_tasks_", $task->getId());
            }
        }
        /**
         * Класс модели
         */
        $doc->addField("_class_", $metaModel->class_name);
        /**
         * Модель является основной
         */
        if ($isMain) {
            $doc->addField("_is_main_", "1");
        } else {
            $doc->addField("_is_main_", "0");
        }
        return $doc;
    }
    private static function getConditionField(array $params = array()) {
        $result = "";
        if ($params["relationPower"] == RELATION_HAS_ONE) {
            if ($params["storageField"] != "") {
                $condition = $params["storageField"];
                $result = CUtils::strLeft($condition, "=");
                $result = str_replace(" ", "", $result);
            }
        }
        return $result;
    }
    public static function addObject(CActiveModel $model) {
        $doc = self::createSolrInputDocument($model, true, true);
        $response = self::getClient()->addDocument($doc);
        /**
         * Непосредственно связанные объекты
         */
        foreach ($model->getRelations() as $name=>$params) {
            if ($params["relationPower"] == RELATION_HAS_ONE) {
                $obj = $model->$name;
                if (!is_null($obj)) {
                    $modelMeta = CCoreObjectsManager::getCoreModel(get_class($obj));
                    if (!is_null($modelMeta)) {
                        if ($modelMeta->isExportable()) {
                            $doc = self::createSolrInputDocument($obj, false, false);
                            $doc->addField("_parent_class_", get_class($model));
                            $doc->addField("_parent_field_", self::getConditionField($params));
                            $response = self::getClient()->addDocument($doc);
                        }
                    }
                }
            } elseif ($params["relationPower"] == RELATION_HAS_MANY) {

            } elseif ($params["relationPower"] == RELATION_MANY_TO_MANY) {

            } elseif ($params["relationPower"] == RELATION_COMPUTED) {

            }
        }
    }

    /**
     * @return array
     */
    private static function getOptions() {
        return self::getClient()->getOptions();
    }
    public static function commit() {
        $options = self::getOptions();
        $url = "http://".$options["hostname"].":".$options["port"]."/";
        $url .= $options["path"]."/update?softCommit=true";
        $responseTxt = file_get_contents($url);
    }
    /**
     * url для отправки файлов
     * 
     * @return string
     */
    public static function commitFiles() {
    	$options = self::getOptions();
    	$url = "http://".$options["hostname"]."/";
    	$url .= $options["path"]."/update/extract?commit=true";
    	return $url;
    }

    /**
     * Выполнить поиск
     *
     * @param $query
     * @param array $params
     * @return CSolrQueryResults
     */
    public static function search($query, $params = array()) {
        $solrQuery = new SolrQuery();
        $solrQuery->setQuery("doc_body:*".$query."*");
        if (mb_strpos($query, " ") !== false) {
            $solrQuery->setQuery('doc_body:"*'.$query.'*"');
        }
        foreach ($params as $key=>$value) {
            if ($key == "_highlight_") {
                $solrQuery->addHighlightField($value);
                $solrQuery->setHighlight(true);
                $solrQuery->setHighlightSimplePre("<em>");
                $solrQuery->setHighlightSimplePost("</em>");
            } elseif (is_array($value)) {
                $solrQuery->addFilterQuery($key.":".implode(",", $value));
            } else {
                $solrQuery->addFilterQuery($key.":".$value);
            }
        }
		try {
			$query_response = self::getClient()->query($solrQuery);
		} catch (Exception $e) {
			echo $e->getMessage();
			var_dump(self::getClient()->getDebug());
			break;
		}
		$response = $query_response->getResponse();

        $result = new CSolrQueryResults($response);
        return $result;
    }
}