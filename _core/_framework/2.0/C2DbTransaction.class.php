<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 18:05
 * To change this template use File | Settings | File Templates.
 */
class C2DbTransaction {
    private $_connection=null;
    private $_active;

    /**
     * Constructor.
     * @param C2DbConnection $connection the connection associated with this transaction
     * @see C2DbConnection::beginTransaction
     */
    public function __construct(C2DbConnection $connection)
    {
        $this->_connection=$connection;
        $this->_active=true;
    }

    /**
     * Commits a transaction.
     * @throws C2Exception if the transaction or the DB connection is not active.
     */
    public function commit()
    {
        if($this->_active && $this->_connection->getActive())
        {
            CApp::trace('Committing transaction','system.db.CDbTransaction');
            $this->_connection->getPdoInstance()->commit();
            $this->_active=false;
        }
        else
            throw new C2DbException(CApp::t('CApp','CDbTransaction is inactive and cannot perform commit or roll back operations.'));
    }

    /**
     * Rolls back a transaction.
     * @throws C2Exception if the transaction or the DB connection is not active.
     */
    public function rollback()
    {
        if($this->_active && $this->_connection->getActive())
        {
            CApp::trace('Rolling back transaction','system.db.CDbTransaction');
            $this->_connection->getPdoInstance()->rollBack();
            $this->_active=false;
        }
        else
            throw new C2DbException(CApp::t('CApp','CDbTransaction is inactive and cannot perform commit or roll back operations.'));
    }

    /**
     * @return C2DbConnection the DB connection for this transaction
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * @return boolean whether this transaction is active
     */
    public function getActive()
    {
        return $this->_active;
    }

    /**
     * @param boolean $value whether this transaction is active
     */
    protected function setActive($value)
    {
        $this->_active=$value;
    }
}
