<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.04.13
 * Time: 12:41
 * To change this template use File | Settings | File Templates.
 */

class CSABCommission extends CActiveModel {
    protected $_table = TABLE_SAB_COMMISSIONS;
    protected $_members = null;
    protected $_students = null;
    protected $_manager = null;
    protected $_secretar = null;
    protected $_diploms = null;
    protected $_year = null;

    public function relations() {
        return array(
            "members" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_members",
                "joinTable" => TABLE_SAB_COMMISSION_MEMBERS,
                "leftCondition" => "commission_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "person_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "diploms" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_diploms",
                "joinTable" => TABLE_SAB_COMMISSION_DIPLOMS,
                "leftCondition" => "commission_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "diplom_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getDiplom"
            ),
            "manager" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_manager",
                "storageField" => "manager_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "secretar" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_secretar",
                "storageField" => "secretar_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "year" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_year",
                "storageField" => "year_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getYear"
            )
        );
    }
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "comment" => "Описание",
            "year_id" => "Учебный год",
            "secretar_id" => "Секретарь",
            "manager_id" => "Председатель комиссии",
            "members" => "Члены комиссии"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title"
            )
        );
    }
    public function remove() {
        foreach (CActiveRecordProvider::getWithCondition(TABLE_SAB_COMMISSION_MEMBERS, "commission_id=".$this->getId())->getItems() as $ar) {
            $ar->remove();
        }
        parent::remove();
    }
}