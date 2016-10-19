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

    function handle_submitForm_topic_load($formData = array(), $element = '') {
        $topic = new CWorkPlanContentSectionLoadTopic();
        $topic->setAttributes($formData);
        if ($topic->validate()) {
            $topic->save();
            self::getStatefullFormBean()->getElement($element)->setShow(false);
        } else {
            self::getStatefullFormBean()->getElement($element)->setValidationErrors($topic->getValidationErrors());
        }
    }

    function handle_submitForm_technology_load($formData = array(), $element = '') {
        $technology = new CWorkPlanContentSectionLoadTechnology();
        $technology->setAttributes($formData);
        if ($technology->validate()) {
            $technology->save();
            self::getStatefullFormBean()->getElement($element)->setShow(false);
        } else {
            self::getStatefullFormBean()->getElement($element)->setValidationErrors($technology->getValidationErrors());
        }
    }

    function handle_submitForm_education_load($formData = array(), $element = '') {
        $education = new CWorkPlanSelfEducationBlock();
        $education->setAttributes($formData);
        if ($education->validate()) {
            $education->save();
            self::getStatefullFormBean()->getElement($element)->setShow(false);
        } else {
            self::getStatefullFormBean()->getElement($element)->setValidationErrors($education->getValidationErrors());
        }
    }

    function handle_before_changeState_technology_load_new() {
        $newTechnology = new CWorkPlanContentSectionLoadTechnology();
        $newTechnology->load_id = CRequest::getInt('load_id');
        $this->setData('newTechnology', $newTechnology);
    }

    function handle_before_changeState_topic_load_new() {
        $newTopic = new CWorkPlanContentSectionLoadTopic();
        $newTopic->load_id = CRequest::getInt('load_id');
        $this->setData('newTopic', $newTopic);
    }

    function handle_before_changeState_education_load_new() {
        /* @var $section CWorkPlanContentSection */
        $section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        $newEducation = new CWorkPlanSelfEducationBlock();
        $newEducation->plan_id = $section->category->plan_id;
        $newEducation->load_id = CRequest::getInt('load_id');
        $this->setData('newEducation', $newEducation);
    }

    function handle_toggleDelete_topic_load() {
        $topic_id = CRequest::getInt("model_id");
        $topic = CBaseManager::getWorkPlanContentSectionLoadTopic($topic_id);
        $topic->markDeleted(!$topic->isMarkDeleted());
        $topic->save();
    }

    function handle_toggleDelete_technology_load() {
        $technology_id = CRequest::getInt("model_id");
        $technology = CBaseManager::getWorkPlanContentSectionLoadTechnology($technology_id);
        $technology->markDeleted(!$technology->isMarkDeleted());
        $technology->save();
    }

    function handle_toggleDelete_load() {
        $loadId = CRequest::getInt('model_id');
        $load = CBaseManager::getWorkPlanContentSectionLoad($loadId);
        $load->markDeleted(!$load->isMarkDeleted());
        $load->save();
    }

    function handle_toggleDelete_education_load() {
        $education_id = CRequest::getInt('model_id');
        $education = CBaseManager::getWorkPlanSelfEducationBlock($education_id);
        $education->markDeleted(!$education->isMarkDeleted());
        $education->save();
    }

    function before_render() {
        if ($this->getStatefullFormBean()->getElement('load_new')->isStateNotSet()) {
            $this->getStatefullFormBean()->getElement('load_new')->setShow(true);
        }
        /* @var $section CWorkPlanContentSection */
        $section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        foreach ($section->loads as $load) {
            $elements[] = 'topic_load_' . $load->getId() . '_new';
            $elements[] = 'technology_load_' . $load->getId() . '_new';
            $elements[] = 'education_load_' . $load->getId() . '_new';
            foreach ($elements as $element) {
                if ($this->getStatefullFormBean()->getElement($element)->isStateNotSet()) {
                    $this->getStatefullFormBean()->getElement($element)->setShow(true);
                }
            }
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
        if (CRequest::getInt("showDeleted") == 1) {
        	$sectionLoads = $section->loads;
        	$this->addActionsMenuItem(array(
        		"title" => "Скрыть удалённые",
        		"link" => "workplancontentloads.php?id=".$section->getId().'&bean='.$this->getStatefullFormBean()->getBeanId(),
        		"icon" => "actions/format-text-bold.png"
        	));
        } else {
        	$sectionLoads = $section->loadsDisplay;
        	$this->addActionsMenuItem(array(
        		"title" => "Показать удалённые",
        		"link" => "workplancontentloads.php?id=".$section->getId().'&bean='.$this->getStatefullFormBean()->getBeanId()."&showDeleted=1",
        		"icon" => "actions/format-text-strikethrough.png"
        	));
        }

        $this->setData("bean", $this->getStatefullFormBean());
        $this->setData("section", $section);
        $this->setData("sectionLoads", $sectionLoads);
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
}