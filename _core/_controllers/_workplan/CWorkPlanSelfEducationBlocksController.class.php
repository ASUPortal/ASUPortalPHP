<?php
class CWorkPlanSelfEducationBlocksController extends CBaseController{
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
        $this->setPageTitle("Управление вопросами для самостоятельного изучения");
        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_SELFEDUCATION." as t")
            ->order("t.ordering asc")
            ->condition("plan_id=".CRequest::getInt("plan_id")." and _deleted=0");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanSelfEducationBlock($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
        	"title" => "Обновить",
        	"link" => "workplanselfeducationblocks.php?action=index&plan_id=".CRequest::getInt("plan_id"),
        	"icon" => "actions/view-refresh.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/selfEducationBlocks/index.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanSelfEducationBlock(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanselfeducationblocks.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/selfEducationBlocks/edit.tpl");
    }
    public function actionDelete() {
    	$object = CBaseManager::getWorkPlanSelfEducationBlock(CRequest::getInt("id"));
    	$plan = CWorkPlanManager::getWorkplan($object->plan_id);
    	$object->remove();
    	$order = 1;
    	foreach ($plan->selfEducations as $selfEdu) {
    		$selfEdu->ordering = $order++;
    		$selfEdu->save();
    	}
    	$this->redirect("workplanselfeducationblocks.php?action=index&plan_id=".$object->plan_id);
    }
    public function actionSave() {
        $object = new CWorkPlanSelfEducationBlock();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplanselfeducationblocks.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplanselfeducationblocks.php?action=index&plan_id=".$object->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/selfEducationBlocks/edit.tpl");
    }
}