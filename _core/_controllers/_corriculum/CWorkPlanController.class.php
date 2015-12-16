<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.03.15
 * Time: 21:49
 */

class CWorkPlanController extends CFlowController{
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
        $this->setPageTitle("Рабочие программы");

        parent::__construct();
    }

    /**
     * Добавление плана из представления.
     * Сначала надо выбрать учебный план
     */
    public function actionAddFromView() {
        $items = new CArrayList();
        $this->setData("items", $items);
        /**
         * @var $corriculum CCorriculum
         */
        foreach (CCorriculumsManager::getAllCorriculums()->getItems() as $corriculum) {
            $items->add($corriculum->getId(), $corriculum->title);
        }
        $this->setData("items", $items);
        $this->renderView("_flow/pickList.tpl", get_class($this), "AddFromView_SelectDiscipline");
    }

    /**
     * Добавление плана из представления
     * Выбор дисциплины в указанном учебном плане
     */
    public function actionAddFromView_SelectDiscipline() {
        $selected = CRequest::getArray("selected");
        $items = new CArrayList();
        $corriculum = CCorriculumsManager::getCorriculum($selected[0]);
        /**
         * @var $cycle CCorriculumCycle
         */
        foreach ($corriculum->getDisciplines() as $discipline) {
            $items->add($discipline->getId(), $discipline->discipline->getValue());
        }
        $this->setData("items", $items);
        $this->renderView("_flow/pickList.tpl", get_class($this), "AddFromView_CreateWorkPlan");
    }

    /**
     * Добавление плана из представления
     * Переадресация на стандартную функцию создания
     */
    public function actionAddFromView_CreateWorkPlan() {
        $selected = CRequest::getArray("selected");
        $this->redirect("workplans.php?action=add&id=".$selected[0]);
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("wp.*")
            ->from(TABLE_WORK_PLANS." as wp")
            ->condition("wp.is_archive = 0");
        $isArchive = false;
        if (CRequest::getInt("isArchive") == "1") {
            $isArchive = true;
        }
        if ($isArchive) {
            $query->condition("wp.is_archive = 1");
        }
        $paginated = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $plan = new CWorkPlan($ar);
            $paginated->add($plan->getId(), $plan);
        }
        $this->addActionsMenuItem(array(
        	array(
				"title" => "Групповые операции",
				"link" => "#",
				"icon" => "apps/utilities-terminal.png",
				"child" => array(
					array(
						"title" => "Удалить выделенные",
						"icon" => "actions/edit-delete.png",
						"form" => "#MainView",
						"link" => "workplans.php",
						"action" => "delete"
					),
					array(
						"title" => "Переместить в архив",
						"icon" => "devices/media-floppy.png",
						"form" => "#MainView",
						"link" => "workplans.php",
						"action" => "inArchiv"
					)
				)
			)
        ));
        $this->setData("isArchive", $isArchive);
        $this->setData("plans", $paginated);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_corriculum/_workplan/workplan/index.tpl");
    }
    public function actionDelete() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        if (!is_null($plan)) {
        	$plan->remove();
        }
        $items = CRequest::getArray("selectedDoc");
        foreach ($items as $id){
        	$plan = CWorkPlanManager::getWorkplan($id);
        	$plan->remove();
        }
        $this->redirect("workplans.php");
    }
    public function actionInArchiv() {
    	$items = CRequest::getArray("selectedDoc");
    	foreach ($items as $id){
    		$plan = CWorkPlanManager::getWorkplan($id);
    		$plan->is_archive = 1;
    		$plan->save();
    	}
    	$this->redirect("workplans.php");
    }
    public function actionAdd() {
        /**
         * получим дисциплину, по которой делаем рабочую программу
         * @var CCorriculumDiscipline $discipline
         * @var CCorriculum $corriculum
         */
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        $corriculum = $discipline->cycle->corriculum;
        //
        $plan = new CWorkPlan();
        $plan->title = "Наименование не указано";
        $plan->title_display = $plan->title;
        // дисциплина из учебного плана
        $plan->corriculum_discipline_id = $discipline->getId();
        // дисциплина из справочника
        if (!is_null($discipline->discipline)) {
            $plan->discipline_id = $discipline->discipline->getId();
        }
        // копируем информацию из учебного плана
        if (!is_null($corriculum)) {
            $plan->direction_id = $corriculum->speciality_direction_id;
            $plan->qualification_id = $corriculum->qualification_id;
            $plan->education_form_id = $corriculum->form_id;
        }
        $plan->date_of_formation = date("Y-m-d");
        $plan->year = date("Y");
        $plan->authors = new CArrayList();
        $plan->authors->add(CSession::getCurrentPerson()->getId(), CSession::getCurrentPerson()->getId());
        // место дисциплины в структуре плана
        if (!is_null($discipline->cycle)) {
            $plan->position = "Дисциплина относится к базовой части учебного цикла ".$discipline->cycle->title ;
        }
        $plan->save();
        /**
         * Скопируем компетенции из плана
         * @var CCorriculumDisciplineCompetention $competention
         */
        foreach ($discipline->competentions->getItems() as $competention) {
            $planCompetention = new CWorkPlanCompetention();
            $planCompetention->plan_id = $plan->getId();
            $planCompetention->allow_delete = 0;
            $planCompetention->competention_id = $competention->competention_id;
            if ($competention->knowledge_id != 0) {
                $planCompetention->knowledges->add($competention->knowledge_id, $competention->knowledge_id);
            }
            if ($competention->skill_id != 0) {
                $planCompetention->skills->add($competention->skill_id, $competention->skill_id);
            }
            if ($competention->experience_id != 0) {
                $planCompetention->experiences->add($competention->experience_id, $competention->experience_id);
            }
            $planCompetention->save();
        }
        $category = new CWorkPlanContentCategory();
        $category->plan_id = $plan->getId();
        $category->order = 1;
        $category->title = "Пустая категория";
        $category->save();
        $this->redirect("?action=edit&id=".$plan->getId());
    }
    public function actionEdit() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $plan->date_of_formation = date("d.m.Y", strtotime($plan->date_of_formation));
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "workplans.php?action=index",
                "icon" => "actions/edit-undo.png"
            ),
        	array(
        		"title" => "К дисциплине уч. плана",
        		"link" => "disciplines.php?action=edit&id=".$plan->corriculum_discipline_id,
        		"icon" => "actions/edit-undo.png"
        	),
            array(
                "title" => "Добавить категорию",
                "link" => "workplancontentcategories.php?action=add&id=".$plan->getId(),
                "icon" => "actions/list-add.png"
            ),
            array(
                "title" => "Добавить цель",
                "link" => "workplangoals.php?action=add&id=".$plan->getId(),
                "icon" => "actions/list-add.png"
            ),
        	array(
        		"title" => "Печать по шаблону",
        		"link" => "#",
        		"icon" => "devices/printer.png",
        		"template" => "formset_workplans"
        	),
        	array(
        		"title" => "Копировать рабочую программу",
        		"link" => "workplans.php?action=selectCorriculum&id=".$plan->getId(),
        		"icon" => "actions/edit-copy.png"
        	)
        ));
        $this->setData("plan", $plan);

        //$this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");

        $this->renderView("_corriculum/_workplan/workplan/edit.tpl");
    }
    public function actionSave() {
        $plan = new CWorkPlan();
        $plan->setAttributes(CRequest::getArray($plan->getClassName()));
        if ($plan->validate()) {
        	$plan->date_of_formation = date("Y-m-d", strtotime($plan->date_of_formation));
            $plan->save();
            if ($this->continueEdit()) {
                $this->redirect("workplans.php?action=edit&id=".$plan->getId());
            } else {
                $this->redirect("disciplines.php?action=edit&id=".$plan->corriculum_discipline_id);
            }
            return true;
        }
        $plan->date_of_formation = date("d.m.Y", strtotime($plan->date_of_formation));
        $this->setData("plan", $plan);
        $this->renderView("_corriculum/_workplan/workplan/edit.tpl");
    }
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("query");
        /**
         * Сначала поищем по учебного плана
         */
        $query = new CQuery();
        $query->select("distinct(wp.id) as id, wp.title as title")
            ->from(TABLE_WORK_PLANS." as wp")
            ->condition("wp.title like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "field" => "wp.id",
                "value" => $item["id"],
                "label" => $item["title"],
                "class" => "CWorkPlan"
            );
        }
        echo json_encode($res);
    }
    /**
     * Смена учебного плана для списка рабочих программ.
     * Выбор учебного плана
     */
    public function actionCorriculumToChange() {
    	$items = new CArrayList();
    	foreach (CCorriculumsManager::getAllCorriculums()->getItems() as $corriculum) {
    		$items->add($corriculum->getId(), $corriculum->title);
    	}
    	$this->setData("items", $items);
    	$this->renderView("_flow/pickList.tpl", get_class($this), "ChangeCorriculum");
    }
    /**
     * Смена учебного плана для списка рабочих программ
     */
    public function actionChangeCorriculum() {
    	$bean = self::getStatefullBean();
    	$corriculum = CRequest::getArray("selected");
    	$plans = explode(":", $bean->getItem("selected"));
    	$corriculum = CCorriculumsManager::getCorriculum($corriculum[0]);
    	foreach ($plans as $id) {
    		$plan = CWorkPlanManager::getWorkplan($id);
    		foreach ($corriculum->getDisciplines() as $discipline) {
    			if ($plan->corriculumDiscipline->discipline->getValue() == $discipline->discipline->getValue()) {
    				$plan->corriculum_discipline_id = $discipline->getId();
    				$plan->save();
    			}
    		}
    	}
    	$this->redirect("workplans.php");
    }
    /**
     * Выбор учебного плана для копирования одной рабочей программы
     */
    public function actionSelectCorriculum() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
    	$items = array();
    	foreach (CCorriculumsManager::getAllCorriculums()->getItems() as $corriculum) {
    		$items[$corriculum->getId()] = $corriculum->title;
    	}
    	$this->setData("items", $items);
    	$this->setData("plan", $plan);
    	$this->renderView("_corriculum/_workplan/workplan/select.tpl");
    }
    /**
     * Выбор дисциплины из выбранного учебного плана 
     * для копирования одной рабочей программы
     */
    public function actionCopyWorkPlan() {
    	$pl = new CWorkPlan();
    	$pl->setAttributes(CRequest::getArray($pl->getClassName()));
    	$plan = CWorkPlanManager::getWorkplan($pl->getId());
    	$corriculum = CCorriculumsManager::getCorriculum($pl->corriculum_discipline_id);
    	$items = array();
    	foreach ($corriculum->getDisciplines() as $discipline) {
    		$items[$discipline->getId()] = $discipline->discipline->getValue();
    	}
    	$this->setData("items", $items);
    	$this->setData("plan", $plan);
    	$this->renderView("_corriculum/_workplan/workplan/copy.tpl");
    }
    /**
     * Копирование одной выбранной рабочей программы
     */
    public function actionCopy() {
    	$pl = new CWorkPlan();
    	$pl->setAttributes(CRequest::getArray($pl->getClassName()));
    	$plan = CWorkPlanManager::getWorkplan($pl->getId());
    	$newPlan = $plan->copy();
    	$newPlan->corriculum_discipline_id = $pl->corriculum_discipline_id;
    	$discipline = CCorriculumsManager::getDiscipline($pl->corriculum_discipline_id);
    	if (!is_null($discipline->discipline)) {
    		$newPlan->discipline_id = $discipline->discipline->getId();
    	}
    	$newPlan->save();
    	/**
    	 * Редирект на страницу со списком
    	 */
    	$this->redirect("workplans.php?action=index");
    }
    /**
     * Копирование списка рабочих программ в другой учебный план.
     * Выбор учебного плана
     */
    public function actionCorriculumToCopy() {
    	$items = new CArrayList();
    	foreach (CCorriculumsManager::getAllCorriculums()->getItems() as $corriculum) {
    		$items->add($corriculum->getId(), $corriculum->title);
    	}
    	$this->setData("items", $items);
    	$this->renderView("_flow/pickList.tpl", get_class($this), "CopyInCorriculum");
    }
    /**
     * Копирование списка рабочих программ в другой учебный план
     */
    public function actionCopyInCorriculum() {
    	$bean = self::getStatefullBean();
    	$corriculum = CRequest::getArray("selected");
    	$plans = explode(":", $bean->getItem("selected"));
    	$corriculum = CCorriculumsManager::getCorriculum($corriculum[0]);
    	foreach ($plans as $id) {
    		$plan = CWorkPlanManager::getWorkplan($id);
    		foreach ($corriculum->getDisciplines() as $discipline) {
    			if ($plan->corriculumDiscipline->discipline->getValue() == $discipline->discipline->getValue()) {
    				$newPlan = $plan->copy();
    				$newPlan->corriculum_discipline_id = $discipline->getId();
    				$newPlan->save();
    			}
    		}
    	}
    	$this->redirect("workplans.php");
    }
    /**
     * Добавление литературы с сайта библиотеки.
     * Сначала надо выбрать тип литературы
     */
    public function actionAddFromUrl() {
    	$bean = self::getStatefullBean();
    	$bean->add("planId", CRequest::getInt("plan_id"));
    	$items = new CArrayList();
    	$result = array(1=>"Основная литература", 2=>"Дополнительная литература", 3=>"Интернет-ресурсы");
    	foreach ($result as $key=>$value) {
    		$items->add($key, $value);
    	}
    	$this->setData("items", $items);
    	$this->renderView("_flow/pickList.tpl", get_class($this), "AddFromUrl_SelectLiterature"); 
    }
    /**
     * Добавление литературы с сайта библиотеки.
     * Выбор литературы
     */
    public function actionAddFromUrl_SelectLiterature() {
    	$selected = CRequest::getArray("selected");
    	$bean = self::getStatefullBean();
    	$bean->add("type", $selected[0]);
    	
    	// подключаем PHP Simple HTML DOM Parser
    	require_once("../../simple_html_dom.php");
    
    	// подключаем библиотеку curl с указанием proxy
    	$proxy = CSettingsManager::getSettingValue("proxy_address");
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_PROXY, $proxy);
    	
    	// текущий учебный год
    	$year = str_replace("20", "", CUtils::getCurrentYear()->name);
    	
    	// код дисциплины
    	$plan = CWorkPlanManager::getWorkplan($bean->getItem("planId"));
    	$code = $plan->corriculumDiscipline->code;

    	curl_setopt($curl, CURLOPT_URL, "http://10.70.3.212/SkoWeb/view.aspx?db=Sko_".$year."&report=SKO_DISCIPLINE&Discipline=1,".$code);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
    	$str = curl_exec($curl);
    	curl_close($curl);
    
    	// create DOM from URL or file
    	$html = str_get_html($str);
    	
    	if(!empty($html)) {
    		$result = array();
    		
    		// массив всех элементов
    		$result1 = array();
    		$arr1 = array();
    		foreach($html->find('tr[class^="group_5"]') as $k=>$tr) {
    			foreach ($tr->find('td.groupnameint_5') as $kk=>$td) {
    				$arr1[$k][$kk] = $td->plaintext;
    			}
    			$result1[] = $arr1[$k][1];
    		}
    		
    		// массив элементов с низким, либо нулевым ККО
    		$result2 = array();
    		$arr2 = array();
    		foreach($html->find('tr[class="group_5 h_extraLow"]') as $k=>$tr) {
    			foreach ($tr->find('td.groupnameint_5') as $kk=>$td) {
    				$arr2[$k][$kk] = $td->plaintext;
    			}
    			$result2[] = $arr2[$k][1];
    		}
    		
    		// исключаем из первого массива элементы второго
    		$result = array_unique(array_diff($result1, $result2));
    		
    		$items = new CArrayList();
    		foreach ($result as $key=>$value) {
    			$items->add($key, $value);
    		}
    		$this->setData("items", $items);
    		$this->setData("multiple", true);
    		$this->renderView("_flow/pickListValues.tpl", get_class($this), "SaveFromUrl");
    		
    		// очищаем память
    		$html->clear();
    		unset($html);
    	} else {
    		$this->setData("message", "url не доступен, проверьте адрес прокси в настройках портала");
    		$this->renderView("_flow/dialog.ok.tpl", "", "");
    	}
    }
    public function actionSaveFromUrl() {
    	$bean = self::getStatefullBean();
    	$selected = CRequest::getArray("selected");
    	foreach ($selected as $literature) {
    		$object = new CWorkPlanLiterature();
    		$object->plan_id = $bean->getItem("planId");
    		$object->type = $bean->getItem("type");
    		$plan = CWorkPlanManager::getWorkplan($bean->getItem("planId"));
    		if ($object->type == 1) {
    			$object->ordering = $plan->baseLiterature->getCount() + 1;
    		} elseif($object->type == 2) {
    			$object->ordering = $plan->additionalLiterature->getCount() + 1;
    		} elseif($object->type == 3) {
    			$object->ordering = $plan->internetResources->getCount() + 1;
    		}
    		$object->book_name = urldecode($literature);
    		$object->save();
    	}
    	$this->redirect("workplans.php?action=edit&id=".$bean->getItem("planId"));
    }
}