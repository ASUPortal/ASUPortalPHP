<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.04.13
 * Time: 12:27
 * To change this template use File | Settings | File Templates.
 */

class CSABComissionController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $action = CRequest::getString("action");
            if ($action == "") {
                $action = "index";
            }
            if (!in_array($action, $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_useDojo = true;
        $this->_smartyEnabled = true;
        $this->setPageTitle("Комиссии ГАК");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet(false);
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("com.*")
            ->from(TABLE_SAB_COMMISSIONS." as com")
            ->order("com.year_id desc, com.id desc");
        $showAll = true;
        if (is_null(CRequest::getFilter("showall"))) {
            $query->condition("year_id = ".CUtils::getCurrentYear()->getId());
            $showAll = false;
        }
        $items = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $commission = new CSABCommission($ar);
            $items->add($commission->getId(), $commission);
        }
        $this->setData("showAll", $showAll);
        $this->setData("commissions", $items);
        $this->setData("paginator", $set->getPaginator());
		$this->addActionsMenuItem(array(
            array(
                "title" => "Добавить комиссию",
                "link" => "?action=add",
                "icon" => "actions/list-add.png"
            ),
            array(
                "title" => "Печать по шаблону",
                "link" => "#",
                "icon" => "devices/printer.png",
                "template" => "formset_state_attestation_group"
            )
        ));			
        $this->renderView("_state_attestation/index.tpl");
    }
    public function actionEdit() {
        $commission = CSABManager::getCommission(CRequest::getInt("id"));
        $form = new CSABCommissionForm();
        $form->commission = $commission;
        $this->setData("form", $form);
		$this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "index.php?action=index",
                "icon" => "actions/edit-undo.png"
            ),
            array(
                "title" => "Печать по шаблону",
                "link" => "#",
                "icon" => "devices/printer.png",
                "template" => "formset_state_attestation"
            )
        ));
        $this->renderView("_state_attestation/edit.tpl");
    }
    public function actionAdd() {
        $commission = new CSABCommission();
        $form = new CSABCommissionForm();
        $commission->year_id = CUtils::getCurrentYear()->getId();
        $form->commission = $commission;
        $this->setData("form", $form);
		$this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "index.php?action=index",
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_state_attestation/add.tpl");
    }
    public static function studentByProtocolSorter(CDiplom $first, CDiplom $second) {
    	if ($first->protocol == $second->protocol) {
    		return 0;
    	}
    	if ($first->protocol > $second->protocol) {
    		return 1;
    	}
    	return -1;
    }
    public function actionSave() {
        $form = new CSABCommissionForm();
        $form->setAttributes(CRequest::getArray($form::getClassName()));
        if ($form->validate()) {
            $form->save();
			if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$form->commission->getId());
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        $this->setData("form", $form);
        $this->renderView("_state_attestation/edit.tpl");
    }
    public function actionDelete() {
        $commission = CSABManager::getCommission(CRequest::getInt("id"));
        $commission->remove();
        $this->redirect("?action=index");
    }
    public function actionSearch() {

    }
    public function actionSearchDiplom() {
        $res = array();
        $term = CRequest::getString("term");
        /**
         * Поиск по теме диплома в таблице с дипломами
         */
        $query = new CQuery();
        $query->select("diplom.*")
            ->from(TABLE_DIPLOMS." as diplom")
            ->condition("diplom.dipl_name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $diplom = new CDiplom(new CActiveRecord($item));
            if (!is_null($diplom->student)) {
                $res[] = array(
                    "label" => $diplom->student->getName()." (".$diplom->dipl_name.")",
                    "value" => $diplom->student->getName()." (".$diplom->dipl_name.")",
                    "object_id" => $item["id"],
                    "filter" => "diplom"
                );
            }
        }
        /**
         * Поиск по фамилии студента
         */
        $query = new CQuery();
        $query->select("diplom.*")
            ->from(TABLE_DIPLOMS." as diplom")
            ->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id = student.id")
            ->condition("student.fio like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $diplom = new CDiplom(new CActiveRecord($item));
            if (!is_null($diplom->student)) {
                $res[] = array(
                    "label" => $diplom->student->getName()." (".$diplom->dipl_name.")",
                    "value" => $diplom->student->getName()." (".$diplom->dipl_name.")",
                    "object_id" => $item["id"],
                    "filter" => "diplom"
                );
            }
        }
        echo json_encode($res);
    }
    public function actionLoadDiplomsSubform() {
        $form = new CSABCommissionForm();
        $form->commission = CSABManager::getCommission(CRequest::getInt("id"));
        $this->setData("form", $form);
        $this->renderView("_state_attestation/subform.students.tpl");
    }
    public function actionAddDiplom() {
        $diplom = CStaffManager::getDiplom(CRequest::getInt("diplom_id"));
        if (!is_null($diplom)) {
            $diplom->gak_num = CRequest::getInt("commission_id");
            $diplom->save();
        }
    }
    public function actionRemoveDiplom() {
        $diplom = CStaffManager::getDiplom(CRequest::getInt("diplom_id"));
        if (!is_null($diplom)) {
            $diplom->gak_num = 0;
            $diplom->save();
        }
    }
    public function actionGetStatisticReport() {
        $commission = CSABManager::getCommission(CRequest::getInt("id"));
        $marks = array();
        $marks[0] = "";
        foreach (CTaxonomyManager::getMarksList() as $id=>$mark) {
            if (in_array(mb_strtolower($mark), array(
                "отлично",
                "хорошо",
                "удовлетворительно",
                "неудовлетворительно"
            ))) {
                $marks[$id] = $mark;
            }
        }
        $marks[] = "оценка не указана";
        $report = array();
        /**
         * Добавляем оценки в список
         */
        $row = array();
        foreach ($marks as $mark) {
            $row[] = $mark;
        }
        $report["Оценка"] = $row;
        /**
         * Добавляем оценки по дням
         */
        foreach ($commission->diploms->getItems() as $diplom) {
            $row = array();
            foreach ($marks as $id=>$mark) {
                $row[$id] = array(
                    "Бюджет" => 0,
                    "Контракт" => 0,
                    "Не указана" => 0
                );
            }
            $row[0] = array(
                "Бюджет" => 0,
                "Контракт" => 0,
                "Не указана" => 0
            );
            if (array_key_exists(date("d.m.Y", strtotime($diplom->date_act)), $report)) {
                $row = $report[date("d.m.Y", strtotime($diplom->date_act))];
            }
            /**
             * Добавляем оценку в соответствующую колонку
             */
            if (is_null($diplom->mark)) {
                $key = array_search("оценка не указана", $marks);
            } else {
                $key = array_search($diplom->mark->getValue(), $marks);
            }
            /**
             * Статистика по форме обучения
             */
            $student = $diplom->student;
            $byForm = $row[$key];
            if (!is_null($student)) {
                if ($student->getMoneyForm() == "") {
                    $byForm["Не указана"] += 1;
                } else {
                    $byForm[$student->getMoneyForm()] += 1;
                }
            }
            $row[$key] = $byForm;
            $report[date("d.m.Y", strtotime($diplom->date_act))] = $row;
        }
        /**
         * Посчитаем по дням
         * Лучше отдельно, так нагляднее
         */
        foreach ($commission->diploms->getItems() as $diplom) {
            $byForm = $report[date("d.m.Y", strtotime($diplom->date_act))][0];
            $student = $diplom->student;
            if (!is_null($student)) {
                if ($student->getMoneyForm() == "") {
                    $byForm["Не указана"] += 1;
                } else {
                    $byForm[$student->getMoneyForm()] += 1;
                }
            }
            $report[date("d.m.Y", strtotime($diplom->date_act))][0] = $byForm;
        }
        /**
         * Посчитаем всего.
         * Тоже отдельно для наглядности
         */
        $row = array();
        foreach ($marks as $id=>$mark) {
            $row[$id] = array(
                "Бюджет" => 0,
                "Контракт" => 0,
                "Не указана" => 0
            );
        }
        foreach ($commission->diploms->getItems() as $diplom) {
            if (is_null($diplom->mark)) {
                $key = array_search("оценка не указана", $marks);
            } else {
                $key = array_search($diplom->mark->getValue(), $marks);
            }
            $byMark = $row[$key];
            $student = $diplom->student;
            if (!is_null($student)) {
                if ($student->getMoneyForm() == "") {
                    $byMark["Не указана"] += 1;
                } else {
                    $byMark[$student->getMoneyForm()] += 1;
                }
            }
            $row[$key] = $byMark;
            /**
             * Полнейшее всего
             */
            $byMark = $row[0];
            $student = $diplom->student;
            if (!is_null($student)) {
                if ($student->getMoneyForm() == "") {
                    $byMark["Не указана"] += 1;
                } else {
                    $byMark[$student->getMoneyForm()] += 1;
                }
            }
            $row[0] = $byMark;
        }
        $report["Всего"] = $row;
        /**
         * Слегка пересортируем элементы массива
         */

        $this->setData("report", $report);
        $this->renderView("_state_attestation/subform.report.statistic.tpl");
    }
}
