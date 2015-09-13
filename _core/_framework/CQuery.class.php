<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 05.05.12
 * Time: 10:53
 * To change this template use File | Settings | File Templates.
 *
 * Инкапсулирует запросы к базе данных, построитель запросов
 *
 * В версии от 01.09.2013 сделал поддержку PDO, пока только
 * для протокольной базы. Следующими разработчикам рекомендую
 * адаптировать остальной портал под работу именно таким
 * методом, но для этого нужно переписать оставшуюся часть поратал.
 */
define("QUERY_SELECT", "SELECT");
define("QUERY_UPDATE", "UPDATE");
define("QUERY_INSERT", "INSERT");
define("QUERY_REMOVE", "REMOVE");
define("QUERY_CUSTOM", "CUSTOM");

class CQuery {
    private $_fields = null;
    private $_table = null;
    private $_condition = null;
    private $_result = null;
    private $_type = null;
    private $_query = null;
    private $_isExecuted = false;
    private $_update = null;
    private $_leftJoin = null;
    private $_innerJoin = null;
    private $_order = null;
    private $_limit = null;
    private $_limitStart = null;
    private $_group = null;
    private $_db = null;

    /**
     * Конструктор.
     *
     * @param $db resource|DPO
     * @return CQuery
     */
    public function __construct($db = null) {
        $this->_db = $db;
        return $this;
    }

