<?php

class CWorkPlanContentSectionLoadsController extends CStatefullFormController {
    protected $_isComponent = true;

    function render() {
        /* @var $section CWorkPlanContentSection */
        $section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontentloads.php?id=".$section->getId(),
            "icon" => "actions/view-refresh.png"
        ));

        $this->setData("bean", $this->getStatefullFormBean());
        $this->setData("section", $section);
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
}