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
        if (CRequest::getString("order") == "direction.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_SPECIALITIES." as direction", "corr.direction_id=direction.id");
        		$query->order("direction.name ".$direction);
        } elseif (CRequest::getString("order") == "term.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_TAXONOMY_TERMS." as term", "corr.profile_id=term.id");
        		$query->order("term.name ".$direction);
        } elseif (CRequest::getString("order") == "educ_form.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_EDUCATION_FORMS." as educ_form", "corr.form_id=educ_form.id");
        		$query->order("educ_form.name ".$direction);
        }
        $corriculums = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $corriculum = new CCorriculum($item);
            $corriculums->add($corriculum->getId(), $corriculum);
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
        				"link" => "index.php",
        				"action" => "delete"
        			)
        		)
        	),
            array(
                "title" => "Печать по шаблону",
                "link" => "#",
                "icon" => "devices/printer.png",
                "template" => "formset_corriculums_list"
            )
        ));
        /**
         * Параметры для групповой печати по шаблону
         */
        $this->setData("template", "formset_corriculums");
        $this->setData("selectedDoc", true);
        $this->setData("url", null);
        $this->setData("action", null);
        $this->setData("id", null);
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
        //$this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->setData("corriculum", $corriculum);
        $this->renderView("_corriculum/_plan/edit.tpl");
    }
    public function actionSave() {
        $corriculum = new CCorriculum();
        $corriculum->setAttributes(CRequest::getArray($corriculum::getClassName()));
        if ($corriculum->validate()) {
            $corriculum->save();
            if ($this->continueEdit()) {
            	$this->redirect("?action=edit&id=".$corriculum->getId());
            } else {
            	$this->redirect("?action=view&id=".$corriculum->getId());
            }
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
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Групповые операции",
        		"link" => "#",
        		"icon" => "apps/utilities-terminal.png",
        		"child" => array(
        			array(
        				"title" => "Удалить выделенные дисциплины",
        				"icon" => "actions/edit-delete.png",
        				"form" => "#MainView",
        				"link" => "index.php",
        				"action" => "deleteDisciplines"
        			),
        			array(
        				"title" => "Изменить цикл выбранных дисциплин",
        				"icon" => "actions/edit-copy.png",
        				"form" => "#MainView",
        				"link" => "index.php?id=".$corriculum->getId(),
        				"action" => "selectCycle"
        			)
        		)
        	),
        	array(
        		"title" => "Аналитика",
        		"link" => "#",
        		"icon" => "mimetypes/x-office-spreadsheet.png",
        		"child" => array(
        			array(
        				"title" => "Список нереализованных компетенций УП",
        				"icon" => "actions/edit-copy.png",
        				"link" => "#listUnrealizedCompetentionsCorriculum",
        				"attributes" => array(
        					"data-toggle" => "modal"
        				)
        			),
        			array(
        				"title" => "Список нереализованных компетенций РП",
        				"icon" => "actions/edit-copy.png",
        				"link" => "#listUnrealizedCompetentionsWorkPlans",
        				"attributes" => array(
        					"data-toggle" => "modal"
        				)
        			),
        			array(
        				"title" => "Список дисциплин без компетенций УП",
        				"icon" => "actions/edit-copy.png",
        				"link" => "#listDisciplinesWithOutCompetentionsCorriculum",
        				"attributes" => array(
        					"data-toggle" => "modal"
        				)
        			),
        			array(
        				"title" => "Список дисциплин без компетенций РП",
        				"icon" => "actions/edit-copy.png",
        				"link" => "#listDisciplinesWithOutCompetentionsWorkPlans",
        				"attributes" => array(
        					"data-toggle" => "modal"
        				)
        			),
        			array(
        				"title" => "Список компетенций без ЗУН УП",
        				"icon" => "actions/edit-copy.png",
        				"link" => "#listCorriculumCompetentionsWithoutQES",
        				"attributes" => array(
        					"data-toggle" => "modal"
        				)
        			),
        			array(
        				"title" => "Список компетенций без ЗУН РП УП",
        				"icon" => "actions/edit-copy.png",
        				"link" => "#listWorkPlansCompetentionsWithoutQES",
        				"attributes" => array(
        					"data-toggle" => "modal"
        				)
        			),
        			array(
        				"title" => "Список рабочих программ с ошибками семестров в нагрузке",
        				"icon" => "actions/edit-copy.png",
        				"link" => "#listWorkPlansWithErrorLoadTerms",
        				"attributes" => array(
        					"data-toggle" => "modal"
        				)
        			)
        		)
        	)
        ));
        /**
         * Параметры для групповой печати по шаблону
         */
        $this->setData("templateCorriculumDisciplines", "formset_corriculum_disciplines");
        $this->setData("templateWorkplans", "formset_workplans");
        $this->setData("selectedDoc", false);
        $this->setData("url", WEB_ROOT."_modules/_corriculum/index.php");
        $this->setData("actionGetDisciplines", "JSONGetDisciplines");
        $this->setData("actionGetWorkplans", "JSONGetWorkplans");
        $this->setData("id", CRequest::getInt("id"));
        /**
         * Параметры для печати по шаблону учебных планов
         */
        $template = "formset_corriculums";
        $formset = CPrintManager::getFormset($template);
        $this->setData("formset", $formset);
        $this->setData("template", $template);
        
        /**
         * Списки для аналитики
         */
        // специальность
        $direction = $corriculum->direction;
        // компетенции из справочника Учебный план - Компетенции
        $competentions = array();
        $taxonomy = CTaxonomyManager::getTaxonomy("corriculum_competentions");
        $query = new CQuery();
        $query->select("distinct(taxonomy.id) as id, taxonomy.name as name")
	        ->from(TABLE_TAXONOMY_TERMS." as taxonomy")
	        // берём компетенции, у которых специальность (alias) совпадает с направлением $direction учебного плана
	        ->condition("taxonomy.taxonomy_id=".$taxonomy->getId()." and taxonomy.alias=".$direction->getId());
        foreach ($query->execute()->getItems() as $item) {
        	$competentions[$item["id"]] = $item["name"];
        }
        
        // список нереализованных компетенций учебного плана
        $unrealizedCompetentionsCorriculum = array();
        
        // список нереализованных компетенций рабочих программ
        $unrealizedCompetentionsWorkPlans = array();
        
        // список дисциплин без компетенций учебного плана
        $disciplinesWithOutCompetentionsCorriculum = array();
        
        // список дисциплин без компетенций рабочих программ
        $disciplinesWithOutCompetentionsWorkPlans = array();
        
        // разница между компетенциями учебного плана и компетенциями рабочих программ
        $differenceCorriculumCompetentionsAndWorkPlanCompetentions = array();
        
        // отличия компетенций рабочих программ от учебного плана
        $differenceCorriculumCompetentionsAndWorkPlanCompetentions = array();
        
        // список компетенций без ЗУН учебного плана
        $corriculumCompetentionsWithoutQES = array();
        
        // список компетенций без ЗУН рабочих программ учебного плана
        $workPlansCompetentionsWithoutQES = array();
        
        // рабочие программы с ошибками семестров в нагрузке
        $workPlansWithErrorLoadTerms = array();
        
        // все дисциплины учебного плана
        $allDisciplines = new CArrayList();
        
        foreach ($corriculum->cycles->getItems() as $cycle) {
        	$cycleName = $cycle->number." ".$cycle->title." (".$cycle->title_abbreviated."):";
        	$disciplinesValues = array();
        	foreach ($cycle->allDisciplines->getItems() as $discipline) {
        		$allDisciplines->add($discipline->getId(), $discipline);
        		if ($discipline->children->isEmpty()) {
        			if ($discipline->competentions->isEmpty()) {
        				$disciplinesValues[] = $discipline->discipline->getValue();
        			}
        		}
        	}
        	if (!empty($disciplinesValues)) {
        		$disciplinesWithOutCompetentionsCorriculum[] = $cycleName;
        		$disciplinesWithOutCompetentionsCorriculum[] = $disciplinesValues;
        	}
        }
        foreach ($allDisciplines->getItems() as $discipline) {
        	
        }
        foreach ($competentions as $competentionId=>$competention) {
        	$disciplinesWithCompetentions = array();
        	foreach ($allDisciplines->getItems() as $discipline) {
        		$disciplineName = $discipline->discipline->getValue();
        		$compValues = array();
        		// определяем дисциплины с компетенциями в учебном плане
        		foreach ($discipline->competentions->getItems() as $comp) {
        			if (!is_null($comp->competention)) {
        				if ($comp->competention->getId() == $competentionId) {
        					$disciplinesWithCompetentions[] = $discipline->discipline->getValue();
        				}
        				if ($comp->knowledges->isEmpty() or $comp->skills->isEmpty() or $comp->experiences->isEmpty()) {
        					$compValues[] = $comp->competention->getValue();
        				}
        			}
        		}
        		if (!empty($compValues)) {
        			$corriculumCompetentionsWithoutQES[] = $disciplineName;
        			$corriculumCompetentionsWithoutQES[] = $compValues;
        		}
        	}
        	if (empty($disciplinesWithCompetentions)) {
        		$unrealizedCompetentionsCorriculum[] = $competention;
        	}
        }
        
        foreach ($allDisciplines->getItems() as $discipline) {
        	$disciplineName = $discipline->discipline->getValue();
        	$compValues = array();
        	$disciplineCompetentions = array();
        	// определяем компетенции дисциплин в учебном плане
        	foreach ($discipline->competentions->getItems() as $comp) {
        		if (!is_null($comp->competention)) {
        			$disciplineCompetentions[$comp->competention->getId()] = $comp->competention->getValue();
        		}
        	}
        	// определяем дисциплины с компетенциями в рабочих программах
        	foreach ($discipline->plans->getItems() as $plan) {
        		if (!empty($disciplineCompetentions)) {
        			$planName = $plan->title_display;
        			$competentionValues = array();
        			foreach ($disciplineCompetentions as $competentionId=>$competention) {
        				$isCompetention = false;
        				$competentionWithoutQES = array();
        				foreach ($plan->competentions->getItems() as $comp) {
        					if (!is_null($comp)) {
        						if ($comp->competention->getId() == $competentionId) {
        							$isCompetention = true;
        						}
        						if ($comp->knowledges->isEmpty() or $comp->skills->isEmpty() or $comp->experiences->isEmpty()) {
        							$competentionWithoutQES[] = $comp->competention->getValue();
        						}
        					}
        				}
        				if (!$isCompetention) {
        					$competentionValues[] = $competention;
        				}
        			}
        			if (!empty($competentionWithoutQES)) {
        				$workPlansCompetentionsWithoutQES[$plan->getId()] = $planName;
        				$workPlansCompetentionsWithoutQES[] = $competentionWithoutQES;
        			}
        			if (!empty($competentionValues)) {
        				$unrealizedCompetentionsWorkPlans[$plan->getId()] = $planName;
        				$unrealizedCompetentionsWorkPlans[] = $competentionValues;
        			}
        			if ($plan->competentions->isEmpty()) {
        				$disciplinesWithOutCompetentionsWorkPlans[] = $discipline->discipline->getValue();
        			}
        		}
        
        		$termsMapping = array();
        		foreach ($plan->terms->getItems() as $term) {
        			$termsMapping[$term->getId()] = $term->getId();
        		}
        
        		foreach ($plan->categories->getItems() as $categorie) {
        			foreach ($categorie->sections->getItems() as $section) {
        				foreach ($section->loads->getItems() as $load) {
        					if (!array_key_exists($load->term_id, $termsMapping)) {
        						$workPlansWithErrorLoadTerms[$plan->getId()] = $planName;
        					}
        				}
        			}
        		}
        		 
        	}
        
        }
        /**
         * Передаем данные представлению
         */
        $this->setData("unrealizedCompetentionsCorriculum", $unrealizedCompetentionsCorriculum);
        $this->setData("unrealizedCompetentionsWorkPlans", $unrealizedCompetentionsWorkPlans);
        $this->setData("disciplinesWithOutCompetentionsCorriculum", $disciplinesWithOutCompetentionsCorriculum);
        $this->setData("disciplinesWithOutCompetentionsWorkPlans", $disciplinesWithOutCompetentionsWorkPlans);
        $this->setData("corriculumCompetentionsWithoutQES", $corriculumCompetentionsWithoutQES);
        $this->setData("workPlansCompetentionsWithoutQES", $workPlansCompetentionsWithoutQES);
        $this->setData("workPlansWithErrorLoadTerms", $workPlansWithErrorLoadTerms);
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
    /**
     * Получаем список рабочих программ JSON-ом
     */
    public function actionJSONGetWorkplans() {
    	$corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
    	$arr = array();
    	foreach ($corriculum->getDisciplines()->getItems() as $discipline) {
    		if (!is_null($discipline->plans)) {
    			foreach ($discipline->plans->getItems() as $workplan) {
    				$arr[$workplan->getId()] = $workplan->title;
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
                foreach ($discipline->plans->getItems() as $plan) {
                	$newPlan = $plan->copy();
                	$newPlan->corriculum_discipline_id = $newDiscipline->getId();
                	if (!is_null($newDiscipline->discipline)) {
                		$newPlan->discipline_id = $newDiscipline->discipline->getId();
                	}
                	$newPlan->save();
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
					foreach ($child->plans->getItems() as $plan) {
						$newPlan = $plan->copy();
						$newPlan->corriculum_discipline_id = $newChildDiscipline->getId();
						if (!is_null($newChildDiscipline->discipline)) {
							$newPlan->discipline_id = $newChildDiscipline->discipline->getId();
						}
						$newPlan->save();
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
        if (!is_null($corriculum)) {
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
        			/**
        			 * Удаляем рабочие программы из дисциплин
        			 */
        			foreach ($discipline->plans->getItems() as $plan) {
        				$plan->remove();
        			}
        			/**
        			 * Удаляем дочерние дисциплины из родительской
        			 */
        			foreach ($discipline->children->getItems() as $child) {
        				/**
        				 * Удаляем рабочие программы из дочерних дисциплин
        				 */
        				foreach ($child->plans->getItems() as $plan) {
        					$plan->remove();
        				}
        				$child->remove();
        			}
        			$discipline->remove();
        		}
        		$cycle->remove();
        	}
        	/**
        	 * Удаляем сам учебный план
        	 */
        	$corriculum->remove();
        }
        $items = CRequest::getArray("selectedDoc");
        if (!empty($items)) {
        	foreach ($items as $id){
        		$corriculum = CCorriculumsManager::getCorriculum($id);
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
        				/**
        				 * Удаляем рабочие программы из дисциплин
        				 */
        				foreach ($discipline->plans->getItems() as $plan) {
        					$plan->remove();
        				}
        				/**
        				 * Удаляем дочерние дисциплины из родительской
        				 */
        				foreach ($discipline->children->getItems() as $child) {
        					/**
        					 * Удаляем рабочие программы из дочерних дисциплин
        					 */
        					foreach ($child->plans->getItems() as $plan) {
        						$plan->remove();
        					}
        					$child->remove();
        				}
        				$discipline->remove();
        			}
        			$cycle->remove();
        		}
        		/**
        		 * Удаляем сам учебный план
        		 */
        		$corriculum->remove();
        	}
        }
        /**
         * Все, редирект на страницу со списком
         */
        $this->redirect("index.php?action=index");
    }
    public function actionDeleteDisciplines() {
    	$discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
    	if (!is_null($object)) {
    		$corriculum = $discipline->cycle->corriculum_id;
    		$discipline->remove();
    	}
    	$items = CRequest::getArray("selectedDoc");
    	if (!empty($items)) {
    		$discipline = CCorriculumsManager::getDiscipline($items[0]);
    		$items = CRequest::getArray("selectedDoc");
    		if (!is_null($discipline)) {
    			$corriculum = $discipline->cycle->corriculum_id;
    			foreach ($items as $id){
    				$discipline = CCorriculumsManager::getDiscipline($id);
    				/**
    				 * Удаляем рабочие программы из дисциплины
    				 */
    				foreach ($discipline->plans->getItems() as $plan) {
    					$plan->remove();
    				}
    				/**
    				 * Удаляем дочерние дисциплины из родительской
    				 */
    				foreach ($discipline->children->getItems() as $child) {
    					$child->remove();
    				}
    				$discipline->remove();
    			}
    		}
    	}
    	$this->redirect("index.php?action=view&id=".$corriculum);
    }
    /**
     * Выбор цикла для изменения в дисциплинах
     */
    public function actionSelectCycle() {
    	$corriculum = CCorriculumsManager::getCorriculum(CRequest::getInt("id"));
    	$disciplines = implode(";", CRequest::getArray("selectedDoc"));
    	$items = array();
    	foreach ($corriculum->cycles->getItems() as $cycle) {
    		$items[$cycle->getId()] = $cycle->title;
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => "index.php?action=view&id=".CRequest::getInt("id"),
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->setData("corriculum", $corriculum);
    	$this->setData("disciplines", $disciplines);
    	$this->setData("items", $items);
    	$this->renderView("_corriculum/_plan/selectCycle.tpl");
    }
    /**
     * Смена цикла для выбранных дициплин
     */
    public function actionChangeCycle() {
    	$items = explode(";", CRequest::getString("disciplines"));
    	if (!empty($items)) {
    		$discipline = CCorriculumsManager::getDiscipline($items[0]);
    		if (!is_null($discipline)) {
    			$corriculum = $discipline->cycle->corriculum_id;
    			foreach ($items as $id){
    				$discipline = CCorriculumsManager::getDiscipline($id);
    				$discipline->cycle_id = CRequest::getInt("cycle_id");
    				$discipline->save();
    			}
    		}
    	}
    	$this->redirect("index.php?action=view&id=".$corriculum);
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
