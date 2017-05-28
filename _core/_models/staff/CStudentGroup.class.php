<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 10.06.12
 * Time: 20:50
 * To change this template use File | Settings | File Templates.
 */
class CStudentGroup extends CActiveModel {
    protected $_table = TABLE_STUDENT_GROUPS;
    private $_students = null;
    private $_year = null;
    private $_speciality = null;
    private $_schedules = null;
    protected $_monitor = null;
    protected $_curator = null;
    protected $_corriculum = null;
    public function relations() {
        return array(
            "monitor" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_monitor",
                "storageField" => "head_student_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudent"
            ),
            "curator" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_curator",
                "storageField" => "curator_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "corriculum" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_corriculum",
                "storageField" => "corriculum_id",
                "managerClass" => "CCorriculumsManager",
                "managerGetObject" => "getCorriculum"
            )
        );
    }
    public function attributeLabels() {
        return array(
            "name" => "Название",
            "students_count" => "Число студентов",
            "speciality_id" => "Специальность",
            "head_student_id" => "Староста",
            "year_id" => "Учебный год",
            "curator_id" => "Куратор",
            "comment" => "Комментарий",
            "corriculum_id" => "Учебный план"
        );
    }
    /**
     * Имя студента
     *
     * @return mixed
     */
    public function getName() {
        return $this->getRecord()->getItemValue("name");
    }
    /**
     * Учебный год
     *
     * @return CTerm
     */
    public function getYear() {
        if (is_null($this->_year)) {
            $this->_year = CTaxonomyManager::getYear($this->getRecord()->getItemValue("year_id"));
        }
        return $this->_year;
    }
    /**
     * @return array
     */
    public function toArrayForJSON() {
        $r['id'] = $this->getId();
        $r['value'] = $this->getName()." (".$this->getYear()->getValue().")";

        return $r;
    }
    /**
     * Все студенты группы.
     * Отсортированы по ФИО
     *
     * @return CArrayList
     */
    public function getStudents() {
        if (is_null($this->_students)) {
            $this->_students = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_STUDENTS, "group_id=".$this->getId(), "fio asc")->getItems() as $item) {
                $student = new CStudent($item);
                $this->_students->add($student->getId(), $student);
                CStaffManager::getCacheStudents()->add($student->getId(), $student);
            }
        }
        return $this->_students;
    }
    /**
     * Студенты группы с учётом истории смены группы.
     * Отсортированы по ФИО
     *
     * @return CArrayList
     */
    public function getStudentsWithChangeGroupsHistory() {
        $groups = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_STUDENT_GROUP_HISTORY, "source_id=".$this->getId())->getItems() as $item) {
            $groupHistory = new CStudentGroupChangeHistory($item);
            $groups->add($groupHistory->getId(), $groupHistory);
        }
        $students = new CArrayList();
        if ($groups->getCount() != 0) {
            foreach ($groups->getItems() as $group) {
                $student = CStaffManager::getStudent($group->student_id);
                if (!is_null($student)) {
                    $students->add($student->getId(), $student);
                }
            }
        } else {
            $students = $this->getStudents();
        }
        $comparator = new CDefaultComparator("fio");
        $sorted = CCollectionUtils::sort($students, $comparator);
        return $sorted;
    }
    /**
     * Специальность
     *
     * @return CTerm
     */
    public function getSpeciality() {
        if (is_null($this->_speciality)) {
            $this->_speciality = CTaxonomyManager::getSpeciality($this->getRecord()->getItemValue("speciality_id"));
        }
        return $this->_speciality;
    }
    /**
     * Расписание
     *
     * @return CArrayList
     */
    public function getSchedule() {
    	if (is_null($this->_schedules)) {
    		$this->_schedules = new CArrayList();
    		$query = new CQuery();
    		$query->select("schedule.*")
	    		->from(TABLE_SCHEDULE." as schedule")
	    		->condition("schedule.grup=".$this->getId()." and schedule.year=".CUtils::getCurrentYear()->getId()." and schedule.month=".CUtils::getCurrentYearPart()->getId());
    		foreach ($query->execute()->getItems() as $item) {
    			$schedule = new CSchedule(new CActiveRecord($item));
    			$this->_schedules->add($schedule->getId(), $schedule);
    		}
    	}
    	return $this->_schedules;
    }
    /**
     * Число студентов группы
     *
     * @return int
     */
    public function getStudentsCount() {
        $countStudents = 0;
        if ($this->students_count != 0) {
            $countStudents = $this->students_count;
        } else {
            $countStudents = $this->getStudents()->getCount();
        }
        return $countStudents;
    }
}
