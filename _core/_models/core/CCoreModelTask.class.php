<?php
class CCoreModelTask extends CActiveModel {
    protected $_table = TABLE_CORE_MODEL_TASKS;
    protected $_task = null;

    public function relations() {
        return array(
            "task" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_task",
                "storageField" => "task_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getUserRole"
            )
        );
    }
}