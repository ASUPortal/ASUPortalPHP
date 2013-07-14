<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 14:35
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelField extends CActiveModel{
    protected $_table = TABLE_CORE_MODEL_FIELDS;
    protected $_translations = null;

    public $model_id;

    public function relations() {
        return array(
            "translations" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_translations",
                "storageTable" => TABLE_CORE_MODLE_FIELD_TRANSLATIONS,
                "storageCondition" => "field_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CCoreObjectsManager",
                "managerGetObject" => "getCoreModelFieldTranslation"
            ),
        );
    }
}