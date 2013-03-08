<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 11.11.12
 * Time: 16:10
 * To change this template use File | Settings | File Templates.
 */
class CExamQuestion extends CActiveModel {
    protected $_table = TABLE_EXAMINATION_QUESTIONS;
    protected $_speciality = null;
    protected $_year = null;
    protected $_category = null;
    protected $_discipline = null;
    public function attributeLabels() {
        return array(
            'speciality_id' => 'Специальность',
            'course' => 'Курс',
            'year_id' => 'Учебный год',
            'category_id' => 'Категория вопроса',
            'discipline_id' => 'Дисциплина',
            'text' => 'Текст вопроса'
        );
    }
    public function relations() {
        return array(
            "speciality" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_speciality",
                "storageField" => "speciality_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getSpeciality"
            ),
            "year" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_year",
                "storageField" => "year_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getYear"
            ),
            "category" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_category",
                "storageField" => "category_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "discipline" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_discipline",
                "storageField" => "discipline_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiscipline"
            ),
        );
    }
}
