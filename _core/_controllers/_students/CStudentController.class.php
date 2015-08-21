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
        $set = new CRecordSet(true);
        $query = new CQuery();
        $query->select("student.*")
            ->from(TABLE_STUDENTS." as student")
            ->order("student.id desc");
        // выборка финишных данных и их показ пользователю
        $set->setQuery($query);
        $students = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $student = new CStudent($item);
            $students->add($student->getId(), $student);
        }
        $this->addActionsMenuItem(array(
            array(
                "title" => "Добавить студента",
                "link" => "?action=add",
                "icon" => "actions/list-add.png"
            ),
            array(
                "title" => "ВКР",
                "link" => WEB_ROOT."_modules/_diploms/index.php",
                "icon" => "devices/network-wired.png"
            ),
            array(
                "title" => "Импорт",
                "link" => "?action=import",
                "icon" => "actions/document-save.png"
            ),
            array(
                "title" => "Групповые операции",
                "link" => "#",
                "icon" => "apps/utilities-terminal.png",
                "child" => array(
                    array(
                        "title" => "Перенос в другую группу",
                        "icon" => "actions/edit-redo.png",
                        "form" => "#MainView",
                        "link" => "index.php",
                        "action" => "changeGroup"
                    ),
                	array(
                		"title" => "Удалить выделенные",
                		"icon" => "actions/edit-delete.png",
                		"form" => "#MainView",
                		"link" => "index.php",
                		"action" => "Delete"
                	)
                )
            )
        ));
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
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "?action=index",
                "icon" => "actions/edit-undo.png"
            )
        ));
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
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "?action=index",
                "icon" => "actions/edit-undo.png"
            ),
            array(
                "title" => "Успеваемость",
                "link" => WEB_ROOT."_modules/_gradebook/index.php?action=index&filter=student:".$student->getId(),
                "icon" => "actions/address-book-new.png"
            ),
            array(
                "title" => "Печать по шаблону",
                "link" => "#",
                "icon" => "devices/printer.png",
                "template" => "formset_students"
            )
        ));
        $this->renderView("_students/edit.tpl");
    }
    public function actionSave() {
        $student = new CStudent();
        $student->setAttributes(CRequest::getArray(CStudent::getClassName()));
        if ($student->validate()) {
            $student->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$student->getId());
            } else {
                $this->redirect("?action=index");
            }
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
        if (!is_null($student)) {
        	$student->remove();
        }
        $items = CRequest::getArray("selectedInView");
        foreach ($items as $id){
        	$student = CStaffManager::getStudent($id);
        	$student->remove();
        }
        $this->redirect("?action=index");
    }
    /**
     * Быстрый поиск
     */
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("query");
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
                "field" => "group_id",
                "value" => $item["id"],
                "label" => $item["name"],
                "class" => "CStudent"
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
                "field" => "id",
                "value" => $item["id"],
                "label" => $item["name"],
                "class" => "CStudent"
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
                "field" => "id",
                "value" => $item["id"],
                "label" => $item["phone"],
                "class" => "CStudent"
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
                "field" => "id",
                "value" => $item["id"],
                "label" => $item["number"],
                "class" => "CStudent"
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
                "field" => "id",
                "value" => $item["id"],
                "label" => $item["comment"],
                "class" => "CStudent"
            );
        }
        /**
         * Теперь по теме диплома
         */
        /*
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
        */
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
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "?action=index",
                "icon" => "actions/edit-undo.png"
            )
        ));
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
    						$hours += $disc->getLaborByType("total")->value;
    					}
                        /**
                         * А дочерние дисциплины куда?)
                         */
                        foreach ($disc->children->getItems() as $child) {
                            // здесь нужно проверить, что у студента по этой дисциплине есть оценка
                            $query = new CQuery();
                            $query->select("act.id, mark.name as name")
                                ->from(TABLE_STUDENTS_ACTIVITY." as act")
                                ->condition("act.student_id = ".$student->getId()." and act.kadri_id = 380 and act.subject_id = ".$child->discipline->getId()." and act.study_act_id in (1, 2, 12, 14)")
                                ->leftJoin(TABLE_MARKS." as mark", "mark.id = act.study_mark")
                                ->order("act.id asc");
                            if ($query->execute()->getCount() > 0) {
                                $hours += $child->getLaborByType("total")->value;
                            }
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
                            $hours += $disc->getLaborByType("total")->value;
                        }
                        foreach ($disc->children->getItems() as $child) {
                            // здесь нужно проверить, что у студента по этой дисциплине есть оценка
                            $query = new CQuery();
                            $query->select("act.id, mark.name as name")
                                ->from(TABLE_STUDENTS_ACTIVITY." as act")
                                ->condition("act.student_id = ".$student->getId()." and act.kadri_id = 380 and act.subject_id = ".$child->discipline->getId()." and act.study_act_id in (1, 2, 12, 14)")
                                ->leftJoin(TABLE_MARKS." as mark", "mark.id = act.study_mark")
                                ->order("act.id asc");
                            if ($query->execute()->getCount() > 0) {
                                $hours += $child->getLaborByType("total")->value;
                            }
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
    public function actionChangeGroup() {
        $items = CRequest::getArray("selectedInView");
        $form = new CStudentChangeGroupForm();
        $form->students = $items;
        $this->setData("form", $form);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "?action=index",
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_students/changeGroup.tpl");
    }
    public function actionChangeGroupProcess() {
        $form = new CStudentChangeGroupForm();
        $form->setAttributes(CRequest::getArray(CStudentChangeGroupForm::getClassName()));
        if ($form->validate()) {
            $group = CStaffManager::getStudentGroup($form->group_id);
            foreach ($form->students as $id) {
                $student = CStaffManager::getStudent($id);
                if (!is_null($student)) {
                    $source = $student->group;
                    $student->group_id = $group->getId();
                    $student->save();

                    $student->createGroupChangeHistoryPoint($source, $group);
                }
            }
            $this->redirect("?action=index");
            return false;
        }
        $this->setData("form", $form);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "?action=index",
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_students/changeGroup.tpl");
    }
}
