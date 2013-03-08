<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 04.01.13
 * Time: 18:27
 * To change this template use File | Settings | File Templates.
 */
class CACLManager {
    private static $_cacheTables = null;

    /**
     * Кэш таблиц доступа
     *
     * @return CArrayList|null
     */
    private static function getCacheTables() {
        if (is_null(self::$_cacheTables)) {
            self::$_cacheTables = new CArrayList();
        }
        return self::$_cacheTables;
    }

    /**
     * Таблица доступа
     *
     * @param $key
     * @return CACLTable
     */
    public static function getACLTable($key) {
        if (!self::getCacheTables()->hasElement($key)) {
            if (is_numeric($key)) {
                $item = CActiveRecordProvider::get2ById(TABLE_ACL_TABLES, $key);
                if (!is_null($item)) {
                    $table = new CACLTable($item);
                    self::getCacheTables()->add($key, $table);
                }
            } elseif (is_string($key)) {
                foreach (CActiveRecordProvider::getWithCondition(TABLE_ACL_TABLES, "`table`='".$key."'")->getItems() as $item) {
                    $table = new CACLTable($item);
                    self::getCacheTables()->add($key, $table);
                }
            }
        }
        return self::getCacheTables()->getItem($key);
    }

    /**
     * Обновить таблицы контроля доступа
     *
     * @param CActiveModel $model
     * @param array $entries
     * @param int $level
     */
    public static function updateACLList(CActiveModel $model, array $entries, $level = 1) {
        // 1. удаляем старые записи доступа
        foreach (CActiveRecordProvider::getWithCondition($model->getRecord()->getTable().ACL_ENTRIES, "object_id=".$model->getId())->getItems() as $item) {
            $obj = new CActiveModel($item);
            $obj->remove();
        }
        foreach (CActiveRecordProvider::getWithCondition($model->getRecord()->getTable().ACL_USERS, "object_id=".$model->getId())->getItems() as $item) {
            $obj = new CActiveModel($item);
            $obj->remove();
        }
        // 2. создаем новые записи уровня сущностей
        foreach ($entries["id"] as $key=>$value) {
            $entry = new CActiveModel();
            $entry->getRecord()->setTable($model->getRecord()->getTable().ACL_ENTRIES);
            $entry->object_id = $model->getId();
            $entry->level = $level;
            $entry->entry_type = $entries["type"][$key];
            $entry->entry_id = $value;
            $entry->save();
        }
        // 3. создаем новые записи уровня пользователей
        // для начала получим полный список реальных пользователей
        $users = new CArrayList();
        foreach ($entries["id"] as $key=>$value) {
            if ($entries["type"][$key] == ACL_ENTRY_USER) {
                $user = CStaffManager::getUser($value);
                if (!is_null($user)) {
                    $users->add($user->getId(), $user);
                }
            } elseif ($entries["type"][$key] == ACL_ENTRY_GROUP) {
                $group = CStaffManager::getUserGroup($value);
                if (!is_null($group)) {
                    foreach ($group->getUsersInHierarchy()->getItems() as $user) {
                        $users->add($user->getId(), $user);
                    }
                }
            }
        }
        // теперь создаем для них записи
        foreach ($users->getItems() as $user) {
            $entry = new CActiveModel();
            $entry->getRecord()->setTable($model->getRecord()->getTable().ACL_USERS);
            $entry->object_id = $model->getId();
            $entry->level = $level;
            $entry->user_id = $user->getId();
            $entry->save();
        }
    }
}
