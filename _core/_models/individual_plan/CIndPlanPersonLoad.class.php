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
    private $_conclusions = null;
    private $_changes = null;
    private $_publications = null;
    private $_educations = null;
    private $_science = null;
    private $_organizations = null;

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
     * @return CArrayList|null
     */
    public function getConclusions() {
        if (is_null($this->_conclusions)) {
            $this->_conclusions = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_CONCLUSTIONS,
                    "id_year=".$this->getYear()->getId()." AND ".
                    "id_kadri=".$this->getPerson()->getId())->getItems() as $ar) {
                $c = new CIndPlanPersonConclusion($ar);
                $this->_conclusions->add($c->getId(), $c);
            }
        }
        return $this->_conclusions;
    }

    /**
     * @return CArrayList|null
     */
    public function getPublications() {
        if (is_null($this->_publications)) {
            $this->_publications = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_PUBLICATIONS,
                    "id_year=".$this->getYear()->getId()." AND ".
                    "id_kadri=".$this->getPerson()->getId())->getItems() as $ar) {
                $c = new CIndPlanPersonPublication($ar);
                $this->_publications->add($c->getId(), $c);
            }
        }
        return $this->_publications;
    }

    /**
     * @return CArrayList|null
     */
    public function getChanges() {
        if (is_null($this->_changes)) {
            $this->_changes = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_CHANGES,
                    "id_year=".$this->getYear()->getId()." AND ".
                    "id_kadri=".$this->getPerson()->getId())->getItems() as $ar) {

                $c = new CIndPlanPersonChange($ar);
                $this->_changes->add($c->getId(), $c);
            }
        }
        return $this->_changes;
    }

    /**
     * @return CArrayList|null
     */
    public function getEducationLoad() {
        if (is_null($this->_educations)) {
            $this->_educations = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_LOAD_EDUCATION,
                    "id_year=".$this->getYear()->getId()." AND ".
                    "id_kadri=".$this->getPerson()->getId())->getItems() as $ar) {

                $c = new CIndPlanPersonLoadEducation($ar);
                $this->_educations->add($c->getId(), $c);
            }
        }
        return $this->_educations;
    }

    /**
     * @return CArrayList|null
     */
    public function getScienceLoad() {
        if (is_null($this->_science)) {
            $this->_science = new CArrayList();

            foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_LOAD_SCIENCE,
                "id_year=".$this->getYear()->getId()." AND ".
                    "id_kadri=".$this->getPerson()->getId())->getItems() as $ar) {

                $c = new CIndPlanPersonLoadScience($ar);
                $this->_science->add($c->getId(), $c);
            }
        }
        return $this->_science;
    }

    /**
     * Учебная и организационно-методическая
     *
     * @return CArrayList|null
     */
    public function getOrganizationalLoad() {
        if (is_null($this->_organizations)) {
            $this->_organizations = new CArrayList();

            $this->_science = new CArrayList();

            foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_LOAD_ORGANIZATIONAL,
                    "id_year=".$this->getYear()->getId()." AND ".
                    "id_kadri=".$this->getPerson()->getId())->getItems() as $ar) {

                $c = new CIndPlanPersonLoadOrg($ar);
                $this->_organizations->add($c->getId(), $c);
            }
        }
        return $this->_organizations;
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
        if ($this->getTeachingLoad()->getFact()->getCount() > 0) {
            return true;
        } elseif ($this->getConclusions()->getCount() > 0) {
            return true;
        } elseif ($this->getPublications()->getCount() > 0) {
            return true;
        } elseif ($this->getEducationLoad()->getCount() > 0) {
            return true;
        } elseif ($this->getScienceLoad()->getCount() > 0) {
            return true;
        }
        return false;
    }
}