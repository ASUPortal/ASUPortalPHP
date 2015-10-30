<?php
class CWorkPlanSelfEducationBlocksController extends CBaseController{
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
        $this->setPageTitle("Управление какими-то объектами");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_SELFEDUCATION." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id"));
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
            "title" => "Добавить",
            "link" => "workplanselfeducationblocks.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/selfEducationBlocks/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanSelfEducationBlock();
        $load = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        $object->load_id = $load->getId();
        $object->plan_id = $load->section->category->plan_id;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentloads.php?action=edit&id=".$object->load_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/selfEducationBlocks/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanSelfEducationBlock(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentloads.php?action=edit&id=".$object->load_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/selfEducationBlocks/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanSelfEducationBlock(CRequest::getInt("id"));
        $load = $object->load_id;
        $object->remove();
        $this->redirect("workplancontentloads.php?action=edit&id=".$load);
    }
    public function actionSave() {
        $object = new CWorkPlanSelfEducationBlock();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontentloads.php?action=edit&id=".$object->load_id);
            } else {
                $this->redirect("workplancontentloads.php?action=edit&id=".$object->load_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/selfEducationBlocks/edit.tpl");
    }
}