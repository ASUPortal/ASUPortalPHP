<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 26.12.12
 * Time: 21:27
 * To change this template use File | Settings | File Templates.
 */
class CGradebook extends CActiveModel{
    protected $_table = TABLE_GRADEBOOKS;
    protected $_discipline = null;
    protected $_person = null;
    protected $_group = null;
    protected $_activities = null;
    protected $_items = null;
    public function relations() {
        return array(
            "discipline" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_discipline",
                "storageField" => "subject_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiscipline"
            ),
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "kadri_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "group" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_group",
                "storageField" => "group_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudentGroup"
            ),
            "activities" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_activities",
                "relationFunction" => "getActivities"
            )
        );
    }
    public function getActivities() {
        if (is_null($this->_activities)) {
            $this->_activities = new CArrayList();
            $query = new CQuery();
            $query->select("activity.*")
                ->from(TABLE_STUDENTS_ACTIVITY." as activity")
                ->condition('
                    activity.date_act BETWEEN
						"'.date("Y-m-d", strtotime($this->date_start)).'" AND "'.date("Y-m-d", strtotime($this->date_end)).'"
                    AND
                        activity.subject_id = '.$this->subject_id.'
                    AND
                        activity.kadri_id = '.$this->kadri_id.'
                    AND
                        student.group_id = '.$this->group_id.'
                ')->innerJoin(TABLE_STUDENTS." as student", "activity.student_id = student.id");
            foreach ($query->execute()->getItems() as $item) {
                $activity = new CStudentActivity(new CActiveRecord($item));
                $this->_activities->add($activity->getId(), $activity);
            }
        }
        return $this->_activities;
    }

    /**
     * Преобразовать плоскую таблицу в журнал
     *
     * @return CArrayList
     */
    public function toGradebookTable() {
        $rows = new CArrayList();
        // шаг 1. определим количество записей в одной строке
        $types = new CArrayList();
        $dates = new CArrayList();
        $comments = new CArrayList();
        foreach ($this->activities->getItems() as $activity) {
            if (!is_null($activity->controlType)) {
            	/*
                $types->add($activity->study_act_id."_".str_pad($activity->study_act_comment, 4, "0", STR_PAD_LEFT)."_".date("Ymd", strtotime($activity->getDate())), $activity->controlType->getValue());
                $dates->add($activity->study_act_id."_".str_pad($activity->study_act_comment, 4, "0", STR_PAD_LEFT)."_".date("Ymd", strtotime($activity->getDate())), $activity->getDate());
                $comments->add($activity->study_act_id."_".str_pad($activity->study_act_comment, 4, "0", STR_PAD_LEFT)."_".date("Ymd", strtotime($activity->getDate())), $activity->study_act_comment);
                */
            	$types->add($activity->study_act_id."_".str_pad($activity->study_act_comment, 4, "0", STR_PAD_LEFT), $activity->controlType->getValue());
            	$dates->add($activity->study_act_id."_".str_pad($activity->study_act_comment, 4, "0", STR_PAD_LEFT), $activity->getDate());
            	$comments->add($activity->study_act_id."_".str_pad($activity->study_act_comment, 4, "0", STR_PAD_LEFT), $activity->study_act_comment);
            }
        }
        $types = $types->getSortedByKey(SORT_ASC);
        $dates = $dates->getSortedByKey(SORT_ASC);
        $firstCol = new CArrayList();
        $firstCol->add($firstCol->getCount(), "");
        // добавим все виды деятельности
        foreach ($types->getItems() as $key=>$type) {
            $firstCol->add($key, $type);
        }
        $rows->add($rows->getCount(), $firstCol);
        // все номера лабораторных работ
        $col = $firstCol->getCopy();
        foreach ($comments->getItems() as $key=>$date) {
            $col->add($key, $date);
        }
        $rows->add($rows->getCount(), $col);
        // шаг 2. пройдемся по всем записям, получим всех студентов
        $students = new CArrayList();
        foreach ($this->activities->getItems() as $activity) {
            if (!is_null($activity->student)) {
                $students->add($activity->student->getName(), $activity->student);
            }
        }
        $students = $students->getSortedByKey(SORT_ASC);
        // шаг 3. создадим на каждого студента по строчке в таблице
        foreach ($students->getItems() as $student) {
            $col = $firstCol->getCopy();
            foreach ($col->getItems() as $key=>$value) {
                if ($key !== 0) {
                    $col->add($key, "");
                }
            }
            $col->add(0, $student->getName());
            foreach ($this->activities->getItems() as $activity) {
                if ($activity->student_id == $student->getId()) {
                	$arr = array();
                	if ($col->hasElement($activity->study_act_id."_".str_pad($activity->study_act_comment, 4, "0", STR_PAD_LEFT))) {
                		$arr = $col->getItem($activity->study_act_id."_".str_pad($activity->study_act_comment, 4, "0", STR_PAD_LEFT));
                	}
                	if (!is_null($activity->mark)) {
                		$arr[] = $activity->mark->getValue();
                	}
                	$col->add($activity->study_act_id."_".str_pad($activity->study_act_comment, 4, "0", STR_PAD_LEFT), $arr);
                }
            }
            $rows->add($rows->getCount(), $col);
        }
        // по просьбе ГВ в клетку 1.1 ставим название группы
        if (!is_null($this->group)) {
            $firstCol->add(0, $this->group->getName());
        }
        return $rows;
    }
}
