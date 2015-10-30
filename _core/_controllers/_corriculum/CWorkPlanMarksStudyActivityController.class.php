<?php
class CWorkPlanMarksStudyActivityController extends CBaseController{
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
        $this->setPageTitle("Управление баллами видов учебной деятельности");

        parent::__construct();
    }
public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_MARKS_STUDY_ACTIVITY." as t")
            ->order("t.id asc")
            ->condition("activity_id=".CRequest::getInt("id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanMarkStudyActivity($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplanmarksstudyactivity.php?action=add&id=".CRequest::getInt("id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/studyActMarks/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanMarkStudyActivity();
        $object->activity_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanmarksstudyactivity.php?action=index&id=".$object->activity_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/studyActMarks/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanMarkStudyActivity(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanmarksstudyactivity.php?action=index&id=".$object->activity_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/studyActMarks/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanMarkStudyActivity(CRequest::getInt("id"));
        $activity = $object->activity_id;
        $object->remove();
        $this->redirect("workplanmarksstudyactivity.php?action=index&id=".$activity);
    }
    public function actionSave() {
        $object = new CWorkPlanMarkStudyActivity();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplanmarksstudyactivity.php?action=index&id=".$object->activity_id);
            } else {
                $this->redirect("workplanmarksstudyactivity.php?action=index&id=".$object->activity_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/studyActMarks/edit.tpl");
    }
}