<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 05.08.13
 * Time: 20:54
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class CIndPlanPersonLoad
 *
 * @property CPerson person
 */
class CIndPlanPersonLoad extends CActiveModel{
    protected $_table = TABLE_IND_PLAN_LOADS;
    protected $_person = null;
    protected $_works = null;
    protected $_year = null;
    private $_loadTable = null;
    public $person_id;

    public function relations() {
        return array(
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "person_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "works" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_works",
                "relationFunction" => "getWorks",
            ),
            "year" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_year",
                "storageField" => "year_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getYear"
            )
        );
    }
    
    public function getType() {
        if (is_numeric($this->type)) {
            return CTaxonomyManager::getTerm($this->type);
        } else {
            return $this->type;
        }
    }

    protected function getWorks() {
        if (is_null($this->_works)) {
            $this->_works = new CArrayList();
            if (!is_null($this->getId())) {
                foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_WORKS, "load_id=".$this->getId())->getItems() as $ar) {
                    $work = new CIndPlanPersonWork($ar);
                    $this->_works->add($work->getId(), $work);
                }
            }
        }
        return $this->_works;
    }

    /**
     * @param $type
     * @return CArrayList
     */
    public function getWorksByType($type) {
        $result = new CArrayList();
        foreach ($this->works->getItems() as $work) {
            if ($work->work_type == $type) {
                $result->add($work->getId(), $work);
            }
        }
        return $result;
    }

    /**
     * Таблица учебной нагрузки. Отдельным классом проще
     *
     * @return CIndPlanPersonLoadTable
     */
    public function getStudyLoadTable() {
        if (is_null($this->_loadTable)) {
            $this->_loadTable = new CIndPlanPersonLoadTable($this);
        }
        return $this->_loadTable;
    }

    /**
     * Показывать в учебной форме разделение на бюджет и контракт
     *
     * @return bool
     */
    public function isSeparateContract() {
        return $this->separate_contract == "1";
    }
    public function remove() {
        foreach ($this->getWorks()->getItems() as  $ar) {
            $ar->remove();
        }
        parent::remove();
    }
    
    /**
     * Сумма планируемого количества часов по типу работ
     * 
     * @param $type
     * @return int
     */
    public function getSumPlanAmountWorksByType($type) {
        $result = 0;
        if ($type == CIndPlanPersonWorkType::STUDY_AND_METHODICAL_LOAD) {
            foreach ($this->works->getItems() as $work) {
                if ($work->work_type == $type) {
                    $result += $work->plan_amount;
                }
            }		
        }
        if ($type == CIndPlanPersonWorkType::SCIENTIFIC_METHODICAL_LOAD or $type == CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD) {
            foreach ($this->works->getItems() as $work) {
                if ($work->work_type == $type) {
                    $result += $work->plan_hours;
                }
            }
        }
        return $result;
    }
    
    /**
     * Сумма часов по учебной нагрузке
     * 
     * @return int
     */
    public function getSumHoursStudyLoad() {
    	$sumHours = 0;
    	foreach ($this->works->getItems() as $work) {
    		if ($work->work_type == CIndPlanPersonWorkType::STUDY_LOAD) {
    			$dataRow[] = $work->load_value;
    		}
    	}
    	$fall = 0;
    	$spring = 0;
    	if (isset($dataRow)) {
    		$dataRows = array_values($dataRow);
    		//план для осеннего семестра
    		for ($i=0; $i<=count($dataRow); $i=$i+13) {
    			if (isset($dataRow[$i])) {
    				$fall += $dataRow[$i];
    			}
    		}
    		//план для весеннего семестра
    		for ($i=6; $i<=count($dataRow); $i=$i+13) {
    			if (isset($dataRow[$i])) {
    				$spring += $dataRow[$i];
    			}
    		}
    		$sumHours = $fall + $spring;
    	}
    	return $sumHours;
    }
    
    /**
     * Всего часов в индивидуальном плане сотрудника из справочника ставок
     * 
     * @return int
     */
    public function getTotalHoursInHoursRate() {
    	$hours = 0;
    	$query = new CQuery();
    	$query->select("hours.dolgnost_id, hours.rate as rate")
	    	->from(TABLE_HOURS_RATE." as hours")
	    	->condition("hours.dolgnost_id = ".CIndPlanPersonTotalHours::TOTAL_HOURS_IN_INDIVIDUAL_PLAN." and hours.year_id =".$this->year_id);
    	foreach ($query->execute()->getItems() as $item) {
    		$hours = $item["rate"];
    	}
    	return $hours;
    }
    
    /**
     * Всего часов в индивидуальном плане сотрудника из справочника ставок в текущем году с учётом ставки по основным приказам
     * 
     * @param CPerson $person
     * @return int
     */
    public function getTotalHoursRatesPersonOfBasicOrders(CPerson $person) {
    	$totalHours = 0;
    	$hours = $this->getTotalHoursInHoursRate();
    	$rates = 0;
    	// учет в табеле
    	if ($person->to_tabel) {
    		// все приказы сотрудника
    		foreach ($person->orders->getItems() as $order) {
    			/**
    			 * Если приказ активный, значит, что сегодня между началом и концом срока действия приказа
    			 */
    			if ($order->isActive()) {
    				/**
    				 * Тип приказа
    				 * 2 - по основному месту, 3 - совместительство
    				 */
    				if ($order->type_order == CIndPlanPersonOrderType::BASIC) {
    					$rates += $order->rate;
    				}
    			}
    		}
    	}
    	$totalHours = $rates*$hours;
    	return $totalHours;
    }
    
    /**
     * Разница между часами по учебной нагрузке и часами ставок сотрудника по основным приказам
     *
     * @param CPerson $person
     * @return int
     */
    public function getDifferenceHoursRatesPersonOfBasicOrders(CPerson $person) {
    	$difference = 0;
    	$totalHours = $this->getTotalHoursRatesPersonOfBasicOrders($person);
    	$studyHours = $this->getSumHoursStudyLoad();
    	$difference = $totalHours-$studyHours;
    	return $difference;
    }
    
    /**
     * Всего часов в индивидуальном плане сотрудника из справочника ставок в текущем году с учётом ставки по приказам совместительства
     *
     * @param CPerson $person
     * @return int
     */
    public function getTotalHoursRatesPersonOfCombineOrders(CPerson $person) {
    	$totalHours = 0;
    	$hours = $this->getTotalHoursInHoursRate();
    	$rates = 0;
    	// учет в табеле
    	if ($person->to_tabel) {
    		// все приказы сотрудника
    		foreach ($person->orders->getItems() as $order) {
    			/**
    			 * Если приказ активный, значит, что сегодня между началом и концом срока действия приказа
    			 */
    			if ($order->isActive()) {
    				/**
    				 * Тип приказа
    				 * 2 - по основному месту, 3 - совместительство
    				 */
    				if ($order->type_order == CIndPlanPersonOrderType::COMBINE) {
    					$rates += $order->rate;
    				}
    			}
    		}
    	}
    	$totalHours = $rates*$hours;
    	return $totalHours;
    }
    
    /**
     * Разница между часами по учебной нагрузке и часами ставок сотрудника по приказам совместительства
     *
     * @param CPerson $person
     * @return int
     */
    public function getDifferenceHoursRatesPersonOfCombineOrders(CPerson $person) {
    	$difference = 0;
    	$totalHours = $this->getTotalHoursRatesPersonOfCombineOrders($person);
    	$studyHours = $this->getSumHoursStudyLoad();
    	$difference = $totalHours-$studyHours;
    	return $difference;
    }
}