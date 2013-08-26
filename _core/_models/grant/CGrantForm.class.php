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
    public function save() {
        $this->grant->save();
        /**
         * Работа с участниками
         */
        if (array_key_exists("members", $this->grant)) {
            $members = array();
            $members = $this->grant["members"];
            /**
             * Удаляем старых участников
             */
            foreach (CActiveRecordProvider::getWithCondition(TABLE_GRANT_MEMBERS, "grant_id= ".$grant->getId())->getItems() as $ar) {
                $ar->remove();
            }
            /**
             * Добавляем новых
             */
            foreach ($members as $member) {
                $ar = new CActiveRecord(array(
                    "id" => null,
                    "grant_id" => $grant->getId(),
                    "person_id" => $member
                ));
                $ar->setTable(TABLE_GRANT_MEMBERS);
                $ar->insert();
            }
        }
    }

    /**
     * validate() вызывается раньше save(), поэтому
     * грант устанавливается здесь
     *
     * @return bool
     */
    public function validate() {
        $fields = $this->grant;
        $this->grant = new CGrant();
        $this->grant->setAttributes($fields);
        return $this->grant->validate();
    }
}