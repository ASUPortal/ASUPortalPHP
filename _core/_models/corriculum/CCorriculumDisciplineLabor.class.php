<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 31.07.12
 * Time: 21:42
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumDisciplineLabor extends CActiveModel {
    protected $_form = null;
    protected $_type = null;
    protected $_table = TABLE_CORRICULUM_DISCIPLINE_LABORS;

    /**
     * Публичные свойства
     */
    public $discipline_id = 0;
    public $value = 0;

    /**
     * Связи с другими сущностями
     *
     * @return array
     */
    protected function relations() {
        return array(
            "form" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_form",
                "storageField" => "form_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            // вид занятия: лекция, практика и т.п.
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_type",
                "storageField" => "type_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
        );
    }
    public function attributeLabels() {
        return array(
            "type_id" => "Вид занятий",
            "value" => "Количество часов"
        );
    }
    public function validationRules() {
        return array(
            "selected" => array(
                "type_id"
            )
        );
    }
}
