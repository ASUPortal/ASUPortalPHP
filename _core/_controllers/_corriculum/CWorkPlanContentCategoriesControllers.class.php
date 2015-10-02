<?php
class CWorkPlanContentCategoriesControllers extends CBaseController{
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
        $this->setPageTitle("Управление категориями");

        parent::__construct();
    }
    public function actionIndex() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $this->setData("objects", $plan->modules);
        /**
         * Генерация меню
         */
        $menu = array(
            array(
                "title" => "Обновить",
                "link" => "workplancontentcategories.php?action=index&plan_id=".CRequest::getInt("plan_id"),
                "icon" => "actions/view-refresh.png"
            ), array(
                "title" => "Добавить модуль",
                "link" => "workplancontentcategories.php?action=add&id=".CRequest::getInt("plan_id"),
                "icon" => "actions/list-add.png"
            )
        );
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        if ($plan->modules->getCount() > 0) {
            $menu[] = array(
                "title" => "Добавить раздел",
                "link" => "workplancontentsections.php?action=add&id=".$plan->modules->getFirstItem()->getId(),
                "icon" => "actions/list-add.png"
            );
        }
        $menu[] = array(
            "title" => "К списку",
            "link" => "workplancontentcategories.php?action=list&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/format-justify-fill.png"
        );
        $this->addActionsMenuItem($menu);
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentModules/index.tpl");
    }
    public function actionList() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_CONTENT_MODULES." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id"))
            ->order("t.order asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanContentCategory($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $menu = array(
            array(
                "title" => "Обновить",
                "link" => "workplancontentcategories.php?action=list&plan_id=".CRequest::getInt("plan_id"),
                "icon" => "actions/view-refresh.png"
            ), array(
                "title" => "К полному представлению",
                "link" => "workplancontentcategories.php?action=index&id=".CRequest::getInt("plan_id"),
                "icon" => "actions/edit-undo.png"
            ), array(
                "title" => "Добавить категорию",
                "link" => "workplancontentcategories.php?action=add&id=".CRequest::getInt("plan_id"),
                "icon" => "actions/list-add.png"
            )
        );
        $this->addActionsMenuItem($menu);
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentModules/list.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanContentCategory();
        $object->plan_id = CRequest::getInt("id");
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $object->order = $plan->modules->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplans.php?action=edit&id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentModules/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanContentCategory(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplans.php?action=edit&id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить раздел",
            "link" => "workplancontentsections.php?action=add&id=".$object->getId(),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentModules/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanContentCategory(CRequest::getInt("id"));
        $plan = $object->plan_id;
        $object->remove();
        $this->redirect("workplans.php?action=edit&id=".$plan);
    }
    public function actionSave() {
        $object = new CWorkPlanContentCategory();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontentcategories.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancontentcategories.php?action=index&plan_id=".$object->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/contentModules/edit.tpl");
    }
}