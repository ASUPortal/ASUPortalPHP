<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 31.03.13
 * Time: 17:08
 * To change this template use File | Settings | File Templates.
 */

class CLibraryDocument extends CActiveModel {
    protected $_table = TABLE_LIBRARY_DOCUMENTS;
    protected $_subject = null;
    protected $_person = null;

    public function relations() {
        return array(
            "subject" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_subject",
                "storageField" => "subj_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiscipline"
            ),
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "user_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            )
        );
    }

    /**
     * @return mixed|null
     */
    public function getFolderId() {
        return $this->nameFolder;
    }
}