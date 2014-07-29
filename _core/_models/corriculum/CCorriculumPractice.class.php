<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.03.13
 * Time: 15:41
 * To change this template use File | Settings | File Templates.
 *
 * Практики в учебном плане
 */
class CCorriculumPractice extends CActiveModel {
    protected $_table = TABLE_CORRICULUM_PRACTICES;
    /**
     * Публичные свойства
     */
    public $type_id = 0;
    public $alias = "";
    public $length = "";
    public $corriculum_id = 0;
    /**
     * Свойства для хранения связанных объектов
     */
    protected $_type;
    protected $_discipline;

    public function attributeLabels() {
        return array(
            "type_id" => "Тип",
            "alias" => "Короткое имя для поиска",
            "length" => "Длительность (недель)",
            "length_hours" => "Длительность (в часах)",
            "length_credits" => "Длительность (зачетных единицы)",
            "discipline_id" => "Дисциплина"
        );
    }
    public function relations() {
        return array(
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_type",
                "storageField" => "type_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "discipline" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_discipline",
                "storageField" => "discipline_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiscipline"
            )
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "alias",
                "length"
            ),
            "selected" => array(
                "type_id"
            )
        );
    }
}