    /**
     * База, в которой выполняется запрос
     *
     * @return resource
     */
    private function getDb() {
        if (is_null($this->_db)) {
            global $sql_connect;
            $this->_db = $sql_connect;
        }
        return $this->_db;
    }
    /**
     * Список полей, которые выбираем.
     * Строка или обычный array
     *
     * @param $fields
     * @return CQuery
     */
    public function select($fields) {
        if (!is_array($fields)) {
            $fields = explode(",", $fields);
        }
        $this->_fields = $fields;
        $this->_type = QUERY_SELECT;
        return $this;
    }
    /**
     * Список полей и значений для обновления.
     * Строка или обычный array
     *
     * @param $fields
     * @param $table
     * @return CQuery
     */
    public function update($table, $fields) {
        $this->_type = QUERY_UPDATE;
        $this->_update = $fields;
        $this->_table = $table;

        return $this;
    }
    /**
     * Таблица, из которой будет делаться выборка
     *
     * @param $table
     * @return CQuery
     */
    public function from($table) {
        $this->_table = $table;
        return $this;
    }
    /**
     * Условия выборки
     *
     * @param $cond
     * @return CQuery
     */
    public function condition($cond) {
        $this->_condition = $cond;
        return $this;
    }
    /**
     * Тип запроса
     *
     * @return string
     */
    private function getQueryType() {
        return $this->_type;
    }
    public function getFields() {
        return implode(", ", $this->_fields);
    }
    /**
     * Исполнение запроса на выборку данных
     *
     * @return CArrayList
     */
    private function querySelect() {
        $q =
            "SELECT ".$this->getFields()." ".
                "FROM ".$this->_table." ";
        foreach ($this->getInnerJoins()->getItems() as $key=>$value) {
            $q .= "
            INNER JOIN ".$key." ON ".$value." ";
        }
        foreach ($this->getLeftJoins() as $key=>$value) {
            $q .= "
            LEFT JOIN ".$key." ON ".$value." ";
        }
        if(!is_null($this->_condition)) {
            $q .=
                "WHERE ".$this->_condition." ";
        }
        if (!is_null($this->_group)) {
            $q .= "GROUP BY ".$this->_group." ";
        }
        if (!is_null($this->_order)) {
            $q .= " ORDER BY ".$this->_order." ";
        }
        if (!is_null($this->_limit)) {
            $q .= " LIMIT ".$this->_limitStart.", ".$this->_limit;
        }
        $q .= ";";
        $this->_query = $q;
        if (is_object($this->getDb())) {
            if (!$this->_isExecuted) {
                $start = microtime();
                $this->_result = $this->getDb()->prepare($q);
                $this->_result->execute();
                $end = microtime();
                $this->_isExecuted = true;
                CLog::writeToLog($this->getQueryString()." (".($end - $start).")");
                CLog::addQueryTime($end-$start);
            }
            $rArr = new CArrayList();
            foreach ($this->_result->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $rArr->add($rArr->getCount(), $row);
            }
            return $rArr;
        } else {
            if (!$this->_isExecuted) {
                $start = microtime();
                $this->_result = mysql_query($q, $this->getDb()) or die(mysql_error($this->getDb())." -> ".$q);
                $end = microtime();
                $this->_isExecuted = true;
                CLog::writeToLog($this->getQueryString()." (".($end - $start).")");
                CLog::addQueryTime($end-$start);
            }
            $rArr = new CArrayList();
            while ($row = mysql_fetch_assoc($this->_result)) {
                $rArr->add($rArr->getCount(), $row);
            }
            return $rArr;
        }
    }
    /**
     * Строка запроса
     *
     * @return string
     */
    public function getQueryString() {
        return $this->_query;
    }
    /**
     * Исполнение запроса на вставку данных
     */
    private function queryInsert() {
        if (is_object($this->getDb())) {
            $columns = array();
            $placeholders = array();
            $values = array();
            foreach ($this->_fields as $key=>$value) {
                if ($key != "id") {
                    $columns[] = "`".$key."`";
                    $values[":".$key] = $value;
                    $placeholders[] = ":".$key;
                }
            }
            $q =
                "INSERT INTO ".$this->_table." ".
                    "(".implode(", ", $columns).") VALUES ".
                    "(".implode(", ", $placeholders).");";
            $this->_query = $q;
            if (!$this->_isExecuted) {
                $statement = $this->getDb()->prepare($q);
                $this->_result = $statement->execute($values);
                $this->_isExecuted = true;
            }
        } else {
            $columns = array();
            $values = array();
            foreach ($this->_fields as $key=>$value) {
                if ($key != "id") {
                    $columns[] = "`".$key."`";
                    $values[] = "'".$value."'";
                }
            }
            $q =
                "INSERT INTO ".$this->_table." ".
                    "(".implode(", ", $columns).") VALUES ".
                    "(".implode(", ", $values).");";
            $this->_query = $q;
            if (!$this->_isExecuted) {
                $this->_result = mysql_query($q, $this->getDb()) or die(mysql_error($this->getDb())." -> ".$q);
                $this->_isExecuted = true;
                //CLog::writeToLog($this->getQueryString());
            }
        }
    }
    /**
     * Исполнение запроса на обновление данных
     */
    private function queryUpdate() {
        if (is_object($this->getDb())) {
            $t = array();
            $values = array();
            foreach($this->_update as $key=>$value) {
                if ($key != "id") {
                    $t[] = "`".$key."` = :".$key;
                    $values[":".$key] = $value;
                }
            }
            $q =
                "UPDATE ".$this->_table." ".
                    "SET ".implode(", ", $t)." ";
            if(!is_null($this->_condition)) {
                $q .=
                    "WHERE ".$this->_condition;
            }
            $q .= ";";
            $this->_query = $q;
            if (!$this->_isExecuted) {
                $this->_result = $this->getDb()->prepare($q);
                $this->_result->execute($values);
                $this->_isExecuted = true;
                CLog::writeToLog($this->getQueryString());
            }
        } else {
            $t = array();
            foreach($this->_update as $key=>$value) {
                if ($key != "id") {
                    if (is_int($value)) {
                        $t[] = "`".$key."` = ".$value;
                    } elseif(is_string($value)) {
                        $t[] = "`".$key."` = '".$value."'";
                    }
                }
            }
            $q =
                "UPDATE ".$this->_table." ".
                    "SET ".implode(", ", $t)." ";
            if(!is_null($this->_condition)) {
                $q .=
                    "WHERE ".$this->_condition;
            }
            $q .= ";";
            $this->_query = $q;
            if (!$this->_isExecuted) {
                $this->_result = mysql_query($q, $this->getDb()) or die(mysql_error($this->getDb())." -> ".$q);
                $this->_isExecuted = true;
                CLog::writeToLog($this->getQueryString());
            }
        }
    }
    /**
     * Исполнение запроса на удаление
     */
    private function queryRemove() {
        $q =
            "DELETE FROM ".$this->_table." ";
        if(!is_null($this->_condition)) {
            $q .=
                "WHERE ".$this->_condition;
        }
        $q .= ";";
        $this->_query = $q;
        if (is_object($this->getDb())) {
            if (!$this->_isExecuted) {
                $this->_result = $this->getDb()->prepare($q);
                $this->_result->execute();
                $this->_isExecuted = true;
                CLog::writeToLog($this->getQueryString());
            }
        } else {
            if (!$this->_isExecuted) {
                $this->_result = mysql_query($q, $this->getDb()) or die(mysql_error($this->getDb())." -> ".$q);
                $this->_isExecuted = true;
                CLog::writeToLog($this->getQueryString());
            }
        }
    }
    /**
     * Исполнение запроса
     */
    public function execute() {
        switch($this->getQueryType()) {
            case QUERY_SELECT: {
                return $this->querySelect();
                break;
            };
            case QUERY_INSERT: {
                return $this->queryInsert();
                break;
            };
            case QUERY_UPDATE: {
                return $this->queryUpdate();
                break;
            };
            case QUERY_REMOVE: {
                return $this->queryRemove();
                break;
            };
            case QUERY_CUSTOM: {
                return $this->queryCustom();
                break;
            }
        }
    }
    /**
     * Вставка данных в таблицу
     *
     * @param $table
     * @param $fields
     * @return CQuery
     */
    public function insert($table, $fields) {
        $this->_type = QUERY_INSERT;
        $this->_table = $table;
        $this->_fields = $fields;

        return $this;
    }
    /**
     * Удаление записи из таблицы
     *
     * @param $table
     * @return CQuery
     */
    public function remove($table) {
        $this->_type = QUERY_REMOVE;
        $this->_table = $table;

        return $this;
    }
    public function leftJoin($table, $on) {
        $this->_leftJoin[$table] = $on;
        return $this;
    }

