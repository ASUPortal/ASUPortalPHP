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
    public function save() {
        $personArr = $this->person;
        $types = array();
        if (array_key_exists("types", $personArr)) {
            $types = $personArr["types"];
            unset($personArr["types"]);
        }
        /**
         * Сохраняем сотрудника
         */
        $personObj = new CPerson();
        $personObj->setAttributes($personArr);
        $personObj->save();
        /**
         * Удаляем старые типы участия на кафедре
         */
        foreach (CActiveRecordProvider::getWithCondition(TABLE_PERSON_BY_TYPES, "kadri_id=".$personObj->getId())->getItems() as $ar) {
            $ar->remove();
        }
        /**
         * Сохраняем новые типы участия на кафедре
         */
        foreach ($types as $type) {
            $ar = new CActiveRecord(array(
                "kadri_id" => $personObj->getId(),
                "person_type_id" => $type,
                "id" => null
            ));
            $ar->setTable(TABLE_PERSON_BY_TYPES);
            $ar->insert();
        }
    }
}