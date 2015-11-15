<?php

class CWorkPlanContentSectionLoadsController extends CStatefullFormController {
    protected $_isComponent = true;

    function submitForm_load($formData = array(), $element = '') {
        $load = new CWorkPlanContentSectionLoad();
        $load->setAttributes($formData);
        if ($load->validate()) {
            $load->save();
            self::getStatefullFormBean()->getElement($element)->setShow(true);
        } else {
            self::getStatefullFormBean()->getElement($element)->setValidationErrors($load->getValidationErrors());
        }
    }


    function render() {
        /* @var $section CWorkPlanContentSection */
        $section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontentloads.php?id=".$section->getId().'&bean='.$this->getStatefullFormBean()->getBeanId(),
            "icon" => "actions/view-refresh.png"
        ));

        $this->setData("bean", $this->getStatefullFormBean());
        $this->setData("section", $section);
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
}