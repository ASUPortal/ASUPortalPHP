<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 05.08.13
 * Time: 20:23
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanLoadController extends CBaseController{
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }
        if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_NO_ACCESS) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Учебная нагрузка");

        parent::__construct();
    }
    public function actionIndex() {
        $selectedYear = null;
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);

        $query->select("p.*")
            ->from(TABLE_PERSON." as p")
            ->order("p.fio asc");
        if(CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY || 
        CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY){
            if(is_null(CSession::getCurrentPerson())){
                $query->condition("p.id = 0");
            } else {
                $query->condition("p.id = ".CSession::getCurrentPerson()->getId());
            }
        }
        // фильтр по году
        if (!is_null(CRequest::getFilter("year.id"))) {
        	$query->innerJoin(TABLE_IND_PLAN_LOADS." as l", "l.person_id = p.id");
        	$query->innerJoin(TABLE_YEARS." as year", "l.year_id = year.id");
        	$selectedYear = CRequest::getFilter("year.id");
        }
        $yearsQuery = new CQuery();
        $yearsQuery->select("year.*")
	        ->from(TABLE_YEARS." as year")
	        ->order("year.name asc");
        $years = array();
        foreach ($yearsQuery->execute()->getItems() as $ar) {
        	$year = new CTimeIntervals(new CActiveRecord($ar));
        	$years[$year->getId()] = $year->name;
        }

        $persons = new CArrayList();

        foreach ($set->getPaginated()->getItems() as $ar) {
            $person = new CPerson($ar);
            $persons->add($person->getId(), $person);
        }

        $this->addActionsMenuItem(
            array(
                array(
                    "title" => "Назад",
                    "link" => "index.php",
                    "icon" => "actions/edit-undo.png"
                ),
                array(
                    "title" => "Печать по шаблону",
                    "link" => "#",
                    "icon" => "devices/printer.png",
                    "template" => "formset_ind_plan_view"
                )
            )
        );

        $this->setData("paginator", $set->getPaginator());
        $this->setData("persons", $persons);
        $this->setData("years", $years);
        $this->setData("selectedYear", $selectedYear);
        $this->renderView("_individual_plan/load/index.tpl");
    }
    public function actionView() {
        $person = CStaffManager::getPerson(CRequest::getInt("id"));
        $year = CRequest::getInt("year");
        /**
         * Формируем меню
         */
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "load.php?action=index",
                "icon" => "actions/edit-undo.png"
            ),
            array(
                "title" => "Добавить",
                "link" => "load.php?action=add&id=".$person->getId()."&year=".$year,
                "icon" => "actions/list-add.png"
            ),
            array(
                "title" => "Печать по шаблону",
                "link" => "#",
                "icon" => "devices/printer.png",
                "template" => "formset_individual_plan"
            )
        ));
        $this->setData("person", $person);
        $this->renderView("_individual_plan/load/view.tpl");
    }
    public function actionAdd() {
        $load = new CIndPlanPersonLoad();
        $load->person_id = CRequest::getInt("id");
        $year = CRequest::getInt("year");
        $this->addActionsMenuItem(
        	array(
        		"title" => "Назад",
        		"link" => "load.php?action=view&id=".$load->person_id."&year=".$year,
        		"icon" => "actions/edit-undo.png"
        	)
        );
        $this->setData("load", $load);
        $this->renderView("_individual_plan/load/add.tpl");
    }
    public function actionEdit() {
        $load = CIndPlanManager::getLoad(CRequest::getInt("id"));
        $year = CRequest::getInt("year");
        $this->addActionsMenuItem(
        		array(
        				"title" => "Назад",
        				"link" => "load.php?action=view&id=".$load->person_id."&year=".$year,
        				"icon" => "actions/edit-undo.png"
        		)
        );
        $this->setData("load", $load);
        $this->renderView("_individual_plan/load/edit.tpl");
    }
    public function actionSave() {
        $load = new CIndPlanPersonLoad();
        $load->setAttributes(CRequest::getArray($load::getClassName()));
        if ($load->validate()) {
            $load->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$load->getId()."&year=".$load->year_id);
            } else {
                $this->redirect("?action=view&id=".$load->person_id."&year=".$load->year_id);
            }
            return true;
        }
        $this->setData("load", $load);
        $this->renderView("_individual_plan/load/add.tpl");
    }
    public function actionDelete() {
        $load = CIndPlanManager::getLoad(CRequest::getInt("id"));
        $person = $load->person;
        $year = $load->year_id;
        $load->remove();
        $this->redirect("?action=view&id=".$person->getId()."&year=".$year);
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по ФИО
    	*/
    	$query = new CQuery();
    	$query->select("distinct(person.id) as id, person.fio as name")
    	->from(TABLE_PERSON." as person")
    	->condition("person.fio like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CPerson"
    		);
    	}
    	echo json_encode($res);
    }
    /**
     * Выбор года для копирования нагрузки
     */
    public function actionSelectYearLoad() {
        $load = CIndPlanManager::getLoad(CRequest::getInt("id"));
        $items = CTaxonomyManager::getYearsList();
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "load.php?action=view&id=".$load->person_id."&year=".$load->year_id,
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->setData("items", $items);
        $this->setData("load", $load);
        $this->renderView("_individual_plan/load/selectYearLoad.tpl");
    }
    /**
     * Копирование нагрузки
     */
    public function actionCopyLoad() {
        $load = CIndPlanManager::getLoad(CRequest::getInt("id"));
        $year = CTaxonomyManager::getYear($load->year_id);
        $newYear = CTaxonomyManager::getYear(CRequest::getInt("year_id"));
        if (!is_null($newYear)) {
            $newLoad = $load->copy();
            $newLoad->year_id = $newYear->getId();
            $newLoad->conclusion = "Скопировано из ".$year->getValue()." года ".$newLoad->conclusion;
            $newLoad->save();
            $this->redirect("load.php?action=view&id=".$load->person_id."&year=".$newYear->getId());
        } else {
            $this->addActionsMenuItem(array(
                array(
    				"title" => "Назад",
    				"link" => "load.php?action=selectYearLoad&id=".CRequest::getInt("id"),
    				"icon" => "actions/edit-undo.png"
                )
            ));
            $this->setData("message", "Год не выбран, продолжение невозможно!");
            $this->renderView("_individual_plan/load/error.tpl");
        }
    }
    /**
     * Выбор года для копирования работ из нагрузки
     */
    public function actionSelectYearLoadWorks() {
        $load = CIndPlanManager::getLoad(CRequest::getInt("load_id"));
        $items = CTaxonomyManager::getYearsList();
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "load.php?action=view&id=".$load->person_id."&year=".$load->year_id,
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->setData("type", CRequest::getInt("type"));
        $this->setData("items", $items);
        $this->setData("load", $load);
        $this->renderView("_individual_plan/load/selectYearLoadWorks.tpl");
    }
    /**
     * Выбор нагрузки из выбранного учебного года для копирования работ
     */
    public function actionSelectLoad() {
        $load = CIndPlanManager::getLoad(CRequest::getInt("load_id"));
        $year = CTaxonomyManager::getYear(CRequest::getInt("year_id"));
        $person = CStaffManager::getPerson($load->person_id);
        if (!is_null($year)) {
            $items = CIndPlanManager::getLoadsByYearAndPerson($year, $person);
            $this->addActionsMenuItem(array(
                array(
    				"title" => "Назад",
    				"link" => "load.php?action=selectYearLoadWorks&load_id=".$load->getId()."&year=".$load->year_id,
    				"icon" => "actions/edit-undo.png"
                )
            ));
            $this->setData("type", CRequest::getInt("type"));
            $this->setData("items", $items);
            $this->setData("load", $load);
            $this->renderView("_individual_plan/load/selectLoad.tpl");
        } else {
            $this->addActionsMenuItem(array(
                array(
    				"title" => "Назад",
    				"link" => "load.php?action=selectYearLoadWorks&load_id=".$load->getId()."&type=".CRequest::getInt("type"),
    				"icon" => "actions/edit-undo.png"
                )
            ));
            $this->setData("message", "Год не выбран, продолжение невозможно!");
            $this->renderView("_individual_plan/load/error.tpl");
        }

    }
    /**
     * Копирование работ из нагрузки
     */
    public function actionCopyLoadWorks() {
        $load = CIndPlanManager::getLoad(CRequest::getInt("id"));
        $year = CTaxonomyManager::getYear($load->year_id);
        $newLoad = CIndPlanManager::getLoad(CRequest::getInt("load_id"));
        $newYear = CTaxonomyManager::getYear($newLoad->year_id);
        $type = CRequest::getInt("type");
        if (!is_null($newLoad)) {
            foreach ($load->getWorksByType($type)->getItems() as $work) {
                $newWork = $work->copy();
                if ($type == CIndPlanPersonWorkType::STUDY_AND_METHODICAL_LOAD or
    					$type == CIndPlanPersonWorkType::SCIENTIFIC_METHODICAL_LOAD or
    					$type == CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD) {
    						
                	$newWork->comment = "Скопировано из ".$year->getValue()." года ".$newWork->comment;
                	
                	// указываем срок выполнения в соответствии с годом, в который копируем
                	$date = date("Y-m-d", strtotime($newWork->plan_expiration_date));
                	$dateFirstPart = date(CSettingsManager::getSettingValue("dateStartPlanExpirationDateCurrentYear"), strtotime($newWork->plan_expiration_date));
                	$dateSecondPart = date(CSettingsManager::getSettingValue("dateStartPlanExpirationDateNextYear"), strtotime($newWork->plan_expiration_date));
                	if ($date >= $dateFirstPart) {
                		$newWork->plan_expiration_date = date("d.m.".date("Y", strtotime($newYear->date_start)), strtotime($date));
                	}
                	if ($date <= $dateSecondPart) {
                		$newWork->plan_expiration_date = date("d.m.".date("Y", strtotime($newYear->date_end)), strtotime($date));
                	}
                	
                }
                if ($type == CIndPlanPersonWorkType::STUDY_AND_METHODICAL_LOAD or
    					$type == CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD or
    					$type == CIndPlanPersonWorkType::CHANGE_RECORDS) {
                	$newWork->is_executed = 0;
                }
                $newWork->load_id = $newLoad->getId();
                $newWork->work_type = $type;
                $newWork->save();
            }
            $this->redirect("load.php?action=view&id=".$newLoad->person_id."&year=".$newLoad->year_id);
        } else {
            $this->addActionsMenuItem(array(
                array(
    				"title" => "Назад",
    				"link" => "load.php?action=selectYearLoadWorks&load_id=".$load->getId()."&type=".$type,
    				"icon" => "actions/edit-undo.png"
                )
            ));
            $this->setData("message", "Нугрузка не выбрана, продолжение невозможно!");
            $this->renderView("_individual_plan/load/error.tpl");
        }
    }
}
