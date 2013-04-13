<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 11.06.12
 * Time: 17:22
 * To change this template use File | Settings | File Templates.
 */
class CUserRole extends CActiveModel {
    protected $_table = TABLE_USER_ROLES;
    protected $_menu = null;
    public function getName() {
        return $this->getRecord()->getItemValue("name");
    }
    public function relations() {
        return array(
            "menu" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_menu",
                "storageField" => "menu_name_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTitle"
            )
        );
    }
}
