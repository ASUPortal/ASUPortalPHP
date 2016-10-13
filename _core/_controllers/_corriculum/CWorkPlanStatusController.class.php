<?php
class CWorkPlanStatusController extends CBaseController{
    protected $_isComponent = true;

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
        $this->setPageTitle("Управление статусом рабочей программы");

        parent::__construct();
    }
    public function actionIndex() {
    	$queryTemplates = new CQuery();
    	$queryTemplates->select("template.*")
	    	->from(TABLE_PRINT_FORMS." as template")
	    	->condition("template.formset_id = 11")
	    	->order("template.title asc");
    	$templates = array();
    	foreach ($queryTemplates->execute()->getItems() as $item) {
    		$templates[$item["id"]] = $item["title"];
    	}
        $this->addActionsMenuItem(array(
        	"title" => "Обновить",
        	"link" => "workplanstatus.php?action=index&id=".CRequest::getInt("id"),
        	"icon" => "actions/view-refresh.png"
        ));
        $this->setData("id", CRequest::getInt("id"));
        $this->setData("templates", $templates);
        $this->renderView("_corriculum/_workplan/status/index.tpl");
    }
    public function actionView() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
    	$countFields = 0;
    	$countFullFields = 0;
    	$countEmptyTextFields = 0;
    	$countEmptyTableFields = 0;
    	$emptyTextFields = array();
    	$emptyTableFields = array();
    	if (CRequest::getInt("template") != 0) {
    		$form = CPrintManager::getForm(CRequest::getInt("template"));
    		if (file_exists(PRINT_TEMPLATES_DIR.$form->template_file)) {
    			$writer = new CPHPOdt();
    			$wordTemplate = $writer->loadTemplate(PRINT_TEMPLATES_DIR.$form->template_file);
    			$fieldsFromTemplate = $wordTemplate->getFields();
    			foreach ($fieldsFromTemplate as $fieldName=>$descriptors) {
    				if (!is_null($form->formset->getFieldByName($fieldName))) {
    					$field = $form->formset->getFieldByName($fieldName);
    				} elseif (mb_strpos($fieldName, ".class") !== false) {
    					$classFieldName = CUtils::strLeft($fieldName, ".class");
    					$classField = new $classFieldName();
    					$field = new CPrintClassFieldToFieldAdapter($classField, $plan);
    				}
    				if ($field->type_id == "1" || $field->type_id == "0") {
    					$countFields += 1;
    					if ($field->evaluateValue($plan) == "") {
    						$countEmptyTextFields += 1;
    						$emptyTextFields[] = $field->title;
    					} else {
    						$countFullFields += 1;
    					}
    				} elseif ($field->type_id == "2") {
    					$countFields += 1;
    					if (empty($field->evaluateValue($plan))) {
    						$countEmptyTableFields += 1;
    						$emptyTableFields[] = $field->title;
    					} else {
    						$countFullFields += 1;
    					}
    				}
    			}
    		} else {
    			$this->setData("error", "Не найден шаблон!");
    		}
    	}
    	if ($countFields != 0) {
    		$percentFull = round(($countFullFields/$countFields)*100, 2);
    	} else {
    		$percentFull = 0;
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => "workplanstatus.php?action=index&id=".$plan->getId(),
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->setData("countFields", $countFields);
    	$this->setData("countFullFields", $countFullFields);
    	$this->setData("countEmptyTextFields", $countEmptyTextFields);
    	$this->setData("countEmptyTableFields", $countEmptyTableFields);
    	$this->setData("emptyTextFields", $emptyTextFields);
    	$this->setData("emptyTableFields", $emptyTableFields);
    	$this->setData("percentFull", $percentFull);
    	$this->renderView("_corriculum/_workplan/status/view.tpl");
    }
}