<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 12:15
 * To change this template use File | Settings | File Templates.
 */
class C2ActiveRecord {
    private static $_models = array();
    private static $_dbConnection = null;

    /**
     * @param string $className
     * @return C2ActiveRecord
     */
    public static function model($className = __CLASS__) {
        if (array_key_exists($className, self::$_models)) {
            return self::$_models[$className];
        } else {
            $model = self::$_models[$className] = new $className(null);
            return $model;
        }
    }

    /**
     * Поиск одной записи по условию. Если запись найдена не будет, то вернет Null.
     *
     * @param mixed $condition - строковое условие
     * @param array $params - условие для C2DbCriteria
     * @return C2ActiveRecord
     */
    public function find(mixed $condition = "", array $params = array()) {
        $criteria = $this->getCommandBuilder()->createCriteria($condition, $params);
        return $this->query($criteria);
    }
    protected function query(C2DbCriteria $criteria, $all = false) {
        if (empty($criteria->with)) {
            if (!$all) {
                $criteria->limit = 1;
            }
        } else {

        }
    }

    /**
     * Соединение с базой данных, через которое данный объект получен
     *
     * @return C2DbConnection
     */
    public function getDbConnection() {
        if (is_null(self::$_dbConnection)) {
            self::$_dbConnection = CApp::getApp()->getDb();
        }
        return self::$_dbConnection;
    }

    /**
     * Построитель запросов
     *
     * @return C2CommandBuilder
     */
    public function getCommandBuilder() {
        return $this->getDbConnection()->getSchema()->getCommandBuilder();
    }
}
