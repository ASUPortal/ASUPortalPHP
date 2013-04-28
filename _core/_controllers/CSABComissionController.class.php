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

        $this->_smartyEnabled = true;
        $this->setPageTitle("Комиссии ГАК");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
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
        $this->renderView("_state_attestation/index.tpl");
    }
    public function actionEdit() {
        $commission = CSABManager::getCommission(CRequest::getInt("id"));
        $form = new CSABCommissionForm();
        $form->commission = $commission;
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("form", $form);
        $this->renderView("_state_attestation/edit.tpl");
    }
    public function actionAdd() {
        $commission = new CSABCommission();
        $form = new CSABCommissionForm();
        $commission->year_id = CUtils::getCurrentYear()->getId();
        $form->commission = $commission;
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("form", $form);
        $this->renderView("_state_attestation/add.tpl");
    }
    public function actionSave() {
        $form = new CSABCommissionForm();
        $form->setAttributes(CRequest::getArray($form::getClassName()));
        if ($form->validate()) {
            $form->save();
            $this->redirect("?action=index");
            return true;
        }
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
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
        $ar = new CActiveRecord(array(
            "id" => null,
            "commission_id" => CRequest::getInt("commission_id"),
            "diplom_id" => CRequest::getInt("diplom_id")
        ));
        $ar->setTable(TABLE_SAB_COMMISSION_DIPLOMS);
        $ar->insert();
    }
    public function actionRemoveDiplom() {
        var_dump("commission_id = ".CRequest::getInt("commission_id")." AND diplom_id = ".CRequest::getInt("diplom_id"));
        foreach (CActiveRecordProvider::getWithCondition(TABLE_SAB_COMMISSION_DIPLOMS, "commission_id = ".CRequest::getInt("commission_id")." AND diplom_id = ".CRequest::getInt("diplom_id"))->getItems() as $ar) {
            $ar->remove();
        }
    }
}