<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 04.05.12
 * Time: 22:19
 * To change this template use File | Settings | File Templates.
 *
 * Отвечает за работу с персоналом и пользователями портала
 */

class CStaffManager{
    private static $_fullInit = false;
    private static $_cachePerson = null;
    private static $_cacheUsers = null;
    private static $_cacheUsersInit = false;
    private static $_cacheGroups = null;
    private static $_cacheGroupsInit = false;
    private static $_cacheStudents = null;
    private static $_cacheStudentsInit = false;
    private static $_cacheRoles = null;
    private static $_cacheUserGroups = null;
    private static $_cacheUserGroupsInit = false;
    private static $_cacheOrders = null;
    private static $_cacheRecoveryRequests = null;
    private static $_cachePublications = null;
    private static $_cacheDegrees = null;
    private static $_cacheStudentActivities = null;
    private static $_cacheUserSettings = null;
    private static $_bDaysThisWeek = null;
    private static $_cacheMessages = null;
    private static $_cacheGradebooks = null;
    private static $_cacheGradebookItems = null;
    private static $_cacheDiploms = null;
    private static $_cacheDiplomPreviews = null;
    private static $_cacheDiplomPreviewComissions = null;
    private static $_cacheUsatuOrders = null;
    /**
     * Инициализация всех сотрудников.
     *
     * @static
     */
    public static function initPerson() {
        if (!self::$_fullInit) {
            self::$_fullInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_PERSON)->getItems() as $item) {
                $person = new CPerson($item);
                self::getCachePerson()->add($person->getId(), $person);
            }
        }
    }
    /**
     * Все студенческие группы
     *
     * @static
     * @return CArrayList
     */
    public static function getAllStudentGroups() {
        if (!self::$_cacheGroupsInit) {
            self::$_cacheGroupsInit = true;
            foreach (CActiveRecordProvider::getWithCondition(TABLE_STUDENT_GROUPS, "1=1", "name asc")->getItems() as $ar) {
                $group = new CStudentGroup($ar);
                self::getCacheGroups()->add($group->getId(), $group);
            }
        }
        return self::getCacheGroups();
    }
    /**
     * Учебные группы по году
     *
     * @static
     * @param CTerm $year
     * @return CArrayList
     */
    public static function getStudentGroupsByYear(CTerm $year) {
        $groups = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_STUDENT_GROUPS, "year_id=".$year->getId(), "name asc")->getItems() as $ar) {
            $group = new CStudentGroup($ar);
            $groups->add($group->getId(), $group);
            self::getCacheGroups()->add($group->getId(), $group);
        }
        return $groups;
    }
    /**
     * Список студенческих групп для подстановки.
     *
     * @static
     * @return array
     */
    public static function getAllStudentGroupsList() {
        $arr = array();
        foreach (self::getAllStudentGroups()->getItems() as $group) {
            $arr[$group->getId()] = $group->getName()." (".$group->getYear()->getValue().")";
        }
        return $arr;
    }

    /**
     * Кэш ученых степеней, привязанных к сотруднику
     * @return CArrayList|null
     */
    private static function getCacheDegrees() {
        if (is_null(self::$_cacheDegrees)) {
            self::$_cacheDegrees = new CArrayList();
        }
        return self::$_cacheDegrees;
    }
    /**
     * Кэш учебных групп
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheGroups() {
        if (is_null(self::$_cacheGroups)) {
            self::$_cacheGroups = new CArrayList();
        }
        return self::$_cacheGroups;
    }
    /**
     * @static
     * @return CArrayList
     */
    private static function getCachePublications() {
        if (is_null(self::$_cachePublications)) {
            self::$_cachePublications = new CArrayList();
        }
        return self::$_cachePublications;
    }
    /**
     * @static
     * @return CArrayList
     */
    private static function getCacheRecoveryRequests() {
        if (is_null(self::$_cacheRecoveryRequests)) {
            self::$_cacheRecoveryRequests = new CArrayList();
        }
        return self::$_cacheRecoveryRequests;
    }
    /**
     * @static
     * @return CArrayList
     */
    private static function getCacheOrders() {
        if (is_null(self::$_cacheOrders)) {
            self::$_cacheOrders = new CArrayList();
        }
        return self::$_cacheOrders;
    }
    /**
     * Кэш студентов
     *
     * @static
     * @return CArrayList
     */
    public static function getCacheStudents() {
        if (is_null(self::$_cacheStudents)) {
            self::$_cacheStudents = new CArrayList();
        }
        return self::$_cacheStudents;
    }
    /**
     * Получение сотрудника по идентификатору
     *
     * @static
     * @param $id
     * @return CPerson
     */
    public static function getPersonById($id) {
        if (!self::getCachePerson()->hasElement($id)) {
            $rec = CActiveRecordProvider::getById(TABLE_PERSON, $id);
            if (!is_null($rec)) {
                $person = new CPerson($rec);
                self::getCachePerson()->add($person->getId(), $person);
                self::getCachePerson()->add($person->getName(), $person);
                self::getCacheGroups()->add($person->e_mail, $person);
            }
        };
        return self::getCachePerson()->getItem($id);
    }
    /**
     * Получение сотрудника по полю
     *
     * @static
     * @param $key
     * @return CPerson
     */
    public static function getPerson($key) {
        if (!self::getCachePerson()->hasElement($key)) {
            if (is_numeric($key)) {
                return self::getPersonById($key);
            } elseif (is_string($key)) {
                $person = null;
                foreach (CActiveRecordProvider::getWithCondition(TABLE_PERSON, "fio='".$key."' or fio_short='".$key."'")->getItems() as $item) {
                    $person = new CPerson($item);
                    return self::getPersonById($person->getId());
                }
                if (is_null($person)) {
                    foreach (CActiveRecordProvider::getWithCondition(TABLE_PERSON, "e_mail='".$key."'")->getItems() as $item) {
                        $person = new CPerson($item);
                        return self::getPersonById($person->getId());
                    }
                }
            }
        }
        return self::getCachePerson()->getItem($key);
    }
    /**
     * Строит организационную структуру
     *
     * @static
     */
    public static function buildPersonHierarchy() {
        if (!self::$_fullInit) {
            self::initPerson();
            self::orderPersonsByName();
        }
        foreach (self::getCachePerson()->getItems() as $item) {
            $item->initSubordinators();
            if ($item->getManagerId() != 0) {
                $parent = self::getPersonById($item->getManagerId());
                if (!is_null($parent)) {
                    $parent->addSubordinator($item);
                }
            }
        }
    }
    /**
     * Инициализация типов участия на кафедре сотрудников
     * для всех сотрудников, находящихся в кеше
     *
     * @static
     */
    public static function initPersonTypes() {
        foreach (CActiveRecordProvider::getAllFromTable("kadri_in_ptypes")->getItems() as $item) {
            if (self::getCachePerson()->hasElement($item->getItemValue("kadri_id"))) {
                $person = self::getCachePerson()->getItem($item->getItemValue("kadri_id"));
                $person->initTypes();
                $term = CTaxonomyManager::getTypeById($item->getItemValue("person_type_id"));
                if (!is_null($term)) {
                    $person->getTypes()->add($term->getId(), $term);
                }
            }
        }
    }
    /**
     * Кэш сотрудников
     *
     * @static
     * @return CArrayList
     */
    public static function getCachePerson() {
        if (is_null(self::$_cachePerson)) {
            self::$_cachePerson = new CArrayList();
        }
        return self::$_cachePerson;
    }
    /**
     * Сортирует сотрудников по имени.
     *
     * @static
     */
    private static function orderPersonsByName() {
        if (!self::$_fullInit) {
            self::$_fullInit;
        }
        // отсортировать листы уже не так-то просто.
        // создадим новый массив с ФИО вместо ключа и id вместо
        // значения. отсортируем его и затем отсортируем id
        // и все это в новый лист сотрудников вместо старого
        $arr = array();
        foreach (self::getCachePerson()->getItems() as $item) {
            $arr[$item->getId()] = $item->getName();
        }
        asort($arr);
        $res = new CArrayList();
        foreach ($arr as $key=>$value) {
            $res->add(self::getCachePerson()->getItem($key)->getId(), self::getCachePerson()->getItem($key));
        }
        self::$_cachePerson = $res;
    }
    /**
     * Лист сотрудников для полей автоподстановки
     *
     * @static
     * @return array
     */
    public static function getPersonsList() {
        self::initPerson();
        self::orderPersonsByName();
        $res = array();
        foreach (self::getCachePerson()->getItems() as $item) {
            $res[$item->getId()] = $item->getName();
        }
        return $res;
    }
    /**
     * @static
     * @return CArrayList
     */
    public static function getAllPersons() {
        self::initPerson();
        self::orderPersonsByName();
        return self::getCachePerson();
    }
    /**
     * Лист сотрудников для полей автоподстановки,
     * обладающих определенным типом участия на кафедре
     *
     * @static
     * @param $type
     * @return array
     */
    public static function getPersonsListWithType($type) {
        self::initPerson();
        self::initPersonTypes();
        self::orderPersonsByName();
        $res = array();
        foreach (self::getCachePerson()->getItems() as $item) {
            if ($item->hasPersonType($type)) {
                $res[$item->getId()] = $item->getName();
            }
        }
        return $res;
    }
    /**
     * Лист сотрудников, обладающих как минимум одной из ролей
     *
     * @static
     * @param CArrayList $types
     * @return CArrayList
     */
    public static function getPersonsWithTypes(CArrayList $types) {
        $res = new CArrayList();
        foreach ($types->getItems() as $type) {
            $byType = self::getPersonsListWithType($type);
            foreach ($byType as $p) {
                $person = self::getPerson($p);
                if (!is_null($person)) {
                    $res->add($person->getId(), $person);
                }
            }
        }
        return $res;
    }
    /**
     * Кэш пользователей
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheUsers() {
        if (is_null(self::$_cacheUsers)) {
            self::$_cacheUsers = new CArrayList();
        }
        return self::$_cacheUsers;
    }
    /**
     * Достать пользователя по id
     *
     * @static
     * @param $id
     * @return CUser
     */
    public static function getUserById($id) {
        if (!self::getCacheUsers()->hasElement($id)) {
            $item = CActiveRecordProvider::getById(TABLE_USERS, $id);
            if (!is_null($item)) {
                $user = new CUser($item);
                self::getCacheUsers()->add($user->getId(), $user);
                self::getCacheUsers()->add($user->getName(), $user);
                self::getCacheUsers()->add($user->getLogin(), $user);
            }
        }
        return self::getCacheUsers()->getItem($id);
    }

    /**
     * Ученая степень преподавателя по id ученой степени
     *
     * @param $key
     * @return CDegree
     */
    public static function getDegree($key) {
        if (!self::getCacheDegrees()->hasElement($key)) {
            foreach (CActiveRecordProvider::getWithCondition(TABLE_PERSON_DISSER, "id = ".$key." AND disser_type = 'степень'")->getItems() as $obj) {
                $degree = new CDegree($obj);
                self::getCacheDegrees()->add($degree->getId(), $degree);
            }
        }
        return self::getCacheDegrees()->getItem($key);
    }
    /**
     * Достать пользователя по id, логину или почте
     *
     * @static
     * @param $key
     * @return CUser
     */
    public static function getUser($key) {
        if (!self::getCacheUsers()->hasElement($key)) {
            if (is_numeric($key)) {
                return self::getUserById($key);
            } elseif (is_string($key)) {
                // пробуем найти по логину, мылу кадра или фио
                $user = null;
                foreach (CActiveRecordProvider::getWithCondition(TABLE_USERS, "login='".$key."'")->getItems() as $item) {
                    $user = new CUser($item);
                    self::getCacheUsers()->add($user->getId(), $user);
                    self::getCacheUsers()->add($user->getName(), $user);
                    self::getCacheUsers()->add($user->getLogin(), $user);
                    if (!is_null($user->getPerson())) {
                        self::getCacheUsers()->add($user->getPerson()->e_mail, $user);
                    }
                }
                if (is_null($user)) {
                    foreach (CActiveRecordProvider::getWithCondition(TABLE_USERS, "FIO='".$key."'")->getItems() as $item) {
                        $user = new CUser($item);
                        self::getCacheUsers()->add($user->getId(), $user);
                        self::getCacheUsers()->add($user->getName(), $user);
                        self::getCacheUsers()->add($user->getLogin(), $user);
                        if (!is_null($user->getPerson())) {
                            self::getCacheUsers()->add($user->getPerson()->e_mail, $user);
                        }
                    }
                }
                if (is_null($user)) {
                    foreach (CActiveRecordProvider::getWithCondition(TABLE_PERSON, "e_mail='".$key."'")->getItems() as $item) {
                        if (!is_null(self::getPerson($item->getId()))) {
                            $user = self::getPerson($item->getId())->getUser();
                            if (!is_null($user)) {
                                self::getCacheUsers()->add($user->getId(), $user);
                                self::getCacheUsers()->add($user->getName(), $user);
                                self::getCacheUsers()->add($user->getLogin(), $user);
                                self::getCacheUsers()->add($user->getPerson()->e_mail, $user);
                            }
                        }
                    }
                }
            }
        }
        return self::getCacheUsers()->getItem($key);
    }
    /**
     * @static
     * @param $key
     * @return CStudentGroup
     */
    public static function getStudentGroup($key) {
        if (!self::getCacheGroups()->hasElement($key)) {
            $ar = null;
            if (is_numeric($key)) {
                $ar = CActiveRecordProvider::getById(TABLE_STUDENT_GROUPS, $key);
            } elseif (is_string($key)) {
                $key = mb_strtoupper($key);
                foreach (CActiveRecordProvider::getWithCondition(TABLE_STUDENT_GROUPS, "UPPER(name) = '".$key."'")->getItems() as $item) {
                    $ar = $item;
                }
            }
            if (!is_null($ar)) {
                $group = new CStudentGroup($ar);
                self::getCacheGroups()->add($group->getId(), $group);
                self::getCacheGroups()->add(mb_strtoupper($group->getName()), $group);
            }
        }
        return self::getCacheGroups()->getItem($key);
    }
    /**
     * @static
     * @param $key
     * @return CStudent
     */
    public static function getStudent($key) {
        if (is_numeric($key)) {
            $keySeek = $key;
        }else if (is_string($key)) {
            $keySeek = mb_strtoupper($key);
        }
        if (!self::getCacheStudents()->hasElement($keySeek)) {
            if (is_numeric($keySeek)) {
                $ar = CActiveRecordProvider::getById(TABLE_STUDENTS, $keySeek);
            } else if (is_string($keySeek)) {
                $ar = null;
                foreach (CActiveRecordProvider::getWithCondition(TABLE_STUDENTS, "UPPER(fio) = '".$keySeek."'")->getItems() as $item) {
                    $ar = $item;
                }
            }
            if (!is_null($ar)) {
                $student = new CStudent($ar);
                self::getCacheStudents()->add($student->getId(), $student);
                self::getCacheStudents()->add(mb_strtoupper($student->getName()), $student);
            }
        }
        return self::getCacheStudents()->getItem($keySeek);
    }
    /**
     * Инициализируем сразу всех студентов
     *
     * @static
     * @return CArrayList
     */
    public static function getAllStudents() {
        if (!self::$_cacheStudentsInit) {
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_STUDENTS)->getItems() as $ar) {
                $student = new CStudent($ar);
                self::getCacheStudents()->add($student->getId(), $student);
            }
        }
        return self::getCacheStudents();
    }
    /**
     * Кэш ролей пользователей
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheRoles() {
        if (is_null(self::$_cacheRoles)) {
            self::$_cacheRoles = new CArrayList();
            // инициализируем все сразу, иначе там 100500 запросов делается на
            // каждого пользователя, а это совсем не комильфо
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_USER_ROLES, "name asc")->getItems() as $ar) {
                $role = new CUserRole($ar);
                self::$_cacheRoles->add($role->getId(), $role);
            }
        }
        return self::$_cacheRoles;
    }
    /**
     * Все роли, зарегистрированные в системе
     *
     * @static
     * @return CArrayList
     */
    public static function getAllUserRoles() {
        return self::getCacheRoles();
    }
    public static function getAllUserRolesList() {
        $res = array();
        foreach (self::getAllUserRoles()->getItems() as $role) {
            $res[$role->getId()] = $role->getName();
        }
        return $res;
    }
    /**
     * Роль пользователя
     *
     * @static
     * @param $key
     * @return CUserRole
     */
    public static function getUserRole($key) {
        if (!self::getCacheRoles()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_USER_ROLES, $key);
            if (!is_null($ar)) {
                $role = new CUserRole($ar);
                self::getCacheRoles()->add($role->getId(), $role);
            }
        }
        return self::getCacheRoles()->getItem($key);
    }
    /**
     * Кэш групп пользователей
     *
     * @static
     * @return CArrayList
     */
    private static function getCacheUserGroups() {
        if (is_null(self::$_cacheUserGroups)) {
            self::$_cacheUserGroups = new CArrayList();
        }
        return self::$_cacheUserGroups;
    }
    /**
     * Группа пользователей
     *
     * @static
     * @param $key
     * @return CUserGroup
     */
    public static function getUserGroup($key) {
        if (!self::getCacheUserGroups()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_USER_GROUPS, $key);
            if (!is_null($ar)) {
                $group = new CUserGroup($ar);
                self::getCacheUserGroups()->add($group->getId(), $group);
            }
        }
        return self::getCacheUserGroups()->getItem($key);
    }
    /**
     * Все пользователи
     *
     * @static
     * @return CArrayList
     */
    public static function getAllUsers() {
        if (!self::$_cacheUsersInit) {
            self::$_cacheUsersInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_USERS, "FIO asc")->getItems() as $ar) {
                $user = new CUser($ar);
                self::getCacheUsers()->add($user->getId(), $user);
            }
        }
        return self::getCacheUsers();
    }

    /**
     * Все пользователи в виде списка для подстановки
     *
     * @return array
     */
    public static function getAllUsersList() {
        $res = array();
        foreach (self::getAllUsers()->getItems() as $user) {
            $res[$user->getId()] = $user->getName();
        }
        return $res;
    }
    /**
     * Получить приказ по идентификатору или номеру
     *
     * @static
     * @param $key
     * @return COrder
     */
    public static function getOrder($key) {
        if (!self::getCacheOrders()->hasElement($key)) {
            if (is_numeric($key)) {
                $item = CActiveRecordProvider::getById(TABLE_STAFF_ORDERS, $key);
                if (!is_null($item)) {
                    $order = new COrder($item);
                    self::getCacheOrders()->add($order->id, $order);
                    self::getCacheOrders()->add($order->num_order, $order);
                }
            } else {
                foreach (CActiveRecordProvider::getWithCondition(TABLE_STAFF_ORDERS, "num_order = '".$key."'")->getItems() as $item) {
                    $order = new COrder($item);
                    self::getCacheOrders()->add($order->id, $order);
                    self::getCacheOrders()->add($order->num_order, $order);
                }
            }
        }
        return self::getCacheOrders()->getItem($key);
    }
    /**
     * @static
     * @param $key
     * @return CPasswordRecoveryRequest
     */
    public static function getPasswordRecoveryRequest($key) {
        if (!self::getCacheRecoveryRequests()->hasElement($key)) {
            if (is_numeric($key)) {
                $item = CActiveRecordProvider::getById(TABLE_PASSWORD_RECOVERY_REQUESTS, $key);
                if (!is_null($item)) {
                    $request = new CPasswordRecoveryRequest($item);
                    self::getCacheRecoveryRequests()->add($request->getId(), $request);
                    self::getCacheRecoveryRequests()->add($request->hash, $request);
                }
            } elseif (is_string($key)) {
                $request = null;
                foreach (CActiveRecordProvider::getWithCondition(TABLE_PASSWORD_RECOVERY_REQUESTS, "hash='".$key."'")->getItems() as $item) {
                    $request = new CPasswordRecoveryRequest($item);
                    self::getCacheRecoveryRequests()->add($request->getId(), $request);
                    self::getCacheRecoveryRequests()->add($request->hash, $request);
                }
            }
        }
        return self::getCacheRecoveryRequests()->getItem($key);
    }
    /**
     * @static
     * @param $key
     * @return CPublication
     */
    public static function getPublication($key) {
        if (!self::getCachePublications()->hasElement($key)) {
            if (is_numeric($key)) {
                $item = CActiveRecordProvider::getById(TABLE_PUBLICATIONS, $key);
                if (!is_null($item)) {
                    $obj = new CPublication($item);
                    self::getCachePublications()->add($obj->getId(), $obj);
                }
            } else {
                trigger_error("Не реализовано");
            }
        }
        return self::getCachePublications()->getItem($key);
    }

    /**
     * Все зарегистрированные группы пользователей
     *
     * @return CArrayList
     */
    public static function getAllUserGroups() {
        if (!self::$_cacheUserGroupsInit) {
            self::$_cacheGroupsInit = true;
            foreach (CActiveRecordProvider::getAllFromTable(TABLE_USER_GROUPS)->getItems() as $ar) {
                $group = new CUserGroup($ar);
                self::getCacheUserGroups()->add($group->getId(), $group);
            }
        }
        return self::getCacheUserGroups();
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheStudentActivities() {
        if (is_null(self::$_cacheStudentActivities)) {
            self::$_cacheStudentActivities = new CArrayList();
        }
        return self::$_cacheStudentActivities;
    }

    /**
     *
     *
     * @param $key
     * @return CStudentActivity
     */
    public static function getStudentActivity($key) {
        if (!self::getCacheStudentActivities()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_STUDENTS_ACTIVITY, $key);
            if (!is_null($item)) {
                $activity = new CStudentActivity($item);
                self::getCacheStudentActivities()->add($activity->getId(), $activity);
            }
        }
        return self::getCacheStudentActivities()->getItem($key);
    }

    /**
     * Кэш личных настроек пользователя
     *
     * @return CArrayList|null
     */
    private static function getCacheUserSettings() {
        if (is_null(self::$_cacheUserSettings)) {
            self::$_cacheUserSettings = new CArrayList();
        }
        return self::$_cacheUserSettings;
    }

    /**
     * Личные настройки пользователя по ID пользователя
     *
     * @param $id
     * @return CUserSettings
     */
    public static function getUserSettingsByUser($id) {
        if (!self::getCacheUserSettings()->hasElement("user_".$id)) {
            foreach (CActiveRecordProvider::getWithCondition(TABLE_USER_SETTINGS, "user_id = ".$id)->getItems() as $item) {
                $settings = new CUserSettings($item);
                self::getCacheUserSettings()->add("user_".$id, $settings);
            }
        }
        return self::getCacheUserSettings()->getItem("user_".$id);
    }

    /**
     * Дни рождения на этой неделе
     *
     * @return CArrayList
     */
    public static function getBirthdaysThisWeek() {
        if (is_null(self::$_bDaysThisWeek)) {
            self::$_bDaysThisWeek = new CArrayList();
            $start = date("d.m.Y", strtotime("this week"));
            $end = date("d.m.Y", strtotime("this week +6 days"));
            $condition = 'STR_TO_DATE(CONCAT(LEFT(date_rogd, 5), "'.date("Y").'"), "%d.%m.%Y") BETWEEN STR_TO_DATE("'.$start.'", "%d.%m.%Y") AND STR_TO_DATE("'.$end.'", "%d.%m.%Y")';
            foreach (CActiveRecordProvider::getWithCondition(TABLE_PERSON, $condition)->getItems() as $item) {
                $person = new CPerson($item);
                self::$_bDaysThisWeek->add($person->getId(), $person);
            }
        }
        return self::$_bDaysThisWeek;
    }

    /**
     * Кэш сообщений
     *
     * @return CArrayList|null
     */
    private static function getCacheMessages() {
        if (is_null(self::$_cacheMessages)) {
            self::$_cacheMessages = new CArrayList();
        }
        return self::$_cacheMessages;
    }

    /**
     * @param $key
     * @return CMessage
     */
    public static function getMessage($key) {
        if (!self::getCacheMessages()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_MESSAGES, $key);
            if (!is_null($item)) {
                $msg = new CMessage($item);
                self::getCacheMessages()->add($msg->getId(), $msg);
            }
        }
        return self::getCacheMessages()->getItem($key);
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheGradebooks() {
        if (is_null(self::$_cacheGradebooks)) {
            self::$_cacheGradebooks = new CArrayList();
        }
        return self::$_cacheGradebooks;
    }

    /**
     * Получить журнал успеваемости
     *
     * @param $key
     * @return CGradebook
     */
    public static function getGradebook($key) {
        if (!self::getCacheGradebooks()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_GRADEBOOKS, $key);
            if (!is_null($item)) {
                $gradebook = new CGradebook($item);
                self::getCacheGradebooks()->add($gradebook->getId(), $gradebook);
            }
        }
        return self::getCacheGradebooks()->getItem($key);
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheGradebookItems() {
        if (is_null(self::$_cacheGradebookItems)) {
            self::$_cacheGradebookItems = new CArrayList();
        }
        return self::$_cacheGradebookItems;
    }

    /**
     * @param $key
     * @return CGradebookItem
     */
    public static function getGradebookItem($key) {
        if (!self::getCacheGradebookItems()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_GRADEBOOK_ITEMS, $key);
            if (!is_null($item)) {
                $gradebook = new CGradebookItem($item);
                self::getCacheGradebookItems()->add($gradebook->getId(), $gradebook);
            }
        }
        return self::getCacheGradebookItems()->getItem($key);
    }

    /**
     * @return CArrayList|null
     */
    private static function getCacheDiploms() {
        if (is_null(self::$_cacheDiploms)) {
            self::$_cacheDiploms = new CArrayList();
        }
        return self::$_cacheDiploms;
    }

    /**
     * Получить диплом по идентификатору
     *
     * @param $key
     * @return CDiplom
     */
    public static function getDiplom($key) {
        if (!self::getCacheDiploms()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_DIPLOMS, $key);
            if (!is_null($item)) {
                $diplom = new CDiplom($item);
                self::getCacheDiploms()->add($key, $diplom);
            }
        }
        return self::getCacheDiploms()->getItem($key);
    }

    /**
     * Кэш предзащит диплома
     *
     * @return CArrayList|null
     */
    private static function getCacheDiplomPreviews() {
        if (is_null(self::$_cacheDiplomPreviews)) {
            self::$_cacheDiplomPreviews = new CArrayList();
        }
        return self::$_cacheDiplomPreviews;
    }

    /**
     * Предзащита диплома
     *
     * @param $id
     * @return CDiplomPreview
     */
    public static function getDiplomPreview($id) {
        if (!self::getCacheDiplomPreviews()->hasElement($id)) {
            $item = CActiveRecordProvider::getById(TABLE_DIPLOM_PREVIEWS, $id);
            if (!is_null($item)) {
                $preview = new CDiplomPreview($item);
                self::getCacheDiplomPreviews()->add($preview->getId(), $preview);
            }
        }
        return self::getCacheDiplomPreviews()->getItem($id);
    }

    /**
     * Все студенты за текущий год
     *
     * @return CArrayList
     */
    public static function getAllStudentsThisYear() {
        $res = new CArrayList();
        $query = new CQuery();
        $query->select("student.*")
            ->from(TABLE_STUDENTS." as student")
            ->leftJoin(TABLE_STUDENT_GROUPS." as st_group", "st_group.id = student.group_id")
            ->condition("st_group.year_id = ".CUtils::getCurrentYear()->getId());
        foreach ($query->execute()->getItems() as $arr) {
            $student = new CStudent(new CActiveRecord($arr));
            $res->add($student->getId(), $student);
        }
        return $res;
    }

    /**
     * Студенты за текущий год для подстановки
     *
     * @return array
     */
    public static function getAllStudentsThisYearList() {
        $res = array();
        foreach (self::getAllStudentsThisYear()->getItems() as $student) {
            $res[$student->getId()] = $student->getName();
        }
        return $res;
    }

    /**
     * Кэш комиссий по предзащите
     *
     * @return CArrayList|null
     */
    private static function getCacheDiplomPreviewComissions() {
        if (is_null(self::$_cacheDiplomPreviewComissions)) {
            self::$_cacheDiplomPreviewComissions = new CArrayList();
        }
        return self::$_cacheDiplomPreviewComissions;
    }

    /**
     * Коммия по предзащите диплома
     *
     * @param $key
     * @return CDiplomPreviewComission
     */
    public static function getDiplomPreviewComission($key) {
        if (!self::getCacheDiplomPreviewComissions()->hasElement($key)) {
            $item = CActiveRecordProvider::getById(TABLE_DIPLOM_PREVIEW_COMISSIONS, $key);
            if (!is_null($item)) {
                $comission = new CDiplomPreviewComission($item);
                self::getCacheDiplomPreviewComissions()->add($comission->getId(), $comission);
            }
        }
        return self::getCacheDiplomPreviewComissions()->getItem($key);
    }
    public static function getDiplomPreviewComissionsList() {
        $res = array();
        $query = new CQuery();
        $query->select("comission.*")
            ->from(TABLE_DIPLOM_PREVIEW_COMISSIONS." as comission")
            ->leftJoin(TABLE_PERSON." as person", "comission.secretary_id = person.id")
            ->order("comission.name");
        return $res;
    }

    /**
     * Кэш приказов угату
     *
     * @return CArrayList
     */
    private static function getCacheUsatuOrders() {
        if (is_null(self::$_cacheUsatuOrders)) {
            self::$_cacheUsatuOrders = new CArrayList();
        }
        return self::$_cacheUsatuOrders;
    }

    /**
     * Приказ по УГАТУ
     *
     * @param $key
     * @return COrderUsatu
     */
    public static function getUsatuOrder($key) {
        if (!self::getCacheUsatuOrders()->hasElement($key)) {
            $ar = CActiveRecordProvider::getById(TABLE_USATU_ORDERS, $key);
            if (!is_null($ar)) {
                $order = new COrderUsatu($ar);
                self::getCacheUsatuOrders()->add($order->getId(), $order);
            }
        }
        return self::getCacheUsatuOrders()->getItem($key);
    }
}
