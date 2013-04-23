<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 08.06.12
 * Time: 12:26
 * To change this template use File | Settings | File Templates.
 */
class CMenu extends CActiveModel {
    private $_items = null;
    private $_itemsHierarchy = null;
    private $_itemsPublished = null;
    private $_itemsPublishedHierarchy = null;

    public function getAlias() {
        return $this->getRecord()->getItemValue("alias");
    }
    public function getName() {
        return $this->getRecord()->getItemValue("title");
    }
    public function getDescription() {
        return $this->getRecord()->getItemValue("description");
    }
    public function isPublished() {
        if ($this->getRecord()->getItemValue("published") == "1") {
            return true;
        } else {
            return false;
        }
    }
    public function setName($value) {
        $this->getRecord()->setItemValue("title", $value);
    }
    public function setAlias($value) {
        $this->getRecord()->setItemValue("alias", $value);
    }
    public function setDescription($value) {
        $this->getRecord()->setItemValue("description", $value);
    }
    public function setPublished($value) {
        if ($value) {
            $this->getRecord()->setItemValue("published", "1");
        } else {
            $this->getRecord()->setItemValue("published", "0");
        }
    }
    /**
     * Все пункты текущего меню с учетом иерархии
     *
     * @return CArrayList
     */
    public function getMenuItems() {
        if (is_null($this->_items)) {
            $this->_items = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_MENU_ITEMS, "menu_id=".$this->getId())->getItems() as $i) {
                $item = new CMenuItem($i);
                $this->_items->add($item->getId(), $item);
                CMenuManager::getCacheItems()->add($item->getId(), $item);
            }
            // исключительно вспомогательная операция, сократит обращения к базе
            // в последующем
            $this->getMenuItemsInHierarchy();
        }
        return $this->_items;
    }
    /**
     * Все опубликованные пункты текущего меню с учетом иерархии
     * Показываются только опубликованные и доступные текущему пользователю
     *
     * @return CArrayList
     */
    public function getMenuPublishedItems() {
        if (is_null($this->_itemsPublished)) {
            $this->_itemsPublished = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_MENU_ITEMS, "menu_id=".$this->getId()." and published=1")->getItems() as $i) {
                $item = new CMenuItem($i);
                if ($item->getRoles()->getCount() == 0) {
                    $this->_itemsPublished->add($item->getId(), $item);
                } else {
                    if (is_null(CSession::getCurrentUser())) {
                        if ($item->getRoles()->getCount() == 0) {
                            $this->_itemsPublished->add($item->getId(), $item);
                        }
                    } else {
                        foreach ($item->getRoles()->getItems() as $role) {
                            if (CSession::getCurrentUser()->getRoles()->hasElement($role->getId())) {
                                $this->_itemsPublished->add($item->getId(), $item);
                                break;
                            }
                        }
                    }
                }
                CMenuManager::getCacheItems()->add($item->getId(), $item);
            }
            // инициализируем иерархический вывод
        }
        return $this->_itemsPublished;
    }
    /**
     * Пункты меню в виде иерархии
     *
     * @return CArrayList
     */
    public function getMenuItemsInHierarchy() {
        if (is_null($this->_itemsHierarchy)) {
            $this->_itemsHierarchy = new CArrayList();
            foreach ($this->getMenuItems()->getItems() as $i) {
                $i->initChilds();
                if ($i->getParentId() != 0) {
                    $parent = $this->getMenuItems()->getItem($i->getParentId());
                    if (!is_null($parent)) {
                        $parent->getChilds()->add($i->getId(), $i);
                    }
                } else {
                    $this->_itemsHierarchy->add($i->getId(), $i);
                }
            }
        }
        return $this->_itemsHierarchy;
    }
    /**
     * Опубликованные пункты меню в виде иерархии
     *
     * @return CArrayList
     */
    public function getMenuPublishedItemsInHierarchy() {
        if (is_null($this->_itemsPublishedHierarchy)) {
            $this->_itemsPublishedHierarchy = new CArrayList();
            if ($this->alias == "admin_menu") {
                foreach ($this->getMenuPublishedItems()->getItems() as $i) {
                    $i->initChilds();
                    if ($i->getParentId() != 0) {
                        $parent = $this->getMenuPublishedItems()->getItem($i->getParentId());
                        if (!is_null($parent)) {
                            if (CSession::getCurrentUser()->getRoles()->hasElement($i->getId()) || $i->getId() > 200000) {
                                $parent->getChilds()->add($i->getId(), $i);
                            }
                        }
                    } else {
                        $this->_itemsPublishedHierarchy->add($i->getId(), $i);
                    }
                }
            } else {
                foreach ($this->getMenuPublishedItems()->getItems() as $i) {
                    $i->initChilds();
                    if ($i->getParentId() != 0) {
                        $parent = $this->getMenuPublishedItems()->getItem($i->getParentId());
                        if (!is_null($parent)) {
                            $parent->getChilds()->add($i->getId(), $i);
                        }
                    } else {
                        $this->_itemsPublishedHierarchy->add($i->getId(), $i);
                    }
                }
            }
        }
        return $this->_itemsPublishedHierarchy;
    }
    /**
     * Все пункты меню в виде плоского массива
     *
     * @return array
     */
    public function getMenuItemsList() {
        $ar = array();
        foreach ($this->getMenuItems()->getItems() as $i) {
            $ar[$i->getId()] = $i->getName();
        }
        return $ar;
    }
    /**
     * Пункты меню в виде плоского списка со ссылками
     *
     * @return array
     */
    public function getMenuLinksList() {
        $ar = array();
        foreach ($this->getMenuItems()->getItems() as $i) {
            $ar[$i->getLink()] = $i->getName();
        }
        return $ar;
    }
    /**
     * Добавление в меню пункта.
     * Сразу строит иерархию при наличии родительского пункта меню
     *
     * @param CMenuItem $item
     */
    public function addMenuItem(CMenuItem $item) {
        if (is_null($this->_items)) {
            $this->_items = new CArrayList();

        }
        if (is_null($this->_itemsPublished)) {
            $this->_itemsPublished = new CArrayList();
        }
        $this->_items->add($item->getId(), $item);
        $this->_itemsPublished->add($item->getId(), $item);
    }
    /**
     * Очистка списка пунктов меню
     */
    public function removeAllItems() {
        $this->_items = new CArrayList();
    }
}
