<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 07.09.15
 * Time: 21:41
 *
 * @property int load_id
 */
class CWorkPlanContentSectionLoadTopic extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_CONTENT_TOPICS;

    public function attributeLabels() {
        return array(
            "title" => "Тема",
            "value" => "Число часов"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "title",
                "value"
            )
        );
    }


}