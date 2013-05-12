<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 12.05.13
 * Time: 15:04
 * To change this template use File | Settings | File Templates.
 */

class CGrantPeriod extends CActiveModel{
    protected $_table = TABLE_GRANT_PERIODS;
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "comment" => "Комментарий",
            "date_start" => "Дата начала",
            "date_end" => "Дата окончания"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "title"
            )
        );
    }
}