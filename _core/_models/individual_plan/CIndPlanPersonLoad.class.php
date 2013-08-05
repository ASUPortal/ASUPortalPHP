<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 05.08.13
 * Time: 20:54
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanPersonLoad {
    public $person = null;
    public $year = null;

    private $_teachingLoad = null;

    /**
     * @return CIndPlanPersonLoadTeaching|null
     */
    public function getTeachingLoad() {
        if (is_null($this->_teachingLoad)) {
            $this->_teachingLoad = new CIndPlanPersonLoadTeaching();
            $this->_teachingLoad->kadri_id = $this->getPerson()->getId();
            $this->_teachingLoad->year_id = $this->getYear()->getId();
        }
        return $this->_teachingLoad;
    }

    /**
     * @return CPerson
     */
    private function getPerson() {
        return $this->person;
    }

    /**
     * @return CTerm
     */
    private function getYear() {
        return $this->year;
    }

    /**
     * Есть ли значения в этом объекте
     * По большому счету, полностью его инициализирует
     *
     * @return bool
     */
    public function haveValues() {
        /**
         * Проверяем, есть ли учебная работа
         */
        if ($this->getTeachingLoad()->getPlan()->getCount() > 0) {
            return true;
        } elseif ($this->getTeachingLoad()->getFact()->getCount() > 0) {
            return true;
        }
        return false;
    }
}