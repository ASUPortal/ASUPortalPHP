<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 16.06.15
 * Time: 22:25
 *
 * @property int plan_id
 * @property string project_title
 */
class CWorkPlanProjectTheme extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_PROJECT_THEMES;

    public function attributeLabels() {
        return array(
            "project_title" => "Тема"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "project_title"
            )
        );
    }


}