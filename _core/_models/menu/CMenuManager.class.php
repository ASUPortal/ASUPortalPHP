<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 08.06.12
 * Time: 12:23
 * To change this template use File | Settings | File Templates.
 */
class CMenuManager {
    private static $_cacheMenu = null;
    private static $_cacheInit = false;
    private static $_cacheItems = null;

    /**
     * Кэш меню
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheMenu() {
        if (is_null(self::$_cacheMenu)) {
            self::$_cacheMenu = new CArrayList();
        }
        return self::$_cacheMenu;
    }
    /**
     * Кэш пунктов меню
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheItems() {
        if (is_null(self::$_cacheItems)) {
            self::$_cacheItems = new CArrayList();
        }
        return self::$_cacheItems;
    }
    /**
     * Меню по ключу или псевдониму
     *
     * @static
     * @param $key
     * @return CMenu
     */
    public static function getMenu($key) {
        if (!self::getCacheMenu()->hasElement($key)) {
            if (strtolower($key) == "admin_menu") {
                $menu = self::getTasksMenu();
                self::getCacheMenu()->add($menu->getId(), $menu);
                self::getCacheMenu()->add($menu->getAlias(), $menu);
            } elseif (is_numeric($key)) {
                $ar = CActiveRecordProvider::getById(TABLE_MENUS, $key);
                if (!is_null($ar)) {
                    $menu = new CMenu($ar);
                    self::getCacheMenu()->add($menu->getId(), $menu);
                    self::getCacheMenu()->add($menu->getAlias(), $menu);
                }
            } elseif (is_string($key)){
                $ar = CActiveRecordProvider::getWithCondition(TABLE_MENUS, "alias = '".$key."'");
                if ($ar->getCount() > 0) {
                    foreach ($ar->getItems() as $i) {
                        $menu = new CMenu($i);
                        self::getCacheMenu()->add($menu->getId(), $menu);
                        self::getCacheMenu()->add($menu->getAlias(), $menu);
                    }
                }
            }
        }
        return self::getCacheMenu()->getItem($key);
    }
    /**
     * Пункт меню с кэшем
     *
     * @static
     * @param $key
     * @return CMenuItem
     */
    public static function getMenuItem($key) {
        if (!self::getCacheItems()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_MENU_ITEMS, $key);
            if (!is_null($ar)) {
                $item = new CMenuItem($ar);
                self::getCacheItems()->add($item->getId(), $item);
            }
        }
        return self::getCacheItems()->getItem($key);
    }
    /**
     * Все меню
     *
     * @static
     * @return CArrayList
     */
    public static function getAllMenus() {
        if (!self::$_cacheInit) {
            self::$_cacheInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_MENUS)->getItems() as $i) {
                $menu = new CMenu($i);
                self::getCacheMenu()->add($menu->getId(), $menu);
                self::getCacheMenu()->add($menu->getAlias(), $menu);
            }
        }
        $arr = new CArrayList();
        foreach (self::getCacheMenu()->getItems() as $menu) {
            $arr->add($menu->getId(), $menu);
        }
        return $arr;
    }
    /**
     * Меню задач для текущего пользователя.
     * Возвращаются только доступные ему задачи
     *
     * @static
     * @return CMenu
     */
    private static function getTasksMenu() {
        if (!self::getCacheMenu()->hasElement("admin_menu")) {
            $user = CSession::getCurrentUser();
            /**
             * Чтобы не выбиваться из общей концепции придется адаптировать существующую таблицу
             * задач портала под вывод в виде меню. Также было бы неплохо сделать редактор этой
             * гадости в виде такого же простого вида, как и с моими меню
             *
             * 1. Создадим фиктивную activeRecord из моего меню для ссылки в другое меню
             * 2. Берем из таблицы группы меню (из task_menu_names)
             * 3. Выберем пункты меню из нужной таблицы и добавим их как обычные пункты.
             * 4. Добавим недостающие
             * 5. Исключим недоступные текущему пользователю
			 *
			 * Обновление от 06.02.2014 - все пункты меню хранятся в базе
             */
            // дополнительный пункт "Прочее"
            $arr = array();
            $arr['id'] = 100000;
            $arr['title'] = "Меню админки";
            $arr['alias'] = "admin_menu";
            $arr['description'] = "Фиктивное меню для отображения задач портала";
            $arr['published'] = 1;
            $menu = new CMenu(new CActiveRecord($arr));

            foreach (CActiveRecordProvider::getWithCondition("task_menu_names", "1=1 order by name desc")->getItems() as $i) {
                $arr = $i->getItems();

                // адаптер для моей системы меню
                $arr['id'] = $arr['id'] + 100000;
                $arr['title'] = $arr['name'];
                $arr['anchor'] = "#";
                $arr['menu_id'] = $menu->getId();
                $arr['parent_id'] = 0;

                $i->setItems($arr);

                $menuItem = new CMenuItem($i);
                $menu->addMenuItem($menuItem);
            }

            // дополнительный пункт "Прочее"
            $arr = array();
            $arr['id'] = 100000;
            $arr['title'] = CUtils::getTextStringInCorrectEncoding("Прочее");
            $arr['anchor'] = "#";
            $arr['menu_id'] = $menu->getId();
            $arr['parent_id'] = 0;
            $menuItem = new CMenuItem(new CActiveRecord($arr));
            $menu->addMenuItem($menuItem);
            
            foreach (CActiveRecordProvider::getWithCondition("tasks", "hidden=0 order by name asc")->getItems() as $i) {
                $arr = $i->getItems();

                // адаптер для совместимости с моей системой
                $arr['title'] = $arr['name'];
                $arr['anchor'] = $arr['url'];
                $arr['parent_id'] = $arr['menu_name_id'] + 100000;
                $arr['menu_id'] = $menu->getId();

                unset($arr['name']);
                unset($arr['url']);
                unset($arr['menu_name_id']);
                unset($arr['hidden']);
                unset($arr['kadri_in_task']);
                unset($arr['students_in_task']);
                unset($arr['comment']);

                $i->setItems($arr);
                $menuItem = new CMenuItem($i);
                $menu->addMenuItem($menuItem);
            }

            // дополнительный пункт меню "Выход"
            $arr = array();
            $arr['id'] = 200000;
            $arr['title'] = CUtils::getTextStringInCorrectEncoding("Выход");
            $arr['anchor'] = "p_administration.php?exit=1";
            $arr['menu_id'] = $menu->getId();
            $arr['parent_id'] = 0;
            $menuItem = new CMenuItem(new CActiveRecord($arr));
            $menu->addMenuItem($menuItem);

            // теперь исключаем все задачи недоступные текущему пользователю
            foreach ($menu->getMenuItems()->getItems() as $menuItem) {
                if ($menuItem->getId() < 100000) {
                    if (!$user->getRoles()->hasElement($menuItem->getId())) {
                        $menu->getMenuItems()->removeItem($menuItem->getId());
                    }
                }
            }

            self::getCacheMenu()->add("admin_menu", $menu);
        }
        return self::getCacheMenu()->getItem("admin_menu");
    }
}
