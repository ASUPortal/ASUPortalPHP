<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 06.08.12
 * Time: 17:38
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumDisciplineHour extends CActiveModel {
    protected $_discipline = null;

    public static function getClassName() {
        return __CLASS__;
    }

    protected function relations() {
        return array(
            "discipline" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_discipline",
                "storageField" => "discipline_id",
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getDiscipline"
            ),
        );
    }

    public function attributeLabels() {
        return array(
            "period" => "Семестр",
            "value" => "Число часов"
        );
    }

    public function validationRules() {
        return array(
            "required" => array(
                "value"
            ),
            "numeric" => array(
                "value"
            )
        );
    }
}
