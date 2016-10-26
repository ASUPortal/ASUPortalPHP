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
    		$template = $form->title;
    		try {
    			$writer = CPrintService::getTemplateWriter($form, $plan);
    			$wordTemplate = $writer->loadTemplate();
    			$fieldsFromTemplate = $wordTemplate->getFields();
    			foreach ($fieldsFromTemplate as $templateField) {
    				$field = CPrintService::getFieldValue($templateField->getName(), $plan, $form);
    				if ($field->getFieldType() == "text") {
    					$countFields += 1;
    					if ($field->evaluateValue($plan) == "") {
    						$countEmptyTextFields += 1;
    						$emptyTextFields[] = $field->title;
    					} else {
    						$countFullFields += 1;
    					}
    				} elseif ($field->getFieldType() == "table") {
    					$countFields += 1;
    					if (empty($field->evaluateValue($plan))) {
    						$countEmptyTableFields += 1;
    						$emptyTableFields[] = $field->title;
    					} else {
    						$countFullFields += 1;
    					}
    				}
    			}
    			$wordTemplate->deleteTempFile();
    		} catch (Exception $e) {
    			$this->setData("error", $e->getMessage());
    		}
    	} else {
    		$template = "не выбран!";
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
    	$this->setData("template", $template);
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