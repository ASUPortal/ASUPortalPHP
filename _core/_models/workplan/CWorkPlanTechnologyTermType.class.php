<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 16.06.15
 * Time: 23:29
 *
 * @property int technology_term_id
 * @property int type_id
 *
 * @property CTerm type
 */
class CWorkPlanTechnologyTermType extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TECHNOLOGY_TERM_TYPES;

    protected function relations() {
        return array(
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "type_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "type_id" => "Вид нагрузки"
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