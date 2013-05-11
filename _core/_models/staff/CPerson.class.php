<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 04.05.12
 * Time: 22:23
 * To change this template use File | Settings | File Templates.
 *
 * Сотрудник
 */


class CPerson extends CActiveModel{
    protected $_table = TABLE_PERSON;
    private $_subordinators = null;
    private $_post = null;
    private $_user = null;
    private $_role = null;
    private $_resource = null;
    private $_manager = null;
    protected $_types = null;
    protected $_orders = null;
    protected $_ratingIndexesValues = null;
    protected $_publications = null;
    protected $_title = null;
    protected $_degrees = null;
    protected $_children = null;
    protected $_diploms = null;
    protected $_cources = null;
    protected $_phdpapers = null;
    protected $_doctorpapers = null;
    protected $_degree = null;
    protected $_order_sab = null;

    protected function relations() {
        return array(
            "orders" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_orders",
                "storageTable" => TABLE_STAFF_ORDERS,
                "storageCondition" => "kadri_id = " . $this->id,
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getOrder"
            ),
            "ratingIndexValues" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_ratingIndexesValues",
                "joinTable" => TABLE_PERSON_RATINGS,
                "leftCondition" => "person_id = ". $this->id,
                "rightKey" => "index_id",
                "managerClass" => "CRatingManager",
                "managerGetObject" => "getRatingIndexValue"
            ),
            "publications" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_publications",
                "joinTable" => TABLE_PUBLICATION_BY_PERSONS,
                "leftCondition" => "kadri_id = ". $this->id,
                "rightKey" => "izdan_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPublication"
            ),
            "title" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_title",
                "storageField" => "zvanie",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTitle"
            ),
            "orderSAB" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_order_sab",
                "storageField" => "order_sab_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getUsatuOrder"
            ),
            "degree" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_degree",
                "storageField" => "stepen",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDegree"
            ),
            "degrees" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_degrees",
                "storageTable" => TABLE_PERSON_DISSER,
                "storageCondition" => "kadri_id = " . $this->id . " and disser_type = 'степень'",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getDegree"
            ),
            "types" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_types",
                "relationFunction" => "getTypes"
            ),
            "children" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_children",
                "storageTable" => TABLE_PERSON_CHILDREN,
                "storageCondition" => "kadri_id = " . $this->id,
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPersonChild"
            ),
            "diploms" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_diploms",
                "storageTable" => TABLE_PERSON_DIPLOMS,
                "storageCondition" => "kadri_id = " . $this->id,
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPersonDiplom"
            ),
            "cources" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_cources",
                "storageTable" => TABLE_PERSON_COURCES,
                "storageCondition" => "kadri_id = " . $this->id,
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPersonCourse"
            ),
            "phdpapers" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_phdpapers",
                "storageTable" => TABLE_PERSON_DISSER,
                "storageCondition" => "kadri_id = " . $this->id." AND disser_type='кандидат'",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPersonPHDPaper"
            ),
            "doctorpapers" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_doctorpapers",
                "storageTable" => TABLE_PERSON_DISSER,
                "storageCondition" => "kadri_id = " . $this->id." AND disser_type='доктор'",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPersonDoctorPaper"
            )
        );
    }
    public function attributeLabels() {
        return array(
            "types" => "Тип участия на кафедре",
            "to_tabel" => "Учитывать в табеле",
            "is_slave" => "Совместитель",
            "manager_id" => "Руководитель",
            "department_role_id" => "Роль на кафедре",
            "fio" => "ФИО полностью",
            "fio_short" => "ФИО кратко",
            "pol" => "Пол",
            "date_rogd" => "Дата рождения",
            "birth_place" => "Место рождения",
            "nation" => "Национальность",
            'social' => "Социальное происхождение",
            "family_status" => "Семейное положение",
            "INN" => "ИНН",
            "insurance_num" => "Страховой номер",
            "passp_seria" => "Паспорт серия",
            "passp_nomer" => "Паспорт номер",
            "passp_place" => "Кем выдан",
            "language1" => "Иностранный язык",
            "work_place" => "Основное место работы (для совместителей)",
            "dolgnost" => "Должность",
            "zvanie" => "Звание",
            "stepen" => "Ученая степень",
            "add_work" => "Рабочий адрес",
            "tel_work" => "Рабочий телефон",
            "add_home" => "Домашний адрес",
            "tel_home" => "Домашний телефон",
            "e_mail" => "Адрес электронной почты",
            "add_contact" => "Дополнительные контакты",
            "site" => "Адрес сайта",
            "stag_ugatu" => "Стаж в УГАТУ",
            "stag_pps" => "Стаж ППС",
            "stag_itogo" => "Стаж общий",
            "din_nauch_kar" => "Динамика научной карьеры",
            "ekspert_spec" => "Экспертная область, научная специальность",
            "ekspert_kluch_slova" => "Экспертная область, ключевые слова",
            "nauch_eksper" => "Опыт научной экспертизы",
            "prepod_rabota" => "Опыт преподавательской работы",
            "nagradi" => "Научные награды",
            "primech" => "Примечание",
            "order_seb_id" => "Приказ по ГАК"
        );
    }
    /**
     * Есть ли приказ, который в настоящее время действует
     *
     * @return bool
     */
    public function hasActiveOrder() {
        foreach ($this->orders->getItems() as $order) {
            if ($order->isActive()) {
                return true;
            }
        }
        return false;
    }
    /**
     * Лист подчиненных
     *
     * @return CArrayList
     */
    public function getSubordinators() {
        if (is_null($this->_subordinators)) {
            $this->_subordinators = new CArrayList();

            foreach (CActiveRecordProvider::getWithCondition(TABLE_PERSON, "manager_id=".$this->getId())->getItems() as $item) {
                $person = CStaffManager::getPersonById($item->getId());
                if (!is_null($person)) {
                    $this->_subordinators->add($this->_subordinators->getCount(), $person);
                }
            }
        }
        return $this->_subordinators;
    }
    /**
     * Инициализация списка починенных.
     */
    public function initSubordinators() {
        if (is_null($this->_subordinators)) {
            $this->_subordinators = new CArrayList();   
        }
    }
    /**
     * Инициализирует список типов участия на кафедре
     */
    public function initTypes() {
        if (is_null($this->_types)) {
            $this->_types = new CArrayList();
        }
    }
    /**
     * Полное имя сотрудника
     *
     * @return string
     */
    public function getName() {
        return (string) $this->getRecord()->getItemValue("fio");
    }
    public function getDisplayName() {
        if (!CSettingsManager::getSettingValue("hide_personal_data")) {
            return $this->getName();
        } else {
            return CSettingsManager::getSettingValue("hide_person_data_text");
        }
    }
    /**
     * Добавить человека в список подчиненных
     *
     * @param CPerson $person
     */
    public function addSubordinator(CPerson $person) {
        if (is_null($this->_subordinators)) {
            $this->_subordinators = new CArrayList();
        }
        $this->_subordinators->add($person->getId(), $person);
    }
    /**
     * Идентификатор записи менеджера
     *
     * @return int
     */
    public function getManagerId() {
        return (int) $this->getRecord()->getItemValue("manager_id");
    }
    /**
     * ID должности
     *
     * @return int
     */
    public function getPostId() {
        return $this->getRecord()->getItemValue("dolgnost");
    }
    /**
     * Должность
     *
     * @return CTerm
     */
    public function getPost() {
        if (is_null($this->_post)) {
            $term = CTaxonomyManager::getPostById($this->getPostId());
            if (!is_null($term)) {
                $this->_post = $term;
            }
        }
        return $this->_post;
    }
    /**
     * Установить руководителя
     *
     * @param CPerson $manager
     */
    public function setManager(CPerson $manager) {
        $this->getRecord()->setItemValue("manager_id", $manager->getId());
    }
    /**
     * id руководителя
     *
     * @param $id
     */
    public function setManagerId($id) {
        $this->getRecord()->setItemValue("manager_id", $id);
    }
    /**
     * Связанный с сотрудником пользователь портала
     *
     * @return CUser
     */
    public function getUser() {
        if (is_null($this->_user)) {
            foreach (CActiveRecordProvider::getWithCondition(TABLE_USERS, "kadri_id=".$this->getId())->getItems() as $item) {
                $user = new CUser($item);
                $this->_user = $user;
                break;
            }
        }
        return $this->_user;
    }
    /**
     * Является ли пользователем портала
     *
     * @return bool
     */
    public function isUser() {
        if (!is_null($this->getUser())) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * Роль на кафедре
     *
     * @return string
     */
    public function getRole() {
        if (is_null($this->_role)) {
            if ($this->getRoleId() != 0) {
                $term = CTaxonomyManager::getTerm($this->getRoleId());
                if (!is_null($term)) {
                    $this->_role = $term;
                }
            }
        }
        return $this->_role;
    }
    /**
     * ID роли на кафедре
     *
     * @return int
     */
    public function getRoleId() {
        return $this->getRecord()->getItemValue("department_role_id");
    }
    /**
     * Ресурс, который связан с данным сотрудником
     *
     * @return CResource
     */
    public function getResource() {
        if (is_null($this->_resource)) {
            $ar = CActiveRecordProvider::getWithCondition(TABLE_RESOURCES, "resource_id=".$this->getId()." AND type='kadri'");
            foreach ($ar->getItems() as $i) {
                $res = new CResource($i);
                $this->_resource = $res;
            }
        }
        return $this->_resource;
    }
    /**
     * Руководитель текущего сотрудника
     *
     * @return CPerson
     */
    public function getManager() {
        if (is_null($this->_manager)) {
            if (!$this->getManagerId() == 0) {
                $person = CStaffManager::getPersonById($this->getManagerId());
                if (!is_null($person)) {
                    $this->_manager = $person;
                }
            }
        }
        return $this->_manager;
    }
    /**
     * Роль пользователя
     *
     * @param CTerm $role
     */
    public function setRole(CTerm $role) {
        $this->_role = $role;
        $this->getRecord()->setItemValue("department_role_id", $role->getId());
    }
    /**
     * Тип участия на кафедре
     *
     * @return CArrayList
     */
    public function getTypes() {
        if (is_null($this->_types)) {
            $this->_types = new CArrayList();
            $tList = CActiveRecordProvider::getWithCondition("kadri_in_ptypes", "kadri_id=".$this->getId());
            foreach ($tList->getItems() as $item) {
                $term = CTaxonomyManager::getTypeById($item->getItemValue("person_type_id"));
                if (!is_null($term)) {
                    $this->_types->add($term->getId(), $term);
                }
            }
        }
        return $this->_types;
    }
    /**
     * Проверялка, обладает ли сотрудник указанной ролью на кафедре
     *
     * @param $type
     * @return bool
     */
    public function hasPersonType($type) {
        foreach ($this->getTypes()->getItems() as $key=>$item) {
            if (is_numeric($type)) {
                if ($key == $type) {
                    return true;
                }
            } elseif (is_string($type)) {
                if (strtoupper($item->getValue()) == strtoupper($type)) {
                    return true;
                }
            } elseif (is_object($type)) {
                if (strtoupper(get_class($type)) == "CTERM") {
                    if ($key == $type->getId()) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    /**
     * Суммарный рейтинг преподавателя в указанном году
     *
     * @param CTerm $year
     * @return int
     */
    public function getRatingIndexValue(CTerm $year) {
        $res = 0;
        foreach ($this->getRatingIndexesByYear($year)->getItems() as $index) {
            foreach ($index->getIndexValues()->getItems() as $value) {
                $res += $value->getValue();
            }
        }
        return $res;
    }
    /**
     * Показатели преподавателя в указанном году.
     * Выпадает из глобального identityMap!!!!!!!!!!
     *
     * @param CTerm $year
     * @return CArrayList
     */
    public function getRatingIndexesByYear(CTerm $year) {
        $res = new CArrayList();
        foreach ($this->ratingIndexValues->getItems() as $item) {
            if ($item->parentIndex->year_id == $year->getId()) {
                $index = $item->parentIndex;
                $index = clone $index;
                $index->truncateValues();
                if ($res->hasElement($index->id)) {
                    $index = $res->getItem($index->id);
                }
                $index->addIndexValue($item);
                $res->add($index->id, $index);
            }
        }
        return $res;
    }
    /**
     * Звание
     *
     * @return CTerm
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Действующие приказы
     *
     * @return CArrayList
     */
    public function getActiveOrders() {
        $result = new CArrayList();
        foreach ($this->orders->getItems() as $order) {
            if ($order->isActive()) {
                $result->add($order->getId(), $order);
            }
        }
        return $result;
    }

    /**
     * Активные приказы указанного типа
     *
     * @param $money_type
     * @param $order_type
     * @return CArrayList
     */
    public function getActiveOrdersByType($money_type, $order_type) {
        $result = new CArrayList();
        foreach ($this->getActiveOrders()->getItems() as $order) {
            if ($order->type_money == $money_type && $order->type_order == $order_type) {
                $result->add($order->getId(), $order);
            }
        }
        return $result;
    }

    /**
     * Все приказы указанного типа
     *
     * @param $money_type
     * @param $order_type
     * @return CArrayList
     */
    public function getAllOrdersByType($money_type, $order_type) {
        $result = new CArrayList();
        foreach ($this->orders->getItems() as $order) {
            if ($order->type_money == $money_type && $order->type_order == $order_type) {
                $result->add($order->getId(), $order);
            }
        }
        return $result;
    }
	
	public function getArchiveOrdersByType($money_type, $order_type) {
		$result = new CArrayList();
		$result = $this->getAllOrdersByType($money_type, $order_type);
		$active = $this->getActiveOrdersByType($money_type, $order_type);
		foreach($result->getItems() as $order) {
			if ($active->hasElement($order->getId())) {
				$result->removeItem($order->getId());
			}
		}
		return $result;
	}

    /**
     * Общая ставка по активным приказам
     *
     * @return int
     */
    public function getOrdersRate() {
        $result = 0;
        foreach ($this->getActiveOrders()->getItems() as $order) {
            $result += $order->rate;
        }
        return $result;
    }

    /**
     * Дата рождения
     *
     * @return string
     */
    public function getBirthday() {
        if ($this->date_rogd == "") {
            return "";
        }
        $date = strtotime($this->date_rogd);
        return date("d", $date)." ".CUtils::getMonthAsWord(date("m", $date));
    }
}
