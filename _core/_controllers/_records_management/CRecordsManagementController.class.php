<?php
/**
 * Управление записями таблиц базы данных
 */
class CRecordsManagementController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }
        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление записями таблиц базы данных");

        parent::__construct();
    }
    public function actionIndex() {
    	$query = new CQuery();
    	$query->query("SHOW DATABASES");
    	$bases = array();
    	foreach ($query->execute()->getItems() as $values) {
    		foreach ($values as $base) {
    			$bases[$base] = $base;
    		}
    	}
    	$this->setData("bases", $bases);
    	$this->renderView("_records_management/index.tpl");
    }
    public function actionTables() {
    	$base = CRequest::getString("base");
    	$this->setData("base", $base);
    	
    	$query = new CQuery();
    	$query->select("TABLE_NAME, TABLE_COMMENT")
	    	->from("information_schema.TABLES")
	    	->condition("TABLE_SCHEMA = '".$base."'");
    	$tables = array();
    	foreach ($query->execute()->getItems() as $table) {
    		if ($table["TABLE_COMMENT"] != "") {
    			$tables[$table["TABLE_NAME"]] = $table["TABLE_COMMENT"]." (".$table["TABLE_NAME"].")";
    		}
    	}
    	$this->setData("tables", $tables);
    	
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "К выбору базы",
    			"link" => WEB_ROOT."_modules/_records_management/index.php",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	
        $this->renderView("_records_management/tables.tpl");
    }
    public function actionRequest() {
    	$base = CRequest::getString("base");
    	$table = CRequest::getString("table");
    	$this->setData("base", $base);
    	$this->setData("table", $table);
    	
    	$query = new CQuery();
    	$query->select("COLUMN_NAME, COLUMN_COMMENT")
	    	->from("information_schema.COLUMNS")
	    	->condition("TABLE_NAME = '".$table."'");
    	$fields = array();
    	foreach ($query->execute()->getItems() as $column) {
    		if ($column["COLUMN_NAME"] == "id") {
    			$fields[$column["COLUMN_NAME"]] = "id";
    		} elseif ($column["COLUMN_COMMENT"] != "") {
    			$fields[$column["COLUMN_NAME"]] = $column["COLUMN_COMMENT"]." (".$column["COLUMN_NAME"].")";
    		}
    	}
    	$this->setData("fields", $fields);
    	
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "К выбору базы",
    			"link" => WEB_ROOT."_modules/_records_management/index.php",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	
    	$this->renderView("_records_management/request.tpl");
    }
    public function actionSearchDuplicates() {
    	$base = CRequest::getString("base");
    	$table = CRequest::getString("table");
    	$this->setData("base", $base);
    	$this->setData("tableFound", $table);
    	
    	$firstFieldName = CRequest::getString("firstFieldName");
    	$secondFieldName = CRequest::getString("secondFieldName");
    	$firstFieldValue = CRequest::getString("firstFieldValue");
    	$secondFieldValue = CRequest::getString("secondFieldValue");
    	$this->setData("firstFieldName", $firstFieldName);
    	$this->setData("secondFieldName", $secondFieldName);
    	$this->setData("firstFieldValue", $firstFieldValue);
    	$this->setData("secondFieldValue", $secondFieldValue);
    	
    	if ($secondFieldValue == "") {
    		$queryFields = new CQuery();
    		$queryFields->select("t.*")
	    		->from($table." as t")
	    		->condition("t.".$firstFieldName." = '".$firstFieldValue."'");
    	} else {
    		$queryFields = new CQuery();
    		$queryFields->select("t.*")
	    		->from($table." as t")
	    		->condition("t.".$firstFieldName." = '".$firstFieldValue."' and t.".$secondFieldName." = '".$secondFieldValue."'");
    	}
    	$records = new CArrayList();
    	foreach ($queryFields->execute()->getItems() as $ar) {
    		$record = new CActiveModel(new CActiveRecord($ar));
    		$records->add($record->getId(), $record);
    	}
    	$this->setData("records", $records);
    	
    	$queryTables = new CQuery();
    	$queryTables->select("TABLE_NAME, TABLE_COMMENT")
	    	->from("information_schema.TABLES")
	    	->condition("TABLE_SCHEMA = '".$base."'");
    	$tables = array();
    	foreach ($queryTables->execute()->getItems() as $item) {
    		if ($item["TABLE_COMMENT"] != "") {
    			$tables[$item["TABLE_NAME"]] = $item["TABLE_COMMENT"]." (".$item["TABLE_NAME"].")";
    		}
    	}
    	$this->setData("tables", $tables);
    	
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "К выбору базы",
    			"link" => WEB_ROOT."_modules/_records_management/index.php",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	
    	$this->renderView("_records_management/found.tpl");
    }
    public function actionSearchInTable() {
    	$table = CRequest::getString("table");
    	$tableFound = CRequest::getString("tableFound");
    	$this->setData("table", $table);
    	$this->setData("tableFound", $tableFound);
    	
    	$fields = array();
    	$query = new CQuery();
    	$query->select("COLUMN_NAME, COLUMN_COMMENT")
	    	->from("information_schema.COLUMNS")
	    	->condition("TABLE_NAME = '".$table."'");
    	$fields = array();
    	foreach ($query->execute()->getItems() as $column) {
    		if ($column["COLUMN_NAME"] == "id") {
    			$fields[$column["COLUMN_NAME"]] = "id";
    		} elseif ($column["COLUMN_COMMENT"] != "") {
    			$fields[$column["COLUMN_NAME"]] = $column["COLUMN_COMMENT"]." (".$column["COLUMN_NAME"].")";
    		}
    	}
    	$this->setData("fields", $fields);
    	$this->setData("items", CRequest::getArray("selectedInView"));
    	
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "К выбору базы",
    			"link" => WEB_ROOT."_modules/_records_management/index.php",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	
    	$this->renderView("_records_management/searchInTable.tpl");
    }
    public function actionResultSearchInTable() {
    	$table = CRequest::getString("table");
    	$fieldSearch = CRequest::getString("fieldSearch");
    	$this->setData("table", $table);
    	$this->setData("fieldSearch", $fieldSearch);
    	
    	$items = explode(",", CRequest::getString("items"));
    	$this->setData("items", $items);
    	$arr = array();
    	foreach ($items as $key=>$value) {
    		$arr[] = "t.".$fieldSearch." = '".$value."'";
    	}
    	$query = new CQuery();
    	$query->select("t.*")
	    	->from($table." as t")
	    	->condition(implode(" or ", $arr));
    	$records = new CArrayList();
    	foreach ($query->execute()->getItems() as $ar) {
    		$record = new CActiveModel(new CActiveRecord($ar));
    		$records->add($record->getId(), $record);
    	}
    	$this->setData("records", $records);
    	
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "К выбору базы",
    			"link" => WEB_ROOT."_modules/_records_management/index.php",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	
    	$this->renderView("_records_management/resultSearchInTable.tpl");
    }
    public function actionReplace() {
    	$table = CRequest::getString("table");
    	$fieldSearch = CRequest::getString("fieldSearch");
    	$this->setData("table", $table);
    	$this->setData("fieldSearch", $fieldSearch);
    	
    	$valueReplace = CRequest::getString("valueReplace");
    	$items = CRequest::getArray("selectedInView");
    	foreach ($items as $item) {
    		$ar = CActiveRecordProvider::getById($table, $item);
    		$ar->setItemValue($fieldSearch, $valueReplace);
    		$ar->update();
    	}
    	
    	$arr = array();
    	foreach ($items as $key=>$value) {
    		$arr[] = "t.".$fieldSearch." = '".$valueReplace."'";
    	}
    	$query = new CQuery();
    	$query->select("t.*")
	    	->from($table." as t")
	    	->condition(implode(" or ", $arr));
    	 
    	$records = new CArrayList();
    	foreach ($query->execute()->getItems() as $ar) {
    		$record = new CActiveModel(new CActiveRecord($ar));
    		$records->add($record->getId(), $record);
    	}
    	$this->setData("records", $records);
    	
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "К выбору базы",
    			"link" => WEB_ROOT."_modules/_records_management/index.php",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	
    	$this->renderView("_records_management/resultSearchInTable.tpl");
    }
    public function actionDelete() {
    	$id = CRequest::getInt("id");
    	$table = CRequest::getString("table");
    	$ar = CActiveRecordProvider::getById($table, $id);
    	if (!is_null($ar)) {
    		$ar->remove();
    	}
    	$this->redirect("?action=index");
    }
}