    /**
     * @param $table
     * @param $on
     * @return CQuery
     */
    public function innerJoin($table, $on) {
        $this->getInnerJoins()->add($table, $on);
        return $this;
    }

    /**
     * @return CArrayList|null
     */
    public function getInnerJoins() {
        if (is_null($this->_innerJoin)) {
            $this->_innerJoin = new CArrayList();
        }
        return $this->_innerJoin;
    }

    /**
     * @return array
     */
    public function getLeftJoins() {
        if (is_null($this->_leftJoin)) {
            $this->_leftJoin = array();
        }
        return $this->_leftJoin;
    }
    public function order ($by) {
        $this->_order = $by;
        return $this;
    }
    public function group($by) {
        $this->_group = $by;
        return $this;
    }

    /**
     * Таблица, над которой выполняется действие
     *
     * @return string
     */
    public function getTable() {
        return $this->_table;
    }
    public function limit($start, $limit) {
        $this->_limit = $limit;
        $this->_limitStart = $start;
    }
    public function getCondition() {
        return $this->_condition;
    }

    /**
     * Запрос собственного типа, для всякого разного
     *
     * @param $query
     * @return $this
     */
    public function query($query) {
        $this->_type = QUERY_CUSTOM;
        $this->_query = $query;
        return $this;
    }
    private function queryCustom() {
        if (is_object($this->getDb())) {
            $start = microtime();
            $res = new CArrayList();
            $this->_result = $this->getDb()->prepare($this->getQueryString());
            $this->_result->execute();
            foreach ($this->_result->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res->add($res->getCount(), $row);
            }
            $end = microtime();
            CLog::writeToLog($this->getQueryString()." (".($end - $start).")");
            CLog::addQueryTime($end-$start);
            return $res;
        } else {
            $start = microtime();
            $res = new CArrayList();
            $q = mysql_query($this->getQueryString(), $this->getDb()) or die(mysql_error($this->getDb())." -> ".$this->getQueryString());
            if (mysql_affected_rows($this->getDb()) > 0) {
                while ($row = mysql_fetch_assoc($q)) {
                    $res->add($res->getCount(), $row);
                }
            }
            $end = microtime();
            CLog::writeToLog($this->getQueryString()." (".($end - $start).")");
            CLog::addQueryTime($end-$start);
            return $res;
        }
    }
}