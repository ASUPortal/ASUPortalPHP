<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 13.04.13
 * Time: 20:20
 * To change this template use File | Settings | File Templates.
 */

class CUserGroupForm extends CFormModel{
    public $group = null;
    public function save() {
        $group = $this->group;
        $roles = array();
        $members = array();
        if (array_key_exists("roles", $group)) {
            $roles = $group["roles"];
            unset($group["roles"]);
        }
        if (array_key_exists("members", $group)) {
            $members = $group["members"];
            unset($group["members"]);
        }
        $groupObj = new CUserGroup();
        $groupObj->setAttributes($group);
        $groupObj->save();
        /**
         * Удаляем старые задачи группы и пользователей
         */
        foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_GROUP_HAS_ROLES, "user_group_id = ".$groupObj->getId())->getItems() as $ar) {
            $ar->remove();
        }
        foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_IN_GROUPS, "group_id = ".$groupObj->getId())->getItems() as $ar) {
            $ar->remove();
        }
        /**
         * Создаем новые задачи группы и пользователей
         */
        foreach ($roles as $role=>$level) {
            if ($level != 0) {
                $ar = new CActiveRecord(array(
                    "id" => null,
                    "user_group_id" => $groupObj->getId(),
                    "task_id" => $role,
                    "task_rights_id" => $level
                ));
                $ar->setTable(TABLE_USER_GROUP_HAS_ROLES);
                $ar->insert();
            }
        }
        foreach ($members as $member) {
            $ar = new CActiveRecord(array(
                "id" => null,
                "group_id" => $groupObj->getId(),
                "user_id" => $member
            ));
            $ar->setTable(TABLE_USER_IN_GROUPS);
            $ar->insert();
        }
    }
}