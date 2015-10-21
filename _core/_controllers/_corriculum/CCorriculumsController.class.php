<?php
/**
 * Description of CCorriculumsController
 *
 * @author TERRAN
 */
class CCorriculumsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Индивидуальные учебные планы");

        parent::__construct();        
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("corr.*")
            ->from(TABLE_CORRICULUMS." as corr")
            ->order("corr.id desc");
        $set->setQuery($query);
        $corriculums = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $corriculum = new CCorriculum($item);
            $corriculums->add($corriculum->getId(), $corriculum);
        }
        /**
         * Передаем данные
         */
        $this->setData("paginator", $set->getPaginator());
        $this->setData("corriculums", $corriculums);
        $this->renderView("_corriculum/_plan/index.tpl");
    }
    public function actionAdd() {
        $corriculum = new CCorriculum();
        $this->setData("corriculum", $corriculum);
        $this->renderView("_corriculum/_plan/add.tpl");
    }
    public function actionEdit() {
        $corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
        /**
         * Подключаем скрипты
         */
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->setData("corriculum", $corriculum);
        $this->renderView("_corriculum/_plan/edit.tpl");
    }
    public function actionSave() {
        $corriculum = new CCorriculum();
        $corriculum->setAttributes(CRequest::getArray($corriculum::getClassName()));
        if ($corriculum->validate()) {
            $corriculum->save();
            $this->redirect("?action=view&id=".$corriculum->getId());
            return true;
        }
        $this->setData("corriculum", $corriculum);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->renderView("_corriculum/_plan/edit.tpl");
    }
    public function actionView() {
        $corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
        /**
         * По дисциплинам получаем все виды занятий.
         * Нужно для того, чтобы сформировать шапку таблицы
         */
        $labors = new CArrayList();
        foreach ($corriculum->cycles->getItems() as $cycle) {
            foreach ($cycle->disciplines->getItems() as $discipline) {
                foreach ($discipline->labors->getItems() as $labor) {
                    $labors->add($labor->type_id, $labor);
                }
            }
        }
        /**
         * Передаем данные представлению
         */
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->setData("labors", $labors);
        $this->setData("corriculum", $corriculum);
        $this->renderView("_corriculum/_plan/view.tpl");
    }
    /**
     * Получаем список дисциплин JSON-ом
     */
    public function actionJSONGetDisciplines() {
    	$corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
    	$arr = array();
    	foreach ($corriculum->getDisciplines()->getItems() as $discipline) {
        	if (!is_null($discipline->competentions)) {
        		foreach ($discipline->competentions->getItems() as $disc) {
        			if (!is_null($discipline->plans)) {
        				foreach ($discipline->plans->getItems() as $disc) {
        					$arr[$discipline->getId()] = $discipline->discipline->getValue();
        				}
        			}
        		}
        	}
        }
    	echo json_encode($arr);
    }
    public function actionCopy() {
        $corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
        /**
         * Клонируем сам учебный план
         */
        $newCorriculum = $corriculum->copy();
        $newCorriculum->title = "Копия ".$newCorriculum->title;
        $newCorriculum->save();
        /**
         * Клонируем практики учебного плана
         */
        foreach ($corriculum->practices->getItems() as $practice) {
            $newPractice = $practice->copy();
            $newPractice->corriculum_id = $newCorriculum->getId();
            $newPractice->save();
        }
        /**
         * Клонируем циклы учебного плана
         */
        foreach ($corriculum->cycles->getItems() as $cycle) {
            $newCycle = $cycle->copy();
            $newCycle->corriculum_id = $newCorriculum->getId();
            $newCycle->save();
            /**
             * Клонируем дисциплины из циклов
             */
            foreach ($cycle->disciplines->getItems() as $discipline) {
                $newDiscipline = $discipline->copy();
                $newDiscipline->cycle_id = $newCycle->getId();
                $newDiscipline->save();
                /**
                 * Копируем рабочие программы из дисциплин
                 */
                if ($discipline->plans->getCount() > 0) {
                	foreach ($discipline->plans->getItems() as $plan) {
                		$newPlan = $plan->copy();
                		$newPlan->corriculum_discipline_id = $newDiscipline->getId();
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
                	}
                }
                /**
                 * Копируем компетенции из дисциплин
                 */
                if ($discipline->competentions->getCount() > 0) {
                	foreach ($discipline->competentions->getItems() as $competention) {
                		$newCompetention = $competention->copy();
                		$newCompetention->discipline_id = $newDiscipline->getId();
                		/**
                		 * Копируем знания из компетенций
                		 */
                		foreach ($competention->knowledges->getItems() as $knowledge) {
                			$newCompetention->knowledges->add($knowledge->getId(), $knowledge->getId());
                		}
                		/**
                		 * Копируем умения из компетенций
                		 */
                		foreach ($competention->skills->getItems() as $skill) {
                			$newCompetention->skills->add($skill->getId(), $skill->getId());
                		}
                		/**
                		 * Копируем навыки из компетенций
                		 */
                		foreach ($competention->experiences->getItems() as $experience) {
                			$newCompetention->experiences->add($experience->getId(), $experience->getId());
                		}
                		$newCompetention->save();
                	}
                }
                /**
                 * Копируем семестры
                 * @var CCorriculumDisciplineSection $section
                 */
				if ($discipline->sections->getCount() > 0) {
					foreach ($discipline->sections->getItems() as $section) {
						$newSection = $section->copy();
						$newSection->discipline_id = $newDiscipline->getId();
						$newSection->save();
						/**
						 * Копируем виды нагрузку из семестров
						 * @var CCorriculumDisciplineLabor $labor
						 */
						foreach ($section->labors->getItems() as $labor) {
							$newLabor = $labor->copy();
							$newLabor->section_id = $newSection->getId();
							$newLabor->type_id = $labor->type_id;
							$newLabor->discipline_id = $newDiscipline->getId();
							$newLabor->save();
						}
					}
				} else {
					/**
					 * Клонируем нагрузку из дисциплин
					 */
					foreach ($discipline->labors->getItems() as $labor) {
						$newLabor = $labor->copy();
						$newLabor->discipline_id = $newDiscipline->getId();
						$newLabor->type_id = $labor->type_id;
						$newLabor->save();
					}
				}
				// копируем дочерние дисциплины
				foreach ($discipline->children->getItems() as $child) {
					$newChildDiscipline = $child->copy();
					$newChildDiscipline->parent_id = $newDiscipline->getId();
					$newChildDiscipline->cycle_id = $newCycle->getId();
					$newChildDiscipline->save();
					if ($child->plans->getCount() > 0) {
						foreach ($child->plans->getItems() as $plan) {
							$newPlan = $plan->copy();
							$newPlan->corriculum_discipline_id = $newChildDiscipline->getId();
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
						}
					}	
					/**
					 * Копируем компетенции из дочерних дисциплин
					 */
					if ($child->competentions->getCount() > 0) {
						foreach ($child->competentions->getItems() as $competention) {
							$newChildCompetention = $competention->copy();
							$newChildCompetention->discipline_id = $newChildDiscipline->getId();
							/**
							 * Копируем знания из компетенций
							 */
							foreach ($competention->knowledges->getItems() as $knowledge) {
								$newChildCompetention->knowledges->add($knowledge->getId(), $knowledge->getId());
							}
							/**
							 * Копируем умения из компетенций
							 */
							foreach ($competention->skills->getItems() as $skill) {
								$newChildCompetention->skills->add($skill->getId(), $skill->getId());
							}
							/**
							 * Копируем навыки из компетенций
							 */
							foreach ($competention->experiences->getItems() as $experience) {
								$newChildCompetention->experiences->add($experience->getId(), $experience->getId());
							}
							$newChildCompetention->save();
						}
					}
                    /**
                     * Копируем семестры
                     * @var CCorriculumDisciplineSection $section
                     */
					if ($child->sections->getCount() > 0) {
						foreach ($child->sections->getItems() as $section) {
							$newSection = $section->copy();
							$newSection->discipline_id = $newChildDiscipline->getId();
							$newSection->save();
							/**
							 * Копируем виды нагрузку из семестров
							 * @var CCorriculumDisciplineLabor $labor
							 */
							foreach ($section->labors->getItems() as $labor) {
								$newLabor = $labor->copy();
								$newLabor->section_id = $newSection->getId();
								$newLabor->type_id = $labor->type_id;
								$newLabor->discipline_id = $newChildDiscipline->getId();
								$newLabor->save();
							}
						}
					} else {
						/**
						 * Клонируем нагрузку из дисциплин
						 */
						foreach ($child->labors->getItems() as $labor) {
							$newLabor = $labor->copy();
							$newLabor->discipline_id = $newChildDiscipline->getId();
							$newLabor->type_id = $labor->type_id;
							$newLabor->save();
						}
					}
				}
            }
        }
        /**
         * Все, редирект на страницу со списком
         */
        $this->redirect("index.php?action=index");
    }
    public function actionDelete() {
        $corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
        /**
         * Удаляем практики из плана
         */
        foreach ($corriculum->practices->getItems() as $practice) {
            $practice->remove();
        }
        /**
         * Удаляем циклы
         */
        foreach ($corriculum->cycles->getItems() as $cycle) {
            /**
             * Удаляем дисциплины из циклов
             */
            foreach ($cycle->disciplines->getItems() as $discipline) {
                /**
                 * Удаляем нагрузку из дисциплин
                 */
                foreach ($discipline->labors->getItems() as $labor) {
                    $labor->remove();
                }
                $discipline->remove();
            }
            $cycle->remove();
        }
        /**
         * Удаляем сам учебный план
         */
        $corriculum->remove();
        /**
         * Все, редирект на страницу со списком
         */
        $this->redirect("index.php?action=index");
    }
    /*





    public function actionSave() {
        if (CRequest::getInt("id") == 0) {
            $corriculum = CFactory::createCorriculum();
        } else {
            $corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
        }
        
        $corriculum->direction = CTaxonomyManager::getSpeciality(CRequest::getInt("direction_id"));
        $corriculum->basic_education = CRequest::getString("basic_education");
        $corriculum->save();
        
        $this->redirect("?action=index");
    }
    public function actionView() {
        $id = CRequest::getInt("id");
        $corriculum = CCorriculumsManager::getCorriculum($id);

        // получаем лист всех видов трудоемкостей, чтобы
        // по нему сформировать таблицу
        $labors = new CArrayList();
        $controls = new CArrayList();
        $periods = new CArrayList();
        foreach ($corriculum->cycles->getItems() as $cycle) {
            foreach ($cycle->disciplines->getItems() as $discipline) {
                foreach ($discipline->labors->getItems() as $labor) {
                    $labors->add($labor->type_id, $labor);
                }
                foreach ($discipline->controls->getItems() as $control) {
                    $controls->add($control->form_id, $control);
                }
                foreach ($discipline->hours->getItems() as $hour) {
                    $periods->add($hour->period, $hour);
                }
            }
        }

        $this->setData("periods", $periods);
        $this->setData("controls", $controls);
        $this->setData("labors", $labors);
        $this->setData("corriculum", $corriculum);
        $this->renderView("_corriculum/_plan/view.tpl");
    }
    public function actionAddDiscipline() {
        $cycyle = CCorriculumsManager::getCycle(CRequest::getInt("cycle_id"));
        $type = CRequest::getInt("type");

        $this->setData("cycle", $cycyle);
        $this->setData("type", $type);
        // обязательная часть
        if ($type == 1) {
            $this->renderView("_corriculum/_plan/addDiscipline.basic.tpl");
        // вариативная часть
        } elseif ($type == 2) {
            $this->renderView("_corriculum/_plan/addDiscipline.variant.tpl");
        }
    }
    public function actionAddCycle() {
        $id = CRequest::getInt("corriculum_id");
        $corriculum = CCorriculumsManager::getCorriculum($id);

        $this->setData("corriculum", $corriculum);
        $this->renderView("_corriculum/_plan/addCycle.tpl");
    }
    public function actionSaveCycle() {
        if (CRequest::getInt("id") == 0) {
            $cycle = CFactory::createCorriculumCycle();
        } else {
            $cycle = null;
        }

        $cycle->title = CRequest::getString("title");
        $cycle->number = CRequest::getString("number");
        $cycle->corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("corriculum_id"));
        $cycle->save();

        $this->redirect("?action=view&id=".$cycle->corriculum->id);
    }
    public function actionViewCycle() {
        $cycle = CCorriculumsManager::getCycle(CRequest::getInt("id"));

        $this->setData("cycle", $cycle);
        $this->renderView("_corriculum/_plan/view.cycle.tpl");
    }
    public function actionSaveDiscipline() {
        if (CRequest::getInt("id") == 0) {
            $discipline = CFactory::createCorriculumDiscipline();
        } else {
            $discipline = null;
        }

        $discipline->discipline_id = CRequest::getInt("discipline_id");
        $discipline->type = CRequest::getInt("type");
        $discipline->cycle_id = CRequest::getInt("cycle_id");
        $discipline->parent_id = CRequest::getInt("parent_id");
        $discipline->number = CRequest::getString("number");
        $discipline->save();

        $this->redirect("?action=viewCycle&id=".$discipline->cycle->id);
    }
    public function actionViewDiscipline() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));

        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_plan/view.discipline.tpl");
    }
    public function actionAddLabor() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));

        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_plan/addLabor.tpl");
    }
    public function actionSaveLabor() {
        if (CRequest::getInt("id") == 0) {
            $labor = CFactory::createCorriculumDisciplineLabor();
        } else {
            $labor = null;
        }

        $labor->discipline_id = CRequest::getInt("discipline_id");
        $labor->type_id = CRequest::getInt("type_id");
        $labor->form_id = CRequest::getInt("form_id");
        $labor->value = CRequest::getInt("value");
        $labor->save();

        $this->redirect("?action=viewDiscipline&id=".$labor->discipline_id);
    }
    public function actionAddControl() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
        $control = CFactory::createCorriculumDisciplineControl();
        $control->discipline = $discipline;

        $this->setData("discipline", $discipline);
        $this->setData("control", $control);
        $this->renderView("_corriculum/_plan/addControl.tpl");
    }
    public function actionSaveControl() {
        if (CRequest::getInt("id", CCorriculumDisciplineControl::getClassName()) != 0) {
            $control = CCorriculumsManager::getControl(CRequest::getInt("id", CCorriculumDisciplineControl::getClassName()));
        } else {
            $control = CFactory::createCorriculumDisciplineControl();
        }
        $control->setAttributes(CRequest::getArray(CCorriculumDisciplineControl::getClassName()));
        if ($control->validate()) {
            $control->save();
            $this->redirect("?action=viewDiscipline&id=".$control->discipline_id);
        }

        $this->setData("discipline", $control->discipline);
        $this->setData("control", $control);
        $this->renderView("_corriculum/_plan/addControl.tpl");
    }
    public function actionAddHour() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
        $hour = CFactory::createCorriculumDisciplineHour();
        $hour->discipline = $discipline;

        $this->setData("hour", $hour);
        $this->renderView("_corriculum/_plan/addHour.tpl");
    }
    public function actionSaveHour() {
        if (CRequest::getInt("id", CCorriculumDisciplineHour::getClassName()) != 0) {
            $hour = CCorriculumsManager::getHour(CRequest::getInt("id", CCorriculumDisciplineHour::getClassName()));
        } else {
            $hour = CFactory::createCorriculumDisciplineHour();
        }
        $hour->setAttributes(CRequest::getArray(CCorriculumDisciplineHour::getClassName()));
        if ($hour->validate()) {
            $hour->save();
            $this->redirect("?action=viewDiscipline&id=".$hour->discipline->id);
        }

        $this->setData("hour", $hour);
        $this->renderView("_corriculum/_plan/addHour.tpl");
    }
    */
}

?>
