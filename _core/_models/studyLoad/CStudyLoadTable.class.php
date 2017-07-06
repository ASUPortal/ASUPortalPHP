<?php
/**
 * Таблица для учебной нагрузки
 */

class CStudyLoadTable extends CFormModel {
    private $_load = null;
    private $_workTypes = null;
    private $_workTypesIsTotal = null;
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
    private function getWorktypesIsTotal() {
    	if (is_null($this->_workTypesIsTotal)) {
    		$this->_workTypesIsTotal = array();
    		foreach (CTaxonomyManager::getLegacyTaxonomy(TABLE_WORKLOAD_WORK_TYPES)->getTerms()->getItems() as $term) {
    			if ($term->is_total) {
    				$this->_workTypesIsTotal[$term->getId()] = $term->getValue();
    			}
    		}
    	}
    	return $this->_workTypesIsTotal;
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
     * Таблица для редактирования нагрузки (бюджет и контракт)
     * 
     * @return array
     */
    public function getTable() {
        $result = array();
        foreach ($this->getWorktypes() as $key=>$type) {
        	$row = array();
        	
        	// тип работы
        	$row[0] = $type;
        	
        	// бюджет
        	$row[CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::BUDGET)->getId()] = $this->getLoadByKindAndType(CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::BUDGET)->getId(), $key);
        	
        	// коммерция
        	$row[CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::CONTRACT)->getId()] = $this->getLoadByKindAndType(CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::CONTRACT)->getId(), $key);
        	
        	$result[$key] = $row;
        } 
        return $result;
    }
    
    /**
     * Таблица для просмотра нагрузки (бюджет и контракт вместе)
     * 
     * @return array
     */
    public function getTableTotal() {
    	$result = array();
    	foreach ($this->getWorktypes() as $key=>$type) {
    		$row = array();
    		 
    		// тип работы
    		$row[0] = $type;
    		 
    		// бюджет и коммерция
    		$row[1] = $this->getLoadByType($key);
    		
    		$result[$key] = $row;
    	}
    	return $result;
    }
    
    /**
     * Таблица для редактирования нагрузки по типу (бюджет или контракт)
     * 
     * @param boolean $isBudget
     * @param boolean $isContract
     * @return array
     */
    public function getTableByKind($isBudget = false, $isContract = false) {
    	$result = array();
    	foreach ($this->getWorktypes() as $key=>$type) {
    		$row = array();
    		 
    		// тип работы
    		$row[0] = $type;
    		
    		// бюджет
    		if ($isBudget) {
    			$row[CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::BUDGET)->getId()] = $this->getLoadByKindAndType(CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::BUDGET)->getId(), $key);
    		}
    		
    		// коммерция
    		if ($isContract) {
    			$row[CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::CONTRACT)->getId()] = $this->getLoadByKindAndType(CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::CONTRACT)->getId(), $key);
    		}
    		
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
    private function getLoadByKindAndType($kind, $type) {
        $result = 0;
        foreach ($this->getLoad()->getWorksByType($type)->getItems() as $work) {
        	if ($work->kind_id == $kind) {
        		$result += $work->workload;
        	}
        }
        return $result;
    }
    
    /**
     * Нагрузка по типу (лекция, практика, ргр)
     *
     * @param $type
     * @return int
     */
    private function getLoadByType($type) {
    	$result = 0;
    	foreach ($this->getLoad()->getWorksByType($type)->getItems() as $work) {
    		$result += $work->workload;
    	}
    	return $result;
    }
    
    /**
     * Поле для ввода значений для редактирования одной нагрузки
     * 
     * @param int $typeId - идентификатор вида нагрузки
     * @param int $kindId - идентификатор типа нагруки (бюджет/контракт)
     * @return string
     */
    public function getFieldName($typeId, $kindId) {
        return "CModel[data][".$typeId."][".$kindId."]";
    }
    
    /**
     * Поле для ввода значений для редактирования всех нагрузок преподавателя
     * 
     * @param int $studyLoadId - идентификатор нагрузки
     * @param int $typeId - идентификатор вида нагрузки
     * @param int $kindId - идентификатор типа нагруки (бюджет/контракт)
     * @return string
     */
    public function getFieldNameAllLoads($studyLoadId, $typeId, $kindId) {
        return "data[".$studyLoadId."][".$typeId."][".$kindId."]";
    }
    
    /**
     * Сохранение значений для редактирования одной нагрузки
     */
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
    			$obj->_created_by = $this->getLoad()->_created_by;
    			$obj->save();
    		}
    	}
    }
}
