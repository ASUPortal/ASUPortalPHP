<?php
/**
 * 
 * @property int plan_id
 * @property string rgr_title
 */
class CWorkPlanRgrTheme extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_RGR_THEMES;

    public function attributeLabels() {
        return array(
            "rgr_title" => "Тема"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "rgr_title"
            )
        );
    }


}