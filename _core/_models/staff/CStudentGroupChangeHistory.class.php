<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 29.01.14
 * Time: 22:23
 * To change this template use File | Settings | File Templates.
 */

class CStudentGroupChangeHistory extends CActiveModel{
    protected $_table = TABLE_STUDENT_GROUP_HISTORY;
    protected $_source;
    protected $_target;
    protected $_person;

    public function relations() {
        return array(
            "source" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_source",
                "storageField" => "source_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudentGroup"
            ),
            "target" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_target",
                "storageField" => "target_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudentGroup"
            ),
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "person_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
        );
    }
}