<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 23.02.13
 * Time: 21:35
 * To change this template use File | Settings | File Templates.
 */
class CSpeciality extends CTerm {
    protected $_table = TABLE_SPECIALITIES;
    protected $_specialization = null;
    public function attributeLabels() {
        return array(
            "name" => "Название",
            "comment" => "Комментарий",
            "specialization_id" => "Специализация",
            "practice_internship" => "Длительность производственной практики",
            "practice_undergraduate" => "Длительность преддипломной практики",
            "diplom_preparation" => "Длительность подготовки диплома"
        );
    }
    protected function relations() {
        return array(
            "specialization" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_specialization",
                "storageField" => "specialization_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
        );
    }
}
