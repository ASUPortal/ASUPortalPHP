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
    public static function getSumPlanAmountWorksByType(CIndPlanPersonLoad $load, $type) {
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
    public static function getSumHoursStudyLoad(CIndPlanPersonLoad $load) {
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
    public static function getTotalHoursInHoursRate(CIndPlanPersonLoad $load) {
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
    public static function getTotalHoursRatesPersonOfBasicOrders(CIndPlanPersonLoad $load, CPerson $person) {
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
    public static function getDifferenceHoursRatesPersonOfBasicOrders(CIndPlanPersonLoad $load, CPerson $person) {
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
    public static function getTotalHoursRatesPersonOfCombineOrders(CIndPlanPersonLoad $load, CPerson $person) {
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
    public static function getDifferenceHoursRatesPersonOfCombineOrders(CIndPlanPersonLoad $load, CPerson $person) {
        $difference = 0;
        $totalHours = CIndividualPlanLoadService::getTotalHoursRatesPersonOfCombineOrders($load, $person);
        $studyHours = CIndividualPlanLoadService::getSumHoursStudyLoad($load);
        $difference = $totalHours-$studyHours;
        return $difference;
    }
    
    /**
     * Всего часов в индивидуальном плане сотрудника из справочника ставок в текущем году с учётом ставки по приказу, указанному в плане
     *
     * @param CIndPlanPersonLoad $load
     * @return int
     */
    public static function getTotalHoursRates(CIndPlanPersonLoad $load) {
        $totalHours = 0;
        $hours = CIndividualPlanLoadService::getTotalHoursInHoursRate($load);
        $rate = 0;
        $order = $load->order;
        if (!is_null($order)) {
            // если приказ активный, значит, что сегодня между началом и концом срока действия приказа
            if ($order->isActive()) {
                $rate = $order->rate;
            }
        }
        $totalHours = $rate*$hours;
        return $totalHours;
    }
    
    /**
     * Разница между часами по учебной нагрузке и часами ставок сотрудника в приказе с учётом заполненных видов работ
     *
     * @param CIndPlanPersonLoad $load
     * @return int
     */
    public static function getDifferenceHours(CIndPlanPersonLoad $load) {
        $difference = 0;
        $totalHours = CIndividualPlanLoadService::getTotalHoursRates($load);
    	
        $studyHours = CIndividualPlanLoadService::getSumHoursStudyLoad($load);
    	
        $studyAndMethodicalLoadHours = CIndividualPlanLoadService::getSumPlanAmountWorksByType($load, CIndPlanPersonWorkType::STUDY_AND_METHODICAL_LOAD);
        $scientificMethodicalLoadHours = CIndividualPlanLoadService::getSumPlanAmountWorksByType($load, CIndPlanPersonWorkType::SCIENTIFIC_METHODICAL_LOAD);
        $studyAndEducationalLoadHours = CIndividualPlanLoadService::getSumPlanAmountWorksByType($load, CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD);
    	
        $difference = $totalHours-$studyHours-$studyAndMethodicalLoadHours-$scientificMethodicalLoadHours-$studyAndEducationalLoadHours;
        return round($difference, 2);
    }
    
    /**
     * Копирование работ из нагрузки
     * 
     * @param CIndPlanPersonLoad $load
     * @param CIndPlanPersonLoad $newLoad
     * @param CTerm $year
     * @param CTerm $newYear
     * @param $type
     */
    public static function copyLoadWorks(CIndPlanPersonLoad $load, CIndPlanPersonLoad $newLoad, CTerm $year, CTerm $newYear, $type) {
        foreach ($load->getWorksByType($type)->getItems() as $work) {
            $newWork = $work->copy();
            if ($type == CIndPlanPersonWorkType::STUDY_AND_METHODICAL_LOAD or
                    $type == CIndPlanPersonWorkType::SCIENTIFIC_METHODICAL_LOAD or
                    $type == CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD) {
    					 
                $newWork->comment = "Скопировано из ".$year->getValue()." года ".$newWork->comment;
    						
                // указываем срок выполнения в соответствии с годом, в который копируем
                $date = date("Y-m-d", strtotime($newWork->plan_expiration_date));
                $dateFirstPart = date(CSettingsManager::getSettingValue("dateStartPlanExpirationDateCurrentYear"), strtotime($newWork->plan_expiration_date));
                $dateSecondPart = date(CSettingsManager::getSettingValue("dateStartPlanExpirationDateNextYear"), strtotime($newWork->plan_expiration_date));
                if ($date >= $dateFirstPart) {
                    $newWork->plan_expiration_date = date("d.m.".date("Y", strtotime($newYear->date_start)), strtotime($date));
                }
                if ($date <= $dateSecondPart) {
                    $newWork->plan_expiration_date = date("d.m.".date("Y", strtotime($newYear->date_end)), strtotime($date));
                }
            }
            if ($type == CIndPlanPersonWorkType::STUDY_AND_METHODICAL_LOAD or
                    $type == CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD or
                    $type == CIndPlanPersonWorkType::CHANGE_RECORDS) {
                $newWork->is_executed = 0;
            }
            $newWork->load_id = $newLoad->getId();
            $newWork->work_type = $type;
            $newWork->save();
        }
    }
}