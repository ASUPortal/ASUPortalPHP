<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 03.04.15
 * Time: 15:05
 *
 * @property int plan_id
 * @property String number
 * @property CWorkPlan plan
 */
class CWorkPlanTerm extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TERMS;

    protected function relations(){
        return array(
            "plan" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "plan_id",
                "targetClass" => "CWorkPlan"
            ),
        	"corriculum_discipline_section" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "number",
        		"targetClass" => "CCorriculumDisciplineSection"
        	)
        );
    }

    public function attributeLabels() {
        return array(
            "number" => "Номер семестра",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "number"
            )
        );
    }

    function __toString() {
        return (String) $this->corriculum_discipline_section->title;
    }


}