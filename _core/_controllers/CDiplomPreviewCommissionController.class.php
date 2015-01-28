<?php
class CDiplomPreviewCommissionController extends CBaseController {
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
        $this->setPageTitle("Предзащита дип. проектов - комиссии");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet(false);
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("com.*")
            ->from(TABLE_DIPLOM_PREVIEW_COMISSIONS." as com")
            ->order("com.date_act desc");
        $showAll = true;
        if (is_null(CRequest::getFilter("showall"))) {
            $query->condition('com.date_act between "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" and "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_end)).'"');
            $showAll = false;
        }
        $items = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $commission = new CDiplomPreviewComission($ar);
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
            )
        ));			
        $this->renderView("_diploms/preview_commission/index.tpl");
    }
    public function actionEdit() {
        $commission = CSABManager::getPreviewCommission(CRequest::getInt("id"));
        $form = new CDiplomPreviewCommissionForm();
        $form->commission = $commission;
        $this->setData("form", $form);
		$this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "preview_comm.php?action=index",
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_diploms/preview_commission/edit.tpl");
    }
    public function actionAdd() {
        $commission = new CDiplomPreviewComission();
        $form = new CDiplomPreviewCommissionForm();
        $commission->date_act = date("d.m.Y", mktime());
        $form->commission = $commission;
        $this->setData("form", $form);
		$this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "preview_comm.php?action=index",
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_diploms/preview_commission/add.tpl");
    }
    public function actionSave() {
        $form = new CDiplomPreviewCommissionForm();
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
        $this->renderView("_diploms/preview_commission/edit.tpl");
    }
    public function actionDelete() {
        $commission = CSABManager::getPreviewCommission(CRequest::getInt("id"));
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
}