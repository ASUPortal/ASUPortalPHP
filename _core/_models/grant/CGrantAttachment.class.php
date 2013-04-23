<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 18.04.13
 * Time: 21:36
 * To change this template use File | Settings | File Templates.
 */

class CGrantAttachment extends CActiveModel {
    protected $_table = TABLE_GRANT_ATTACHMENTS;
    protected $_author = null;
    public function relations() {
        return array(
            "author" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_author",
                "storageField" => "author_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            )
        );
    }
}