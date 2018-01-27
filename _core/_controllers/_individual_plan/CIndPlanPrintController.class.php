<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 06.02.15
 * Time: 18:30
 */

class CIndPlanPrintController extends CFlowController{
    public function actionSelectPlanByTypeAndYear() {
        $items = new CArrayList();
        /**
         * Собираем все года и все виды нагрузки в них
         */
        $bean = self::getStatefullBean();
        $person = CStaffManager::getPerson($bean->getItem("id"));
        /**
         * @var $load CIndPlanPersonLoad
         */
        foreach ($person->loads->getItems() as $load) {
            $item = "";
            if (!is_null($load->year)) {
                $item = $load->year->getValue();
            }
            $item .= " ".$load->getType();
            $items->add($load->getId(), $item);
        }
        $this->setData("items", $items);
        $this->renderView("_flow/pickList.tpl", get_class($this), "PrintIndPlan");
    }
    public function actionPrintIndPlan() {
        // складываем из бина параметры
        $bean = self::getStatefullBean();
        $selected = CRequest::getArray("selected");
        $bean->add("planId", $selected[0]);
        // редирект на печать
        $this->redirectNextAction("CPrintController", "PrintWithBeanData");
        return true;
    }

    /**
     * Соберем все года, за которые у выбранных пользователей есть
     * индивидуальные планы
     */
    public function actionSelectYearsByPersonsPlans() {
        $bean = self::getStatefullBean();
        $ids = explode(":", $bean->getItem("id"));
        $bean->add("id", new CArrayList($ids));
        $persons = new CArrayList();
        foreach ($ids as $id) {
            $person = CStaffManager::getPerson($id);
            if (!is_null($person)) {
                $persons->add($person->getId(), $person);
            }
        }
        $years = new CArrayList();
        /**
         * @var $person CPerson
         */
        foreach ($persons->getItems() as $person) {
            foreach ($person->getIndPlansByYears()->getItems() as $year_id=>$data) {
                $year = CTaxonomyManager::getYear($year_id);
                if (!is_null($year)) {
                    $years->add($year->getId(), $year->getValue());
                }
            }
        }
        if ($years->getCount() == 0) {
            $this->setData("message", "Год не выбран, продолжение невозможно");
            $this->renderView("_flow/dialog.ok.tpl", "", "");
            return true;
        }
        $this->setData("items", $years);
        $this->renderView("_flow/pickList.tpl", get_class($this), "SelectLoadTypesByPersonsPlans");
    }

