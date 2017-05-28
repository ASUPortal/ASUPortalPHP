<?php
/**
 * Учебная нагрузка по видам
 */

class CStudyLoadWork extends CActiveModel {
    protected $_table = TABLE_WORKLOAD_WORKS;
    protected $_workload = null;

    public function relations() {
        return array(
            "studyLoad" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_workload",
                "storageField" => "workload_id",
                "managerClass" => "CBaseManager",
                "managerGetObject" => "getStudyLoad"
            ),
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "type_id",
                "targetClass" => "CStudyLoadWorkType"
            ),
            "kind" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "kind_id",
                "targetClass" => "CTerm"
            )
        );
    }
}
