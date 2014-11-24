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
    public $changePassword = 0;
    public $newPassword = "";
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
        if ($this->changePassword == 1) {
            if ($this->newPassword !== "") {
                $userObj->password = md5($this->newPassword);
            }
        }
        $userObj->save();
        $this->user = $userObj;
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
         * Исключаем из личных прав пользователей те, которые совпадают с правами
         * на задачу
         *
         * 08.11.2014
         * Больше так не делаем - пусть система работает тривиально. Если это
         * личные права, то они до конца личные
         * 
        foreach ($userObj->getGroups()->getItems() as $group) {
            foreach ($group->getRoles()->getItems() as $role) {
                if (array_key_exists($role->getId(), $roles)) {
                    if ($role->level == $roles[$role->getId()]) {
                        unset($roles[$role->getId()]);
                    }
                }
            }
        }
         */
        /**
         * Удаляем старые и сохраняем отличающиеся
         */
        foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_HAS_ROLES, "user_id = ".$userObj->getId())->getItems() as $ar) {
            $ar->remove();
        }
        /**
         * Сохраняем личные права пользователя
         */
        foreach ($roles as $role=>$level) {
            /**
             * Можно индивидуально запрещать доступ к задаче
             */
            // if ($level != 0) {
                $ar = new CActiveRecord(array(
                    "id" => null,
                    "user_id" => $userObj->getId(),
                    "task_id" => $role,
                    "task_rights_id" => $level
                ));
                $ar->setTable(TABLE_USER_HAS_ROLES);
                $ar->insert();
            // }
        }
    }
}