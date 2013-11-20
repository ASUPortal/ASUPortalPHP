<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 04.11.13
 * Time: 18:40
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanPersonWork extends CActiveModel{
    protected $_table = TABLE_IND_PLAN_WORKS;
    public $load_id;
    public $work_type;
    public $is_executed = 0;
    public $separate_contract = 0;
    protected $_load = null;
    protected $_workType = null;
    protected $_publication = null;

    public function relations() {
        return array(
            "load" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_load",
                "storageField" => "load_id",
                "managerClass" => "CIndPlanManager",
                "managerGetObject" => "getLoad"
            ),
            "workType" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_workType",
                "storageField" => "title_id",
                "managerClass" => "CIndPlanManager",
                "managerGetObject" => "getWorktype"
            ),
            "publication" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_publication",
                "storageField" => "title_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPublication"
            )
        );
    }
    public function getTitle() {
        $result = "";
        if ($this->work_type == "2") {
            if (!is_null($this->workType)) {
                $result = $this->workType->name;
            }
        } elseif ($this->work_type == "3") {
            if (!is_null($this->workType)) {
                $result = $this->workType->name;
            }
        } elseif ($this->work_type == "4") {
            if (!is_null($this->workType)) {
                $result = $this->workType->name;
            }
        } elseif ($this->work_type == "5") {
            if (!is_null($this->publication)) {
                $result = $this->publication->name;
            }
        }
        return $result;
    }
    public function isExecuted() {
        if ($this->is_executed == 1) {
            return "Да";
        }
        return "Нет";
    }
}