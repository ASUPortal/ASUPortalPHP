<?php
/**
 * Таблица для учебной нагрузки
 */

class CStudyLoadTable extends CFormModel {
    private $_load = null;
    private $_workTypes = null;
    private $_workTypesAlias = null;
    public $load_id;

    /**
     * @param CStudyLoad $load
     */
    function __construct(CStudyLoad $load) {
        if (!is_null($load)) {
            $this->_load = $load;
            $this->load_id = $load->getId();
        }
    }

    /**
     * @return array
     */
    private function getWorktypes() {
        if (is_null($this->_workTypes)) {
            $this->_workTypes = array();
            foreach (CTaxonomyManager::getLegacyTaxonomy(TABLE_WORKLOAD_WORK_TYPES)->getTerms()->getItems() as $term) {
                $this->_workTypes[$term->getId()] = $term->getValue();
            }
        }
        return $this->_workTypes;
    }

    /**
     * @return array
     */
    private function getWorktypesAlias() {
        if (is_null($this->_workTypesAlias)) {
            $this->_workTypesAlias = array();
            foreach (CTaxonomyManager::getLegacyTaxonomy(TABLE_WORKLOAD_WORK_TYPES)->getTerms()->getItems() as $term) {
                $this->_workTypesAlias[$term->getId()] = $term->name_hours_kind;
            }
        }
        return $this->_workTypesAlias;
    }

    /**
     * @return CStudyLoad|null
     */
    public function getLoad() {
        return $this->_load;
    }

    /**
     * @return array
     */
    public function getTable() {
        $result = array();
        foreach ($this->getWorktypes() as $key=>$type) {
        	$row = array();
        	
        	// тип работы
        	$row[0] = $type;
        	
        	// бюджет
        	$row[CTaxonomyManager::getTaxonomy("hours_kind")->getTerm("budgetStudyLoad")->getId()] = $this->getLoadByType(CTaxonomyManager::getTaxonomy("hours_kind")->getTerm("budgetStudyLoad")->getId(), $key);
        	
        	// коммерция
        	$row[CTaxonomyManager::getTaxonomy("hours_kind")->getTerm("contractStudyLoad")->getId()] = $this->getLoadByType(CTaxonomyManager::getTaxonomy("hours_kind")->getTerm("contractStudyLoad")->getId(), $key);
        	
        	$result[$key] = $row;
        } 
        return $result;
    }

    /**
     * Нагрузка по виду (бюджет/контракт) 
     * и типу (лекция, практика, ргр)
     * 
     * @param $kind
     * @param $type
     * @return int
     */
    private function getLoadByType($kind, $type) {
        $result = 0;
        foreach ($this->getLoad()->getWorksByType($type)->getItems() as $work) {
        	if ($work->kind_id == $kind ) {
        		$result += $work->workload;
        	}
        }
        return $result;
    }
    
    public function getFieldName($typeId, $kindId) {
        return "CModel[data][".$typeId."][".$kindId."]";
    }
    
    public function save() {
    	// удаляем старые данные
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD_WORKS, "workload_id=".$this->getLoad()->getId())->getItems() as $ar) {
    		$ar->remove();
    	}
    	// добавляем новые
    	foreach ($this->data as $typeId=>$works) {
    		foreach ($works as $kindId=>$value) {
    			$obj = new CStudyLoadWork();
    			$obj->workload_id = $this->getLoad()->getId();
    			$obj->type_id = $typeId;
    			$obj->kind_id = $kindId;
    			$obj->workload = $value;
    			$obj->save();
    		}
    	}
    }
}
