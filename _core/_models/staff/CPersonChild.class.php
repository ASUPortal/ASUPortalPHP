<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 27.04.13
 * Time: 16:01
 * To change this template use File | Settings | File Templates.
 */

class CPersonChild extends CActiveModel{
    protected $_table = TABLE_PERSON_CHILDREN;
    protected $_gender = null;
    public function relations() {
        return array(
            "gender" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_gender",
                "storageField" => "pol_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getGender"
            )
        );
    }
    public function attributeLabels() {
        return array(
            "pol_id" => "Пол",
            "birth_date" => "Дата рождения"
        );
    }
    public function getBirthDate() {
        if ($this->birth_date !== "0000-00-00") {
            return date("d.m.Y", strtotime($this->birth_date));
        } else {
            return "";
        }
    }
    public function getAge() {
        if ($this->getBirthDate() !== "") {
            return floor ((strtotime(date("d.m.Y")) - strtotime($this->birth_date)) / (86400 * 365));
        }
    }
}