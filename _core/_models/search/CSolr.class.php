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
    public static function addObject(CActiveModel $model) {
        $doc = new SolrInputDocument();
        $doc->addField("id", $model->getRecord()->getTable()."_".$model->getId());
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
        foreach ($metaModel->tasks->getItems() as $task) {
            $doc->addField("_tasks_", $task->getId());
        }
        /**
         * Класс модели
         */
        $doc->addField("_class_", $metaModel->class_name);
        $response = self::getClient()->addDocument($doc);
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
    public static function search($query, $params = array()) {
        $solrQuery = new SolrQuery();
        $solrQuery->setQuery("doc_body:*".$query."*");
        foreach ($params as $key=>$value) {
            $solrQuery->addFilterQuery($key.":".$value);
        }

        $query_response = self::getClient()->query($solrQuery);
        $response = $query_response->getResponse();

        return $response["response"]["docs"];
    }
}