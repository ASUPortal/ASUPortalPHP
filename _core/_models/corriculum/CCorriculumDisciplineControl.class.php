<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 06.08.12
 * Time: 17:27
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumDisciplineControl extends CActiveModel {
    protected $_discipline = null;
    protected $_form = null;

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
            "form" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_form",
                "storageField" => "form_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
        );
    }

    public function attributeLabels() {
        return array(
            "form_id" => "Форма контроля",
            "isFinal" => "Форма итогового контроля",
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
