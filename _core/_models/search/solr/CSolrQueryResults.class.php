<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.02.14
 * Time: 11:52
 * To change this template use File | Settings | File Templates.
 */

class CSolrQueryResults {
    private $_response = null;

    function __construct($_response) {
        $this->_response = $_response;
    }

    /**
     * Документы, попавшие в результаты поиска
     *
     * @return array
     */
    public function getDocuments() {
        if ($this->_response["response"]["docs"] === false) {
            return array();
        }
        return $this->_response["response"]["docs"];
    }

    /**
     *
     *
     * @return mixed
     */
    public function getHighlighting() {
        return $this->_response["highlighting"];
    }
    public function getHighlighingByDocument(SolrObject $document) {
        /**
         * Получаем идентификатор переданного документа
         */
        $id = false;
        if (property_exists($document, "id")) {
            $id = $document->id;
        }
        if ($id === false) {
            return array();
        }
        /**
         * Смотрим, есть ли подсветка для этого документа в полученных результатах
         */
        $hlObj = false;
        if (property_exists($this->getHighlighting(), $id)) {
            $hlObj = $this->getHighlighting()->$id;
        }
        if ($hlObj === false) {
            return array();
        }
        $results = array();
        foreach (get_object_vars($hlObj) as $key=>$value) {
            if (is_array($value)) {
                $results[$key] = $value[0];
            } else {
                $results[$key] = $value;
            }
        }
        return $results;
    }
}