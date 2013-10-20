<?php
class CCoreModelTask extends CActiveModel {
    protected $_table = TABLE_CORE_MODEL_TASKS;
    protected $_task = null;
    protected $_model = null;

    public function relations() {
        return array(
            "task" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_task",
                "storageField" => "task_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getUserRole"
            ),
            "model" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_model",
                "storageField" => "model_id",
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModel"
            )
        );
    }
}