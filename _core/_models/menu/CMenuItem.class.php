<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 08.06.12
 * Time: 13:09
 * To change this template use File | Settings | File Templates.
 */
class CMenuItem extends CActiveModel {
    protected $_table = TABLE_MENU_ITEMS;

    private $_childs = null;
    private $_parent = null;
    private $_menu = null;
    protected $_roles = null;

    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "anchor" => "Ссылка",
            "parent_id" => "Родительский пункт меню",
            "order" => "Порядковый номер",
            "icon" => "Значок",
            "published" => "Опубликован",
            "roles" => "Роли, которым доступен данный пункт"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title",
                "anchor"
            )
        );
    }
    protected function relations() {
        return array(
            "roles" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_roles",
                "joinTable" => TABLE_MENU_ITEMS_ACCESS,
                "leftCondition" => "item_id = ". $this->id,
                "rightKey" => "role_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getUserRole"
            )
        );
    }
    /**
     * Дочерние к текущему пункты меню
     *
     * @return CArrayList
     */
    public function getChilds() {
        if (is_null($this->_childs)) {
            $this->_childs = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_MENU_ITEMS, "parent_id=".$this->getId())->getItems() as $i) {
                $item = new CMenuItem($i);
                $this->_childs->add($item->getId(), $item);
            }
            /*
            // сортируем детей
            $sort = new CArrayList();
            foreach ($this->_childs->getItems() as $i) {
                $sort->add($i->getOrder()."_".$i->getId(), $i->getId());
            }
            $res = new CArrayList();
            foreach ($sort->getSortedByKey(false)->getItems() as $i) {
                $item = $this->_childs->getItem($i);
                $res->add($item->getId(), $item);
            }
            $this->_childs = $res;
            */
        }
        return $this->_childs;
    }
    /**
     * Инициализация приватной переменной для автоматического построения иерархии надо
     */
    public function initChilds() {
        if (is_null($this->_childs)) {
            $this->_childs = new CArrayList();
        }
    }
    public function getParentId() {
        return $this->getRecord()->getItemValue("parent_id");
    }
    public function isPublished() {
        if ($this->getRecord()->getItemValue("published") == "1") {
            return true;
        } else {
            return false;
        }
    }
    public function setPublished($value) {
        if ($value) {
            $this->getRecord()->setItemValue("published", "1");
        } else {
            $this->getRecord()->setItemValue("published", "0");
        }
    }
    public function getLink() {
        // проверяем, абсолютный это адрес или относительный
        if (strpos($this->getRecord()->getItemValue("anchor"), "http://") === false) {
            if (strpos($this->getRecord()->getItemValue("anchor"), "ftp://") === false) {
                return WEB_ROOT.$this->getRecord()->getItemValue("anchor");
            }
            return $this->getRecord()->getItemValue("anchor");
        } else {
            return $this->getRecord()->getItemValue("anchor");
        }
    }
    public function setLink($value) {
        $this->getRecord()->setItemValue("anchor", $value);
    }
    public function getName() {
        return $this->getRecord()->getItemValue("title");
    }
    public function setName($value) {
        $this->getRecord()->setItemValue("title", $value);
    }
    public function getParent() {
        if (is_null($this->_parent)) {
            if ($this->getParentId() != 0) {
                $parent = CMenuManager::getMenuItem($this->getParentId());
                if (!is_null($parent)) {
                    $this->_parent = $parent;
                }
            }
        }
        return $this->_parent;
    }
    public function setParent(CMenuItem $value) {
        $this->_parent = $value;
        $this->setParentId($value->getId());
    }
    public function setParentId($value) {
        $this->getRecord()->setItemValue("parent_id", $value);
    }
    public function setMenu(CMenu $value) {
        $this->_menu = $value;
        $this->getRecord()->setItemValue("menu_id", $value->getId());
    }
    /**
     * Меню, к которому данный пункт привязан
     *
     * @return CMenu
     */
    public function getMenu() {
        if (is_null($this->_menu)) {
            $this->_menu = CMenuManager::getMenu($this->getRecord()->getItemValue("menu_id"));
        }
        return $this->_menu;
    }
    public function setIcon($value) {
        $this->getRecord()->setItemValue("icon", $value);
    }
    public function getIcon() {
        return $this->getRecord()->getItemValue("icon");
    }
    public function setOrder($value) {
        $this->getRecord()->setItemValue("order", $value);
    }
    public function getOrder() {
        return $this->getRecord()->getItemValue("order");
    }
    /**
     * Удаление пункта меню. Если у него есть дочки, то их
     * надо перепривязать к родителю
     */
    public function remove() {
        $parent = $this->getParent();
        // если вдруг это родительский пункт меню - перестраиваем все меню
        if (is_null($parent)) {
            foreach ($this->getChilds()->getItems() as $i) {
                $i->setParentId(0);
                $i->save();
            }
        }  else {
            foreach ($this->getChilds()->getItems() as $i) {
                $i->setParent($parent);
                $i->save();
            }
        }

        parent::remove();
    }
    /**
     * Добавление дочернего пункта меню
     *
     * @param CMenuItem $item
     */
    public function addChild(CMenuItem $item) {
        if (is_null($this->_childs)) {
            $this->_childs = new CArrayList();
        }
        $this->_childs->add($item->getId(), $item);
    }
    /**
     * Лист ролей, которыми должен обладать пользователь, чтобы
     * видеть данный пункт меню (как минимум, одной)
     *
     * @return CArrayList
     */
    public function getRoles() {
        if (is_null($this->_roles)) {
            if (!CApp::getApp()->cache->hasCache("menu_item_access_".$this->getId())) {
                $this->_roles = new CArrayList();
                foreach (CActiveRecordProvider::getWithCondition(TABLE_MENU_ITEMS_ACCESS, "item_id = " . $this->getId())->getItems() as $item) {
                    $role = CStaffManager::getUserRole($item->getItemValue("role_id"));
                    if (!is_null($role)) {
                        $this->_roles->add($role->getId(), $role);
                    }
                }
                CApp::getApp()->cache->set("menu_item_access_".$this->getId(), $this->_roles, 300);
            }
            $this->_roles = CApp::getApp()->cache->get("menu_item_access_".$this->getId());
        }
        return $this->_roles;
    }
}
