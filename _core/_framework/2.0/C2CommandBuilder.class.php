<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 12:51
 * To change this template use File | Settings | File Templates.
 */
class C2CommandBuilder {
    const PARAM_PREFIX=':asu';
    private $_schema = null;
    private $_connection = null;

    /**
     * @param C2Schema $schema
     */
    public function __construct(C2Schema $schema) {
        $this->_schema = $schema;
        $this->_connection = $schema->getConnection();
    }

    /**
     * Сгенерировать объект запроса
     *
     * @param string $condition
     * @param array $params
     * @return CDbCriteria
     */
    public function createCriteria($condition = "", array $params = array()) {
        if(is_array($condition)) {
            $criteria = new CDbCriteria($condition);
        } elseif($condition instanceof CDbCriteria) {
            $criteria = clone $condition;
        } else {
            $criteria = new CDbCriteria;
            $criteria->condition = $condition;
            $criteria->params = $params;
        }
        return $criteria;
    }

    /**
     * @return C2DbConnection|null
     */
    public function getConnection() {
        return $this->_connection;
    }

    /**
     * @return C2Schema|null
     */
    public function getSchema() {
        return $this->_schema;
    }
    protected function ensureTable(&$table) {

    }
    /**
     * Alters the SQL to apply LIMIT and OFFSET.
     * Default implementation is applicable for PostgreSQL, MySQL and SQLite.
     * @param string $sql SQL query string without LIMIT and OFFSET.
     * @param integer $limit maximum number of rows, -1 to ignore limit.
     * @param integer $offset row offset, -1 to ignore offset.
     * @return string SQL with LIMIT and OFFSET
     */
    public function applyLimit($sql,$limit,$offset)
    {
        if($limit>=0)
            $sql.=' LIMIT '.(int)$limit;
        if($offset>0)
            $sql.=' OFFSET '.(int)$offset;
        return $sql;
    }
}
