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
            $item .= " ".$load->type;
            $items->add($load->getId(), $item);
        }
        $this->setData("items", $items);
        $this->renderView("_flow/pickList.tpl", "CIndPlanPrintController", "PrintIndPlan");
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
}