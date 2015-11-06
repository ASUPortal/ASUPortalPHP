<?php
class CWorkPlanContentSectionLoadsController extends CBaseController{
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
        $this->setPageTitle("Нагрузка");

        parent::__construct();
    }
    public function actionIndex() {
        $section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        $this->setData("section", $section);
        $this->setData("loads", $section->loads);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontentloads.php?id=".$section->getId(),
            "icon" => "actions/view-refresh.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionAdd() {
        $section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        $this->setData("section", $section);
        $this->setData("loads", $section->loads);

        $newLoad = new CWorkPlanContentSectionLoad();
        $newLoad->section_id = $section->getId();
        $this->setData("editSectionLoad", $newLoad);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontentloads.php?id=".$section->getId(),
            "icon" => "actions/view-refresh.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionEdit() {
        $load = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        $section = $load->section;
        $this->setData("editSectionLoad", $load);
        $this->setData("section", $load->section);
        $this->setData("loads", $section->loads);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontentloads.php?id=".$section->getId(),
            "icon" => "actions/view-refresh.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        $section = $object->section_id;
        $object->remove();
        $this->redirect("workplancontentloads.php?action=index&section_id=".$section);
    }
    public function actionSave() {
        $object = new CWorkPlanContentSectionLoad();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            $this->redirect("workplancontentloads.php?action=index&id=".$object->section_id);
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
}