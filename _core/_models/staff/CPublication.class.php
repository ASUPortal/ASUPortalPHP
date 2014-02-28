<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 25.09.12
 * Time: 10:05
 * To change this template use File | Settings | File Templates.
 */
class CPublication extends CActiveModel {
    protected $_table = TABLE_PUBLICATIONS;
    protected $_authors = null;
    protected $_type = null;

    public function relations() {
        return array(
            "authors" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_authors",
                "joinTable" => TABLE_PUBLICATION_BY_PERSONS,
                "leftCondition" => "izdan_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "kadri_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "type" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_type",
                "relationFunction" => "getType"
            ),
        );
    }

    public function fieldsProperty() {
        return array(
            "copy" => array(
                "type" => FIELD_UPLOADABLE,
                "upload_dir" => CORE_CWD.CORE_DS."library".CORE_DS."izdan".CORE_DS
            )
        );
    }
    public function getType() {
        if (is_null($this->_type)) {
            $this->_type = CTaxonomyManager::getLegacyTerm($this->type_book, "izdan_type");
        }
        return $this->_type;
    }
}
