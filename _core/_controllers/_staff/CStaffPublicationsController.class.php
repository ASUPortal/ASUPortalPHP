<?php
class CStaffPublicationsController extends CBaseController{
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
        $this->setPageTitle("Управление публикациями");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_PUBLICATIONS." as t")
            ->order("t.id asc");
        if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY or
            CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {

            $query->innerJoin(TABLE_PUBLICATION_BY_PERSONS." as p", "p.izdan_id = t.id");
            $query->condition("p.kadri_id=".CSession::getCurrentPerson()->getId());
        }
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CPublication($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить сотрудника",
            "link" => "publications.php?action=add",
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_staff/publications/index.tpl");
    }
    public function actionAdd() {
        $object = new CPublication();
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "publications.php?action=index",
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_staff/publications/add.tpl");
    }
    public function actionEdit() {
        $object = CStaffManager::getPublication(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "publications.php?action=index",
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_staff/publications/edit.tpl");
    }
    public function actionDelete() {
        $object = CStaffManager::getPublication(CRequest::getInt("id"));
        $object->remove();
        $this->redirect("publications.php?action=index");
    }
    public function actionSave() {
        $object = new CPublication();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("publications.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("publications.php?action=index");
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_staff/publications/edit.tpl");
    }
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("query");
        /**
         * Сначала поищем по названию публикации
         */
        $query = new CQuery();
        $query->select("distinct(pub.id) as id, pub.name as title")
            ->from(TABLE_PUBLICATIONS." as pub")
            ->condition("pub.name like '%".$term."%'")
            ->limit(0, 5);
        if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY or
            CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {

            $query->innerJoin(TABLE_PUBLICATION_BY_PERSONS." as p", "p.izdan_id = pub.id");
            $query->condition("p.kadri_id=".CSession::getCurrentPerson()->getId());
        }
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "field" => "t.id",
                "value" => $item["id"],
                "label" => $item["title"],
                "class" => "CPublication"
            );
        }
        echo json_encode($res);
   }
}
