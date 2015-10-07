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
            "disciplinesBefore" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_disciplinesBefore",
                "joinTable" => TABLE_WORK_PLAN_DISCIPLINES_BEFORE,
                "leftCondition" => "plan_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "discipline_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiscipline"
            ),
            "disciplinesAfter" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_disciplinesAfter",
                "joinTable" => TABLE_WORK_PLAN_DISCIPLINES_AFTER,
                "leftCondition" => "plan_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "discipline_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiscipline"
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
        	"rgr_description" => "Расчётно-графическая работа"
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
    	 * @var $load CWorkPlanContentSectionLoad
    	 * @var $technology CWorkPlanContentSectionLoadTechnology
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
}