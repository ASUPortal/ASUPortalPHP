<?php
class CWorkPlanQuestionsToExaminationController extends CBaseController{
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
        $this->setPageTitle("Управление вопросами к экзамену");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_QUESTIONS_TO_EXAMINATION." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanQuestionsToExamination($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню 
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplanqeustionstoexamination.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/questionsToExamination/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanQuestionsToExamination();
        $object->plan_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanqeustionstoexamination.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/questionsToExamination/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanQuestionsToExamination(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanqeustionstoexamination.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/questionsToExamination/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanQuestionsToExamination(CRequest::getInt("id"));
        $plan = $object->plan_id;
        $object->remove();
        $this->redirect("workplanqeustionstoexamination.php?action=index&plan_id=".$plan);
    }
    public function actionSave() {
        $object = new CWorkPlanQuestionsToExamination();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplanqeustionstoexamination.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplanqeustionstoexamination.php?action=index&plan_id=".$object->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/questionsToExamination/edit.tpl");
    }
}