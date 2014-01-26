<?php
class #controllerName# extends CBaseController{
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
        $this->setPageTitle("#pageTitle#");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(#modelTable#." as t")
            ->order("t.id asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new #modelName#($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить сотрудника",
            "link" => "#controllerFile#?action=add",
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("#viewPath#/index.tpl");
    }
    public function actionAdd() {
        $object = new #modelName#();
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "#controllerFile#?action=index",
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("#viewPath#/add.tpl");
    }
    public function actionEdit() {
        $object = #modelManager#::#modelManagerGetter#(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "#controllerFile#?action=index",
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("#viewPath#/edit.tpl");
    }
    public function actionDelete() {
        $object = #modelManager#::#modelManagerGetter#(CRequest::getInt("id"));
        $object->remove();
        $this->redirect("#controllerFile#?action=index");
    }
    public function actionSave() {
        $object = new #modelName#();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("#controllerFile#?action=edit&id=".$object->getId());
            } else {
                $this->redirect("#controllerFile#?action=index");
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("#viewPath#/edit.tpl");
    }
}