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
            ->from(TABLE_WORK_PLANS." as wp");
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
        	"title" => "Удалить выделенные",
        	"icon" => "actions/edit-delete.png",
        	"form" => "#MainView",
        	"link" => "workplans.php",
        	"action" => "delete"
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
        $items = CRequest::getArray("selectedInView");
        foreach ($items as $id){
        	$plan = CWorkPlanManager::getWorkplan($id);
        	$plan->remove();
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
    public function actionCopy() {
    	$pl = new CWorkPlan();
    	$pl->setAttributes(CRequest::getArray($pl->getClassName()));
    	$plan = CWorkPlanManager::getWorkplan($pl->getId());
    	/**
    	 * Клонируем саму рабочую программу
    	*/
    	$newPlan = $plan->copy();
    	$newPlan->title = "Копия ".$newPlan->title;
    	$newPlan->corriculum_discipline_id = $pl->corriculum_discipline_id;
    	$discipline = CCorriculumsManager::getDiscipline($pl->corriculum_discipline_id);
    	if (!is_null($discipline->discipline)) {
    		$newPlan->discipline_id = $discipline->discipline->getId();
    	}
    	$newPlan->save();
    	/**
    	 * Клонируем профили рабочей программы
    	*/
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_PROFILES, "plan_id=".$plan->getId())->getItems() as $ar) {
    		$profile = new CActiveModel($ar);
    		$ar = new CActiveRecord(array(
    			"plan_id" => $newPlan->getId(),
    			"profile_id" => $profile->profile_id,
    			"id" => null
    		));
    		$ar->setTable(TABLE_WORK_PLAN_PROFILES);
    		$ar->insert();
    	}
    	/**
    	 * Клонируем цели рабочей программы
    	 */
    	foreach ($plan->goals->getItems() as $goal) {
    		$newGoal = $goal->copy();
    		$newGoal->plan_id = $newPlan->getId();
    		$newGoal->save();
    	}
    	/**
    	 * Клонируем задачи рабочей программы
    	 */
    	foreach ($plan->tasks->getItems() as $task) {
    		$newTask = $task->copy();
    		$newTask->plan_id = $newPlan->getId();
    		$newTask->save();
    	}
    	/**
    	 * Клонируем компетенции рабочей программы
    	 */
    	foreach ($plan->competentions->getItems() as $competention) {
    		$newCompetention = $competention->copy();
    		$newCompetention->plan_id = $newPlan->getId();
    		$newCompetention->save();
    		/**
    		 * Копируем знания из компетенций
    		 * @var CTerm $knowledge
    		 */
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_KNOWLEDGES, "competention_id=".$competention->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    					"competention_id" => $newCompetention->getId(),
    					"knowledge_id" => $item->knowledge_id,
    					"id" => null
    			));
    			$ar->setTable(TABLE_WORK_PLAN_KNOWLEDGES);
    			$ar->insert();
    		}
    		/**
    		 * Копируем умения из компетенций
    		 * @var CTerm $skill
    		 */
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_SKILLS, "competention_id=".$competention->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    				"competention_id" => $newCompetention->getId(),
    				"skill_id" => $item->skill_id,
    				"id" => null
    			));
    			$ar->setTable(TABLE_WORK_PLAN_SKILLS);
    			$ar->insert();
    		}
    		/**
    		 * Копируем навыки из компетенций
    		 * @var CTerm $experience
    		 */
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_EXPERIENCES, "competention_id=".$competention->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    				"competention_id" => $newCompetention->getId(),
    				"experience_id" => $item->experience_id,
    				"id" => null
    			));
    			$ar->setTable(TABLE_WORK_PLAN_EXPERIENCES);
    			$ar->insert();
    		}
    		/**
    		 * Копируем умеет использовать из компетенций
    		 * @var CTerm $canUse
    		 */
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_COMPETENTION_CAN_USE, "competention_id=".$competention->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    					"competention_id" => $newCompetention->getId(),
    					"term_id" => $item->term_id,
    					"id" => null
    			));
    			$ar->setTable(TABLE_WORK_PLAN_COMPETENTION_CAN_USE);
    			$ar->insert();
    		}
    	}
    	/**
    	 * Клонируем предшествующие дисциплины рабочей программы
    	 */
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_DISCIPLINES_BEFORE, "plan_id=".$plan->getId())->getItems() as $ar) {
    		$item = new CActiveModel($ar);
    		$ar = new CActiveRecord(array(
    			"plan_id" => $newPlan->getId(),
    			"discipline_id" => $item->discipline_id,
    			"id" => null
    		));
    		$ar->setTable(TABLE_WORK_PLAN_DISCIPLINES_BEFORE);
    		$ar->insert();
    	}
    	/**
    	 * Клонируем последующие дисциплины рабочей программы
    	 */
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_DISCIPLINES_AFTER, "plan_id=".$plan->getId())->getItems() as $ar) {
    		$item = new CActiveModel($ar);
    		$ar = new CActiveRecord(array(
    			"plan_id" => $newPlan->getId(),
    			"discipline_id" => $item->discipline_id,
    			"id" => null
    		));
    		$ar->setTable(TABLE_WORK_PLAN_DISCIPLINES_AFTER);
    		$ar->insert();
    	}
    	/**
    	 * Клонируем семестры рабочей программы
    	 */
    	$termsMapping = array();
    	foreach ($plan->terms->getItems() as $term) {
    		$newTerm = $term->copy();
    		$newTerm->plan_id = $newPlan->getId();
    		$newTerm->save();
    		$termsMapping[$term->getId()] = $newTerm->getId();
    	}
    	
    	/**
    	 * Клонируем категории рабочей программы
    	 */
    	foreach ($plan->categories->getItems() as $categorie) {
    		$newCategorie = $categorie->copy();
    		$newCategorie->plan_id = $newPlan->getId();
    		$newCategorie->save();
    		/**
    		 * Копируем разделы из категорий
    		 * @var CWorkPlanContentSection $section
    		 */
    		foreach ($categorie->sections->getItems() as $section) {
    			$newSection = $section->copy();
    			$newSection->category_id = $newCategorie->getId();
    			$newSection->save();
    			/**
    			 * Копируем формы контроля из разделов
    			 * @var CTerm $control
    			 */
    			foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_CONTENT_CONTROLS, "section_id=".$section->getId())->getItems() as $ar) {
    				$item = new CActiveModel($ar);
    				$ar = new CActiveRecord(array(
    					"section_id" => $newSection->getId(),
    					"control_id" => $item->control_id,
    					"id" => null
    				));
    				$ar->setTable(TABLE_WORK_PLAN_CONTENT_CONTROLS);
    				$ar->insert();
    			}
    			/**
    			 * Копируем нагрузку из разделов
    			 * @var CWorkPlanContentSectionLoad $load
    			 */
    			foreach ($section->loads->getItems() as $load) {
    				$newLoad = $load->copy();
    				$newLoad->section_id = $newSection->getId();
    				$newLoad->term_id = $termsMapping[$load->term_id];
    				$newLoad->save();
    				/**
    				 * Копируем темы из нагрузки
    				 * @var CWorkPlanContentSectionLoadTopic $topic
    				 */
    				foreach ($load->topics->getItems() as $topic) {
    					$newTopic = $topic->copy();
    					$newTopic->load_id = $newLoad->getId();
    					$newTopic->save();
    				}
    				/**
    				 * Копируем технологии из нагрузки
    				 * @var CWorkPlanContentSectionLoadTechnology $technologie
    				 */
    				foreach ($load->technologies->getItems() as $technologie) {
    					$newTechnologie = $technologie->copy();
    					$newTechnologie->load_id = $newLoad->getId();
    					$newTechnologie->save();
    				}
    				/**
    				 * Копируем вопросы самоподготовки из нагрузки
    				 * @var CWorkPlanSelfEducationBlock $selfEducation
    				 */
    				foreach ($load->selfEducations->getItems() as $selfEducation) {
    					$newSelfEducation = $selfEducation->copy();
    					$newSelfEducation->load_id = $newLoad->getId();
    					$newSelfEducation->save();
    				}
    			}
    			/**
    			 * Копируем виды контроля из разделов
    			 * @var CWorkPlanControlTypes $controlType
    			 */
    			foreach ($section->controlTypes->getItems() as $controlType) {
    				$newControlType = $controlType->copy();
    				$newControlType->section_id = $newSection->getId();
    				$newControlType->save();
    				/**
    				 * Копируем баллы из видов контроля
    				 * @var CWorkPlanMarkStudyActivity $mark
    				 */
    				foreach ($controlType->marks->getItems() as $mark) {
    					$newMark = $mark->copy();
    					$newMark->activity_id = $newControlType->getId();
    					$newMark->save();
    				}
    			}
    		}
    	}
    	/**
    	 * Клонируем темы курсовых и РГР рабочей программы
    	 */
    	foreach ($plan->projectThemes->getItems() as $projectTheme) {
    		$newProjectTheme = $projectTheme->copy();
    		$newProjectTheme->plan_id = $newPlan->getId();
    		$newProjectTheme->save();
    	}
    	/**
    	 * Клонируем авторов рабочей программы
    	 */
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_AUTHORS, "plan_id=".$plan->getId())->getItems() as $ar) {
    		$item = new CActiveModel($ar);
    		$ar = new CActiveRecord(array(
    			"plan_id" => $newPlan->getId(),
    			"person_id" => $item->person_id,
    			"id" => null
    		));
    		$ar->setTable(TABLE_WORK_PLAN_AUTHORS);
    		$ar->insert();
    	}
    	/**
    	 * Клонируем самостоятельное изучение рабочей программы
    	 */
		foreach ($plan->selfEducations->getItems() as $selfEducation) {
    		$newSelfEducation = $selfEducation->copy();
    		$newSelfEducation->plan_id = $newPlan->getId();
    		$newSelfEducation->save();
    	}
    	/**
    	 * Клонируем фонд оценочных средств рабочей программы
    	 */
    	foreach ($plan->fundMarkTypes->getItems() as $fundMarkType) {
    		$newFundMarkType = $fundMarkType->copy();
    		$newFundMarkType->plan_id = $newPlan->getId();
    		$newFundMarkType->save();
    		/**
    		 * Копируем компетенции из фонда оценочных средств
    		 * @var CTerm $competention
    		 */
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_FUND_MARK_TYPES_COMPETENTIONS, "fund_id=".$fundMarkType->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    					"fund_id" => $newFundMarkType->getId(),
    					"competention_id" => $item->competention_id,
    					"id" => null
    			));
    			$ar->setTable(TABLE_WORK_PLAN_FUND_MARK_TYPES_COMPETENTIONS);
    			$ar->insert();
    		}
    		/**
    		 * Копируем уровни освоения из фонда оценочных средств
    		 * @var CTerm $level
    		 */
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_FUND_MARK_TYPES_LEVELS, "fund_id=".$fundMarkType->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    					"fund_id" => $newFundMarkType->getId(),
    					"level_id" => $item->level_id,
    					"id" => null
    			));
    			$ar->setTable(TABLE_WORK_PLAN_FUND_MARK_TYPES_LEVELS);
    			$ar->insert();
    		}
    		/**
    		 * Копируем оценочные средства из фонда оценочных средств
    		 * @var CTerm $level
    		 */
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_FUND_MARK_TYPES_CONTROLS, "fund_id=".$fundMarkType->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    					"fund_id" => $newFundMarkType->getId(),
    					"control_id" => $item->control_id,
    					"id" => null
    			));
    			$ar->setTable(TABLE_WORK_PLAN_FUND_MARK_TYPES_CONTROLS);
    			$ar->insert();
    		}
    	}
    	/**
    	 * Клонируем балльно-рейтинговую систему рабочей программы
    	 */
    	foreach ($plan->BRS->getItems() as $BRS) {
    		$newBRS = $BRS->copy();
    		$newBRS->plan_id = $newPlan->getId();
    		$newBRS->save();
    	}
    	/**
    	 * Клонируем оценочные средства рабочей программы
    	 */
    	foreach ($plan->markTypes->getItems() as $markTypes) {
    		$newMarkTypes = $markTypes->copy();
    		$newMarkTypes->plan_id = $newPlan->getId();
    		$newMarkTypes->save();
    		/**
    		 * Копируем фонды оценочных средств из перечня оченочных средств
    		 * @var CTerm $fund
    		 */
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_MARK_TYPE_FUNDS, "mark_id=".$markTypes->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    					"mark_id" => $newMarkTypes->getId(),
    					"fund_id" => $item->fund_id,
    					"id" => null
    			));
    			$ar->setTable(TABLE_WORK_PLAN_MARK_TYPE_FUNDS);
    			$ar->insert();
    		}
    		/**
    		 * Копируем места размещения оценочных средств из перечня оченочных средств
    		 * @var CTerm $place
    		 */
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_MARK_TYPE_PLACES, "mark_id=".$markTypes->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    					"mark_id" => $newMarkTypes->getId(),
    					"place_id" => $item->place_id,
    					"id" => null
    			));
    			$ar->setTable(TABLE_WORK_PLAN_MARK_TYPE_PLACES);
    			$ar->insert();
    		}
    	}
    	/**
    	 * Клонируем литературу рабочей программы
    	 */
    	foreach ($plan->literature->getItems() as $literature) {
    		$newLiterature = $literature->copy();
    		$newLiterature->plan_id = $newPlan->getId();
    		$newLiterature->save();
    	}
    	/**
    	 * Клонируем программное обеспечение рабочей программы
    	 */
    	foreach ($plan->software->getItems() as $software) {
    		$newSoftware = $software->copy();
    		$newSoftware->plan_id = $newPlan->getId();
    		$newSoftware->save();
    	}
    	/**
    	 * Клонируем доп. обеспечение рабочей программы
    	 */
    	foreach ($plan->additionalSupply->getItems() as $additionalSupply) {
    		$newAdditionalSupply = $additionalSupply->copy();
    		$newAdditionalSupply->plan_id = $newPlan->getId();
    		$newAdditionalSupply->save();
    	}
    	/**
    	 * Клонируем итоговый контроль рабочей программы
    	 */
    	foreach ($plan->finalControls->getItems() as $finalControl) {
    		$newFinalControl = $finalControl->copy();
    		$newFinalControl->plan_id = $newPlan->getId();
    		$newFinalControl->save();
    	}
    	/**
    	 * Клонируем вопросы к экзамену и зачету рабочей программы
    	 */
    	foreach ($plan->questions->getItems() as $question) {
    		$newQuestion = $question->copy();
    		$newQuestion->plan_id = $newPlan->getId();
    		$newQuestion->save();
    	}
    	/**
    	 * Клонируем оценочные материалы рабочей программы
    	 */
    	foreach ($plan->materialsOfEvaluation->getItems() as $materialOfEvaluation) {
    		$newMaterialOfEvaluation = $materialOfEvaluation->copy();
    		$newMaterialOfEvaluation->plan_id = $newPlan->getId();
    		$newMaterialOfEvaluation->save();
    	}
    	/**
    	 * Клонируем оценочные критерии рабочей программы
    	 */
    	foreach ($plan->criteria->getItems() as $criteria) {
    		$newCriteria = $criteria->copy();
    		$newCriteria->plan_id = $newPlan->getId();
    		$newCriteria->save();
    	}
    	/**
    	 * Редирект на страницу со списком
    	 */
    	$this->redirect("workplans.php?action=index");
    }
}