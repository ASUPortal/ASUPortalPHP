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
    protected $_markInternship = null;
    protected $_markUndergraduate = null;
    protected $_complexExamMark = null;
    protected $_groupChangeHistory = null;
    private $_corriculum = null;

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
                "storageCondition" => "student_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
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
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_secondaryEducationEndType",
                "relationFunction" => "getSecondaryEducationEndType"
            ),
            /*
            "secondaryEducationEndType" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_secondaryEducationEndType",
                "storageField" => "education_form_end",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getEductionForm"
            ),
            */
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
            ),
            "groupChangeHistory" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_groupChangeHistory",
                "storageTable" => TABLE_STUDENT_GROUP_HISTORY,
                "storageCondition" => "student_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudentGroupChangeHistory"
            )
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "fio"
            )
        );
    }

    public function getName() {
        return $this->getRecord()->getItemValue("fio");
    }
    
    /**
     * Фамилия и инициалы студента
     * @return string
     */
    public function getShortName() {
        $name = explode(" ", $this->getName());
        $shortName = $name[0]." ".mb_substr($name[1], 0, 1).".".mb_substr($name[2], 0, 1).".";
        return $shortName;
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
        	$corriculum = $this->getCorriculum();
        	if (!is_null($corriculum)) {
        		$this->_speciality = $corriculum->speciality_direction;
        	}
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
    /**
     * Учебный план, по которому обуается студент
     * В перспективе может быть личным учебным планом для студента
     * 
     * @return CCorriculum
     */
    public function getCorriculum() {
    	if (is_null($this->_corriculum)) {
    		if (!is_null($this->getGroup())) {
    			$group = $this->getGroup();
    			$this->_corriculum = $group->corriculum;
    		}
    	}
    	return $this->_corriculum;
    }

    /**
     * Форма обучения, которую студент заканчивает
     *
     * @return CTerm|mixed|null
     */
    public function getSecondaryEducationEndType() {
        if (is_null($this->_secondaryEducationEndType)) {
            $this->_secondaryEducationEndType = CTaxonomyManager::getEductionForm($this->education_form_end);
            if (is_null($this->_secondaryEducationEndType)) {
                $corriculum = $this->getCorriculum();
                if (!is_null($corriculum)) {
                    $this->_secondaryEducationEndType = $corriculum->educationForm;
                }
            }
        }
        return $this->_secondaryEducationEndType;
    }
    public function createGroupChangeHistoryPoint(CStudentGroup $source = null, CStudentGroup $target = null) {
        $history = new CStudentGroupChangeHistory();
        $history->student_id = $this->getId();
        $history->source_id = 0;
        if (!is_null($source)) {
            $history->source_id = $source->getId();
        }
        $history->target_id = 0;
        if (!is_null($target)) {
            $history->target_id = $target->getId();
        }
        $history->date = date("d.m.Y");
        $history->person_id = CSession::getCurrentPerson()->getId();
        $history->save();
    }

}
