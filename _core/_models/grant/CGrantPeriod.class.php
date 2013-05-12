<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 12.05.13
 * Time: 15:04
 * To change this template use File | Settings | File Templates.
 */

class CGrantPeriod extends CActiveModel{
    protected $_table = TABLE_GRANT_PERIODS;
    protected $_money = null;
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "comment" => "Комментарий",
            "date_start" => "Дата начала",
            "date_end" => "Дата окончания"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title"
            )
        );
    }
    public function relations() {
        return array(
            "money" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_money",
                "storageTable" => TABLE_GRANT_MONEY,
                "storageCondition" => "period_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CGrantManager",
                "managerGetObject" => "getMoney"
            )
        );
    }

    /**
     * @return string
     */
    public function getPeriod() {
        $nv = "";
        if ($this->date_start !== "") {
            $nv = "с ".date("d.m.Y", strtotime($this->date_start));
        }
        if ($this->date_end !== "") {
            if ($nv == "") {
                $nv = "по ".date("d.m.Y", strtotime($this->date_end));
            } else {
                $nv .= " по ".date("d.m.Y", strtotime($this->date_end));
            }
        }
        return $nv;
    }
}