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
         * Набираем выборку
         */
        $persons = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $person = new CPerson($ar);
            $persons->add($person->getId(), $person);
        }
        /**
         * Все передаем в представление
         */
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
            $this->redirect("?action=index");
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

    }
}