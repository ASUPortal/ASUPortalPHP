<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 19.06.15
 * Time: 22:02
 *
 * @property int plan_id
 * @property int type_id
 * @property int form_id
 *
 * @property CTerm type
 * @property CTerm form
 * @property CArrayList funds
 * @property CArrayList places
 */
class CWorkPlanMarkType extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_MARK_TYPES;

    protected function relations() {
        return array(
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "type_id",
                "targetClass" => "CTerm"
            ),
            "form" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "form_id",
                "targetClass" => "CTerm"
            ),
            "funds" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "joinTable" => TABLE_WORK_PLAN_MARK_TYPE_FUNDS,
                "leftCondition" => "mark_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "fund_id",
                "targetClass" => "CTerm"
            ),
            "places" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "joinTable" => TABLE_WORK_PLAN_MARK_TYPE_PLACES,
                "leftCondition" => "mark_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "place_id",
                "targetClass" => "CTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "type_id" => "Вид контроля",
            "form_id" => "Форма контроля",
            "funds" => "Фонды оценочных средств",
            "places" => "Место размещения"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "type_id",
                "form_id"
            )
        );
    }


}