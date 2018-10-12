<?php
/**
 * Студенческая группа из нагрузки
 */

class CStudyLoadGroup extends CActiveModel implements IVersionControl {
    protected $_table = TABLE_WORKLOAD_STUDY_GROUPS;
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
            "group" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "group_id",
                "targetClass" => "CStudentGroup"
            )
        );
    }
    
}
