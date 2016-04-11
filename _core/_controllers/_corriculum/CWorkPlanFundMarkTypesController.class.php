<?php
class CWorkPlanFundMarkTypesController extends CBaseController{
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
        $this->setPageTitle("Управление фондом оценочных средств");

        parent::__construct();
    }
    public function actionIndex() {
    	$section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_FUND_MARK_TYPES." as t")
            ->order("t.ordering asc")
            ->condition("section_id=".CRequest::getInt("id")." and plan_id=".$section->category->plan_id." and _deleted=0");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanFundMarkType($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
        	"title" => "Обновить",
        	"link" => "workplanfundmarktypes.php?action=index&id=".CRequest::getInt("id"),
        	"icon" => "actions/view-refresh.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplanfundmarktypes.php?action=add&id=".CRequest::getInt("id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/fundMarkTypes/index.tpl");
    }
    public function actionView() {
    	$set = new CRecordSet();
    	$query = new CQuery();
    	$set->setQuery($query);
    	$query->select("t.*")
	    	->from(TABLE_WORK_PLAN_FUND_MARK_TYPES." as t")
	    	->order("t.ordering asc")
	    	->condition("plan_id=".CRequest::getInt("plan_id")." and _deleted=0");
    	$objects = new CArrayList();
    	foreach ($set->getPaginated()->getItems() as $ar) {
    		$object = new CWorkPlanFundMarkType($ar);
    		$objects->add($object->getId(), $object);
    	}
    	$this->setData("objects", $objects);
    	$this->setData("paginator", $set->getPaginator());
    	/**
    	 * Генерация меню
    	 */
    	$this->addActionsMenuItem(array(
    		"title" => "Обновить",
    		"link" => "workplanfundmarktypes.php?action=view&plan_id=".CRequest::getInt("plan_id"),
    		"icon" => "actions/view-refresh.png"
    	));
    	/**
    	 * Отображение представления
    	*/
    	$this->renderView("_corriculum/_workplan/fundMarkTypes/view.tpl");
    }
    public function actionAdd() {
    	$section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt($section->category->plan_id));
        $object = new CWorkPlanFundMarkType();
        $object->section_id = CRequest::getInt("id");
        $object->plan_id = $section->category->plan_id;
        $object->ordering = $plan->fundMarkTypes->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanfundmarktypes.php?action=index&id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/fundMarkTypes/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanFundMarkType(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanfundmarktypes.php?action=index&id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/fundMarkTypes/edit.tpl");
    }
    public function actionDelete() {
        if (CRequest::getInt("amp;view") == 1) {
        	$object = CBaseManager::getWorkPlanFundMarkType(CRequest::getInt("id"));
        	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("amp;plan_id"));
        	$object->markDeleted(true);
        	$object->save();
        	$order = 1;
        	foreach ($plan->fundMarkTypes as $fundMarkType) {
        		$fundMarkType->ordering = $order++;
        		$fundMarkType->save();
        	}
        	$this->redirect("workplanfundmarktypes.php?action=view&plan_id=".$plan->getId());
        } else {
        	$object = CBaseManager::getWorkPlanFundMarkType(CRequest::getInt("id"));
        	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("amp;plan_id"));
        	$section = $object->section;
        	$object->markDeleted(true);
        	$object->save();
        	$order = 1;
        	foreach ($plan->fundMarkTypes as $fundMarkType) {
        		$fundMarkType->ordering = $order++;
        		$fundMarkType->save();
        	}
        	$this->redirect("workplanfundmarktypes.php?action=index&id=".$section->getId());
        }
        
    }
    public function actionSave() {
        $object = new CWorkPlanFundMarkType();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplanfundmarktypes.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplanfundmarktypes.php?action=index&id=".$object->section_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/fundMarkTypes/edit.tpl");
    }
}