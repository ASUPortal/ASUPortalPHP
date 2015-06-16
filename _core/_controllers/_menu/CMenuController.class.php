<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 08.06.12
 * Time: 12:21
 *
 *
 * Контроллер пунктов меню
 */
class CMenuController extends CBaseController {
    public function __construct() {
        $this->_smartyEnabled = true;
        parent::__construct();
    }
    /**
     * Главная страница со списком меню
     */
    public function actionIndex() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        // все доступные меню
        $this->setData("menus", CMenuManager::getAllMenus()->getItems());
        $this->renderView("_menumanager/index.tpl");
    }
    /**
     * Добавление нового меню
     */
    public function actionAdd() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->renderView("_menumanager/add.tpl");
    }
    /**
     * Сохранение меню
     */
    public function actionSave() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        if (CRequest::getInt("id") == 0) {
            $menu = CFactory::createMenu();
        } else {
            $menu = CMenuManager::getMenu(CRequest::getInt("id"));
        }

        $menu->setName(CRequest::getString("name"));
        $menu->setAlias(CRequest::getString("alias"));
        $menu->setDescription(CRequest::getString("description"));
        $menu->setPublished(CRequest::getInt("published") == 1);
        $menu->save();

        $this->redirect("?action=index");
    }
    /**
     * Сохранение пункта меню
     */
    public function actionSaveItem() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $item = new CMenuItem();
        $item->setAttributes(CRequest::getArray(CMenuItem::getClassName()));
        if ($item->validate()) {
            $item->save();
            // сохранение ролей. удаляем старые, записываем новые
            foreach (CActiveRecordProvider::getWithCondition(TABLE_MENU_ITEMS_ACCESS, "item_id = ".$item->id)->getItems() as $val) {
                $val->remove();
            }
            // делаем новые и сохраняем их
            $items = CRequest::getArray(CMenuItem::getClassName());
            if (array_key_exists("roles", $items)) {
                foreach ($items["roles"] as $role) {
                    $r = new CMenuItemRole();
                    $r->item_id = $item->id;
                    $r->role_id = $role;
                    $r->save();
                }
            }
            $this->redirect("?action=view&id=".$item->getMenu()->getId());
        }
        $this->setData("item", $item);
        $this->setData("menu", CMenuManager::getMenu(CRequest::getInt("menu_id", CMenuItem::getClassName())));
        $this->renderView("_menumanager/editItem.tpl");
    }
    public function actionView() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $menu = CMenuManager::getMenu(CRequest::getInt("id"));
        if (is_null($menu)) {
            $this->redirectNoAccess();
        }

        $this->setData("menu", $menu);
        $this->renderView("_menumanager/view.tpl");
    }
    /**
     * Редактирование меню
     */
    public function actionEdit() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->setData("menu", CMenuManager::getMenu(CRequest::getInt("id")));
        $this->renderView("_menumanager/edit.tpl");
    }
    public function actionAddItem() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $item = new CMenuItem();
        $item->menu_id = CRequest::getInt("id");
        $this->setData("item", $item);
        $this->setData("menu", CMenuManager::getMenu(CRequest::getInt("id")));
        $this->renderView("_menumanager/addItem.tpl");
    }
    public function actionRemove() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $menu = CMenuManager::getMenu(CRequest::getInt("id"));
        foreach ($menu->getMenuItems()->getItems() as $i) {
            $i->remove();
        }
        $menu->remove();
        $this->redirect("?action=index");
    }
    public function actionViewItem() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $item = CMenuManager::getMenuItem(CRequest::getInt("id"));
        $this->setData("item", $item);
        $this->setData("menu", $item->getMenu());
        $this->renderView("_menumanager/viewItem.tpl");
    }
    public function actionEditItem() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $item = CMenuManager::getMenuItem(CRequest::getInt("id"));
        $this->setData("item", $item);
        $this->setData("menu", $item->getMenu());
        $this->renderView("_menumanager/editItem.tpl");
    }
    public function actionRemoveItem() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $item = CMenuManager::getMenuItem(CRequest::getInt("id"));
        $id = $item->getMenu()->getId();
        $item->remove();

        $this->redirect("?action=view&id=".$id);
    }
}