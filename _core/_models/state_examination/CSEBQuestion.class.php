<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.06.12
 * Time: 10:16
 * To change this template use File | Settings | File Templates.
 */
class CSEBQuestion extends CActiveModel {
    private $_discipline = null;
    private $_speciality = null;

    public function setDiscipline(CTerm $value) {
        $this->_discipline = $value;
        $this->getRecord()->setItemValue("discipline_id", $value->getId());
    }
    public function setText($value) {
        $this->getRecord()->setItemValue("question", $value);
    }
    /**
     * Специальность, к которой вопрос
     *
     * @return CTerm
     */
    public function getSpeciality() {
        if (is_null($this->_speciality)) {
            $this->_speciality = CTaxonomyManager::getCacheSpecialities()->getItem($this->getRecord()->getItemValue("speciality_id"));
        }
        return $this->_speciality;
    }
    /**
     * Специальность
     *
     * @param CTerm $value
     */
    public function setSpeciality(CTerm $value) {
        $this->_speciality = $value;
        $this->getRecord()->setItemValue("speciality_id", $value->getId());
    }
    /**
     * Дисциплина, к которой вопрос привязан
     *
     * @return CTerm
     */
    public function getDiscipline() {
        if (is_null($this->_discipline)) {
            $this->_discipline = CTaxonomyManager::getCacheDisciplines()->getItem($this->getRecord()->getItemValue("discipline_id"));
        }
        return $this->_discipline;
    }
    public function getText() {
        return $this->getRecord()->getItemValue("question");
    }
    public function toArrayForJSON() {
        $r['id'] = $this->getId();
        $r['value'] = $this->getText();

        return $r;
    }
}