    /**
     * На основе выбранных людей, планов у них и выбранного года дадим на выбор
     * типы планов, по которым печатать
     */
    public function actionSelectLoadTypesByPersonsPlans() {
        if ($this->getSelectedInPickListDialog()->getCount() == 0) {
            $this->setData("message", "Год не выбран, продолжение невозможно");
            $this->renderView("_flow/dialog.ok.tpl", "", "");
            return true;
        }
        $year_id = $this->getSelectedInPickListDialog()->getFirstItem();
        // соберем всех людей
        $bean = self::getStatefullBean();
        $bean->add("year_id", $year_id);
        $ids = $bean->getItem("id");
        $persons = new CArrayList();
        foreach ($ids->getItems() as $id) {
            $person = CStaffManager::getPerson($id);
            if (!is_null($person)) {
                $persons->add($person->getId(), $person);
            }
        }
        /**
         * @var $person CPerson
         */
        $types = new CArrayList();
        foreach ($persons->getItems() as $person) {
            foreach ($person->getIndPlansByYears($year_id)->getItems() as $year_id=>$plans) {
                foreach ($plans->getItems() as $plan) {
                    $types->add($plan->type, $plan->getType());
                }
            }
        }
        $this->setData("items", $types);
        $this->setData("multiple", true);
        $this->renderView("_flow/pickList.tpl", get_class($this), "SelectMonthForPersonPlans");
    }
    public function actionSelectMonthForPersonPlans() {
        if ($this->getSelectedInPickListDialog()->getCount() == 0) {
            $this->setData("message", "Не выбраны виды нагрузки, продолжение невозможно");
            $this->renderView("_flow/dialog.ok.tpl", "", "");
            return true;
        }
        $bean = self::getStatefullBean();
        $bean->add("types", $this->getSelectedInPickListDialog());
        // номера месяцев соответствуют номерам столбцов в индивидуальном плане
        // для суммированных показателей
        $months = new CArrayList(array(
            6 => "январь",
            9 => "февраль",
            10 => "март",
            11 => "апрель",
            12 => "май",
            13 => "июнь",
            14 => "июль",
            2 => "сентябрь",
            3 => "октябрь",
            4 => "ноябрь",
            5 => "декабрь"
        ));
        $this->setData("items", $months);
        $this->setData("multiple", true);
        $this->renderView("_flow/pickList.tpl", get_class($this), "PrintIndPlayByMonth");
    }
    public function actionPrintIndPlayByMonth() {
        if ($this->getSelectedInPickListDialog()->getCount() == 0) {
            $this->setData("message", "Не выбран месяц, продолжение невозможно");
            $this->renderView("_flow/dialog.ok.tpl", "", "");
            return true;
        }
        $bean = self::getStatefullBean();
        $bean->add("months", $this->getSelectedInPickListDialog());
        // редирект на печать
        $this->redirectNextAction("CPrintController", "PrintWithBeanData");
    }
    /**
     * Диалог для смены возможности редактирования (выбор месяца)
     */
    public function actionDialogSelectMonthForPersonPlans() {
    	$bean = self::getStatefullBean();
    	$months = new CArrayList(array(
    		20 => "план на осенний семестр",
    		9 => "сентябрь",
    		10 => "октябрь",
    		11 => "ноябрь",
    		12 => "декабрь",
    		1 => "январь",
    		21 => "план на весенний семестр",
    		2 => "февраль",
    		3 => "март",
    		4 => "апрель",
    		5 => "май",
    		6 => "июнь",
    		7 => "июль"
    	));
    	$this->setData("items", $months);
    	$this->setData("multiple", true);
    	$this->renderView("_flow/pickList.tpl", get_class($this), "DialogIndPlanSelectOption");
    }
    /**
     * Диалог для смены возможности редактирования (выбор установки ограничения)
     */
    public function actionDialogIndPlanSelectOption() {
    	if ($this->getSelectedInPickListDialog()->getCount() == 0) {
    		$this->setData("message", "Не выбран месяц, продолжение невозможно");
    		$this->renderView("_flow/dialog.ok.tpl", "", "");
    		return true;
    	}
    	$bean = self::getStatefullBean();
    	$bean->add("months", $this->getSelectedInPickListDialog());
    	$options = new CArrayList(array(
    		0 => "Снять ограничение",
    		1 => "Ограничить редактирование"
    	));
    	$this->setData("items", $options);
    	$this->renderView("_flow/pickList.tpl", get_class($this), "DialogIndPlanByEditChoice");
    }
    /**
     * Переход на обработку смены статуса
     */
    public function actionDialogIndPlanByEditChoice() {
    	$bean = self::getStatefullBean();
    	$bean->add("options", $this->getSelectedInPickListDialog());
    	$this->redirectNextAction("CIndPlanPrintController", "UpdateEditStatusFromDialog");
    }
    /**
     * Обработку смены статуса редактирования
     */
    public function actionUpdateEditStatusFromDialog() {
    	$bean = CApp::getApp()->beans->getStatefullBean(CRequest::getString("beanId"));
    	$months = $bean->getItem("months");
    	$options = $bean->getItem("options");
    	$ids = $bean->getItem("id");
    	foreach (explode(":", $ids) as $loadId) {
    		foreach ($months->getItems() as $monthId) {
    			$works = CIndPlanManager::getIndPlanPersonWorkByMonthId($loadId, $monthId);
    			foreach ($works as $work) {
    				if ($options->getFirstItem() == 0) {
    					$work->_edit_restriction = 0;
    				} else {
    					$work->_edit_restriction = 1;
    				}
    				$work->save();
    			}
    		}
    	}
    	$url = WEB_ROOT."_modules/_individual_plan/load.php?action=viewLoads";
    	$this->redirect($url);
    }
}