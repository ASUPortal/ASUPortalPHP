<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 23.04.13
 * Time: 19:45
 * To change this template use File | Settings | File Templates.
 */

class CStaffController extends CBaseController{
    public function __construct() {
        if (!CSession::isAuth()) {
            $action = CRequest::getString("action");
            if ($action == "") {
                $action = "index";
            }
            if (!in_array($action, $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление сотрудниками кафедры");

        parent::__construct();
    }
    public function actionIndex() {
    	$selectedType = null;
        $set = new CRecordSet(true, true);
        $query = new CQuery();
        $set->setQuery($query);
        /**
         * Исходный запрос
         */
        $query->select("person.*")
            ->from(TABLE_PERSON." as person")
            ->leftJoin(TABLE_PERSON_BY_TYPES." as types", "types.kadri_id = person.id")
            ->leftJoin(TABLE_TYPES." as person_types", "types.person_type_id = person_types.id")
            ->leftJoin(TABLE_GENDERS." as pol", "person.pol = pol.id")
            ->leftJoin(TABLE_TAXONOMY_TERMS." as term", "person.department_role_id = term.id")
            ->leftJoin(TABLE_LANGUAGES." as lang", "person.language1 = lang.id")
            ->leftJoin(TABLE_POSTS." as post", "person.dolgnost = post.id")
            ->leftJoin(TABLE_TITLES." as zvanie", "person.zvanie = zvanie.id")
            ->leftJoin(TABLE_DEGREES." as stepen", "person.stepen = stepen.id")
            ->order("person.fio asc");
        
        $isAll = false;
        if (CRequest::getInt("isAll") == "1") {
        	$isAll = true;
        }
        $filter = CRequest::getString("filterClass");
        if (CRequest::getInt("type") != 0) {
        	$selectedType = CRequest::getInt("type");
        } elseif (!$isAll and $filter == "") {
        	$query->condition("(types.person_type_id = 1 or types.person_type_id = 3)");
        }
        $typesQuery = new CQuery();
        $typesQuery->select("types.*")
	        ->from(TABLE_TYPES." as types")
	        ->order("types.name asc");
        $types = array();
        foreach ($typesQuery->execute()->getItems() as $ar) {
        	$type = new CActiveModel(new CActiveRecord($ar));
        	$types[$type->getId()] = $type->name;
        }
        /**
         * Набираем выборку
         */
        $persons = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $person = new CPerson($ar);
            $persons->add($person->getId(), $person);
        }
        $this->setData("paginator", $set->getPaginator());
        $this->setData("persons", $persons);
        $this->setData("types", $types);
        $this->setData("isAll", $isAll);
        $this->setData("selectedType", $selectedType);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(
            array(
                array(
                    "title" => "Добавить сотрудника",
                    "link" => "index.php?action=add",
                    "icon" => "actions/list-add.png"
                ),
                array(
                    "title" => "Печать по шаблону",
                    "link" => "#",
                    "icon" => "devices/printer.png",
                    "template" => "formset_person"
                )
            )
        );
        /**
         * Отображение представления
         */
        $this->renderView("_staff/person/index.tpl");
    }
    public function actionAdd() {
        $form = new CPersonForm();
        $person = new CPerson();
        $form->person = $person;
        $this->setData("form", $form);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=index",
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_staff/person/add.tpl");
    }
    public function actionEdit() {
        $form = new CPersonForm();
        $person = CStaffManager::getPerson(CRequest::getInt("id"));
        $form->person = $person;
        /**
         * Подключаем красивые элементы
         */
        $this->setData("form", $form);
        $this->setData("person", $person);
        /**
         * Собираем меню
         */
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "index.php?action=index",
                "icon" => "actions/edit-undo.png"
            ),
            array(
                "title" => "Добавить ребенка",
                "link" => "children.php?action=add&parent_id=".$person->getId(),
                "icon" => "actions/contact-new.png"
            ),
            array(
                "title" => "Добавить образование",
                "link" => "#",
                "icon" => "actions/address-book-new.png",
                "child" => array(
                    array(
                        "title" => "Добавить высшее образование",
                        "link" => "diploms.php?action=add&id=".$person->getId(),
                        "icon" => "actions/address-book-new.png"
                    ),
                    array(
                        "title" => "Добавить курсы повышения квалификации",
                        "link" => "courses.php?action=add&id=".$person->getId(),
                        "icon" => "actions/address-book-new.png"
                    ),
                    array(
                        "title" => "Добавить кандидатскую диссертацию",
                        "link" => "papers.php?action=add&id=".$person->getId()."&type=1",
                        "icon" => "actions/address-book-new.png"
                    ),
                    array(
                        "title" => "Добавить докторскую диссертацию",
                        "link" => "papers.php?action=add&id=".$person->getId()."&type=2",
                        "icon" => "actions/address-book-new.png"
                    ),
                    array(
                        "title" => "Добавить звание",
                        "link" => "papers.php?action=add&id=".$person->getId()."&type=3",
                        "icon" => "actions/address-book-new.png"
                    )
                )
            ),
            array(
                "title" => "Приказы",
                "link" => "#",
                "icon" => "actions/bookmark-new.png",
                "child" => array(
                    array(
                        "title" => "Добавить приказ ГЭК",
                        "link" => "orderssab.php?action=add&id=".$person->getId(),
                        "icon" => "actions/bookmark-new.png"
                    ),
                    array(
                        "title" => "Приказы по преподавательской деятельности",
                        "link" => WEB_ROOT."_modules/_orders/index.php?action=view&id=".$person->getId(),
                        "icon" => "actions/address-book-new.png"
                    ),
                    array(
                        "title" => "Добавить приказ по преподавательской деятельности",
                        "link" => WEB_ROOT."_modules/_orders/index.php?action=add&id=".$person->getId(),
                        "icon" => "actions/list-add.png"
                    ),
                )
            ),
            array(
                "title" => "Печать по шаблону",
                "link" => "#",
                "icon" => "devices/printer.png",
                "template" => "formset_person"
            ),
            array(
                "title" => "Импортировать информацию о сотруднике",
                "link" => "staffInfo.php?action=add&id=".CRequest::getInt("id"),
                "icon" => "actions/document-save.png"
            )
        ));
        /**
         * Отображение
         */
        $this->renderView("_staff/person/edit.tpl");
    }
    public function actionSave() {
        $form = new CPersonForm();
        $form->setAttributes(CRequest::getArray($form::getClassName()));
        if ($form->validate()) {
            $form->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$form->person->getId());
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        $this->setData("form", $form);
        $this->renderView("_staff/person/edit.tpl");
    }
    public function actionDelete() {
        $person = CStaffManager::getPerson(CRequest::getInt("id"));
        $person->remove();
        $this->redirect("?action=index");
    }
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("query");
        /**
         * Поиск по ФИО
         */
        $query = new CQuery();
        $query->select("person.id as id, person.fio as name")
            ->from(TABLE_PERSON." as person")
            ->condition("person.fio like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
            	"field" => "person.id",
        		"value" => $item["id"],
        		"label" => $item["name"],
        		"class" => "CPerson"
            );
        }
        /**
         * Поиск по типу участия на кафедре
         */
        $query = new CQuery();
        $query->select("types.person_type_id as id, person_types.name as name")
	        ->from(TABLE_PERSON_BY_TYPES." as types")
	        ->innerJoin(TABLE_TYPES." as person_types", "types.person_type_id = person_types.id")
            ->condition("person_types.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
            	"field" => "types.person_type_id",
            	"value" => $item["id"],
            	"label" => $item["name"],
            	"class" => "CTerm"
            );
        }
        /**
         * Поиск по полу
         */
        $query = new CQuery();
        $query->select("pol.id as id, pol.name as name")
            ->from(TABLE_GENDERS." as pol")
            ->condition("pol.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
            	"field" => "pol.id",
            	"value" => $item["id"],
            	"label" => $item["name"],
            	"class" => "CTerm"
            );
        }
        /**
         * Поиск по роли на кафедре
         */
        $query = new CQuery();
        $query->select("term.id as id, term.name as name")
            ->from(TABLE_TAXONOMY_TERMS." as term")
            ->innerJoin(TABLE_TAXONOMY." as taxonomy", "term.taxonomy_id = taxonomy.id AND taxonomy.alias='department_roles'")
            ->condition("term.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
            	"field" => "term.id",
            	"value" => $item["id"],
            	"label" => $item["name"],
            	"class" => "CTerm"
            );
        }
        /**
         * Поиск по семейному положению
         */
        $query = new CQuery();
        $query->select("fam_st.id as id, fam_st.name as name")
            ->from(TABLE_FAMILY_STATUS." as fam_st")
            ->condition("fam_st.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
            	"field" => "fam_st.id",
            	"value" => $item["id"],
            	"label" => $item["name"],
            	"class" => "CTerm"
            );
        }
        /**
         * Поиск по иностранному языку
         */
        $query = new CQuery();
        $query->select("lang.id as id, lang.name as name")
            ->from(TABLE_LANGUAGES." as lang")
            ->condition("lang.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
            	"field" => "lang.id",
            	"value" => $item["id"],
            	"label" => $item["name"],
            	"class" => "CTerm"
            );
        }
        /**
         * Поиск по должности
         */
        $query = new CQuery();
        $query->select("post.id as id, post.name as name")
            ->from(TABLE_POSTS." as post")
            ->condition("post.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(		
            	"field" => "post.id",
            	"value" => $item["id"],
            	"label" => $item["name"],
            	"class" => "CTerm"
            );
        }
        /**
         * Поиск по званию
         */
        $query = new CQuery();
        $query->select("zvanie.id as id, zvanie.name as name")
            ->from(TABLE_TITLES." as zvanie")
            ->condition("zvanie.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
            	"field" => "zvanie.id",
            	"value" => $item["id"],
            	"label" => $item["name"],
            	"class" => "CTerm"
            );
        }
        /**
         * Поиск по ученой степени
         */
        $query = new CQuery();
        $query->select("stepen.id as id, stepen.name as name")
            ->from(TABLE_DEGREES." as stepen")
            ->condition("stepen.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
            	"field" => "stepen.id",
            	"value" => $item["id"],
            	"label" => $item["name"],
            	"class" => "CTerm"
            );
        }
        /**
         * Полнотекстовый поиск по остальным полям
         */
        $fields = array(
            "passp_seria",
            "passp_nomer",
            "date_rogd",
            "INN",
            "insurance_num",
            "add_work",
            "tel_work",
            "add_home",
            "tel_home",
            "e_mail",
            "site",
            "ekspert_spec",
            "ekspert_kluch_slova",
            "nagradi",
            "primech",
            "add_contact"
        );
        foreach ($fields as $field) {
        	$query = new CQuery();
        	$query->select("person.id as id, person.".$field." as name")
	        	->from(TABLE_PERSON." as person")
	        	->condition("person.".$field." like '%".$term."%'")
	        	->limit(0, 5);
        	foreach ($query->execute()->getItems() as $item) {
        		$res[] = array(
        				"field" => "person.id",
        				"value" => $item["id"],
        				"label" => $item["name"],
        				"class" => "CPerson"
        		);
        	}
        }
        echo json_encode($res);
    }
}
