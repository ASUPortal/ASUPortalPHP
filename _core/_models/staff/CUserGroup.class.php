<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 11.06.12
 * Time: 17:26
 * To change this template use File | Settings | File Templates.
 */
class CUserGroup extends CActiveModel {
    private $_roles = null;
    protected $_table = TABLE_USER_GROUPS;
    protected $_childGroups = null;
    protected $_users = null;
    protected $_members = null;
    protected $_aclRelations = null;
    private $_allUsers = null;
    public function relations() {
        return array(
            "childGroups" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_childGroups",
                "joinTable" => TABLE_USER_GROUPS_HIERARCHY,
                "leftCondition" => "group_id = ". $this->id,
                "rightKey" => "child_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getUserGroup"
            ),
            "users" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_users",
                "joinTable" => TABLE_USER_IN_GROUPS,
                "leftCondition" => "group_id = ". $this->id,
                "rightKey" => "user_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getUser"
            ),
            "members" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_members",
                "relationFunction" => "getMembers"
            ),
            "aclRelations" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_aclRelations",
                "relationFunction" => "getACLRelations"
            )
        );
    }
    public static function getClassName() {
        return __CLASS__;
    }
    /**
     * Роли, которые связаны с данной группой
     *
     * @return CArrayList
     */
    public function getRoles() {
        if (is_null($this->_roles)) {
            $this->_roles = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_GROUP_HAS_ROLES, "user_group_id=".$this->getId())->getItems() as $ar) {
                $role = CStaffManager::getUserRole($ar->getItemValue("task_id"));
                if (!is_null($role)) {
                    $this->_roles->add($role->getId(), $role);
                }
            }
        }
        return $this->_roles;
    }

    /**
     * Название группы.
     *
     * @return mixed|null
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Члены группы
     *
     * @return CArrayList|null
     */
    public function getMembers() {
        if (is_null($this->_members)) {
            $this->_members = new CArrayList();
            foreach ($this->users->getItems() as $user) {
                $this->_members->add($this->_members->getCount(), $user);
            }
            foreach ($this->childGroups->getItems() as $group) {
                $this->_members->add($this->_members->getCount(), $group);
            }
        }
        return $this->_members;
    }

    /**
     * Тип объекта для ACL
     *
     * @return int
     */
    public function getType() {
        return 2;
    }

    /**
     * Записи, связанные с ACL для создания иерархии. Все сразу
     *
     * @return CArrayList|null
     */
    public function getACLRelations() {
        if (is_null($this->_aclRelations)) {
            $this->_aclRelations = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_IN_GROUPS, "group_id=".$this->getId())->getItems() as $item) {
                $model = new CActiveModel($item);
                $this->_aclRelations->add($this->_aclRelations->getCount(), $model);
            }
            foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_GROUPS_HIERARCHY, "group_id=".$this->getId())->getItems() as $item) {
                $model = new CActiveModel($item);
                $this->_aclRelations->add($this->_aclRelations->getCount(), $model);
            }
        }
        return $this->_aclRelations;
    }

    /**
     * Пользователи из всех групп, включая дочерние
     *
     * @return CArrayList|null
     */
    public function getUsersInHierarchy() {
        if (is_null($this->_allUsers)) {
            $this->_allUsers = new CArrayList();
            foreach ($this->users->getItems() as $user) {
                $this->_allUsers->add($user->getId(), $user);
            }
            foreach ($this->childGroups->getItems() as $group) {
                foreach ($group->getUsersInHierarchy()->getItems() as $user) {
                    $this->_allUsers->add($user->getId(), $user);
                }
            }
        }
        return $this->_allUsers;
    }
}
