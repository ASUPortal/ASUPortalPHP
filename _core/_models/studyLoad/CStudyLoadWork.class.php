<?php
/**
 * Учебная нагрузка по видам
 */

class CStudyLoadWork extends CActiveModel implements IVersionControl {
    protected $_table = TABLE_WORKLOAD_WORKS;
    protected $_workload = null;

    public function relations() {
        return array(
            "studyLoad" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_workload",
                "storageField" => "workload_id",
                "managerClass" => "CBaseManager",
                "managerGetObject" => "getStudyLoad"
            ),
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "type_id",
                "targetClass" => "CStudyLoadWorkType"
            ),
            "kind" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "kind_id",
                "targetClass" => "CTerm"
            )
        );
    }
    
    /**
     * Сумма часов видов учебной нагрузки по типу
     *
     * @param $type
     * @return int
     */
    public function getSumWorkHoursByType($type) {
    	$value = 0;
    	if ($this->type->name_hours_kind == $type) {
    		$value = $this->workload;
    	}
    	return $value;
    }
    
    /**
     * Сумма часов по псевдонимам типов учебной нагрузки (основная, дополнительная, надбавка, почасовка)
     *
     * @param $type
     * @return int
     */
    public function getSumWorkHoursByLoadType($type) {
    	$value = 0;
    	if ($this->studyLoad->load_type_id == CStudyLoadService::getStudyLoadTypeByAlias($type)->getId()) {
    		$value = $this->workload;
    	}
    	return $value;
    }
}
