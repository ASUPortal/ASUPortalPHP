<?php
/**
 * Учебная нагрузка по видам
 */

class CIndPlanPersonWork extends CActiveModel implements IVersionControl {
    protected $_table = TABLE_WORKLOAD_WORKS;
    protected $_workload = null;

    public function relations() {
        return array(
            "workload" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_workload",
                "storageField" => "workload_id",
                "managerClass" => "CIndPlanManager",
                "managerGetObject" => "getLoad"
            ),
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "type_id",
                "targetClass" => "CIndPlanWorktype"
            ),
            "kind" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "kind_id",
                "targetClass" => "CTerm"
            )
        );
    }
}
