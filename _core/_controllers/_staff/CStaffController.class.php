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
        $set = new CRecordSet(true, true);
        $query = new CQuery();
        $set->setQuery($query);
        /**
         * Исходный запрос
         */
        $query->select("person.*")
            ->from(TABLE_PERSON." as person")
            ->order("person.fio asc");
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
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить сотрудника",
            "link" => "index.php?action=add",
            "icon" => "actions/list-add.png"
        ));
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
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("form", $form);
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