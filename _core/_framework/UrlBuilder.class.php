<?php
/**
 * Построитель ссылок
 */

class UrlBuilder {
    private $_link = "";
    private $params;
    
    /**
     * @param string $page
     */
    private function __construct($page) {
        $this->_link = $page;
    }
    
    /**
     * Создание ссылки для страницы
     *
     * @param string $page
     * @return UrlBuilder
     */
    public static function newBuilder($page) {
        $UrlBuilder = new UrlBuilder($page);
        return $UrlBuilder;
    }
    
    /**
     * Добавление параметров к ссылке
     *
     * @param string $param
     * @param string $value
     * @return $this
     */
    public function addParameter($param, $value) {
        $this->params[] = $param."=".$value;
        return $this;
    }
    
    /**
     * Построить ссылку
     * 
     * @return string $url
     */
    public function build() {
        $url = $this->_link."?".implode("&", $this->params);
        return $url;
    }
}