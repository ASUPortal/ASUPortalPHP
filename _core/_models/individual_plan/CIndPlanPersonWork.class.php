<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 04.11.13
 * Time: 18:40
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanPersonWork extends CActiveModel{
    protected $_table = TABLE_IND_PLAN_WORKS;
    public $load_id;
    public $work_type;
    public $is_executed = 0;
    public $separate_contract = 0;
    protected $_load = null;
    protected $_workType = null;
    protected $_publication = null;

    public function relations() {
        return array(
            "load" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_load",
                "storageField" => "load_id",
                "managerClass" => "CIndPlanManager",
                "managerGetObject" => "getLoad"
            ),
            "workType" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_workType",
                "storageField" => "title_id",
                "managerClass" => "CIndPlanManager",
                "managerGetObject" => "getWorktype"
            ),
            "publication" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_publication",
                "storageField" => "title_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPublication"
            )
        );
    }
    protected function modelValidators() {
        return array(
            new CIndPlanPersonLoadModelOptionalValidator()
        );
    }
    public function getTitle() {
        $result = "";
        if ($this->work_type == CIndPlanPersonWorkType::STUDY_AND_METHODICAL_LOAD) {
            if (!is_null($this->workType)) {
                $result = $this->workType->name;
            }
        } elseif ($this->work_type == CIndPlanPersonWorkType::SCIENTIFIC_METHODICAL_LOAD) {
            if (!is_null($this->workType)) {
                $result = $this->workType->name;
            }
        } elseif ($this->work_type == CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD) {
            if (!is_null($this->workType)) {
                $result = $this->workType->name;
            }
        } elseif ($this->work_type == CIndPlanPersonWorkType::LIST_SCIENTIFIC_WORKS) {
            if (!is_null($this->publication)) {
                $result = $this->publication->name;
            }
        } elseif ($this->work_type == CIndPlanPersonWorkType::ORGANIZATIONAL_AND_METHODICAL_LOAD) {
            if (!is_null($this->workType)) {
                $result = $this->workType->name;
            }
        } elseif ($this->work_type == CIndPlanPersonWorkType::ASPIRANTS_LOAD) {
            if (!is_null($this->workType)) {
                $result = $this->workType->name;
            }
        }
        return $result;
    }
    public function isExecuted() {
        if ($this->is_executed == 1) {
            return "Вып";
        }
        return "";
    }
    
    /**
     * Отмечена ли запись как нередактируемая
     *
     * @return bool
     */
    public function isEditRestriction() {
    	if ($this->_edit_restriction == 1 or $this->load->_edit_restriction == 1) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    /**
     * Атрибут нередактируемой записи
     *
     * @return string
     */
    public function restrictionAttribute() {
    	$attribute = "";
    	if ($this->isEditRestriction()) {
    		$attribute = "readonly";
    	}
    	return $attribute;
    }
}
