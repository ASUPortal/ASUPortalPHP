<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.05.12
 * Time: 0:28
 * To change this template use File | Settings | File Templates.
 *
 * Пользователь приложения
 */
class CUser extends CActiveModel {
    private $_person = null;
    private $_roles = null;
    private $_groups = null;
    private $_settings = null;
    private $_unreadMessages = null;
    /**
     * Статус пользователя
     *
     * @return mixed
     */
    public function getStatus() {
        return $this->getRecord()->getItemValue("status");
    }
    /**
     * Список всех ролей, которыми пользователь обладает
     *
     * @return CArrayList
     */
    public function getRoles() {
        if (is_null($this->_roles)) {
            $this->_roles = new CArrayList();
            // сначала глянем, в какие группы входит пользователь и какие роли он получил от них
            foreach ($this->getGroups()->getItems() as $group) {
                foreach ($group->getRoles()->getItems() as $role) {
                    $this->_roles->add($role->getId(), $role);
                }
            }
            // теперь смотрим, какие личные права он имеет
            foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_HAS_ROLES, "user_id=".$this->getId())->getItems() as $ar) {
                $role = CStaffManager::getUserRole($ar->getItemValue("task_id"));
                if (!is_null($role)) {
                    $this->_roles->add($role->getId(), $role);
                }
            }
        }
        return $this->_roles;
    }
    /**
     * Группы, в которых пользователь состоит
     *
     * @return CArrayList
     */
    public function getGroups() {
        if (is_null($this->_groups)) {
            $this->_groups = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_IN_GROUPS, "user_id=".$this->getId())->getItems() as $ar) {
                $group = CStaffManager::getUserGroup($ar->getItemValue("group_id"));
                if (!is_null($group)) {
                    $this->_groups->add($group->getId(), $group);
                }
            }
        }
        return $this->_groups;
    }
    public function getName() {
        return $this->getRecord()->getItemValue("FIO");
    }
    public function getLogin() {
        return $this->getRecord()->getItemValue("login");
    }
    /**
     * Сотрудник, с которым связан пользователь
     *
     * @return CPerson
     */
    public function getPerson() {
        if (is_null($this->_person)) {
            if ($this->getRecord()->getItemValue("kadri_id") != 0) {
                $person = CStaffManager::getPersonById($this->getRecord()->getItemValue("kadri_id"));
                if (!is_null($person)) {
                    $this->_person = $person;
                }
            }
        }
        return $this->_person;
    }

    /**
     * Личные настройки пользователя
     *
     * @return CUserSettings|null
     */
    public function getPersonalSettings() {
        if (is_null($this->_settings)) {
            $this->_settings = CStaffManager::getUserSettingsByUser($this->getId());
        }
        return $this->_settings;
    }

    /**
     * Непрочитанные сообщения
     *
     * @return CArrayList|null
     */
    public function getUnreadMessages() {
        if (is_null($this->_unreadMessages)) {
            $this->_unreadMessages = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_MESSAGES, "read_status = 0 and to_user_id = ".$this->getId())->getItems() as $item) {
                $msg = new CMessage($item);
                $this->_unreadMessages->add($msg->getId(), $msg);
            }
        }
        return $this->_unreadMessages;
    }

    /**
     * Тип объекта для ACL
     *
     * @return int
     */
    public function getType() {
        return 1;
    }
}
