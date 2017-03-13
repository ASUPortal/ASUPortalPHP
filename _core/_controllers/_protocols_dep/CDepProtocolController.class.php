<?php

class CDepProtocolController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Протоколы кафедры");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("distinct protocol.*")
        	->from(TABLE_DEPARTMENT_PROTOCOLS." as protocol")
        	->leftJoin(TABLE_DEP_PROTOCOL_AGENDA." as details", "protocol.id = details.protocol_id")
        	->order("protocol.date_text desc");
        $set->setQuery($query);
        $onControl = false;
        if (CRequest::getInt("onControl") == 1) {
        	$query->condition("details.on_control = 1");
        	$onControl = true;
        }
        $protocols = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $protocol = new CDepartmentProtocol($item);
            $protocols->add($protocol->getId(), $protocol);
        }
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Добавить протокол",
        		"link" => "index.php?action=add",
        		"icon" => "actions/list-add.png"
        	)
        ));
        $this->setData("onControl", $onControl);
        $this->setData("protocols", $protocols);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_protocols_dep/protocol/index.tpl");
    }
    public function actionView() {
    	$protocol = CProtocolManager::getDepProtocol(CRequest::getInt("id"));
    	
    	// посещаемость заседаний
    	$visits = $protocol->visits;
    	$comparator = new CPersonByFioComparator();
    	$protocolVisits = CCollectionUtils::sort($visits, $comparator);
    	
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => "index.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		),
    		array(
    			"title" => "Печать по шаблону",
    			"link" => "#",
    			"icon" => "devices/printer.png",
    			"template" => "formset_protocols_department"
    		)
    	));
    	$this->setData("protocol", $protocol);
    	$this->setData("protocolVisits", $protocolVisits);
    	$this->renderView("_protocols_dep/protocol/view.tpl");
    }
    public function actionEdit() {
        $protocol = CProtocolManager::getDepProtocol(CRequest::getInt("id"));
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "index.php?action=index",
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->setData("protocol", $protocol);
        $this->renderView("_protocols_dep/protocol/edit.tpl");
    }
    public function actionAdd() {
        $protocol = new CDepartmentProtocol();
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "index.php?action=index",
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->setData("protocol", $protocol);
        $this->renderView("_protocols_dep/protocol/add.tpl");
    }
    public function actionSave() {
        $protocol = new CDepartmentProtocol();
        $protocol->setAttributes(CRequest::getArray($protocol::getClassName()));
        if ($protocol->validate()) {
            $protocol->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$protocol->getId());
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("protocol", $protocol);
        $this->renderView("_protocols_dep/protocol/add.tpl");
    }
    public function actionDelete() {
        $protocol = CProtocolManager::getDepProtocol(CRequest::getInt("id"));
        $protocol->remove();
        $this->redirect("?action=index");
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по дате
    	*/
    	$query = new CQuery();
    	$query->select("distinct(protocol.id) as id, protocol.date_text as name")
	    	->from(TABLE_DEPARTMENT_PROTOCOLS." as protocol")
	    	->condition("protocol.date_text like '%".$term."%'")
	    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "protocol.id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CDepartmentProtocol"
    		);
    	}
    	/**
    	 * Поиск по тексту повестки
    	 */
    	$query = new CQuery();
    	$query->select("distinct(protocol.id) as id, protocol.program_content as content")
	    	->from(TABLE_DEPARTMENT_PROTOCOLS." as protocol")
	    	->condition("protocol.program_content like '%".$term."%'")
	    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "protocol.id",
    				"value" => $item["id"],
    				"label" => $item["content"],
    				"class" => "CDepartmentProtocol"
    		);
    	}
    	/**
    	 * Поиск по комментарию
    	 */
    	$query = new CQuery();
    	$query->select("distinct(protocol.id) as id, protocol.comment as comment")
	    	->from(TABLE_DEPARTMENT_PROTOCOLS." as protocol")
	    	->condition("protocol.comment like '%".$term."%'")
	    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "protocol.id",
    				"value" => $item["id"],
    				"label" => $item["comment"],
    				"class" => "CDepartmentProtocol"
    		);
    	}
    	echo json_encode($res);
    }
}
