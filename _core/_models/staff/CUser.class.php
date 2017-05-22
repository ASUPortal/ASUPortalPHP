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
    protected $_table = TABLE_USERS;
    private $_person = null;
    protected $_roles = null;
    protected $_groups = null;
    private $_settings = null;
    private $_unreadMessages = null;
    protected $_subscription = null;
    public function attributeLabels() {
        return array(
            "FIO" => "ФИО",
            "FIO_short" => "ФИО (краткое)",
            "login" => "Логин",
            "kadri_id" => "Сотрудник кафедры",
            "comment" => "Комментарий",
            "groups" => "Группы",
            "photo" => "Фотография"
        );
    }
    public function relations() {
        return array(
            "groups" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_groups",
                "relationFunction" => "getGroups"
            ),
            "roles" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_roles",
                "relationFunction" => "getRoles"
            )
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "FIO",
                "login"
            )
        );
    }
    public function fieldsProperty() {
        return array(
            "photo" => array(
                "type"  => FIELD_UPLOADABLE,
                "upload_dir" => CORE_CWD.CORE_DS."images".CORE_DS."lects".CORE_DS
            )
        );
    }
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
    				if (!$this->_roles->hasElement($role->getId())) {
    					$this->_roles->add($role->getId(), $role);
    				} else {
    					$currentRole = $this->_roles->getItem($role->getId());
    					if ($role->level > $currentRole->level) {
    						$this->_roles->add($role->getId(), $role);
    					}
    				}
    			}
    		}
    		// теперь смотрим, какие личные права он имеет
    		if (!is_null($this->getId())) {
    			foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_HAS_ROLES, "user_id=".$this->getId())->getItems() as $ar) {
                    $role = CStaffManager::getUserRole($ar->getItemValue("task_id"));
                    if (!is_null($role)) {
                        $role->level = $ar->getItemValue("task_rights_id");
                        /**
                         * Можно запретить доступ пользователю к конкретной задаче

                        if ($role->level == ACCESS_LEVEL_NO_ACCESS) {
                            $role->level = ACCESS_LEVEL_READ_OWN_ONLY;
                        }
                         */
                        $this->_roles->add($role->getId(), $role);
                    }
                }
    		}
    	}
    	return $this->_roles;
    }

    /**
     * Уровень доступа к текущей задаче
     *
     * @return int
     */
    
    public function getLevelForCurrentTask() {
    	$level = ACCESS_LEVEL_NO_ACCESS;
    	$task = CSession::getCurrentTask();
    	if (!is_null($task)) {
    		if ($this->getRoles()->hasElement($task->getId())) {
    			$personTask = $this->getRoles()->getItem($task->getId());
    			$level = $personTask->level;
    		}
    	}
    	return $level;
    }

    /**
     * Проверяем, обладает ли пользователь указанной ролью.
     *
     * @param $roleToFind
     * @return bool
     */
    public function hasRole($roleToFind) {
        $hasRole = false;
        foreach ($this->getRoles()->getItems() as $role) {
            if (!is_null($role->alias)) {
                if (mb_strtoupper($role->alias) == mb_strtoupper($roleToFind)) {
                    $hasRole = true;
                }
            }
        }
        return $hasRole;
    }
    /**
     * Группы, в которых пользователь состоит
     *
     * @return CArrayList
     */
    public function getGroups() {
        if (is_null($this->_groups)) {
            $this->_groups = new CArrayList();
            if (!is_null($this->getId())) {
                foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_IN_GROUPS, "user_id=".$this->getId())->getItems() as $ar) {
                    $group = CStaffManager::getUserGroup($ar->getItemValue("group_id"));
                    if (!is_null($group)) {
                        $this->_groups->add($group->getId(), $group);
                    }
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
            foreach (CActiveRecordProvider::getWithCondition(TABLE_MESSAGES, "read_status = 0 and to_user_id = ".$this->getId()." and mail_type='in'")->getItems() as $item) {
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
    public function remove() {
        /**
         * Удаляем записи о том, где пользователь состоял
         */
        foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_IN_GROUPS, "user_id = ".$this->getId())->getItems() as $ar) {
            $ar->remove();
        }
        /**
         * Удаляем личные права
         */
        foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_HAS_ROLES, "user_id = ".$this->getId())->getItems() as $ar) {
            $ar->remove();
        }
        /**
         * Удаляем самого пользователя
         */
        parent::remove();
    }

    /**
     * Подписка на сообщения электронной почты
     *
     * @return CSubscription
     */
    public function getSubscription() {
        if (is_null($this->_subscription)) {
            foreach (CActiveRecordProvider::getWithCondition(TABLE_SUBSCRIPTIONS, "user_id=".$this->getId())->getItems() as $ar) {
                $this->_subscription = new CSubscription($ar);
            }
        }
        return $this->_subscription;
    }
}
