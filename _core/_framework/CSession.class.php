<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 09.05.12
 * Time: 0:01
 * To change this template use File | Settings | File Templates.
 *
 * Сессия текущего пользователя.
 */
class CSession {
    private static $_user = null;
    private static $_person = null;

    /**
     * Объект текущего пользователя
     *
     * @static
     * @return CUser
     */
    public static function getCurrentUser(){
        if (is_null(self::$_user)) {
            if (self::isAuth()) {
                $user = CStaffManager::getUserById(self::getUserId());
                if (!is_null($user)) {
                    self::$_user = $user;
                }
            }
        }
        return self::$_user;
    }
    /**
     * Объект сотрудника текущего пользователя
     *
     * @static
     * @return CPerson
     */
    public static function getCurrentPerson() {
        if (is_null(self::$_person)) {
            if (self::isAuth()) {
                $person = CStaffManager::getPersonById(self::getPersonId());
                if (!is_null($person)) {
                    self::$_person = $person;
                }
            }
        }
        return self::$_person;
    }
    /**
     * Проверка, является ли текущий пользователь авторизованным
     *
     * @static
     * @return bool
     */
    public static function isAuth() {
        $s = self::getSession();
        if (!isset($s['auth'])) {
            return false;
        } elseif ($s['auth'] != 1) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * Инициализация сессии, в случае необходимости
     *
     * @static
     */
    private static function initSession() {
        if (!isset($_SESSION)) {
            session_start();
        }
    }
    /**
     * @static
     * @return array
     */
    private static function getSession() {
        self::initSession();
        return $_SESSION;
    }
    /**
     * id пользователя из сессии
     *
     * @static
     * @return mixed
     */
    private static function getUserId() {
        $s = self::getSession();
        return $s['id'];
    }
    /**
     * id сотрудника из сессии
     *
     * @static
     * @return mixed
     */
    private static function getPersonId() {
        $s = self::getSession();
        return $s['kadri_id'];
    }

    /**
     * @return CUserRole
     */
    public static function getCurrentTask() {
        $self = $_SERVER["PHP_SELF"];
        $root_folder = CSettingsManager::getSettingValue("root_folder");
        $self = str_replace($root_folder, "", $self);
        if (substr($self, 0, 1) == "/") {
            $self = substr($self, 1);
        }
        /**
         * Начинаем поиск задач, походящих под описание. Если не находим,
         * то отрезаем справа /
         */
        $queryCount = 0;
        $tasks = CActiveRecordProvider::getWithCondition(TABLE_USER_ROLES, "url='".$self."'");
        while ($tasks->getCount() == 0 && strlen($self) > 1 && $queryCount <= 5) {
            $self = CUtils::strLeftBack($self, "/");
            if (substr($self, strlen($self) - 1) != "/") {
                $self .= "/";
            }
            $tasks = CActiveRecordProvider::getWithCondition(TABLE_USER_ROLES, "url='".$self."'");
            $queryCount++;
        }
        if ($tasks->getCount() > 0) {
            foreach ($tasks->getItems() as $ar) {
                $task = new CUserRole($ar);
            }
        }
        return $task;
    }
}
