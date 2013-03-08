<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 04.01.13
 * Time: 15:53
 * To change this template use File | Settings | File Templates.
 */
class CTransaction {
    private $_completed = false;
    public function __construct() {
        $this->_completed = false;
        $query = new C2Query();
        $query->query("START TRANSACTION;")->execute();
    }

    /**
     * Откат тразакции
     */
    public function rollback() {
        $query = new C2Query();
        $query->query("ROLLBACK;")->execute();
    }

    /**
     * Фиксация транзакции
     */
    public function commit() {
        $query = new C2Query();
        $query->query("COMMIT;")->execute();
    }

    /**
     * Деструктор. Если транзакция не была к этому моменту завершена,
     * то откатываем ее
     */
    public function __destroy() {
        if (!$this->isCompleted()) {
            $this->rollback();
        }
    }

    /**
     * Завершена ли работа транзакции
     *
     * @return bool
     */
    private function isCompleted() {
        return $this->_completed;
    }
}
