<?php

class CUserRoleGroup extends CActiveModel {
    protected $_table = TABLE_USER_GROUP_HAS_ROLES;
    protected $_task = null;
    protected $_group = null;
    
    protected function relations() {
    	return array(
    		"task" => array(
    				"relationPower" => RELATION_HAS_ONE,
    				"storageProperty" => "_task",
    				"storageField" => "task_id",
    				"managerClass" => "CStaffManager",
    				"managerGetObject" => "getUserRole"
    		),
    		"group" => array(
    				"relationPower" => RELATION_HAS_ONE,
    				"storageProperty" => "_group",
    				"storageField" => "user_group_id",
    				"managerClass" => "CStaffManager",
    				"managerGetObject" => "getUserGroup"
    		)
    	);
    }
}
