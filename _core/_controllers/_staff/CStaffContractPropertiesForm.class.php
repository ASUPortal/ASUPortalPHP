<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Aleksandr Barmin
 * Date: 12.10.14
 * Time: 18:52
 * 
 * URL: http://mydesignstudio.ru/
 * mailto: abarmin@mydesignstudio.ru
 * twitter: @alexbarmin
 */

class CStaffContractPropertiesForm extends CFormModel{
    private $_bean = null;
    public function attributeLabels() {
        return array(
            "year" => "Год",
            "plan" => "Индивидуальный план"
        );
    }
    public function validationRules() {
        return array(
            "selected" => array(
                "year",
                "plan"
            )
        );
    }
    public function __construct(CStatefullBean $bean) {
        $this->_bean = $bean;
    }
    public function getYears() {
        $person = CStaffManager::getPerson($this->_bean->getItem("id"));
        $plans = $person->getIndPlansByYears();
        $years = array();
        foreach ($plans->getItems() as $year=>$plan) {
            $year = CTaxonomyManager::getYear($year);
            if (!is_null($year)) {
                $years[$year->getId()] = $year->getValue();
            }
        }
        return $years;
    }
    public function getPlans() {
        $plans = array();
        return $plans;
    }
}