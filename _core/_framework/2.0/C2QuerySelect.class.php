<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 04.01.13
 * Time: 17:29
 * To change this template use File | Settings | File Templates.
 */
class C2QuerySelect {
    private $_query = null;
    private $_transaction = null;
    private $_fields = null;
    private $_table = null;
    private $_tableAlias = null;
    private $_joins = null;
    private $_limit = null;
    private $_limitStart = null;
    private $_queryString = null;
    private $_condition = null;

    public function __construct(C2Query $query, $fields, $table, $alias) {
        $this->_query = $query;
        $this->setTable($table);
        $this->setTableAlias($alias);
        $this->setFields($fields);
        $this->_transaction = new CTransaction();
    }

    /**
     * Поля запроса
     *
     * @param $fields
     */
    private function setFields($fields) {
        $flds = array();
        if (is_string($fields)) {
            if (strpos($fields, ",")) {
                $flds = explode(",", $fields);
            } else {
                $flds[] = $fields;
            }
        } elseif(is_array($fields)) {
            $flds = $fields;
        }
        foreach ($flds as $field) {
            $this->getFields()->add($this->getFields()->getCount(), $field);
        }
    }
    private function setTable($table) {
        $this->_table = $table;
    }
    private function setTableAlias($alias) {
        $this->_tableAlias = $alias;
    }

    /**
     * Название таблицы с псевдонимом
     *
     * @return string
     */
    public function getTable() {
        return $this->_table;
    }

    /**
     * Псевдоним таблицы
     *
     * @return string
     */
    private function getTableAlias() {
        if (is_null($this->_tableAlias)) {
            return $this->_table;
        } elseif (trim($this->_tableAlias) == "") {
            return $this->_table;
        }
        return $this->_tableAlias;
    }

    /**
     * @return CArrayList|null
     */
    private function getFields() {
        if (is_null($this->_fields)) {
            $this->_fields = new CArrayList();
        }
        return $this->_fields;
    }

    /**
     * Соединение таблицы с другими таблицами
     *
     * @return CArrayList|null
     */
    private function getJoins() {
        if (is_null($this->_joins)) {
            $this->_joins = new CArrayList();
        }
        return $this->_joins;
    }

    /**
     * Объединить таблицу с другой
     *
     * @param $type
     * @param $table
     * @param $on
     * @return C2QuerySelect
     */
    public function join($type, $table, $on, $alias = "") {
        $joins = new CArrayList();
        $type = strtoupper($type);
        if ($this->getJoins()->hasElement($type)) {
            $joins = $this->getJoins()->getItem($type);
        }
        $joins->add($table, $on);
        $this->getJoins()->add($type, $joins);
        return $this;
    }

    /**
     * Выборка только определенной части данных
     *
     * @param $start
     * @param $limit
     */
    public function limit($start, $limit) {
        $this->_limitStart = $start;
        $this->_limit = $limit;
    }
    public function execute() {
        $res = new CArrayList();

        $start = microtime();
        $result = mysql_query($this->buildQuery()) or die(mysql_error()." -> ".$this->buildQuery());
        $end = microtime();
        CLog::writeToLog($this->buildQuery()." (".($end - $start).")");
        CLog::addQueryTime($end-$start);

        $this->_transaction->commit();

        while ($row = mysql_fetch_assoc($result)) {
            $res->add($res->getCount(), $row);
        }

        return $res;
    }
    private function buildQuery() {
        if (is_null($this->_queryString)) {
            $q = "SELECT ";
            foreach ($this->getFields()->getItems() as $field) {
                $q .= $this->getTableAlias().".".$field;
            }
            $q .= " FROM ".$this->getTable();
            foreach ($this->getJoins()->getItems() as $type=>$list) {
                foreach ($list->getItems() as $table=>$on) {
                    $q .= " ".$type." JOIN ".$table." ON ".$on;
                }
            }
            if (!is_null($this->_limit)) {
                $q .= " LIMIT ".$this->_limitStart.", ".$this->_limit;
            }
            if (!is_null($this->_condition)) {
                $q .= " WHERE ".$this->_condition;
            }
            $this->_queryString = $q;
        }
        return $this->_queryString;
    }

    /**
     * Условие отбора записей
     *
     * @param $condition
     * @return C2QuerySelect
     */
    public function where($condition) {
        $this->_condition = $condition;
        return $this;
    }
    public function getCondition() {
        return $this->_condition;
    }
}
