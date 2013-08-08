<?php
class CIndPlanPersonPublication extends CActiveModel {
    protected $_table = TABLE_IND_PLAN_PUBLICATIONS;

    protected $_publication;

    public $id_year;
    public $id_kadri;

    protected function relations() {
        return array(
            "publication" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_publication",
                "storageField" => "paper_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPublication"
            )
        );
    }
}