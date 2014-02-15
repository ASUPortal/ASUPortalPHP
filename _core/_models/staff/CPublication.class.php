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
            )
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
}
