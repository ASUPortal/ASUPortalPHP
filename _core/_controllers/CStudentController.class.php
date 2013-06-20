<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.01.13
 * Time: 8:05
 * To change this template use File | Settings | File Templates.
 */
class CStudentController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Студенты");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("student.*")
            ->from(TABLE_STUDENTS." as student")
            ->order("student.id desc");
        $set->setQuery($query);
        // здесь разнообразные выборки с сортировками
        if (CRequest::getString("order") == "fio") {
            $direction = "asc";
            if (CRequest::getString("direction") != "") {
                $direction = CRequest::getString("direction");
            }
            $query->order("student.fio ".$direction);
        } elseif (CRequest::getString("order") == "group_id") {
            $direction = "asc";
            if (CRequest::getString("direction") != "") {
                $direction = CRequest::getString("direction");
            }
            $query->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id");
            $query->order("st_group.name ".$direction);
        } elseif(CRequest::getString("order") == "bud_contract") {
            $direction = "asc";
            if (CRequest::getString("direction") != "") {
                $direction = CRequest::getString("direction");
            }
            $query->order("student.bud_contract ".$direction);
        } elseif(CRequest::getString("order") == "telephone") {
            $direction = "asc";
            if (CRequest::getString("direction") != "") {
                $direction = CRequest::getString("direction");
            }
            $query->order("student.telephone ".$direction);
        } elseif(CRequest::getString("order") == "diploms") {
            $direction = "asc";
            if (CRequest::getString("direction") != "") {
                $direction = CRequest::getString("direction");
            }
            $query->innerJoin(TABLE_DIPLOMS." as diplom", "student.id = diplom.student_id");
            $query->order("diplom.dipl_name ".$direction);
        } elseif (CRequest::getString("order") == "stud_num") {
            $direction = "asc";
            if (CRequest::getString("direction") != "") {
                $direction = CRequest::getString("direction");
            }
            $query->order("student.stud_num ".$direction);
        }
        // запросы для фильтров
        $queryGroups = new CQuery();
        $queryGroups->select("distinct(st_group.id) as id, st_group.name as name")
            ->from(TABLE_STUDENT_GROUPS." as st_group")
            ->innerJoin(TABLE_STUDENTS." as student", "student.group_id = st_group.id")
            ->order("st_group.name");
        // фильтры
        $selectedStudent = null;
        $selectedGroup = null;
        $selectedDiplom = null;
        // фильтр по группе
        if (!is_null(CRequest::getFilter("group"))) {
            $query->innerJoin(TABLE_STUDENT_GROUPS." as st_group_f", "st_group_f.id = student.group_id AND st_group_f.id = ".CRequest::getFilter("group"));
            $selectedGroup = CRequest::getFilter("group");
        }
        // фильтр по студенту
        if (!is_null(CRequest::getFilter("student"))) {
            $query->condition("student.id = ".CRequest::getFilter("student"));
            $selectedStudent = CStaffManager::getStudent(CRequest::getFilter("student"));
            $queryGroups->condition("st_group.id = ".$selectedStudent->group_id);
        }
        // фильтр по теме диплома
        if (!is_null(CRequest::getFilter("diplom"))) {
            $query->innerJoin(TABLE_DIPLOMS." as diplom", "diplom.student_id = student.id AND diplom.id=".CRequest::getFilter("diplom"));
            $selectedDiplom = CStaffManager::getDiplom(CRequest::getFilter("diplom"));
            $queryGroups->innerJoin(TABLE_DIPLOMS." as diplom", "diplom.student_id = student.id AND diplom.id=".CRequest::getFilter("diplom"));
        }
        // параметры фильтров
        $groups = array();
        foreach ($queryGroups->execute()->getItems() as $item) {
            $groups[$item["id"]] = $item["name"];
        }
        // выборка финишных данных и их показ пользователю
        $students = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $student = new CStudent($item);
            $students->add($student->getId(), $student);
        }
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->setData("groups", $groups);
        $this->setData("selectedGroup", $selectedGroup);
        $this->setData("selectedStudent", $selectedStudent);
        $this->setData("selectedDiplom", $selectedDiplom);
        $this->setData("students", $students);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_students/index.tpl");
    }
    public function actionAdd() {
        $student = new CStudent();
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $groups = array();
        foreach (CActiveRecordProvider::getAllFromTable(TABLE_STUDENT_GROUPS, "name asc")->getItems() as $item) {
            $group = new CStudentGroup($item);
            $groups[$group->getId()] = $group->getName();
        }
        $forms = array(
            1 => "Бюджет",
            2 => "Контракт"
        );
        $this->setData("forms", $forms);
        $this->setData("groups", $groups);
        $this->setData("student", $student);
        $this->renderView("_students/add.tpl");
    }
    public function actionEdit() {
        $student = CStaffManager::getStudent(CRequest::getInt("id"));
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $groups = array();
        foreach (CActiveRecordProvider::getAllFromTable(TABLE_STUDENT_GROUPS, "name asc")->getItems() as $item) {
            $group = new CStudentGroup($item);
            $groups[$group->getId()] = $group->getName();
        }
        $forms = array(
            1 => "Бюджет",
            2 => "Контракт"
        );
        $this->setData("forms", $forms);
        $this->setData("groups", $groups);
        $this->setData("student", $student);
        $this->renderView("_students/edit.tpl");
    }
    public function actionSave() {
        $student = new CStudent();
        $student->setAttributes(CRequest::getArray(CStudent::getClassName()));
        if ($student->validate()) {
            $student->save();
            $this->redirect("?action=index");
            return true;
        }
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $groups = array();
        foreach (CActiveRecordProvider::getAllFromTable(TABLE_STUDENT_GROUPS, "name asc")->getItems() as $item) {
            $group = new CStudentGroup($item);
            $groups[$group->getId()] = $group->getName();
        }
        $forms = array(
            1 => "Бюджет",
            2 => "Контракт"
        );
        $this->setData("forms", $forms);
        $this->setData("groups", $groups);
        $this->setData("student", $student);
        $this->renderView("_students/edit.tpl");
    }
    public function actionDelete() {
        $student = CStaffManager::getStudent(CRequest::getInt("id"));
        $student->remove();
        $this->redirect("?action=index");
    }

    /**
     * Быстрый поиск
     */
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("term");
        /**
         * Сначала поищем по названию группы
         */
        $query = new CQuery();
        $query->select("distinct(st_group.id) as id, st_group.name as name")
            ->from(TABLE_STUDENTS." as student")
            ->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id")
            ->condition("st_group.name like '%".$term."%'")
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
         * Теперь по ФИО студента
         */
        $query = new CQuery();
        $query->select("distinct(student.id) as id, student.fio as name")
            ->from(TABLE_STUDENTS." as student")
            ->condition("student.fio like '%".$term."%'")
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
         * Еще поиск студента по номеру телефона
         */
        $query = new CQuery();
        $query->select("distinct(student.id) as id, student.fio as name, student.telephone as phone")
            ->from(TABLE_STUDENTS." as student")
            ->condition("student.telephone like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"]." - ".$item["phone"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "type" => 2
            );
        }
        /**
         * Еще поиск по номеру зачетной книжки
         */
        $query = new CQuery();
        $query->select("distinct(student.id) as id, student.fio as name, student.stud_num as number")
            ->from(TABLE_STUDENTS." as student")
            ->condition("student.stud_num like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"]." - ".$item["number"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "type" => 2
            );
        }
        /**
         * Еще поиск по комментарию, вот мало ли
         */
        $query = new CQuery();
        $query->select("distinct(student.id) as id, student.fio as name, student.comment as comment")
            ->from(TABLE_STUDENTS." as student")
            ->condition("student.comment like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"]." - ".$item["comment"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "type" => 2
            );
        }
        /**
         * Теперь по теме диплома
         */
        $query = new CQuery();
        $query->select("distinct(diplom.id) as id, diplom.dipl_name as name")
            ->from(TABLE_STUDENTS." as student")
            ->innerJoin(TABLE_DIPLOMS." as diplom", "diplom.student_id = student.id")
            ->condition("diplom.dipl_name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "type" => 3
            );
        }
        echo json_encode($res);
    }
    public function actionImport() {
        $form = new CStudentImportForm();
        $this->setData("form", $form);
        $this->renderView("_students/import.tpl");
    }
    public function actionImportProcess() {
        $form = new CStudentImportForm();
        $form->setAttributes(CRequest::getArray($form::getClassName()));
        $res = new CArrayList();
        $res = $form->importStudents();
        $this->setData("results", $res);
        $this->renderView("_students/imported.tpl");
    }
    public function actionGetCorriculumHoursTotal() {
    	$hours = 0;
    	$student = CStaffManager::getStudent(CRequest::getInt("id"));
    	if (!is_null($student)) {
    		$corriculum = $student->getCorriculum();
    		if (!is_null($corriculum)) {
    			foreach ($corriculum->cycles->getItems() as $cycle) {
    				foreach ($cycle->disciplines->getItems() as $disc) {
    					// здесь нужно проверить, что у студента по этой дисциплине есть оценка
    					$query = new CQuery();
    					$query->select("act.id, mark.name as name")
    					->from(TABLE_STUDENTS_ACTIVITY." as act")
    					->condition("act.student_id = ".$student->getId()." and act.kadri_id = 380 and act.subject_id = ".$disc->discipline->getId()." and act.study_act_id in (1, 2, 12, 14)")
    					->leftJoin(TABLE_MARKS." as mark", "mark.id = act.study_mark")
    					->order("act.id asc");
    					if ($query->execute()->getCount() > 0) {
    						$hours += $disc->getLaborValue();
    					}
    				}
    			}
    		}
    	}
    	echo $hours;
    }
    public function actionGetCorriculumTimeDifference(){
        $res = "";
        $student = CStaffManager::getStudent(CRequest::getInt("id"));
        if (!is_null($student)) {
            $corriculum = $student->getCorriculum();
            if (!is_null($corriculum)) {
                $res = "По плану (".$corriculum->load_total.")";
                /**
                 * Считаем нагрузку у студента
                 */
                $hours = 0;
                foreach ($corriculum->cycles->getItems() as $cycle) {
                    foreach ($cycle->disciplines->getItems() as $disc) {
                        // здесь нужно проверить, что у студента по этой дисциплине есть оценка
                        $query = new CQuery();
                        $query->select("act.id, mark.name as name")
                            ->from(TABLE_STUDENTS_ACTIVITY." as act")
                            ->condition("act.student_id = ".$student->getId()." and act.kadri_id = 380 and act.subject_id = ".$disc->discipline->getId()." and act.study_act_id in (1, 2, 12, 14)")
                            ->leftJoin(TABLE_MARKS." as mark", "mark.id = act.study_mark")
                            ->order("act.id asc");
                        if ($query->execute()->getCount() > 0) {
                            $hours += $disc->getLaborValue();
                        }
                    }
                }
                $res .= " - у студента (".$hours.") = ";
                /**
                 * Разницу показываем как-нибудь правильно
                 */
                if ($corriculum->load_total != $hours) {
                    $res .= '<span style="color: red; font-weight: bold;">'.($corriculum->load_total - $hours).'</span>';
                } else {
                    $res .= '<span style="color: green; font-weight: bold;">'.($corriculum->load_total - $hours).'</span>';
                }
            }
        }
        echo $res;
    }
}
