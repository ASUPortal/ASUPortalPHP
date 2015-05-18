<?php

class CStaffTimeController extends CBaseController{
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
        $this->setPageTitle("Табель сотрудников кафедры");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("person.*")
        	->innerJoin(TABLE_STAFF_ORDERS." as orders", "person.id = orders.kadri_id")
        	->innerJoin(TABLE_PERSON_BY_TYPES." as type", "person.id = type.kadri_id")
            ->from(TABLE_PERSON." as person")
            ->order("person.fio asc")
        	->condition("orders.order_active=1");
        $PPS = false;
        if (CRequest::getInt("PPS") == 1) {
        	$query->condition("type.person_type_id=1 and orders.order_active=1");
        	$PPS = true;
        }
        $UVP = false;
        if (CRequest::getInt("UVP") == 1) {
        	$query->condition("type.person_type_id=3 and orders.order_active=1");
        	$UVP = true;
        }
        $persons = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $person = new CPersonTime($ar);
            $persons->add($person->getId(), $person);
        }
        $this->setData("paginator", $set->getPaginator());
        $this->setData("PPS", $PPS);
        $this->setData("UVP", $UVP);
        $this->setData("persons", $persons);
        $this->renderView("_staff/time/index.tpl");
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по ФИО сотрудника
    	 */
    	$query = new CQuery();
    	$query->select("distinct(person.id) as id, person.fio as name")
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
    	echo json_encode($res);
    }
}
