<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 01.04.13
 * Time: 9:53
 * To change this template use File | Settings | File Templates.
 */

class CGrant extends CActiveModel{
    protected $_table = TABLE_GRANTS;
    protected $_members = null;
    protected $_attachments = null;
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "comment" => "Комментарий",
            "date_start" => "Дата начала",
            "date_end" => "Дата окончания",
            "organizer" => "Организатор"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title",
                "organizer"
            )
        );
    }
    public function relations() {
        return array(
            "members" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_members",
                "joinTable" => TABLE_GRANT_MEMBERS,
                "leftCondition" => "grant_id = ".(is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "person_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "attachments" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_attachments",
                "storageTable" => TABLE_GRANT_ATTACHMENTS,
                "storageCondition" => "grant_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CGrantManager",
                "managerGetObject" => "getAttachment"
            )
        );
    }
    public function fieldsProperty() {
        return array(
            "upload" => array(
                "type" => FIELD_UPLOADABLE,
                "upload_dir" => CORE_CWD.CORE_DS.'library'.CORE_DS.'grants'.CORE_DS
            )
        );
    }
}