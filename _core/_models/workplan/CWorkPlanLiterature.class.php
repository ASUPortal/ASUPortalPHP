<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 20.06.15
 * Time: 20:42
 *
 * @property int plan_id
 * @property int type
 * @property CArrayList books
 *
 */
class CWorkPlanLiterature extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_LITERATURE;

    protected function relations() {
        return array(
        	"book" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "book_id",
        		"targetClass" => "CCorriculumBook"
        	),
        	"plan" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "plan_id",
        		"targetClass" => "CWorkPlan"
        	)
        );
    }

    public function attributeLabels() {
        return array(
            "book_id" => "Источник",
            "type" => "Тип",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "book_id",
                "type"
            )
        );
    }


}