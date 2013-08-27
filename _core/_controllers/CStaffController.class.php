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
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        /**
         * Исходный запрос
         */
        $query->select("person.*")
            ->from(TABLE_PERSON." as person")
            ->order("person.fio asc");
        /**
         * Сортировки
         */
        $direction = "asc";
        if (CRequest::getString("direction") !== "") {
            $direction = CRequest::getString("direction");
        }
        if (CRequest::getString("order") == "types") {
            $query->innerJoin(TABLE_PERSON_BY_TYPES." as pt1", "pt1.kadri_id = person.id")
                ->innerJoin(TABLE_TYPES." as type1", "type1.id = pt1.person_type_id")
                ->order("type1.name ".$direction);
        } elseif (CRequest::getString("order") == "fio") {
            $query->order("person.fio ".$direction);
        }
        /**
         *
         */
        $selectedPerson = null;
        $selectedType = null;
        /**
         * Запросы для фильтров
         */
        $queryTypes = new CQuery();
        $queryTypes->select("type.id, type.name")
            ->from(TABLE_TYPES." as type")
            ->order("type.name asc");
        /**
         * Фильтры
         * -------
         *
         * Выбор конкретного сотрудника
         */
        if (!is_null(CRequest::getFilter("person"))) {
            $query->condition("person.id = ".CRequest::getFilter("person"));
            $selectedPerson = CStaffManager::getPerson(CRequest::getFilter("person"));
            $queryTypes->innerJoin(TABLE_PERSON_BY_TYPES." as pt", "type.id = pt.person_type_id AND pt.kadri_id=".$selectedPerson->getId());
        }
        /**
         * Тип участия на кафедре
         */
        if (!is_null(CRequest::getFilter("type"))) {
            $query->innerJoin(TABLE_PERSON_BY_TYPES." as type", "person.id = type.kadri_id AND type.person_type_id=".CRequest::getFilter("type"));
            $selectedType = CTaxonomyManager::getLegacyTaxonomy("person_types")->getTerm(CRequest::getFilter("type"))->getId();
        }
        /**
         * Пол
         */
        $this->setData("selectedGender", null);
        if (!is_null(CRequest::getFilter("gender"))) {
            $query->condition("person.pol = ".CRequest::getFilter("gender"));
            $queryTypes->innerJoin(TABLE_PERSON_BY_TYPES." as pt", "type.id = pt.person_type_id")
            ->innerJoin(TABLE_PERSON." as p", "pt.kadri_id = p.id")
            ->condition("p.pol = ".CRequest::getFilter("gender"));
            /**
             * Пол добавляем в фильтры только если по нему искали
             */
            $genders = array();
            foreach (CTaxonomyManager::getCacheGenders()->getItems() as $gender) {
                $genders[$gender->getId()] = $gender->getValue();
            }
            $this->setData("genders", $genders);
            $this->setData("selectedGender", CRequest::getFilter("gender"));
        }
        /**
         * Семейное положение
         */
        $this->setData("selectedFamily", null);
        if (!is_null(CRequest::getFilter("family"))) {
            $query->condition("person.family_status=".CRequest::getFilter("family"));
            $queryTypes->innerJoin(TABLE_PERSON_BY_TYPES." as pt", "type.id = pt.person_type_id")
                ->innerJoin(TABLE_PERSON." as p", "pt.kadri_id = p.id")
                ->condition("p.family_status = ".CRequest::getFilter("family"));
            /**
             * Семейное положение добавляем в фильтры только если по нему искали
             */
            $familyStatuses = CTaxonomyManager::getLegacyTaxonomy("family_status")->getTermsList();
            $this->setData("familyStatuses", $familyStatuses);
            $this->setData("selectedFamily", CRequest::getFilter("family"));
        }
        /**
         * Роль на кафедре
         */
        $this->setData("selectedRole", null);
        if (!is_null(CRequest::getFilter("role"))) {
            $query->condition("person.department_role_id=".CRequest::getFilter("role"));
            $queryTypes->innerJoin(TABLE_PERSON_BY_TYPES." as pt", "type.id = pt.person_type_id")
                ->innerJoin(TABLE_PERSON." as p", "pt.kadri_id = p.id")
                ->condition("p.department_role_id = ".CRequest::getFilter("role"));
            /**
             * Роль добавляем только если по ней искали
             */
            $roles = CTaxonomyManager::getTaxonomy("department_roles")->getTermsList();
            $this->setData("roles", $roles);
            $this->setData("selectedRole", CRequest::getFilter("role"));
        }
        /**
         * Иностранный язык
         */
        $this->setData("selectedLanguage", null);
        if (!is_null(CRequest::getFilter("language"))) {
            $query->condition("person.language1=".CRequest::getFilter("language"));
            $queryTypes->innerJoin(TABLE_PERSON_BY_TYPES." as pt", "type.id = pt.person_type_id")
                ->innerJoin(TABLE_PERSON." as p", "pt.kadri_id = p.id")
                ->condition("p.language1 = ".CRequest::getFilter("language"));
            /**
             * добавляем в фильтры только если по нему искали
             */
            $languages = CTaxonomyManager::getLegacyTaxonomy("language")->getTermsList();
            $this->setData("languages", $languages);
            $this->setData("selectedLanguage", CRequest::getFilter("language"));
        }
        /**
         * Должность
         */
        $this->setData("selectedPost", null);
        if (!is_null(CRequest::getFilter("post"))) {
            $query->condition("person.dolgnost=".CRequest::getFilter("post"));
            $queryTypes->innerJoin(TABLE_PERSON_BY_TYPES." as pt", "type.id = pt.person_type_id")
                ->innerJoin(TABLE_PERSON." as p", "pt.kadri_id = p.id")
                ->condition("p.dolgnost = ".CRequest::getFilter("post"));
            /**
             * добавляем в фильтры только если по нему искали
             */
            $posts = CTaxonomyManager::getLegacyTaxonomy("dolgnost")->getTermsList();
            $this->setData("posts", $posts);
            $this->setData("selectedPost", CRequest::getFilter("post"));
        }
        /**
         * Звание
         */
        $this->setData("selectedTitle", null);
        if (!is_null(CRequest::getFilter("title"))) {
            $query->condition("person.zvanie=".CRequest::getFilter("title"));
            $queryTypes->innerJoin(TABLE_PERSON_BY_TYPES." as pt", "type.id = pt.person_type_id")
                ->innerJoin(TABLE_PERSON." as p", "pt.kadri_id = p.id")
                ->condition("p.zvanie = ".CRequest::getFilter("title"));
            /**
             * добавляем в фильтры только если по нему искали
             */
            $titles = CTaxonomyManager::getLegacyTaxonomy("zvanie")->getTermsList();
            $this->setData("titles", $titles);
            $this->setData("selectedTitle", CRequest::getFilter("title"));
        }
        /**
         * Ученая степень
         */
        $this->setData("selectedDegree", null);
        if (!is_null(CRequest::getFilter("degree"))) {
            $query->condition("person.stepen=".CRequest::getFilter("degree"));
            $queryTypes->innerJoin(TABLE_PERSON_BY_TYPES." as pt", "type.id = pt.person_type_id")
                ->innerJoin(TABLE_PERSON." as p", "pt.kadri_id = p.id")
                ->condition("p.stepen = ".CRequest::getFilter("degree"));
            /**
             * добавляем в фильтры только если по нему искали
             */
            $degrees = CTaxonomyManager::getLegacyTaxonomy("stepen")->getTermsList();
            $this->setData("degrees", $degrees);
            $this->setData("selectedDegree", CRequest::getFilter("degree"));
        }
        /**
         * Набираем выборку
         */
        $persons = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $person = new CPerson($ar);
            $persons->add($person->getId(), $person);
        }
        /**
         * Выборка по фильтрам
         */
        $types = array();
        foreach ($queryTypes->execute()->getItems() as $item) {
            $types[$item["id"]] = $item["name"];
        }
        $this->setData("types", $types);
        $this->setData("selectedType", $selectedType);
        /**
         * Все передаем в представление
         */
        $this->setData("selectedPerson", $selectedPerson);
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("paginator", $set->getPaginator());
        $this->setData("persons", $persons);
        $this->renderView("_staff/person/index.tpl");
    }
    public function actionAdd() {
        $form = new CPersonForm();
        $person = new CPerson();
        $form->person = $person;
        $this->setData("form", $form);
        $this->renderView("_staff/person/add.tpl");
    }
    public function actionEdit() {
        $form = new CPersonForm();
        $person = CStaffManager::getPerson(CRequest::getInt("id"));
        $form->person = $person;
        /**
         * Подключаем красивые элементы
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("form", $form);
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
        $term = CRequest::getString("term");
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
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "filter" => "person"
            );
        }
        /**
         * Поиск по типу участия на кафедре
         */
        $query = new CQuery();
        $query->select("type.id as id, type.name as name")
            ->from(TABLE_TYPES." as type")
            ->condition("type.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "filter" => "type"
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
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "filter" => "gender"
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
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "filter" => "role"
            );
        }
        /**
         * Поиск по семейному положению
         */
        $query = new CQuery();
        $query->select("s.id as id, s.name as name")
            ->from("family_status as s")
            ->condition("s.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "filter" => "family"
            );
        }
        /**
         * Поиск по иностранному языку
         */
        $query = new CQuery();
        $query->select("s.id as id, s.name as name")
            ->from("language as s")
            ->condition("s.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "filter" => "language"
            );
        }
        /**
         * Поиск по должности
         */
        $query = new CQuery();
        $query->select("s.id as id, s.name as name")
            ->from(TABLE_POSTS." as s")
            ->condition("s.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "filter" => "post"
            );
        }
        /**
         * Поиск по званию
         */
        $query = new CQuery();
        $query->select("s.id as id, s.name as name")
            ->from("zvanie as s")
            ->condition("s.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "filter" => "title"
            );
        }
        /**
         * Поиск по ученой степени
         */
        $query = new CQuery();
        $query->select("s.id as id, s.name as name")
            ->from("stepen as s")
            ->condition("s.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "filter" => "degree"
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
        $query = new CQuery();
        $query->select("person.*")
            ->from(TABLE_PERSON." as person")
            ->condition("MATCH (".implode($fields, ", ").") AGAINST ('".$term."')")
            ->limit(0, 5);
        $objects = new CArrayList();
        foreach ($query->execute()->getItems() as $ar) {
            $person = new CPerson(new CActiveRecord($ar));
            $objects->add($person->getId(), $person);
        }
        foreach ($objects->getItems() as $person) {
            foreach ($fields as $field) {
                if (strpos($person->$field, $term) !== false) {
                    $labels = $person->attributeLabels();
                    if (array_key_exists($field, $labels)) {
                        $label = $labels[$field];
                    } else {
                        $label = $field;
                    }
                    $res[] = array(
                        "label" => $person->getName()." (".$label.": ".$person->$field.")",
                        "value" => $person->getName()." (".$label.": ".$person->$field.")",
                        "object_id" => $person->getId(),
                        "filter" => "person"
                    );
                }
            }
        }
        echo json_encode($res);
    }
}