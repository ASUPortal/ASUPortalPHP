<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.03.15
 * Time: 21:54
 *
 * @property int id
 * @property string title
 * @property int department_id
 * @property string approver_post
 * @property string approver_name
 * @property int corriculum_discipline_id
 * @property int discipline_id
 * @property int direction_id
 * @property int qualification_id
 * @property int education_form_id
 * @property string year
 * @property string intended_for // предназначена для
 * @property string position
 * @property string project_description
 * @property string rgr_description
 * @property string education_technologies
 * @property string changes
 * @property string method_instructs
 * @property string adapt_for_ovz
 * @property string director_of_library
 * @property string chief_umr
 *
 * @property CTerm discipline
 * @property CCorriculumDiscipline corriculumDiscipline
 * @property CArrayList profiles
 * @property CArrayList goals
 * @property CArrayList tasks
 * @property CArrayList competentions
 * @property CArrayList terms
 * @property CArrayList projectThemes
 * @property CArrayList authors
 * @property CArrayList categories
 * @property CArrayList selfEducations
 */
class CWorkPlan extends CActiveModel {
    protected $_table = TABLE_WORK_PLANS;
    public $is_archive = 0;

    protected function relations() {
        return array(
            "discipline" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_discipline",
                "storageField" => "discipline_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiscipline"
            ),
        	"corriculumDiscipline" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_corriculum_discipline_id",
        		"storageField" => "corriculum_discipline_id",
        		"managerClass" => "CCorriculumsManager",
        		"managerGetObject" => "getDiscipline"
        	),
            "profiles" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_profiles",
                "joinTable" => TABLE_WORK_PLAN_PROFILES,
                "leftCondition" => "plan_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "profile_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "goals" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_goals",
                "storageTable" => TABLE_WORK_PLAN_GOALS,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanGoal"
            ),
            "tasks" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_tasks",
                "storageTable" => TABLE_WORK_PLAN_TASKS,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanTask"
            ),
            "competentions" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_competentions",
                "storageTable" => TABLE_WORK_PLAN_COMPETENTIONS,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanCompetention"
            ),
        	"competentionsFormed" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageProperty" => "_competentionsFormed",
        		"storageTable" => TABLE_WORK_PLAN_COMPETENTIONS,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=0",
        		"targetClass" => "CWorkPlanCompetention"
        	),
        	"disciplinesBefore" => array(
        		"relationPower" => RELATION_MANY_TO_MANY,
        		"joinTable" => TABLE_WORK_PLAN_DISCIPLINES_BEFORE,
        		"leftCondition" => "plan_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
        		"rightKey" => "discipline_id",
        		"targetClass" => "CCorriculumDiscipline"
        	),
            "disciplinesAfter" => array(
        		"relationPower" => RELATION_MANY_TO_MANY,
        		"joinTable" => TABLE_WORK_PLAN_DISCIPLINES_AFTER,
        		"leftCondition" => "plan_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
        		"rightKey" => "discipline_id",
        		"targetClass" => "CCorriculumDiscipline"	
            ),
            "categories" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_categories",
                "storageTable" => TABLE_WORK_PLAN_CONTENT_CATEGORIES,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanContentCategory"
            ),
            "terms" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_terms",
                "storageTable" => TABLE_WORK_PLAN_TERMS,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanTerm"
            ),
            "projectThemes" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_PROJECT_THEMES,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanProjectTheme"
            ),
            "authors" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_authors",
                "joinTable" => TABLE_WORK_PLAN_AUTHORS,
                "leftCondition" => "plan_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "person_id",
                "managerClass" => "CBaseManager",
                "managerGetObject" => "getPerson"
            ),
            "selfEducations" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_SELFEDUCATION,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanSelfEducationBlock"
            ),
            "department" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_department",
                "storageField" => "department_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
        	"direction" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_direction",
        		"storageField" => "direction_id",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"educationForm" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_educationForm",
        		"storageField" => "education_form_id",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getEductionForm"
        	),
        	"qualification" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_qualification",
        		"storageField" => "qualification_id",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"level" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_level",
        		"storageField" => "level_id",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"fundMarkTypes" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_FUND_MARK_TYPES,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanFundMarkType"
        	),
        	"BRS" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_BRS,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanBRS"
        	),
        	"markTypes" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_MARK_TYPES,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanMarkType"
        	),
        	"literature" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_LITERATURE,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanLiterature"
        	),
        	"baseLiterature" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_LITERATURE,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=1",
        		"targetClass" => "CWorkPlanLiterature"
        	),
        	"additionalLiterature" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_LITERATURE,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=2",
        		"targetClass" => "CWorkPlanLiterature"
        	),
        	"internetResources" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_LITERATURE,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=3",
        		"targetClass" => "CWorkPlanLiterature"
        	),
        	"software" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_SOFTWARE,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanSoftware"
        	),
        	"additionalSupply" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_ADDITIONAL_SUPPLY,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanAdditionalSupply"
        	),
        	"rgrThemes" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_PROJECT_THEMES,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=1",
        		"targetClass" => "CWorkPlanProjectTheme"
        	),
        	"finalControls" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_FINAL_CONTROL,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanFinalControl"
        	),
        	"questions" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_EXAMINATION_QUESTIONS,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CExamQuestion"
        	),
        	"examQuestions" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_EXAMINATION_QUESTIONS,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=1",
        		"targetClass" => "CExamQuestion"
        	),
        	"creditQuestions" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_EXAMINATION_QUESTIONS,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=2",
        		"targetClass" => "CExamQuestion"
        	),
        	"materialsOfEvaluation" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_EVALUATION_MATERIALS,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanEvaluationMaterial"
        	),
        	"criteria" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_CRITERIA_OF_EVALUATION,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
        		"targetClass" => "CWorkPlanCriteriaOfEvaluation"
        	),
        	"criteriaExamOfEvaluation" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_CRITERIA_OF_EVALUATION,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=1",
        		"targetClass" => "CWorkPlanCriteriaOfEvaluation"
        	),
        	"criteriaCreditOfEvaluation" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_CRITERIA_OF_EVALUATION,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=2",
        		"targetClass" => "CWorkPlanCriteriaOfEvaluation"
        	),
        	"criteriaMaterialsOfEvaluation" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_CRITERIA_OF_EVALUATION,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=3",
        		"targetClass" => "CWorkPlanCriteriaOfEvaluation"
        	)
        );
    }

    public function attributeLabels() {
        return array(
            "title" => "Наименование",
            "title_display" => "Отображаемое наименование",
            "department_id" => "Кафедра",
            "approver_post" => "Должность утверждающего",
            "approver_name" => "Утверждающий",
            "direction_id" => "Направление подготовки",
            "profiles" => "Профили",
            "qualification_id" => "Квалификация",
            "education_form_id" => "Форма обучения",
            "year" => "Год",
            "intended_for" => "Предназначено для",
            "authors" => "Авторы",
            "position" => "Место дисциплины",
            "disciplinesBefore" => "Предшествующие дисциплины",
            "disciplinesAfter" => "Последующие дисциплины",
            "project_description" => "Курсовой проект",
            "education_technologies" => "Образовательные технологии",
            "hardware" => "Скобяные изделия",
            "is_archive" => "В архиве",
            "discipline_id" => "Дисциплина",
            "module_id" => "Модуль",
            "level_id" => "Уровень подготовки",
            "date_of_formation" => "Дата формирования",
        	"method_instructs" => "Методические указания по освоению дисциплины",
        	"adapt_for_ovz" => "Адаптация рабочей программы для лиц с ОВЗ",
        	"changes" => "Изменения в рабочей программе",
        	"director_of_library" => "Директор библиотеки",
        	"chief_umr" => "Начальник УМР",
        	"rgr_description" => "Расчётно-графическая работа",
        	"corriculum_discipline_id" => "Выберите название дисциплины из учебного плана",
        	"corriculum" => "Учебный план"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "title",
                "title_display",
                "year"
            ),
            "selected" => array(
                "department_id",
                "authors",
                "direction_id",
                "qualification_id",
                "education_form_id",
                "level_id"
            )
        );
    }

    /**
     * @return CArrayList
     */
    public function getPractices() {
        $practices = new CArrayList();
        /**
         * @var $category CWorkPlanContentCategory
         * @var $section CWorkPlanContentSection
         * @var $load CWorkPlanContentSectionLoad
         * @var $topic CWorkPlanContentSectionLoadTopic
         */
        foreach ($this->categories->getItems() as $category) {
            foreach ($category->sections->getItems() as $section) {
                foreach ($section->loads->getItems() as $load) {
                    if ($load->loadType->getAlias() == "practice") {
                        foreach ($load->topics as $topic) {
                            $practices->add($topic->getId(), $topic);
                        }

                    }
                }
            }

        }
        return $practices;
    }

    /**
     * Все лабораторные работы, сгруппированные по семестрам
     *
     * @return CArrayList
     */
    public function getLabWorks() {
        $labs = new CArrayList();
        /**
         * @var $category CWorkPlanContentCategory
         * @var $section CWorkPlanContentSection
         * @var $load CWorkPlanContentSectionLoad
         * @var $topic CWorkPlanContentSectionLoadTopic
         */
        $loads = new CArrayList();
        foreach ($this->categories->getItems() as $category) {
            foreach ($category->sections->getItems() as $section) {
                foreach ($section->loads->getItems() as $load) {
                    if ($load->loadType->getAlias() == "labwork") {
                        if ($load->topics->getCount() > 0) {
                            $loads->add($load->getId(), $load);
                        }
                    }
                }
            }
        }
        foreach ($loads as $load) {
            $term_id = $load->term_id;
            $termData = new CArrayList();
            if ($labs->hasElement($term_id)) {
                $termData = $labs->getItem($term_id);
            }
            foreach ($load->topics->getItems() as $topic) {
                $termData->add($topic->getId(), $topic);
            }
            $labs->add($term_id, $termData);
        }
        return $labs;
    }
    
    /**
     * Все лекции
     * 
     * @return CArrayList
     */
    public function getLectures() {
    	$lectures = new CArrayList();
    	/**
    	 * @var $category CWorkPlanContentCategory
    	 * @var $section CWorkPlanContentSection
    	 * @var $load CWorkPlanContentSectionLoad
    	 * @var $topic CWorkPlanContentSectionLoadTopic
    	*/
    	foreach ($this->categories->getItems() as $category) {
    		foreach ($category->sections->getItems() as $section) {
    			foreach ($section->loads->getItems() as $load) {
    				if ($load->loadType->getAlias() == "lecture") {
    					foreach ($load->topics as $topic) {
    						$lectures->add($topic->getId(), $topic);
    					}
    
    				}
    			}
    		}
    
    	}
    	return $lectures;
    }

    /**
     * Все образовательные технологии
     *
     * @return CArrayList
     */
    public function getTechnologies() {
        $technologies = new CArrayList();
        /**
         * @var $category CWorkPlanContentCategory
         * @var $section CWorkPlanContentSection
         * @var $load CWorkPlanContentSectionLoad
         * @var $technology CWorkPlanContentSectionLoadTechnology
         */
        foreach ($this->categories->getItems() as $category) {
            foreach ($category->sections->getItems() as $section) {
                foreach ($section->loads->getItems() as $load) {
                    foreach ($load->technologies->getItems() as $technology) {
                        $technologies->add($technology->getId(), $technology);
                    }
                }
            }
        }
        return $technologies;
    }
    
    /**
     * Все виды контроля
     *
     * @return CArrayList
     */
    public function getControlTypes() {
    	$controls = new CArrayList();
    	/**
    	 * @var $category CWorkPlanContentCategory
    	 * @var $section CWorkPlanContentSection
    	 * @var $control CWorkPlanControlTypes
    	*/
    	foreach ($this->categories->getItems() as $category) {
    		foreach ($category->sections->getItems() as $section) {
    			foreach ($section->controlTypes->getItems() as $control) {
    				$controls->add($control->getId(), $control);
    			}
    		}
    	}
    	return $controls;
    }
    
    public function copy($planId, $corriculumDisciplineId) {
    	$plan = CWorkPlanManager::getWorkplan($planId);
    	/**
    	 * Клонируем саму рабочую программу
    	*/
    	$newPlan = parent::copy();
    	$newPlan->title = "Копия ".$newPlan->title;
    	$newPlan->corriculum_discipline_id = $corriculumDisciplineId;
    	$discipline = CCorriculumsManager::getDiscipline($corriculumDisciplineId);
    	if (!is_null($discipline->discipline)) {
    		$newPlan->discipline_id = $discipline->discipline->getId();
    	}
    	/**
    	 * Клонируем профили рабочей программы
    	 */
    	foreach ($plan->profiles->getItems() as $profile) {
    		$newPlan->profiles->add($profile->getId(), $profile->getId());
    	}
    	/**
    	 * Клонируем предшествующие дисциплины рабочей программы
    	 */
    	foreach ($plan->disciplinesBefore->getItems() as $disciplineBefore) {
    		$newPlan->disciplinesBefore->add($disciplineBefore->getId(), $disciplineBefore->getId());
    	}
    	/**
    	 * Клонируем последующие дисциплины рабочей программы
    	 */
    	foreach ($plan->disciplinesAfter->getItems() as $disciplineAfter) {
    		$newPlan->disciplinesAfter->add($disciplineAfter->getId(), $disciplineAfter->getId());
    	}
    	/**
    	 * Клонируем авторов рабочей программы
    	 */
    	foreach ($plan->authors->getItems() as $author) {
    		$newPlan->authors->add($author->getId(), $author->getId());
    	}
    	$newPlan->save();
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
    		/**
    		 * Копируем знания из компетенций
    		 * @var CTerm $knowledge
    		*/
    		foreach ($competention->knowledges->getItems() as $knowledge) {
    			$newCompetention->knowledges->add($knowledge->getId(), $knowledge->getId());
    		}
    		/**
    		 * Копируем умения из компетенций
    		 * @var CTerm $skill
    		 */
    		foreach ($competention->skills->getItems() as $skill) {
    			$newCompetention->skills->add($skill->getId(), $skill->getId());
    		}
    		/**
    		 * Копируем навыки из компетенций
    		 * @var CTerm $experience
    		 */
    		foreach ($competention->experiences->getItems() as $experience) {
    			$newCompetention->experiences->add($experience->getId(), $experience->getId());
    		}
    		/**
    		 * Копируем умеет использовать из компетенций
    		 * @var CTerm $canUse
    		 */
    		foreach ($competention->canUse->getItems() as $canUse) {
    			$newCompetention->canUse->add($canUse->getId(), $canUse->getId());
    		}
    		$newCompetention->save();
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
    			/**
    			 * Копируем формы контроля из разделов
    			 * @var CTerm $control
    			*/
    			foreach ($section->controls->getItems() as $control) {
    				$newSection->controls->add($control->getId(), $control->getId());
    			}
    			$newSection->save();
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
    		/**
    		 * Копируем компетенции из фонда оценочных средств
    		 * @var CTerm $competention
    		*/
    		foreach ($fundMarkType->competentions->getItems() as $competention) {
    			$newFundMarkType->competentions->add($competention->getId(), $competention->getId());
    		}
    		/**
    		 * Копируем уровни освоения из фонда оценочных средств
    		 * @var CTerm $level
    		 */
    		foreach ($fundMarkType->levels->getItems() as $level) {
    			$newFundMarkType->levels->add($level->getId(), $level->getId());
    		}
    		/**
    		 * Копируем оценочные средства из фонда оценочных средств
    		 * @var CTerm $control
    		 */
    		foreach ($fundMarkType->controls->getItems() as $control) {
    			$newFundMarkType->controls->add($control->getId(), $control->getId());
    		}
    		$newFundMarkType->save();
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
    		/**
    		 * Копируем фонды оценочных средств из перечня оченочных средств
    		 * @var CTerm $fund
    		*/
    		foreach ($markTypes->funds->getItems() as $fund) {
    			$newMarkTypes->funds->add($fund->getId(), $fund->getId());
    		}
    		/**
    		 * Копируем места размещения оценочных средств из перечня оченочных средств
    		 * @var CTerm $place
    		 */
    		foreach ($markTypes->places->getItems() as $place) {
    			$newMarkTypes->places->add($place->getId(), $place->getId());
    		}
    		$newMarkTypes->save();
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