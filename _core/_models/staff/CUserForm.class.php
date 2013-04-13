<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 13.04.13
 * Time: 13:33
 * To change this template use File | Settings | File Templates.
 */

class CUserForm extends CFormModel{
    public $user;
    public function attributeLabels() {
        return array(
            "changePassword" => "Изменить пароль",
            "newPassword" => "Новый пароль"
        );
    }

    /**
     * Сохранение данных поручим модели чтобы
     * контроллер не нагружать
     */
    public function save() {
        /**
         * Извлечем все данные из текущей модели.
         * Мы будем сохранять их по отдельности
         */
        $user = $this->user;
        $roles = array();
        $groups = array();
        if (array_key_exists("groups", $user)) {
            $groups = $user["groups"];
            unset($user["groups"]);
        }
        if (array_key_exists("roles", $user)) {
            $roles = $user["roles"];
            unset($user["roles"]);
        }
        $userObj = new CUser();
        $userObj->setAttributes($user);
        $userObj->save();
        /**
         * Удаляем старые упоминания о группах, в которых
         * пользователь состоял
         */
        foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_IN_GROUPS, "user_id = ".$userObj->getId())->getItems() as $ar) {
            $ar->remove();
        }
        foreach ($groups as $group) {
            $ar = new CActiveRecord(array(
                "user_id" => $userObj->getId(),
                "group_id" => $group,
                "id" => null
            ));
            $ar->setTable(TABLE_USER_IN_GROUPS);
            $ar->insert();
        }
        /**
         * Сохраняем личные права пользователя
         */
        foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_HAS_ROLES, "user_id = ".$userObj->getId())->getItems() as $ar) {
            $ar->remove();
        }
        foreach ($roles as $role) {
            $ar = new CActiveRecord(array(
                "id" => null,
                "user_id" => $userObj->getId(),
                "task_id" => $role,
                "task_rights_id" => 4
            ));
            $ar->setTable(TABLE_USER_HAS_ROLES);
            $ar->insert();
        }
    }
}