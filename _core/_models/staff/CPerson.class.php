<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 04.05.12
 * Time: 22:23
 * To change this template use File | Settings | File Templates.
 *
 * Сотрудник
 *
 * @property CArrayList workplans
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
    protected $_orders_sab = null;
    protected $_indPlanLoads = null;
	private $_biographies = null;
    private $_graduatesCurrentYear = null;
    private $_graduatesOld = null;
    private $_documents = null;
    private $_newsCurrentYear = null;
    private $_newsOld = null;
    private $_schedules = null;
    private $_pages = null;
    private $_manuals = null;
    private $_aspirantsCurrent = null;
    private $_aspirantsOld = null;
    private $_questions = null;
    private $_supervisedGroups = null;
    public $to_tabel = 0;
    public $is_slave = 0;

    protected function relations() {
        return array(
            "orders" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_orders",
                "storageTable" => TABLE_STAFF_ORDERS,
                "storageCondition" => "kadri_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getOrder"
            ),
            "ratingIndexValues" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_ratingIndexesValues",
                "joinTable" => TABLE_PERSON_RATINGS,
                "leftCondition" => "person_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "index_id",
                "managerClass" => "CRatingManager",
                "managerGetObject" => "getRatingIndexValue"
            ),
            "publications" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_publications",
                "joinTable" => TABLE_PUBLICATION_BY_PERSONS,
                "leftCondition" => "kadri_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
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
            "ordersSAB" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_orders_sab",
                "storageTable" => TABLE_SAB_PERSON_ORDERS,
                "storageCondition" => "person_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CSABManager",
                "managerGetObject" => "getSABPersonOrder"
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
                "storageCondition" => "kadri_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and disser_type = 'степень'",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPersonPaper"
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
                "storageCondition" => "kadri_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPersonChild"
            ),
            "diploms" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_diploms",
                "storageTable" => TABLE_PERSON_DIPLOMS,
                "storageCondition" => "kadri_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPersonDiplom"
            ),
            "cources" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_cources",
                "storageTable" => TABLE_PERSON_COURCES,
                "storageCondition" => "kadri_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPersonCourse"
            ),
            "phdpapers" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_phdpapers",
                "storageTable" => TABLE_PERSON_DISSER,
                "storageCondition" => "kadri_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND disser_type='кандидат'",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPersonPHDPaper"
            ),
            "doctorpapers" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_doctorpapers",
                "storageTable" => TABLE_PERSON_DISSER,
                "storageCondition" => "kadri_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND disser_type='доктор'",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPersonDoctorPaper"
            ),
            "loads" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_indPlanLoads",
                "storageTable" => TABLE_IND_PLAN_LOADS,
                "storageCondition" => "person_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CIndPlanManager",
                "managerGetObject" => "getLoad"
            ),
        	"biographies" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageProperty" => "_biographies",
        		"storageTable" => TABLE_BIOGRAPHY,
        		"storageCondition" => "user_id = " . (is_null($this->getId()) ? 0 : $this->getUserId()),
        		"managerClass" => "CBiographyManager",
        		"managerGetObject" => "getBiography"
        	),
    		"documents" => array(
    			"relationPower" => RELATION_HAS_MANY,
    			"storageProperty" => "_documents",
    			"storageTable" => TABLE_LIBRARY_DOCUMENTS,
    			"storageCondition" => "user_id = " . (is_null($this->getId()) ? 0 : $this->getUserId()),
    			"managerClass" => "CLibraryManager",
    			"managerGetObject" => "getDocument"
    		),
    		"newsCurrentYear" => array(
    			"relationPower" => RELATION_HAS_MANY,
    			"storageProperty" => "_newsCurrentYear",
    			"storageTable" => TABLE_NEWS,
    			"storageCondition" => 'user_id_insert = '.(is_null($this->getId()) ? 0 : $this->getUserId()).' and date_time>="'.CUtils::getCurrentYear()->date_start.'"',
    			"managerClass" => "CNewsManager",
    			"managerGetObject" => "getNewsItem",
				"managerOrder" => "`date_time` desc"
    		),
    		"newsOld" => array(
    			"relationPower" => RELATION_HAS_MANY,
    			"storageProperty" => "_newsOld",
    			"storageTable" => TABLE_NEWS,
    			"storageCondition" => 'user_id_insert = '.(is_null($this->getId()) ? 0 : $this->getUserId()).' and date_time<"'.CUtils::getCurrentYear()->date_start.'"',
    			"managerClass" => "CNewsManager",
    			"managerGetObject" => "getNewsItem",
    			"managerOrder" => "`date_time` desc"
    		),
    		"pages" => array(
    			"relationPower" => RELATION_HAS_MANY,
    			"storageProperty" => "_pages",
    			"storageTable" => TABLE_PAGES,
    			"storageCondition" => "user_id_insert = ".(is_null($this->getId()) ? 0 : $this->getUserId())." and type_id<>1",
    			"managerClass" => "CPageManager",
    			"managerGetObject" => "getPage"
    		),
    		"questions" => array(
    			"relationPower" => RELATION_HAS_MANY,
    			"storageProperty" => "_questions",
    			"storageTable" => TABLE_QUESTION_TO_USERS,
    			"storageCondition" => 'status!=5 and answer_text is not null and answer_text!="" and user_id="'.(is_null($this->getId()) ? 0 : $this->getUserId()).'"',
    			"managerClass" => "CQuestionManager",
    			"managerGetObject" => "getQuestion",
    			"managerOrder" => "`datetime_quest` desc"
    		),
    		"supervisedGroups" => array(
    			"relationPower" => RELATION_HAS_MANY,
    			"storageProperty" => "_supervisedGroups",
    			"storageTable" => TABLE_STUDENT_GROUPS,
    			"storageCondition" => "curator_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
    			"managerClass" => "CStaffManager",
    			"managerGetObject" => "getStudentGroup"
    		),
            "workplans" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_workplancs",
                "joinTable" => TABLE_WORK_PLAN_AUTHORS,
                "leftCondition" => "person_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "plan_id",
                "managerClass" => "CBaseManager",
                "managerGetObject" => "getWorkPlan"
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
            "order_seb_id" => "Приказ по ГАК",
            "workplans" => "Рабочие программы"
        );
    }
    public function fieldsProperty() {
        return array(
            'photo' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.CORE_DS."images".CORE_DS."lects".CORE_DS
            )
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
     * Идентификатор связанного с сотрудником пользователя портала
     *
     * @return int
     */
    public function getUserId() {
    	$person = CStaffManager::getPerson($this->getId());
    	if (!is_null($person->getUser())) {
    		$userId = $person->getUser()->id;
    	} else {
    		$userId = 0;
    	}
    	return $userId;
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
            if (!is_null($this->getId())) {
                $tList = CActiveRecordProvider::getWithCondition("kadri_in_ptypes", "kadri_id=".$this->getId());
                foreach ($tList->getItems() as $item) {
                    $term = CTaxonomyManager::getTypeById($item->getItemValue("person_type_id"));
                    if (!is_null($term)) {
                        $this->_types->add($term->getId(), $term);
                    }
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
     * Публикации сотрудника в указанном году
     * 
     * @param CTerm $year
     * @return CArrayList
     */
    public function getPublications(CTerm $year) {
    	$res = new CArrayList();
    	foreach ($this->publications->getItems() as $item) {
    		if ($item->year == date("Y", strtotime($year->date_start)) or $item->year == date("Y", strtotime($year->date_end))) {
    			$res->add($item->getId(), $item);
    		}
    	}
    	return $res;
    }
    /**
     * Суммарный вес публикаций преподавателя в указанном году
     *
     * @param CTerm $year
     * @return int
     */
    public function getRatingPublicationsWeight(CTerm $year) {
    	$res = 0;
    	foreach ($this->getPublications($year)->getItems() as $index) {
    		if (!is_null($index->type)) {
    			$res += $index->type->weight;
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
     * @return array
     */
    public function getActiveOrdersList() {
        $result = array();
        foreach ($this->getActiveOrders()->getItems() as $order) {
            $result[$order->getId()] = "Приказ № ".$order->num_order." от ".$order->date_order;
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

    /**
     * @param CTerm $year
     * @return CArrayList
     */
    public function getSABOrdersByYear(CTerm $year) {
        $result = new CArrayList();
        foreach ($this->ordersSAB->getItems() as $order) {
            if ($year->getId() == $order->year_id) {
                $result->add($order->getId(), $order);
            }
        }
        return $result;
    }

    /**
     * @param CTerm $year
     * @param $type
     * @return CSABPersonOrder
     */
    public function getSABOrderByYearAndType(CTerm $year, $type) {
        foreach ($this->getSABOrdersByYear($year)->getItems() as $order) {
            if (!is_null($order->type)) {
                if (mb_strtolower($order->type->alias) == mb_strtolower($type)) {
                    return $order;
                }
            }
        }
        return null;
    }

    /**
     * @param $restrict
     * @return CArrayList
     */
    public function getIndPlansByYears($restrict = 0) {
        $result = new CArrayList();
        foreach ($this->loads->getItems() as $load) {
            $year = new CArrayList();
            if ($result->hasElement($load->year_id)) {
                $year = $result->getItem($load->year_id);
            }
            $year->add($load->getId(), $load);
            $result->add($load->year_id, $year);
        }
        /**
         * Если есть ограничение, то все, которые под него не попадают
         * исключаем из результатов
         */
        if ($restrict > 0) {
            foreach ($result->getItems() as $year=>$load) {
                if ($year != $restrict) {
                    $result->removeItem($year);
                }
            }
        }
        return $result;
    }

    /**
     * Список публикаций для подстановки
     *
     * @return array
     */
    public function getPublicationsList() {
        $result = array();
        foreach ($this->publications->getItems() as $paper) {
            $result[$paper->getId()] = $paper->name;
        }
        return $result;
    }

    public function toJsonObject($relations = true)
    {
        $object = parent::toJsonObject($relations);
        $object->name = $this->getName();
        return $object;
    }
    
    /**
     * Биография
     * 
     * @return CArrayList
     */
    public function getBiographies() {
    	$result = new CArrayList();
    	foreach ($this->biographies->getItems() as $biography) {
    		$result->add($biography->getId(), $biography);
    	}
    	return $result;
    }
    
    /**
     * Дипломники текущего учебного года
     * 
     * @return CArrayList
     */
    public function getGraduatesCurrentYear() {
    	if (is_null($this->_graduatesCurrentYear)) {
    		$this->_graduatesCurrentYear = new CArrayList();
    		$query = new CQuery();
    		$query->select("diploms.*")
	    		->from(TABLE_DIPLOMS." as diploms")
	    		->leftJoin(TABLE_STUDENTS." as students", "diploms.student_id=students.id")
	    		->condition('kadri_id = "'.$this->id.'" and (date_act>="'.CUtils::getCurrentYear()->date_start.'" or date_act is NULL)')
	    		->order("students.fio asc");
    		foreach ($query->execute()->getItems() as $item) {
    			$diplom = new CDiplom(new CActiveRecord($item));
    			$this->_graduatesCurrentYear->add($diplom->getId(), $diplom);
    		}
    	}
    	return $this->_graduatesCurrentYear;
    }
    
    /**
     * Дипломники предыдущих учебных лет
     * 
     * @return CArrayList
     */
    public function getGraduatesOld() {
    	if (is_null($this->_graduatesOld)) {
    		$this->_graduatesOld = new CArrayList();
   			$query = new CQuery();
	    	$query->select("diploms.*")
		    	->from(TABLE_DIPLOMS." as diploms")
		    	->leftJoin(TABLE_STUDENTS." as students", "diploms.student_id=students.id")
		    	->condition('kadri_id = "'.$this->id.'" and date_act<"'.CUtils::getCurrentYear()->date_start.'"')
		    	->order("students.fio asc");
    		foreach ($query->execute()->getItems() as $item) {
    			$diplom = new CDiplom(new CActiveRecord($item));
    			$this->_graduatesOld->add($diplom->getId(), $diplom);
    		}
    	}
    	return $this->_graduatesOld;
    }
    
    /**
     * Документы
     * 
     * @return CArrayList
     */
    public function getDocuments() {
    	$result = new CArrayList();
    	foreach ($this->documents->getItems() as $document) {
    		$result->add($document->getId(), $document);
    	}
    	return $result;
    }
    
    /**
     * Объявления
     * 
     * @return CArrayList
     */
    public function getNews() {
    	$result = new CArrayList();
    	foreach ($this->news->getItems() as $new) {
    		$result->add($new->getId(), $new);
    	}
    	return $result;
    }
    
    /**
     * Объявления текущего учебного года
     * 
     * @return CArrayList
     */
    public function getNewsCurrentYear() {
    	$result = new CArrayList();
    	foreach ($this->newsCurrentYear->getItems() as $new) {
    		$result->add($new->getId(), $new);
    	}
    	return $result;
    }
    
    /**
     * Объявления прошлых учебных лет
     * 
     * @return CArrayList
     */
    public function getNewsOld() {
    	$result = new CArrayList();
    	foreach ($this->newsOld->getItems() as $new) {
    		$result->add($new->getId(), $new);
    	}
    	return $result;
    }
    
    /**
     * Расписание
     * 
     * @return CArrayList
     */
    public function getSchedule() {
    	if (is_null($this->_schedules)) {
    		$this->_schedules = new CArrayList();
    		$query = new CQuery();
    		$query->select("schedule.*")
	    		->from(TABLE_SCHEDULE." as schedule")
	    		->condition("schedule.id=".$this->getUser()->id." and schedule.year=".CUtils::getCurrentYear()->getId()." and schedule.month=".CUtils::getCurrentYearPart()->getId());
    		foreach ($query->execute()->getItems() as $item) {
    			$schedule = new CSchedule(new CActiveRecord($item));
    			$this->_schedules->add($schedule->getId(), $schedule);
    		}
    	}
    	return $this->_schedules;
    }
    
    /**
     * Cтраницы на портале
     * 
     * @return CArrayList
     */
    public function getPages() {
    	$result = new CArrayList();
    	foreach ($this->pages->getItems() as $page) {
    		$result->add($page->getId(), $page);
    	}
    	return $result;
    }
    
    /**
     * Список пособий на портале
     * 
     * @return CArrayList
     */
    public function getManuals() {
    	if (is_null($this->_manuals)) {
    		$this->_manuals = new CArrayList();
    		$query = new CQuery();
    		$query->select("subj.*, doc.nameFolder as nameFolder, (select count(*) from files f where f.nameFolder = doc.nameFolder) as f_cnt")
	    		->from(TABLE_DISCIPLINES." as subj")
	    		->leftJoin(TABLE_LIBRARY_DOCUMENTS." as doc", "subj.id = doc.subj_id")
	    		->condition("doc.user_id=".$this->getUser()->id);
    		foreach ($query->execute()->getItems() as $item) {
    			$subject = new CDiscipline(new CActiveRecord($item));
    			$this->_manuals->add($subject->getId(), $subject);
    		}
    	}
    	return $this->_manuals;
    }
    
    /**
     * Подготовка аспирантов, текущие
     * 
     * @return CArrayList
     */
    public function getAspirantsCurrent() {
    	if (is_null($this->_aspirantsCurrent)) {
    		$this->_aspirantsCurrent = new CArrayList();
    		$query = new CQuery();
    		$query->select("disser.*")
	    		->from(TABLE_PERSON_DISSER." as disser")
	    		->innerJoin(TABLE_PERSON." as kadri", "kadri.id=disser.kadri_id")
	    		->condition('disser.scinceMan="'.$this->id.'" and disser.god_zach>="'.date("Y", strtotime(CUtils::getCurrentYear()->date_end)).'"')
	    		->order("kadri.fio asc");
    		foreach ($query->execute()->getItems() as $item) {
    			$aspir = new CPersonPaper(new CActiveRecord($item));
    			$this->_aspirantsCurrent->add($aspir->getId(), $aspir);
    		}
    	}
    	return $this->_aspirantsCurrent;
    }
    
    /**
     * Подготовка аспирантов, архив
     * 
     * @return CArrayList
     */
    public function getAspirantsOld() {
    	if (is_null($this->_aspirantsOld)) {
    		$this->_aspirantsOld = new CArrayList();
    		$query = new CQuery();
    		$query->select("disser.*")
	    		->from(TABLE_PERSON_DISSER." as disser")
	    		->innerJoin(TABLE_PERSON." as kadri", "kadri.id=disser.kadri_id")
	    		->condition('disser.scinceMan="'.$this->id.'" and disser.scinceMan>0 and (disser.god_zach<"'.date("Y", strtotime(CUtils::getCurrentYear()->date_end)).'" or disser.god_zach is null)')
	    		->order("kadri.fio asc");
    		foreach ($query->execute()->getItems() as $item) {
    			$aspir = new CPersonPaper(new CActiveRecord($item));
    			$this->_aspirantsOld->add($aspir->getId(), $aspir);
    		}
    	}
    	return $this->_aspirantsOld;
    }
    
    /**
     * Вопросы и ответы на них преподавателя
     * 
     * @return CArrayList
     */
    public function getQuestions() {
    	$result = new CArrayList();
    	foreach ($this->questions->getItems() as $question) {
    		$result->add($question->getId(), $question);
    	}
    	return $result;
    }
    
    /**
     * Кураторство учебных групп
     * 
     * @return CArrayList
     */
    public function getSupervisedGroups() {
    	$result = new CArrayList();
    	foreach ($this->supervisedGroups->getItems() as $group) {
    		$result->add($group->getId(), $group);
    	}
    	return $result;
    }

}
