<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 23.02.13
 * Time: 18:38
 * To change this template use File | Settings | File Templates.
 */
class CStudentGroupsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Учебные группы студентов");
        $this->_useDojo = true;

        parent::__construct();
    }
    public function actionIndex() {
        $currentCorriculum = null;
        $set = new CRecordSet(true);
        $query = new CQuery();
        $query->select("st_group.*")
            ->from(TABLE_STUDENT_GROUPS." as st_group")
            ->order("st_group.id desc");
        $set->setQuery($query);
        // фильтр по учебному плану
        if (!is_null(CRequest::getFilter("corriculum.id"))) {
        	$currentCorriculum = CRequest::getFilter("corriculum.id");
        	$query->innerJoin(TABLE_CORRICULUMS." as corriculum", "st_group.corriculum_id=corriculum.id and corriculum.id = ".$currentCorriculum);
        }
        $corriculumsQuery = new CQuery();
        $corriculumsQuery->select("corriculum.*")
	        ->from(TABLE_CORRICULUMS." as corriculum")
	        ->order("corriculum.title asc")
	        ->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "st_group.corriculum_id=corriculum.id");
        $corriculums = array();
        foreach ($corriculumsQuery->execute()->getItems() as $ar) {
        	$corriculum = new CCorriculum(new CActiveRecord($ar));
        	$corriculums[$corriculum->getId()] = $corriculum->title;
        }
        /**
         * Финишная выборка
         */
        $groups = new CArrayList();
        foreach($set->getPaginated()->getItems() as $item) {
            $group = new CStudentGroup($item);
            $groups->add($group->getId(), $group);
        }
        
        $this->addActionsMenuItem(array(
        	array(
    			"title" => "Добавить",
    			"link" => "?action=add",
    			"icon" => "actions/list-add.png"
    		),
        	array(
        		"title" => "Печать по шаблону",
        		"link" => "#",
        		"icon" => "devices/printer.png",
        		"template" => "formset_students_groups_list"
        	)
        ));
        /**
         * Параметры для групповой печати по шаблону по выбранным группам
         */
        $this->setData("template", "formset_students");
        $this->setData("templateWithGroup", "formset_students_with_group");
        $this->setData("selectedDoc", false);
        $this->setData("url", WEB_ROOT."_modules/_student_groups/index.php");
        $this->setData("action", "JSONGetStudentsFromGroups");
        $this->setData("actionWithGroup", "JSONGetStudentsFromGroupsWithGroup");
        $this->setData("id", "selectedInView");
        
        $this->setData("groups", $groups);
        $this->setData("currentCorriculum", $currentCorriculum);
        $this->setData("corriculums", $corriculums);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_student_groups/index.tpl");
    }
    public function actionEdit() {
        $group = CStaffManager::getStudentGroup(CRequest::getInt("id"));
        $students = array();
        foreach ($group->getStudents()->getItems() as $student) {
            $students[$student->getId()] = $student->getName();
        }
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Печать по шаблону",
        		"link" => "#",
        		"icon" => "devices/printer.png",
        		"template" => "formset_student_group"
        	)
        ));
        /**
         * Параметры для групповой печати по шаблону
         */
        $this->setData("template", "formset_students");
        $this->setData("templateWithGroup", "formset_students_with_group");
        $this->setData("selectedDoc", false);
        $this->setData("url", WEB_ROOT."_modules/_student_groups/index.php");
        $this->setData("action", "JSONGetStudents");
        $this->setData("actionWithGroup", "JSONGetStudentsWithGroup");
        $this->setData("id", CRequest::getInt("id"));
        
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("group", $group);
        $this->setData("students", $students);
        $this->renderView("_student_groups/edit.tpl");
    }
    public function actionAdd() {
        $group = new CStudentGroup();
        $this->setData("group", $group);
        $this->renderView("_student_groups/add.tpl");
    }
    public function actionSave() {
        $group = new CStudentGroup();
        $group->setAttributes(CRequest::getArray($group::getClassName()));
        if ($group->validate()) {
            $group->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$group->getId());
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        $students = array();
        foreach ($group->getStudents()->getItems() as $student) {
            $students[$student->getId()] = $student->getName();
        }
        $this->setData("group", $group);
        $this->setData("students", $students);
        $this->renderView("_student_groups/edit.tpl");
    }
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("query");
        /**
         * Ищем группу по названию
         */
        $query = new CQuery();
        $query->select("st_group.id, st_group.name")
            ->from(TABLE_STUDENT_GROUPS." as st_group")
            ->condition("LCASE(st_group.name) like '%".mb_strtolower($term)."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "field" => "id",
                "value" => $item["id"],
                "label" => $item["name"],
                "class" => "CStudentGroup"
            );
        }
        echo json_encode($res);
    }

    /**
     * Получаем список студентов JSON-ом
     */
    public function actionJSONGetStudents() {
        $group = CStaffManager::getStudentGroup(CRequest::getInt("id"));
        $arr = array();
        foreach ($group->getStudentsWithChangeGroupsHistory()->getItems() as $student) {
            $arr[$student->getId()] = $student->getName();
        }
        echo json_encode($arr);
    }
    /**
     * Получаем список студентов JSON-ом с добавлением группы
     */
    public function actionJSONGetStudentsWithGroup() {
        $group = CStaffManager::getStudentGroup(CRequest::getInt("id"));
        $arr = array($group->getId()=>$group->getName());
        foreach ($group->getStudentsWithChangeGroupsHistory()->getItems() as $student) {
            $arr[$student->getId()] = $student->getName();
        }
        echo json_encode($arr);
    }
    /**
     * Получаем список студентов из выбранных групп JSON-ом
     */
    public function actionJSONGetStudentsFromGroups() {
        $groups = explode(":", CRequest::getString("id"));
        $arr = array();
        foreach ($groups as $id) {
            $group = CStaffManager::getStudentGroup($id);
            foreach ($group->getStudentsWithChangeGroupsHistory()->getItems() as $student) {
                $arr[$student->getId()] = $student->getName();
            }
        }
        echo json_encode($arr);
    }
    /**
     * Получаем список студентов из выбранных групп JSON-ом с добавлением группы
     */
    public function actionJSONGetStudentsFromGroupsWithGroup() {
        $groups = explode(":", CRequest::getString("id"));
        $arr = array();
        foreach ($groups as $id) {
            $group = CStaffManager::getStudentGroup($id);
            $arr[$group->getId()] = $group->getName();
            foreach ($group->getStudentsWithChangeGroupsHistory()->getItems() as $student) {
                $arr[$student->getId()] = $student->getName();
            }
        }
        echo json_encode($arr);
    }
    /**
     * Все студенты без оценок по учебному плану в указанной группе
     */
    public function actionGetStudentsWithoutMarks() {
        $result = array();
        $group = CStaffManager::getStudentGroup(CRequest::getInt("id"));
        $corriculum = $group->corriculum;
        if (!is_null($corriculum)) {
            /**
             * Набираем список дисциплин, которые нужно проверить
             * Дисциплины с дочками не берем
             */
            $disciplines = array();
            foreach ($corriculum->cycles->getItems() as $cycle) {
                foreach ($cycle->disciplines->getItems() as $disc) {
                    if ($disc->children->getCount() == 0) {
                        if (!is_null($disc->discipline)) {
                            $disciplines[$disc->discipline_id] = $disc->discipline->getValue();
                        }
                    } else {
                        foreach ($disc->children->getItems() as $child) {
                            if (!is_null($child->discipline)) {
                                $disciplines[$child->discipline_id] = $child->discipline->getValue();
                            }
                        }
                    }
                }
            }
            /**
             * А так же практики
             */
            foreach ($corriculum->practices->getItems() as $practice) {
                if (!is_null($practice->discipline)) {
                    $disciplines[$practice->discipline_id] = $practice->discipline->getValue();
                }
            }
            /**
             * Аттестации
             */
            foreach ($corriculum->attestations->getItems() as $attestation) {
                if (!is_null($attestation->discipline)) {
                    $disciplines[$attestation->discipline_id] = $attestation->discipline->getValue();
                }
            }
            /**
             * Проверяем, по каким дисциплинам у студентов нет оценок
             */
            foreach ($disciplines as $d_id => $d_name) {
                foreach ($group->getStudents()->getItems() as $student) {
                    $query = new CQuery();
                    $query->select("st_act.*")
                        ->from(TABLE_STUDENTS_ACTIVITY." as st_act")
                        ->condition("st_act.student_id = ".$student->getId()." AND subject_id = ".$d_id." AND kadri_id = 380");
                    if ($query->execute()->getCount() == 0) {
                        $disc_array = array();
                        if (array_key_exists($d_name, $result)) {
                            $disc_array = $result[$d_name];
                        }
                        $disc_array[] = $student->getName();
                        $result[$d_name] = $disc_array;
                    }
                }
            }
        }
        /**
         * Сортируем по уменьшению количества студентов
         */
        uasort($result, "CStudentGroupsController::sortByItemsCount");
        $this->setData("result", $result);
        $this->renderView("_student_groups/subform.studentWithoutMarks.tpl");
    }
    public static function sortByItemsCount(array $el1, array $el2) {
        if (count($el1) == count($el2)) {
            return 0;
        }
        return (count($el1) > count($el2)) ? -1 : 1;
    }
    public function actionDelete(){
		$group = CStaffManager::getStudentGroup(CRequest::getInt("id"));
        $group->remove();
		$this->redirect("?action=index");
	}
}
