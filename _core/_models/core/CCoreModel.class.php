<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 14:01
 * To change this template use File | Settings | File Templates.
 */

class CCoreModel extends CActiveModel {
    protected $_table = TABLE_CORE_MODELS;
    protected $_fields = null;

    protected function relations() {
        return array(
            "fields" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_fields",
                "storageTable" => TABLE_CORE_MODEL_FIELDS,
                "storageCondition" => "model_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModelField"
            ),
        );
    }
}