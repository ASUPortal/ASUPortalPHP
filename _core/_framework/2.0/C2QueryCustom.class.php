<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 04.01.13
 * Time: 16:00
 * To change this template use File | Settings | File Templates.
 *
 * Заданный вручную пользователем запрос
 */
class C2QueryCustom {
    private $_queryString = "";
    private $_query = null;

    /**
     * @param C2Query $query
     */
    public function __construct(C2Query $query) {
        $this->_query = $query;
    }

    /**
     * @param $queryString
     */
    public function setQueryString($queryString) {
        $this->_queryString = $queryString;
    }

    /**
     * Выполнить запрос.
     */
    public function execute() {
        $start = microtime();
        mysql_query($this->getQueryString()) or die(mysql_error()." -> ".$this->getQueryString());
        $end = microtime();
        CLog::writeToLog($this->getQueryString()." (".($end - $start).")");
        CLog::addQueryTime($end-$start);
    }

    /**
     * @return string
     */
    private function getQueryString() {
        return $this->_queryString;
    }
}
