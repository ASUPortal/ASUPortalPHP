<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Aleksandr Barmin
 * Date: 12.10.14
 * Time: 17:38
 * 
 * URL: http://mydesignstudio.ru/
 * mailto: abarmin@mydesignstudio.ru
 * twitter: @alexbarmin
 */

class CStaffPrintController extends CFlowController{
    public function actionPrintContractProperties() {
        $object = new CStaffContractPropertiesForm(self::getStatefullBean());
        $this->setData("object", $object);
        $this->renderView("_staff/print/contractProperties.tpl", "CStaffPrintController", "PrintContract");
    }
    public function actionGetIndividualPlansByYear() {
        $person = CStaffManager::getPerson(self::getStatefullBean()->getItem("id"));
        $plans = $person->getIndPlansByYears(CRequest::getInt("year"));
        $types = array();
        foreach ($plans->getItems() as $year=>$plan) {
            foreach ($plan->getItems() as $type) {
                $types[$type->getId()] = $type->type;
            }
        }
        echo json_encode($types);
    }
    public function actionPrintContract() {
        $object = new CStaffContractPropertiesForm(self::getStatefullBean());
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            // все данные в бин
            $items = CRequest::getArray($object::getClassName());
            foreach ($items as $key=>$value) {
                self::getStatefullBean()->add($key, $value);
            }
            // редирект на печать
            $this->redirectNextAction("CPrintController", "PrintWithBeanData");
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_staff/print/contractProperties.tpl", "CStaffPrintController", "PrintContract");
    }
    public function actionSelectPerson() {
    	$items = new CArrayList();
    	$bean = self::getStatefullBean();
    	$publication[] = split (":", $bean->getItem("id"));
    	$publications = array_values($publication);
    	$publications = $publication[0];
    	$load = CStaffManager::getPublication($publications[0]);
    	$izdan = $load->id;
    	$query = new CQuery();
    	$query->select("p.kadri_id")
    	->from(TABLE_PUBLICATION_BY_PERSONS." as p");
    	$query->condition("p.izdan_id=".$izdan);
    	foreach ($query->execute()->getItems() as $item) {
    		$kadri = $item["kadri_id"];
    		$author = CStaffManager::getPersonById($kadri);
    		$value = $author->getName();
    		$items->add($kadri, $value);
    	}
    	$this->setData("items", $items);
    	$this->renderView("_flow/pickList.tpl", "CStaffPrintController", "PrintPerson");
    }
    public function actionPrintPerson() {
    	// складываем из бина параметры
    	$bean = self::getStatefullBean();
    	$selected = CRequest::getArray("selected");
    	$bean->add("person", $selected[0]);
    	// редирект на печать
    	$this->redirectNextAction("CPrintController", "PrintWithBeanData");
    	return true;
    }
}