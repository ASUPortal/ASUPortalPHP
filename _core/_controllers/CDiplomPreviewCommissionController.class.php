<?php
class CDiplomPreviewCommissionController extends CBaseController {
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

        $this->_useDojo = true;
        $this->_smartyEnabled = true;
        $this->setPageTitle("Предзащита ВКР - комиссии");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("com.*");
        $query->innerJoin(TABLE_DIPLOM_PREVIEW_MEMBERS." as members", "com.secretary_id=members.kadri_id");
        $query->innerJoin(TABLE_PERSON." as person", "members.kadri_id = person.id")
            ->from(TABLE_DIPLOM_PREVIEW_COMISSIONS." as com")
            ->order("com.date_act desc");
        $showAll = true;
        if (CRequest::getInt("showAll") != 1) {
            $query->condition('com.date_act between "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" and "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_end)).'"');
            $showAll = false;
        }
        if (CRequest::getString("order") == "person.fio") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_DIPLOM_PREVIEW_MEMBERS." as members", "com.secretary_id=members.kadri_id");
        		$query->innerJoin(TABLE_PERSON." as person", "members.kadri_id = person.id");
        		$query->order("person.fio ".$direction);
        }
        $items = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $commission = new CDiplomPreviewComission($ar);
            $items->add($commission->getId(), $commission);
        }
        $this->setData("showAll", $showAll);
        $this->setData("commissions", $items);
        $this->setData("paginator", $set->getPaginator());
		$this->addActionsMenuItem(array(
            array(
                "title" => "Добавить комиссию",
                "link" => "?action=add",
                "icon" => "actions/list-add.png"
            )
        ));			
        $this->renderView("_diploms/preview_commission/index.tpl");
    }
    public function actionEdit() {
        $commission = CSABManager::getPreviewCommission(CRequest::getInt("id"));
        $form = new CDiplomPreviewCommissionForm();
        $form->commission = $commission;
        $this->setData("form", $form);
		$this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "preview_comm.php?action=index",
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_diploms/preview_commission/edit.tpl");
    }
    public function actionAdd() {
        $commission = new CDiplomPreviewComission();
        $form = new CDiplomPreviewCommissionForm();
        $commission->date_act = date("d.m.Y", mktime());
        $form->commission = $commission;
        $this->setData("form", $form);
		$this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "preview_comm.php?action=index",
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_diploms/preview_commission/add.tpl");
    }
    public function actionSave() {
        $form = new CDiplomPreviewCommissionForm();
        $form->setAttributes(CRequest::getArray($form::getClassName()));
        if ($form->validate()) {
            $form->save();
			if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$form->commission->getId());
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        $this->setData("form", $form);
        $this->renderView("_diploms/preview_commission/edit.tpl");
    }
    public function actionDelete() {
        $commission = CSABManager::getPreviewCommission(CRequest::getInt("id"));
        $commission->remove();
        $this->redirect("?action=index");
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по комиссии
    	*/
    	$query = new CQuery();
    	$query->select("distinct(com.id) as id, com.name as title")
	    	->from(TABLE_DIPLOM_PREVIEW_COMISSIONS." as com")
	    	->condition("com.name like '%".$term."%'")
	    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "com.id",
    				"value" => $item["id"],
    				"label" => $item["title"],
    				"class" => "CDiplomPreviewCommission"
    		);
    	}
    	/**
    	 * Поиск по секретарю
    	 */
    	$query = new CQuery();
    	$query->select("distinct(members.kadri_id) as id, person.fio as title");
    	$query->innerJoin(TABLE_DIPLOM_PREVIEW_MEMBERS." as members", "com.secretary_id=members.kadri_id");
    	$query->innerJoin(TABLE_PERSON." as person", "members.kadri_id = person.id")
	    	->from(TABLE_DIPLOM_PREVIEW_COMISSIONS." as com")
	    	->condition("person.fio like '%".$term."%'")
	    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "kadri_id",
    				"value" => $item["id"],
    				"label" => $item["title"],
    				"class" => "CDiplomPreviewCommission"
    		);
    	}
    	/**
    	 * Поиск по примечанию
    	 */
    	$query = new CQuery();
    	$query->select("distinct(com.id) as id, com.comment as title")
    	->from(TABLE_DIPLOM_PREVIEW_COMISSIONS." as com")
    	->condition("com.comment like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "com.id",
    				"value" => $item["id"],
    				"label" => $item["title"],
    				"class" => "CDiplomPreviewCommission"
    		);
    	}
    	/**
    	 * Поиск по дате создания комиссии
    	 */
    	/*$query = new CQuery();
    	$query->select("distinct(com.id) as id, com.date_act as title")
    	->from(TABLE_DIPLOM_PREVIEW_COMISSIONS." as com")
    	->condition("com.date_act like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "com.id",
    				"value" => $item["id"],
    				"label" => $item["title"],
    				"class" => "CDiplomPreviewCommission"
    		);
    	}*/
    	echo json_encode($res);
    }
}