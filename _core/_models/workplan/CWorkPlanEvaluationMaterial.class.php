<?php
/**
 * 
 * @property int plan_id
 * @property int type_id
 *
 * @property CTerm type
 * @property string material
 */
class CWorkPlanEvaluationMaterial extends CActiveModel {
    protected $_table = TABLE_WORK_PLAN_EVALUATION_MATERIALS;

    protected function relations() {
        return array(
        	"type" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "type_id",
        		"targetClass" => "CTerm"
        	)
        );
    }
    public function attributeLabels() {
        return array(
            "type_id" => "Тип оценочного материала",
            "material" => "Оценочные материалы"
        );
    }
    protected function validationRules() {
        return array(
            "selected" => array(
                "type_id"
            )
        );
    }

}