<?php

/**
 * Сервис по работе с нагрузкой индивидуального плана
 *
 */
class CIndividualPlanLoadService {
	
    /**
     * Сумма планируемого количества часов по типу работ
     * 
     * @param CIndPlanPersonLoad $load
     * @param $type
     * @return int
     */
    public function getSumPlanAmountWorksByType(CIndPlanPersonLoad $load, $type) {
        $result = 0;
        if ($type == CIndPlanPersonWorkType::STUDY_AND_METHODICAL_LOAD) {
            foreach ($load->works->getItems() as $work) {
                if ($work->work_type == $type) {
                    $result += $work->plan_amount;
                }
            }		
        }
        if ($type == CIndPlanPersonWorkType::SCIENTIFIC_METHODICAL_LOAD or $type == CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD) {
            foreach ($load->works->getItems() as $work) {
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
     * @param CIndPlanPersonLoad $load
     * @return int
     */
    public function getSumHoursStudyLoad(CIndPlanPersonLoad $load) {
        $sumHours = 0;
        $dataRow = array();
        foreach ($load->works->getItems() as $work) {
            if ($work->work_type == CIndPlanPersonWorkType::STUDY_LOAD) {
                $dataRow[] = $work->load_value;
            }
        }
        $fall = 0;
        $spring = 0;
        // план для осеннего семестра
        for ($i = CIndPlanPersonLoadConstants::PLAN_LOAD_AUTUMN_START; $i <= count($dataRow); $i = $i + CIndPlanPersonLoadConstants::COUNT_ROWS) {
            if (isset($dataRow[$i])) {
                $fall += $dataRow[$i];
            }
        }
        // план для весеннего семестра
        for ($i = CIndPlanPersonLoadConstants::PLAN_LOAD_SPRING_START; $i <= count($dataRow); $i = $i + CIndPlanPersonLoadConstants::COUNT_ROWS) {
            if (isset($dataRow[$i])) {
                $spring += $dataRow[$i];
            }
        }
        $sumHours = $fall + $spring;
        return $sumHours;
    }
    
    /**
     * Всего часов в индивидуальном плане сотрудника из справочника ставок
     * 
     * @param CIndPlanPersonLoad $load
     * @return int
     */
    public function getTotalHoursInHoursRate(CIndPlanPersonLoad $load) {
        $hours = 0;
        foreach (CActiveRecordProvider::getWithCondition(TABLE_HOURS_RATE, "dolgnost_id =".CIndPlanPersonLoadConstants::TOTAL_HOURS_IN_INDIVIDUAL_PLAN." and year_id =".$load->year_id)->getItems() as $item) {
            $load = new CHoursRate($item);
            $hours += $load->rate;
        }
        return $hours;
    }
    
    /**
     * Всего часов в индивидуальном плане сотрудника из справочника ставок в текущем году с учётом ставки по основным приказам
     * 
     * @param CIndPlanPersonLoad $load
     * @param CPerson $person
     * @return int
     */
    public function getTotalHoursRatesPersonOfBasicOrders(CIndPlanPersonLoad $load, CPerson $person) {
        $totalHours = 0;
        $hours = CIndividualPlanLoadService::getTotalHoursInHoursRate($load);
        $rates = 0;
        // учет в табеле
        if ($person->to_tabel) {
            // все приказы сотрудника
            foreach ($person->orders->getItems() as $order) {
                // если приказ активный, значит, что сегодня между началом и концом срока действия приказа
                if ($order->isActive()) {
                    // тип приказа: 2 - по основному месту, 3 - совместительство
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
     * @param CIndPlanPersonLoad $load
     * @param CPerson $person
     * @return int
     */
    public function getDifferenceHoursRatesPersonOfBasicOrders(CIndPlanPersonLoad $load, CPerson $person) {
        $difference = 0;
        $totalHours = CIndividualPlanLoadService::getTotalHoursRatesPersonOfBasicOrders($load, $person);
        $studyHours = CIndividualPlanLoadService::getSumHoursStudyLoad($load);
        $difference = $totalHours-$studyHours;
        return $difference;
    }
    
    /**
     * Всего часов в индивидуальном плане сотрудника из справочника ставок в текущем году с учётом ставки по приказам совместительства
     *
     * @param CIndPlanPersonLoad $load
     * @param CPerson $person
     * @return int
     */
    public function getTotalHoursRatesPersonOfCombineOrders(CIndPlanPersonLoad $load, CPerson $person) {
        $totalHours = 0;
        $hours = CIndividualPlanLoadService::getTotalHoursInHoursRate($load);
        $rates = 0;
        // учет в табеле
            if ($person->to_tabel) {
            // все приказы сотрудника
            foreach ($person->orders->getItems() as $order) {
                // если приказ активный, значит, что сегодня между началом и концом срока действия приказа
                if ($order->isActive()) {
                    // тип приказа: 2 - по основному месту, 3 - совместительство
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
     * @param CIndPlanPersonLoad $load
     * @param CPerson $person
     * @return int
     */
    public function getDifferenceHoursRatesPersonOfCombineOrders(CIndPlanPersonLoad $load, CPerson $person) {
        $difference = 0;
        $totalHours = CIndividualPlanLoadService::getTotalHoursRatesPersonOfCombineOrders($load, $person);
        $studyHours = CIndividualPlanLoadService::getSumHoursStudyLoad($load);
        $difference = $totalHours-$studyHours;
        return $difference;
    }
}