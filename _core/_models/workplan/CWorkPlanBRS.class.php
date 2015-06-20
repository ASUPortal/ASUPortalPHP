<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 19.06.15
 * Time: 23:20
 *
 * @property int plan_id
 * @property int mark_id
 * @property string range
 * @property int is_ok
 *
 * @property CTerm mark
 */
class CWorkPlanBRS extends CActiveModel{
    protected $_table = TABLE_WORK_PLAB_BRS;

    protected function relations() {
        return array(
            "mark" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "mark_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getMark"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "range" => "Диапазон",
            "mark_id" => "Оценка",
            "is_ok" => "Мера оценки"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "mark_id"
            ),
            "required" => array(
                "range"
            )
        );
    }


}