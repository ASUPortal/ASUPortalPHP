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
        $set = new CRecordSet(false);
        $query = new CQuery();
        $set->setQuery($query);
        $personList = array();
        $currentPerson = null;
        $currentType = null;
        $query->select("t.*")
            ->from(TABLE_PUBLICATIONS." as t")
            ->order("t.id asc");
        if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY or
            CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {

            $query->innerJoin(TABLE_PUBLICATION_BY_PERSONS." as p", "p.izdan_id = t.id");
            $query->condition("p.kadri_id=".CSession::getCurrentPerson()->getId());
            $currentPerson = CSession::getCurrentPerson()->getId();
            $personList[$currentPerson] = CSession::getCurrentPerson()->getName();
        } else {
            $personList = array();
            $personQuery = new CQuery();
            $personQuery->select("person.id, person.fio")
                ->from(TABLE_PERSON." as person")
                ->innerJoin(TABLE_PUBLICATION_BY_PERSONS." as p", "p.kadri_id = person.id")
                ->order("person.fio asc");
            foreach ($personQuery->execute()->getItems() as $arr) {
                $personList[$arr["id"]] = $arr["fio"];
            }
            // фильтр по сотруднику
            if (CRequest::getInt("kadri_id") != 0) {
                $currentPerson = CRequest::getInt("kadri_id");
                $query->innerJoin(TABLE_PUBLICATION_BY_PERSONS." as p", "p.izdan_id = t.id");
                $query->condition("p.kadri_id=".$currentPerson);
            }
        }
        if (CRequest::getString("order") == "year") {
        	$direction = "asc";
        	if (CRequest::getString("direction") == "desc") {
        		$direction = "desc";
        	}
        	$query->order('STR_TO_DATE(year, "%Y") '.$direction);
        }
        // фильтр по виду издания
        if (!is_null(CRequest::getFilter("type.id"))) {
        	$currentType = CRequest::getFilter("type.id");
        	$query->innerJoin(TABLE_PUBLICATIONS_TYPES." as type", "t.type_book = type.id and type.id IN (".CRequest::getFilter("type.id").")");
        }
        // фильтр по названию
        if (!is_null(CRequest::getFilter("title"))) {
        	$query->condition("t.id = ".CRequest::getFilter("title"));
        }
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CPublication($ar);
            $objects->add($object->getId(), $object);
        }
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy("izdan_type");
        $sort = new CArrayList();
        foreach ($taxonomy->getTerms()->getItems() as $i) {
        	$sort->add($i->getValue(), $i->getId());
        }
        $izdanTypes = array();
        foreach ($sort->getSortedByKey(true)->getItems() as $i) {
        	$item = $taxonomy->getTerms()->getItem($i);
        	$izdanTypes[$item->getId()] = $item->getValue();
        }
        $this->setData("currentPerson", $currentPerson);
        $this->setData("currentType", $currentType);
        $this->setData("personList", $personList);
        $this->setData("izdanTypes", $izdanTypes);
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
        		array(
        				"title" => "Добавить публикацию",
        				"link" => "publications.php?action=add",
        				"icon" => "actions/list-add.png"
        		),
        		array(
        				"title" => "Печать по шаблону",
        				"link" => "#",
        				"icon" => "devices/printer.png",
        				"template" => "formset_publications"
        		),
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
            	"label" => $item["title"],
            	"value" => $item["title"],
            	"object_id" => $item["id"],
            	"type" => 1
            );
        }
        echo json_encode($res);
    }
}
