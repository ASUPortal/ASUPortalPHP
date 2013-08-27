<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 18.04.13
 * Time: 20:27
 * To change this template use File | Settings | File Templates.
 */

class CGrantForm extends CFormModel{
    public $grant =  null;
    private $_fields = null;
    public function save() {

        $this->grant->save();
        /**
         * Работа с участниками
         */
        $members = array();
        if (array_key_exists("members", $this->_fields)) {
            $members = $this->_fields["members"];
        }
        /**
         * Делаем руководителя тоже участником
         */
        if ($this->grant->manager_id != "0") {
            $members[] = $this->grant->manager_id;
        }
        /**
         * Удаляем старых участников
         */
        foreach (CActiveRecordProvider::getWithCondition(TABLE_GRANT_MEMBERS, "grant_id= ".$this->grant->getId())->getItems() as $ar) {
            $ar->remove();
        }
        /**
         * Добавляем новых
         */
        foreach ($members as $member) {
            $ar = new CActiveRecord(array(
                "id" => null,
                "grant_id" => $this->grant->getId(),
                "person_id" => $member
            ));
            $ar->setTable(TABLE_GRANT_MEMBERS);
            $ar->insert();
        }
    }

    /**
     * validate() вызывается раньше save(), поэтому
     * грант устанавливается здесь
     *
     * @return bool
     */
    public function validate() {
        $this->_fields = $this->grant;
        $this->grant = new CGrant();
        $this->grant->setAttributes($this->_fields);
        return $this->grant->validate();
    }
}