<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 05.05.12
 * Time: 9:58
 * To change this template use File | Settings | File Templates.
 *
 * С одной стороны, это будут менеджеры разного рода, с другой стороны, это
 * Identity Map для обеспечения синхронизации объектов во всем приложении
 */
class CActiveRecordProvider {
    protected static $_inst = null;
    protected static $_cache = null;
    /**
     * Синглтон провайдера записей
     *
     * @static
     * @return CActiveRecordProvider
     */
    public static function getInstance() {
        if (is_null(self::$_inst)) {
            self::$_inst = new CActiveRecordProvider();
        }
        return self::$_inst;
    }
    /**
     * Синглтон кэша
     *
     * @static
     * @return CArrayList
     */
    protected static function getCache() {
        if (is_null(self::$_cache)) {
            self::$_cache = new CArrayList();
        }
        return self::$_cache;
    }
    /**
     * Возвращает запись из базы
     *
     * @static
     * @param string $table
     * @param string $id
     * @return CActiveRecord
     */
    public static function getById($table, $id) {
        $key = $table."_".$id;
        if (!CApp::getApp()->getCache()->hasCache($key)) {
            $q = new CQuery();
            $r = $q->select("*")
                ->from($table)
                ->condition("id=".$id)
                ->execute();
            if ($r->getCount() == 1) {
                $record = new CActiveRecord($r->getItem(0));
                $record->setTable($table);
                CApp::getApp()->getCache()->set($key, $record);
            }
        }
        return CApp::getApp()->getCache()->get($key);
    }
    /**
     * Лист записей из какой-либо таблицы
     *
     * @static
     * @param string $table
     * @return CRecordSet
     */
    public static function getAllFromTable($table, $order = null) {
        $q = new CQuery();
        $res = new CRecordSet();
        $r = $q->select("*")
        ->from($table);
        if (!is_null($order)) {
        	$r->order($order);
        }
        $res->setQuery($r);

        return $res;
    }
    /**
     * Лист записей из какой-либо таблицы по определенному условию
     *
     * @static
     * @param $table
     * @param $condition
     * @return CRecordSet
     */
    public static function getWithCondition($table, $condition, $order = null) {
        $key = $table."_".$condition."_".$order;
        if (!self::getCache()->hasElement($key)) {
            $set = new CRecordSet();
            $query = new CQuery();
            $query->select("*")->from($table)->condition($condition);
            if (!is_null($order)) {
                $query->order($order);
            }
            $set->setQuery($query);
            self::getCache()->add($key, $set);
        }
        return self::getCache()->getItem($key);
        /*
        $key = $table."_".$condition."_".$order;
        if (!CApp::getApp()->getCache()->hasCache($key)) {
            $q = new CQuery();
            $res = new CArrayList();
            $q->select("*")
                ->from($table)
                ->condition($condition);

            if (!is_null($order)) {
                $q->order($order);
            }

            $r = $q->execute();

            foreach ($r->getItems() as $item) {
                $record = new CActiveRecord($item);
                $record->setTable($table);
                $res->add($res->getCount(), $record);
                CApp::getApp()->getCache()->set($record->getTable()."_".$record->getId(), $record);
            }

            CApp::getApp()->getCache()->set($key, $res);
        }

        $set = new CRecordSet();
        $res = CApp::getApp()->getCache()->get($key);
        foreach ($res->getItems() as $item) {
            $set->add($set->getCount(), $item);
        }
        return $set;
        */
    }

    /**
     * Уникальные по заданному ключу и условию записи из таблицы
     *
     * @param $table
     * @param $condition
     * @param $field
     * @return CRecordSet
     */
    public static function getDistinctWithCondition($table, $condition, $field) {
        $key = $table."_".$condition."_distinct_".$field;
        if (!CApp::getApp()->getCache()->hasCache($key)) {
            $q = new CQuery();
            $res = new CRecordSet();
            $res->setManualAdd(true);
            $q->select("DISTINCT(".$field."), id")
                ->from($table)
                ->condition($condition." GROUP BY ".$field);
            $r = $q->execute();
            foreach ($r->getItems() as $item) {
                $record = new CActiveRecord($item);
                $record->setTable($table);
                $distinct = self::getById($table, $record->getId());
                $res->add($res->getCount(), $distinct);
            }

            CApp::getApp()->getCache()->set($key, $res);
        }
        return CApp::getApp()->getCache()->get($key);
    }
    public static function removeFromCache($table, $id) {
        self::getCache()->removeItem($table."_".$id);
    }

    /**
     * Получить все записи из таблицы с учетом прав доступа
     *
     * @param $table
     * @param string $order
     * @return CRecordSet
     */
    public static function get2AllFromTable($table, $order = "") {
        $set = new CRecordSet();
        $q = new C2Query();
        $q = $q->select("*", $table)
            ->join("INNER", $table.ACL_USERS, $table.".id = ".$table.ACL_USERS.".object_id AND user_id = ".CSession::getCurrentUser()->getId()." AND ".$table.ACL_USERS.".level = 1");
        $set->setQuery($q);
        return $set;
    }

    /**
     * Получить запись из таблицы с учетом прав доступа
     *
     * @param $table
     * @param $id
     * @return CActiveRecord|null
     */
    public static function get2ById($table, $id) {
        $q = new C2Query();
        $q = $q->select("*", $table)
            ->join("INNER", $table.ACL_USERS, $table.".id = ".$table.ACL_USERS.".object_id AND user_id = ".CSession::getCurrentUser()->getId()." AND ".$table.ACL_USERS.".level = 1")
            ->where($table.".id=".$id);
        $res = $q->execute();
        $record = null;
        if ($res->getCount() > 0) {
            $record = new CActiveRecord($res->getItem(0));
            $record->setTable($table);
        }
        return $record;
    }
}
