<?php
class CIndPlanPersonLoadScience extends CActiveModel {
    protected $_table = TABLE_IND_PLAN_LOAD_SCIENCE;

    protected $_worktype = null;

    protected function relations() {
        return array(
            "worktype" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_worktype",
                "storageField" => "id_vidov_rabot",
                "managerClass" => "CIndPlanManager",
                "managerGetObject" => "getWorktype"
            )
        );
    }
}