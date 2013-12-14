<?php
class CCoreModelValidator extends CActiveModel {
    protected $_table = TABLE_CORE_MODEL_VALIDATORS;
    protected $_validator;
    public $model_id;

    protected function relations() {
        return array(
            "validator" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_validator",
                "storageField" => "validator_id",
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreValidator"
            )
        );
    }
}