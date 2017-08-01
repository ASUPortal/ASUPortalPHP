<?php
/**
 * Построитель ссылок
 */

class UrlBuilder {
    private $_link = "";
    
    /**
     * @param string $page
     * @return $this
     */
    public function __construct($page) {
    	$this->_link = $page."?";
    	return $this;
    }
    
    /**
     * Создание ссылки для страницы
     *
     * @param string $page
     * @return UrlBuilder
     */
    public function newBuilder($page) {
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
    	$this->_link .= $param."=".$value."&";
    	return $this;
    }
    
    /**
     * Построить ссылку
     * 
     * @return string $_link
     */
    public function build() {
    	return $this->_link;
    }
    
    /**
     * Получить значение параметра из ссылки
     * 
     * @param string $url
     * @param string $param
     * @return string $item
     */
    public function getValueByParam($url, $param) {
    	$item = "";
    	$values = explode("&", $url);
    	foreach ($values as $value) {
    		if (CUtils::strLeft($value, "=") == $param) {
    			$item = CUtils::strRight($value, "=");
    		}
    	}
    	return $item;
    }
}