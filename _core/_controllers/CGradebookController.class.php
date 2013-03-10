<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 12.12.12
 * Time: 20:41
 * To change this template use File | Settings | File Templates.
 */
class CGradebookController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Журнал успеваемости");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("activity.*")
            ->from(TABLE_STUDENTS_ACTIVITY." as activity");
        // сортировки по столбцам
    	if (CRequest::getString("order") == "") {
            $query->order("activity.id desc");
    	} elseif (CRequest::getString("order") == "date_act") {
    		if (CRequest::getString("direction") == "") {
                $query->order("activity.date_act desc");
    		} else {
                $query->order("activity.id ".CRequest::getString("direction"));
    		}
    	} elseif(CRequest::getString("order") == "subject_id") {
    		$query->leftJoin(TABLE_DISCIPLINES." as discipline", "activity.subject_id = discipline.id");
    		if (CRequest::getString("direction") == "") {
    			$query->order("discipline.name asc");
    		} else {
    			$query->order("discipline.name ".CRequest::getString("direction"));
    		}
    	} elseif(CRequest::getString("order") == "kadri_id") {
    		$query->leftJoin(TABLE_PERSON." as person", "activity.kadri_id = person.id");
    		if (CRequest::getString("direction") == "") {
    			$query->order("person.fio asc");
    		} else {
    			$query->order("person.fio ".CRequest::getString("direction"));
    		}
    	} elseif(CRequest::getString("order") == "student_id") {
            $query->leftJoin(TABLE_STUDENTS." as student_f", "student_f.id = activity.student_id");
            if (CRequest::getString("direction") == "") {
                $query->order("student_f.fio asc");
            } else {
                $query->order("student_f.fio ".CRequest::getString("direction"));
            }
        }else {
            $query = new CQuery();
            $query->select("activity.*")
                ->from(TABLE_STUDENTS_ACTIVITY)
                ->order("activity.id desc");
    	}
        // запросы для получения списка групп и списка преподавателей
        $personQuery = new CQuery();
        $personQuery->select("distinct(person.id) as id, person.fio as name")
            ->from(TABLE_STUDENTS_ACTIVITY." as activity")
            ->innerJoin(TABLE_PERSON." as person", "activity.kadri_id = person.id")
            ->order("person.fio asc");
        $groupQuery = new CQuery();
        $groupQuery->select("distinct(st_group.id) as id, st_group.name as name")
            ->from(TABLE_STUDENTS_ACTIVITY." as activity")
            ->innerJoin(TABLE_STUDENTS." as student", "student.id = activity.student_id")
            ->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "st_group.id = student.group_id")
            ->order("st_group.name asc");
        $disciplineQuery = new CQuery();
        $disciplineQuery->select("distinct(subject.id) as id, subject.name as name")
            ->from(TABLE_STUDENTS_ACTIVITY." as activity")
            ->innerJoin(TABLE_DISCIPLINES." as subject", "activity.subject_id = subject.id")
            ->order("subject.name asc");
        // фильтры
        $selectedPerson = null;
        $selectedGroup = null;
        $selectedDiscipline = null;
        $selectedStudent = null;
        $selectedControl = null;
        if (CRequest::getString("filter") !== "") {
            $filters = explode("_", CRequest::getString("filter"));
            foreach ($filters as $filter) {
            	$f = explode(":", $filter);
            	if (count($f) > 1) {
            		$key = $f[0];
            		$value = $f[1];
            		if ($key == "person") {
            			if ($value != 0) {
            				$selectedPerson = $value;
            				$query->condition("kadri_id=".$value);
            			}
            		} elseif ($key == "group") {
            			if ($value != 0) {
            				$selectedGroup = $value;
            				$query->innerJoin(TABLE_STUDENTS." as student", "activity.student_id = student.id");
            				$query->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id AND st_group.id=".$value);
            			}
            		} elseif ($key == "discipline") {
            			if ($value != 0) {
            				$selectedDiscipline = $value;
            				$query->innerJoin(TABLE_DISCIPLINES." as subject", "subject.id = activity.subject_id AND subject.id=".$value);
            			}
            		} elseif ($key == "student") {
            			if ($value != 0) {
            				$selectedStudent = CStaffManager::getStudent($value);
            				$query->innerJoin(TABLE_STUDENTS." as student", "activity.student_id = student.id AND student.id=".$value);
            			}
            		} elseif($key == "control") {
            			if ($value != 0) {
            				$selectedControl = CTaxonomyManager::getControlType($value);
            				$query->innerJoin(TABLE_STUDENTS_CONTROL_TYPES." as control", "activity.study_act_id = control.id AND control.id=".$value);
            			}
            		}	
            	}
            }
            /**
             * Дополняем фильтры по преподавателям, группам и дисциплинам
             */
            if (!is_null($selectedPerson)) {
                $groupQuery->innerJoin(TABLE_PERSON." as person", "person.id = activity.kadri_id AND person.id=".$selectedPerson);
                $disciplineQuery->innerJoin(TABLE_PERSON." as person", "person.id = activity.kadri_id AND person.id=".$selectedPerson);
            }
            if (!is_null($selectedGroup)) {
                $personQuery->innerJoin(TABLE_STUDENTS." as student", "student.id = activity.student_id");
                $personQuery->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "st_group.id = student.group_id and st_group.id=".$selectedGroup);
                $disciplineQuery->innerJoin(TABLE_STUDENTS." as student", "student.id = activity.student_id");
                $disciplineQuery->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "st_group.id = student.group_id and st_group.id=".$selectedGroup);
            }
            if (!is_null($selectedDiscipline)) {
                $personQuery->innerJoin(TABLE_DISCIPLINES." as subject", "subject.id = activity.subject_id AND subject.id=".$selectedDiscipline);
                $groupQuery->innerJoin(TABLE_DISCIPLINES." as subject", "subject.id = activity.subject_id AND subject.id=".$selectedDiscipline);
            }
            if (!is_null($selectedStudent)) {
                $personQuery->innerJoin(TABLE_STUDENTS." as student_s", "student_s.id = activity.student_id AND student_s.id=".$selectedStudent->getId());
                $groupQuery->innerJoin(TABLE_STUDENTS." as student_s", "student_s.id = activity.student_id AND student_s.id=".$selectedStudent->getId());
                $disciplineQuery->innerJoin(TABLE_STUDENTS." as student_s", "student_s.id = activity.student_id AND student_s.id=".$selectedStudent->getId());
            }
            if (!is_null($selectedControl)) {
                $personQuery->innerJoin(TABLE_STUDENTS_CONTROL_TYPES." as control", "activity.study_act_id = control.id AND control.id=".$selectedControl->getId());
                $groupQuery->innerJoin(TABLE_STUDENTS_CONTROL_TYPES." as control", "activity.study_act_id = control.id AND control.id=".$selectedControl->getId());
                $disciplineQuery->innerJoin(TABLE_STUDENTS_CONTROL_TYPES." as control", "activity.study_act_id = control.id AND control.id=".$selectedControl->getId());
            }
        }
        $set->setQuery($query);
        // ищем преподавателей, у которых есть оценки в списке
        $persons = array();
        foreach ($personQuery->execute()->getItems() as $item) {
            $persons[$item["id"]] = $item["name"];
        }
        // ищем группы, из которых студенты есть в списке
        $groups = array();
        foreach ($groupQuery->execute()->getItems() as $item) {
            $groups[$item["id"]] = $item["name"];
        }
        // ищем дисциплины, по которым ставились оценки
        $disciplines = array();
        foreach ($disciplineQuery->execute()->getItems() as $item) {
            $disciplines[$item["id"]] = $item["name"];
        }
        // остальные данные
        $items = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $a = new CStudentActivity($item);
            $items->add($a->getId(), $a);
        }
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->setData("persons", $persons);
        $this->setData("selectedPerson", $selectedPerson);
        $this->setData("groups", $groups);
        $this->setData("selectedGroup", $selectedGroup);
        $this->setData("disciplines", $disciplines);
        $this->setData("selectedDiscipline", $selectedDiscipline);
        $this->setData("selectedStudent", $selectedStudent);
        $this->setData("selectedControl", $selectedControl);
        $this->setData("records", $items);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_gradebook/index.tpl");
    }
    public function actionAdd() {
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/personTypeFilter.js");
        $this->addJSInclude("_modules/_gradebook/script.js");

        $activity = new CStudentActivity();
        $groups = array();
        foreach (CStaffManager::getStudentGroupsByYear(CUtils::getCurrentYear())->getItems() as $group) {
            if ($group->getStudents()->getCount() > 0) {
                $groups[$group->getId()] = $group->getName();
            }
        }
        $this->setData("students", array());
        $this->setData("groups", $groups);
        $this->setData("activity", $activity);
        $this->renderView("_gradebook/add.tpl");
    }
    public function actionEdit() {
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/personTypeFilter.js");

        $activity = CStaffManager::getStudentActivity(CRequest::getInt("id"));
        $groups = array();
        foreach (CStaffManager::getStudentGroupsByYear(CUtils::getCurrentYear())->getItems() as $group) {
            $groups[$group->getId()] = $group->getName();
        }
        $students = array();
        foreach ($activity->student->getGroup()->getStudents()->getItems() as $student) {
            $students[$student->getId()] = $student->getName();
        }
        $this->setData("students", $students);
        $this->setData("groups", $groups);
        $this->setData("activity", $activity);
        $this->renderView("_gradebook/edit.tpl");
    }
    public function actionSave() {
        $activity = new CStudentActivity();
        $activity->setAttributes(CRequest::getArray($activity::getClassName()));
        if ($activity->validate()) {
        	$activity->date_act = date("Y-m-d", strtotime($activity->date_act));
            // если стоит флаг запоминания данных, то пишем их в куки на месяц
            if (CRequest::getString("saveValues") == "1") {
                setcookie("gradebook[single][date_act]", $activity->date_act, time() + 1209600);
                setcookie("gradebook[single][subject_id]", $activity->subject_id, time() + 1209600);
                setcookie("gradebook[single][kadri_id]", $activity->kadri_id, time() + 1209600);
                setcookie("gradebook[single][group_id]", $activity->group_id, time() + 1209600);
                setcookie("gradebook[single][study_act_id]", $activity->study_act_id, time() + 1209600);
                setcookie("gradebook[single][student_id]", $activity->student_id, time() + 1209600);
                setcookie("gradebook[single][study_act_comment]", $activity->study_act_comment, time() + 1209600);
                setcookie("gradebook[single][study_mark]", $activity->study_mark, time() + 1209600);
                setcookie("gradebook[single][comment]", $activity->comment, time() + 1209600);
            }
            $activity->save();
            $this->redirect("?action=index");
            return false;
        }
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/personTypeFilter.js");
        $groups = array();
        foreach (CStaffManager::getStudentGroupsByYear(CUtils::getCurrentYear())->getItems() as $group) {
            if ($group->getStudents()->getCount() > 0) {
                $groups[$group->getId()] = $group->getName();
            }
        }
        $students = array();
        if (!is_null($activity->student)) {
            foreach ($activity->student->getGroup()->getStudents()->getItems() as $student) {
                $students[$student->getId()] = $student->getName();
            }
        }
        $this->setData("students", $students);
        $this->setData("groups", $groups);
        $this->setData("activity", $activity);
        $this->renderView("_gradebook/edit.tpl");
    }
    public function actionAddGroup() {
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/personTypeFilter.js");
        $this->addJSInclude("_modules/_gradebook/script.js");
        $groups = array();
        foreach (CStaffManager::getStudentGroupsByYear(CUtils::getCurrentYear())->getItems() as $group) {
            if ($group->getStudents()->getCount() > 0) {
                $groups[$group->getId()] = $group->getName();
            }
        }
        $activity = new CStudentActivitiesList();
        $this->setData("groups", $groups);
        $this->setData("activity", $activity);
        $this->renderView("_gradebook/addGroup.tpl");
    }
    public function actionSaveGroup() {
        $activity = new CStudentActivitiesList();
        $activity->setAttributes(CRequest::getArray($activity::getClassName()));
        if ($activity->validate()) {
            // если стоит флаг запоминания данных, то пишем их в куки на месяц
            if (CRequest::getString("saveValues") == "1") {
                setcookie("gradebook[multiple][date_act]", $activity->date_act, time() + 1209600);
                setcookie("gradebook[multiple][subject_id]", $activity->subject_id, time() + 1209600);
                setcookie("gradebook[multiple][kadri_id]", $activity->kadri_id, time() + 1209600);
                setcookie("gradebook[multiple][group_id]", $activity->group_id, time() + 1209600);
                setcookie("gradebook[multiple][study_act_id]", $activity->study_act_id, time() + 1209600);
                setcookie("gradebook[multiple][study_act_comment]", $activity->study_act_comment, time() + 1209600);
                setcookie("gradebook[multiple][comment]", $activity->comment, time() + 1209600);
            }
            foreach ($activity->student as $key=>$value) {
                if ($value != 0) {
                    $a = new CStudentActivity();
                    $a->date_act = date("Y-m-d", strtotime($activity->date_act));
                    $a->subject_id = $activity->subject_id;
                    $a->kadri_id = $activity->kadri_id;
                    $a->study_act_id = $activity->study_act_id;
                    $a->study_act_comment = $activity->study_act_comment;
                    $a->comment = $activity->comment;
                    $a->student_id = $key;
                    $a->study_mark = $value;
                    $a->save();

                    if (CRequest::getString("saveValues") == "1") {
                        setcookie("gradebook[multiple][student_".$key."]", $value, time() + 1209600);
                    }
                }
            }
            $this->redirect("?action=index");
            return true;
        }
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/personTypeFilter.js");
        $groups = array();
        foreach (CStaffManager::getStudentGroupsByYear(CUtils::getCurrentYear())->getItems() as $group) {
            if ($group->getStudents()->getCount() > 0) {
                $groups[$group->getId()] = $group->getName();
            }
        }
        $this->setData("groups", $groups);
        $this->setData("activity", $activity);
        $this->renderView("_gradebook/addGroup.tpl");
    }
    public function actionDelete() {
        $activity = CStaffManager::getStudentActivity(CRequest::getInt("id"));
        $activity->remove();
        $this->redirect("?action=index");
    }
    public function actionCreateGradebook() {
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/personTypeFilter.js");
        $search = new CStudentActivitySearchForm();
        $groups = array();
        foreach (CStaffManager::getStudentGroupsByYear(CUtils::getCurrentYear())->getItems() as $group) {
            if ($group->getStudents()->getCount() > 0) {
                $groups[$group->getId()] = $group->getName();
            }
        }
        $this->setData("groups", $groups);
        $this->setData("search", $search);
        $this->renderView("_gradebook/gradebook.create.tpl");
    }
    public function actionMyGradebooks() {
        $gradebooks = new CArrayList();
        $set = CActiveRecordProvider::getWithCondition(TABLE_GRADEBOOKS, "person_id = ".CSession::getCurrentUser()->getId());
        foreach ($set->getPaginated()->getItems() as $item) {
            $gradebook = new CGradebook($item);
            $gradebooks->add($gradebook->getId(), $gradebook);
        }
        $this->setData("paginator", $set->getPaginator());
        $this->setData("gradebooks", $gradebooks);
        $this->renderView("_gradebook/gradebooks.tpl");
    }
    public function actionSaveGradebook() {
        $search = new CStudentActivitySearchForm();
        $search->setAttributes(CRequest::getArray($search::getClassName()));
        if ($search->validate()) {
            $record = new CGradebook();
            $record->person_id = CSession::getCurrentUser()->getId();
            $record->kadri_id = $search->kadri_id;
            $record->subject_id = $search->subject_id;
            $record->group_id = $search->group_id;
            $record->date_start = $search->date_start;
            $record->date_end = $search->date_end;
            $record->save();
            $this->redirect("?action=viewGradebook&id=".$record->getId());
            return true;
        }
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/personTypeFilter.js");
        $groups = array();
        foreach (CStaffManager::getStudentGroupsByYear(CUtils::getCurrentYear())->getItems() as $group) {
            if ($group->getStudents()->getCount() > 0) {
                $groups[$group->getId()] = $group->getName();
            }
        }
        $this->setData("groups", $groups);
        $this->setData("search", $search);
        $this->renderView("_gradebook/gradebook.create.tpl");
    }
    public function actionViewGradebook() {
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addCSSInclude("_core/jTooltip/jquery.tooltip.css");
        $this->addJSInclude("_core/jTooltip/jquery.tooltip.js");
        $gradebook = CStaffManager::getGradebook(CRequest::getInt("id"));
        $this->setData("gradebook", $gradebook);
        $this->renderView("_gradebook/gradebook.tpl");
    }
    public function actionDeleteGradebook() {
        $gradebook = new CGradebook();
        $gradebook = CStaffManager::getGradebook(CRequest::getInt("id"));
        $gradebook->remove();
        $this->redirect("?action=myGradebooks");
    }

    /**
     * Большой поиск по всему-всему-всему
     */
    public function actionSearch() {
        $term = CRequest::getString("term");
        $res = array();
        /**
         * Поищем сначала преподавателей. 5 будет достаточно за один раз
         */
        $query = new CQuery();
        $query->select("distinct(person.id) as id, person.fio as name")
            ->from(TABLE_STUDENTS_ACTIVITY." as activity")
            ->innerJoin(TABLE_PERSON." as person", "activity.kadri_id = person.id")
            ->condition("person.fio like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "type" => 1
            );
        }
        /**
         * Теперь поищем дисциплины
         */
        $query = new CQuery();
        $query->select("distinct(subject.id) as id, subject.name as name")
            ->from(TABLE_STUDENTS_ACTIVITY." as activity")
            ->innerJoin(TABLE_DISCIPLINES." as subject", "activity.subject_id = subject.id")
            ->condition("subject.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "type" => 2
            );
        }
        /**
         * Теперь ищем группы
         */
        $query = new CQuery();
        $query->select("distinct(st_group.id) as id, st_group.name as name")
            ->from(TABLE_STUDENTS_ACTIVITY." as activity")
            ->innerJoin(TABLE_STUDENTS." as student", "student.id = activity.student_id")
            ->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id")
            ->condition("st_group.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "type" => 3
            );
        }
        /**
         * А теперь из студентов
         */
        $query = new CQuery();
        $query->select("distinct(student.id) as id, student.fio as name, st_group.name as group_name")
            ->from(TABLE_STUDENTS_ACTIVITY." as activity")
            ->innerJoin(TABLE_STUDENTS." as student", "student.id = activity.student_id")
            ->leftJoin(TABLE_STUDENT_GROUPS." as st_group", "st_group.id = student.group_id")
            ->condition("student.fio like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"]." (".$item["group_name"].")",
                "value" => $item["name"]." (".$item["group_name"].")",
                "object_id" => $item["id"],
                "type" => 4
            );
        }
        /**
         * Студенты по номеру зачетки
         * 0000118
         */
        $query = new CQuery();
        $query->select("distinct(student.id) as id, student.fio as name, student.stud_num as stud_num")
            ->from(TABLE_STUDENTS_ACTIVITY." as activity")
            ->innerJoin(TABLE_STUDENTS." as student", "student.id = activity.student_id")
            ->condition("student.stud_num like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"]." (".$item["stud_num"].")",
                "value" => $item["name"]." (".$item["stud_num"].")",
                "object_id" => $item["id"],
                "type" => 4
            );
        }
        /**
         * А еще виды контроля
         */
        $query = new CQuery();
        $query->select("distinct(control.id) as id, control.name as name")
            ->from(TABLE_STUDENTS_ACTIVITY." as activity")
            ->innerJoin(TABLE_STUDENTS_CONTROL_TYPES." as control", "control.id = activity.study_act_id")
            ->condition("control.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "type" => 5
            );
        }
        echo json_encode($res);
    }
}
