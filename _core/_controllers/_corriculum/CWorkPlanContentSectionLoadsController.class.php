<?php

class CWorkPlanContentSectionLoadsController extends CStatefullFormController {
    protected $_isComponent = true;

    function handle_submitForm_load($formData = array(), $element = '') {
        $load = new CWorkPlanContentSectionLoad();
        $load->setAttributes($formData);
        if ($load->validate()) {
            $load->save();
            self::getStatefullFormBean()->getElement($element)->setShow(true);
        } else {
            self::getStatefullFormBean()->getElement($element)->setValidationErrors($load->getValidationErrors());
        }
    }

    function handle_toggleDelete_load() {
        $loadId = CRequest::getInt('model_id');
        $load = CBaseManager::getWorkPlanContentSectionLoad($loadId);
        $load->markDeleted(!$load->isMarkDeleted());
        $load->save();
    }

    function before_render() {
        if ($this->getStatefullFormBean()->getElement('load_new')->isStateNotSet()) {
            $this->getStatefullFormBean()->getElement('load_new')->setShow(true);
        }
    }

    function handle_before_changeState_load_new() {
        $newLoad = new CWorkPlanContentSectionLoad();
        $newLoad->section_id = CRequest::getInt('id');
        $this->setData('newLoad', $newLoad);
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