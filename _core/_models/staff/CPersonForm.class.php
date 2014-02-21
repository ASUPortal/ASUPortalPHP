<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 23.04.13
 * Time: 20:03
 * To change this template use File | Settings | File Templates.
 */

class CPersonForm extends CFormModel{
    public $person = null;
    private $_types = array();
    public function save() {     
        
        $personArr = $this->person;
        /**
         * Сохраняем сотрудника
         */
        $this->person->save();
        /**
         * Удаляем старые типы участия на кафедре
         */
        foreach (CActiveRecordProvider::getWithCondition(TABLE_PERSON_BY_TYPES, "kadri_id=".$this->person->getId())->getItems() as $ar) {
            $ar->remove();
        }
        /**
         * Сохраняем новые типы участия на кафедре
         */
        foreach ($this->_types as $type) {
            if ($type != "" && $type != "0") {
                $ar = new CActiveRecord(array(
                    "kadri_id" => $this->person->getId(),
                    "person_type_id" => $type,
                    "id" => null
                ));
                $ar->setTable(TABLE_PERSON_BY_TYPES);
                $ar->insert();
            }
        }
    }

    public function setAttributes(array $array) {
        parent::setAttributes($array);

        $personArr = $this->person;
        if (array_key_exists("types", $personArr)) {
            $this->_types = $personArr["types"];
            unset($personArr["types"]);
        }
        $this->person = new CPerson();
        $this->person->setAttributes($personArr);
        if (!is_null($this->to_tabel)) {
            $this->person->to_tabel = $this->to_tabel;            
        }
        if (!is_null($this->is_slave)) {
            $this->person->is_slave = $this->is_slave;            
        }
    }

}
