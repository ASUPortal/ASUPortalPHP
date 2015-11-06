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
    private function fillDefaults($sectionId) {
        $section = $section = CBaseManager::getWorkPlanContentSection($sectionId);
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

        return $section;
    }
    public function actionIndex() {
        $this->fillDefaults(CRequest::getInt("id"));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionAdd() {
        $section = $this->fillDefaults(CRequest::getInt("id"));

        $newLoad = new CWorkPlanContentSectionLoad();
        $newLoad->section_id = $section->getId();
        $this->setData("editSectionLoad", $newLoad);
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionEdit() {
        $load = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        $this->fillDefaults($load->section_id);
        $this->setData("editSectionLoad", $load);
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionAddTopic() {
        $load = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        $this->setData("expand", $load->getId());
        $section = $this->fillDefaults($load->section_id);
        $topic = new CWorkPlanContentSectionLoadTopic();
        $topic->load_id = $load->getId();
        $this->setData("editLoadTopic", $topic);
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionEditTopic() {
        $topic = CBaseManager::getWorkPlanContentSectionLoadTopic(CRequest::getInt("id"));
        $this->setData("expand", $topic->load_id);
        $this->fillDefaults($topic->load->section_id);
        $this->setData("editLoadTopic", $topic);
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionSaveTopic() {
        $topic = new CWorkPlanContentSectionLoadTopic();
        $topic->setAttributes(CRequest::getArray($topic::getClassName()));
        if ($topic->validate()) {
            $topic->save();
            $this->redirect("workplancontentloads.php?action=expand&id=".$topic->load_id);
            return true;
        }
        $this->fillDefaults($topic->load->section_id);
        $this->setData("editLoadTopic", $topic);
        $this->setData("expand", $topic->load_id);
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionExpand() {
        $load = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        $this->setData("expand", $load->getId());
        $section = $this->fillDefaults($load->section_id);
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        $section = $object->section_id;
        $object->remove();
        $this->redirect("workplancontentloads.php?action=index&id=".$section);
    }
    public function actionSave() {
        $object = new CWorkPlanContentSectionLoad();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            $this->redirect("workplancontentloads.php?action=index&id=".$object->section_id);
            return true;
        }
        $this->fillDefaults($object->section_id);
        $this->setData("editSectionLoad", $object);
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
}