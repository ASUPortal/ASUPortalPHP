<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 19:26
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelFieldValidator extends CActiveModel {
    protected $_table = TABLE_CORE_MODEL_FIELD_VALIDATORS;
    protected $_field;
    protected $_validator;

    public $field_id;

    protected function relations() {
        return array(
            "field" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_field",
                "storageField" => "field_id",
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModelField"
            ),
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