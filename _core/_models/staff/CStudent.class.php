<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 10.06.12
 * Time: 22:16
 * To change this template use File | Settings | File Templates.
 *
 * Код для генерации новых полей в БД
 *
 * ALTER TABLE  `students` ADD  `year_school_end` VARCHAR( 4 ) NOT NULL ,
 * ADD  `year_university_start` VARCHAR( 4 ) NOT NULL ,
 * ADD  `primary_education_type_id` INT NOT NULL ,
 * ADD  `education_form_start` INT NOT NULL ,
 * ADD  `education_form_end` INT NOT NULL ,
 * ADD  `gender_id` INT NOT NULL ,
 * ADD  `work_current` VARCHAR( 255 ) NOT NULL ,
 * ADD  `work_proposed` VARCHAR( 255 ) NOT NULL
 *
 * Еще нужна таксономия primary_education
 */
class CStudent extends CActiveModel {
    private $_speciality = null;
    protected $_table = TABLE_STUDENTS;
    protected $_primaryEducationType = null;
    protected $_secondaryEducationStartType = null;
    protected $_secondaryEducationEndType = null;
    protected $_gender = null;
    protected $_group = null;
    protected $_diploms = null;
    protected $_specialization = null;
    protected $_markInternship = null;
    protected $_markUndergraduate = null;
    protected $_complexExamMark = null;
    public function attributeLabels() {
        return array(
            "fio" => "ФИО",
            "fio_rp" => "ФИО родительный падеж",
            "group_id" => "Группа",
            "bud_contract" => "Форма обучения",
            "telephone" => "Телефон",
            "diploms" => "Дипломы",
            "stud_num" => "№ зач. книжки",
            "comment" => "Комментарий",
            "gender_id" => "Пол",
            "year_school_end" => "Год окончания образовательного учреждения",
            "year_university_start" => "Год поступления в ВУЗ",
            "year_university_end" => "Год окончания ВУЗа",
            "education_form_start" => "Форма обучения в начале обучения",
            "education_form_end" => "Форма обучения в конце обучения",
            "work_current" => "Текущее место работы",
            "work_proposed" => "Предполагаемое место работы",
            "primary_education_type_id" => "Оконченное образовательное учреждение",
            "education_specialization_id" => "Специализация",
            "practice_internship_mark_id" => "Оценка за производственную практику",
            "practice_undergraduate_mark_id" => "Оценка за преддипломную практику",
            "exam_complex_mark_id" => "Оценка за междисциплинарный экзамен",
            "attach_number" => "Номер диплома",
            "attach_regnum" => "Регистрационный номер",
            "attach_regdate" => "Дата выдачи",
            "birth_date" => "Дата рождения"
        );
    }
    public function relations() {
        return array(
            "diploms" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_diploms",
                "storageTable" => TABLE_DIPLOMS,
                "storageCondition" => "student_id = " . $this->id,
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getDiplom"
            ),
            "primaryEducation" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_primaryEducationType",
                "storageField" => "primary_education_type_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "secondaryEducationStartType" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_secondaryEducationStartType",
                "storageField" => "education_form_start",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getEductionForm"
            ),
            "secondaryEducationEndType" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_secondaryEducationEndType",
                "storageField" => "education_form_end",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getEductionForm"
            ),
            "gender" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_gender",
                "storageField" => "gender_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getGender"
            ),
            "group" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_group",
                "storageField" => "group_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudentGroup"
            ),
            "specialization" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_specialization",
                "storageField" => "education_specialization_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "practiceInternshipMark" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_markInternship",
                "storageField" => "practice_internship_mark_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getMark"
            ),
            "practiceUndergraduateMark" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_markUndergraduate",
                "storageField" => "practice_undergraduate_mark_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getMark"
            ),
            "complexExamMark" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_complexExamMark",
                "storageField" => "exam_complex_mark_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getMark"
            )
        );
    }
    public function getName() {
        return $this->getRecord()->getItemValue("fio");
    }
    /**
     * Группа, к которой студент относится
     *
     * @return CStudentGroup
     */
    public function getGroup() {
        if (is_null($this->_group)) {
            if (array_key_exists("group_id", $this->getRecord()->getItems())) {
                $this->_group = CStaffManager::getStudentGroup($this->getRecord()->getItemValue("group_id"));
            }
        }
        return $this->_group;
    }
    /**
     * Специальность студента
     *
     * @return CTerm
     */
    public function getSpeciality() {
        if (is_null($this->_speciality)) {
            $this->_speciality = $this->getGroup()->getSpeciality();
        }
        return $this->_speciality;
    }

    /**
     * Форма обучения
     *
     * @return string
     */
    public function getMoneyForm() {
        if ($this->bud_contract == 1) {
            return "Бюджет";
        } elseif ($this->bud_contract == 2) {
            return "Контракт";
        } else {
            return "";
        }
    }
}
