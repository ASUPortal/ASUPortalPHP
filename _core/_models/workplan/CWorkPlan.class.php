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
 * @property int comment_file
 * @property int status_on_portal
 * @property int status_workplan
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
 * @property string method_practic_instructs
 * @property string method_labor_instructs
 * @property string method_project_instructs
 * @property string material_technical_supply
 *
 * @property CTerm discipline
 * @property CCorriculumDiscipline corriculumDiscipline
 * @property CArrayList profiles
 * @property CArrayList goals
 * @property CArrayList tasks
 * @property CArrayList competentions
 * @property CArrayList competentionsFormed
 * @property CArrayList disciplinesBefore
 * @property CArrayList disciplinesAfter
 * @property CArrayList terms
 * @property CArrayList projects
 * @property CArrayList projectThemes
 * @property CArrayList rgrThemes
 * @property CArrayList authors
 * @property CArrayList categories
 * @property CArrayList selfEducations
 * @property CArrayList fundMarkTypes
 * @property CArrayList calculationTasks
 * @property CArrayList BRS
 * @property CArrayList markTypes
 * @property CArrayList literature
 * @property CArrayList baseLiterature
 * @property CArrayList additionalLiterature
 * @property CArrayList internetResources
 * @property CArrayList software
 * @property CArrayList additionalSupply
 * @property CArrayList finalControls
 * @property CArrayList intermediateControls
 * @property CArrayList questions
 * @property CArrayList examQuestions
 * @property CArrayList creditQuestions
 * @property CArrayList materialsOfEvaluation
 * @property CArrayList criteria
 * @property CArrayList criteriaExamOfEvaluation
 * @property CArrayList criteriaCreditOfEvaluation
 * @property CArrayList criteriaMaterialsOfEvaluation
 * @property CArrayList protocolsDep
 * @property CArrayList protocolsNMS
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
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
                "targetClass" => "CWorkPlanGoal",
                "managerOrder" => "`ordering` asc"
            ),
            "tasks" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_tasks",
                "storageTable" => TABLE_WORK_PLAN_TASKS,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
                "targetClass" => "CWorkPlanTask",
                "managerOrder" => "`ordering` asc"
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
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
                "targetClass" => "CWorkPlanContentCategory"
            ),
            "terms" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_terms",
                "storageTable" => TABLE_WORK_PLAN_TERMS,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
                "targetClass" => "CWorkPlanTerm",
                "managerOrder" => "`ordering` asc"
            ),
            "projectThemes" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_PROJECT_THEMES,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=0",
                "targetClass" => "CWorkPlanProjectTheme"
            ),
        	"projects" => array(
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
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
                "targetClass" => "CWorkPlanSelfEducationBlock",
                "managerOrder" => "`ordering` asc"
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
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanFundMarkType",
                "managerOrder" => "`ordering` asc"
        	),
        	"calculationTasks" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_CALCULATION_TASKS,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanCalculationTask",
        		"managerOrder" => "`ordering` asc"
        	),
        	"BRS" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_BRS,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanBRS"
        	),
        	"markTypes" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_MARK_TYPES,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanMarkType",
                "managerOrder" => "`ordering` asc"
        	),
        	"literature" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_LITERATURE,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanLiterature"
        	),
        	"baseLiterature" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_LITERATURE,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=1 and _deleted=0",
        		"targetClass" => "CWorkPlanLiterature"
        	),
        	"additionalLiterature" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_LITERATURE,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=2 and _deleted=0",
        		"targetClass" => "CWorkPlanLiterature"
        	),
        	"internetResources" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_LITERATURE,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=3 and _deleted=0",
        		"targetClass" => "CWorkPlanLiterature"
        	),
        	"software" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_SOFTWARE,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanSoftware"
        	),
        	"additionalSupply" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_ADDITIONAL_SUPPLY,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
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
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanFinalControl",
                "managerOrder" => "`ordering` asc"
        	),
        	"intermediateControls" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_INTERMEDIATE_CONTROL,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanIntermediateControl",
                "managerOrder" => "`ordering` asc"
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
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanEvaluationMaterial"
        	),
        	"criteria" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_CRITERIA_OF_EVALUATION,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanCriteriaOfEvaluation"
        	),
        	"criteriaExamOfEvaluation" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_CRITERIA_OF_EVALUATION,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=1 and _deleted=0",
        		"targetClass" => "CWorkPlanCriteriaOfEvaluation"
        	),
        	"criteriaCreditOfEvaluation" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_CRITERIA_OF_EVALUATION,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=2 and _deleted=0",
        		"targetClass" => "CWorkPlanCriteriaOfEvaluation"
        	),
        	"criteriaMaterialsOfEvaluation" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_CRITERIA_OF_EVALUATION,
        		"storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId())." AND type=3 and _deleted=0",
        		"targetClass" => "CWorkPlanCriteriaOfEvaluation"
        	),
            "protocolsDep" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_protocolsDep",
                "joinTable" => TABLE_WORK_PLAN_PROTOCOLS_DEP,
                "leftCondition" => "plan_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "protocol_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getDepProtocol"
            ),
            "protocolsNMS" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_protocolsNMS",
                "joinTable" => TABLE_WORK_PLAN_PROTOCOLS_NMS,
                "leftCondition" => "plan_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "protocol_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getNMSProtocol"
            ),
        	"commentFile" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_comment_file",
        		"storageField" => "comment_file",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"statusOnPortal" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_status_on_portal",
        		"storageField" => "status_on_portal",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"statusWorkplanLibrary" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_status_workplan_library",
        		"storageField" => "status_workplan_library",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"statusWorkplanLecturer" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_status_workplan_lecturer",
        		"storageField" => "status_workplan_lecturer",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"statusWorkplanHeadOfDepartment" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_status_workplan_head_of_department",
        		"storageField" => "status_workplan_head_of_department",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"statusWorkplanNMS" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_status_workplan_nms",
        		"storageField" => "status_workplan_nms",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"statusWorkplanDean" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_status_workplan_dean",
        		"storageField" => "status_workplan_dean",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
        	),
        	"statusWorkplanProrektor" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_status_workplan_prorektor",
        		"storageField" => "status_workplan_prorektor",
        		"managerClass" => "CTaxonomyManager",
        		"managerGetObject" => "getTerm"
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
        	"corriculum" => "Учебный план",
        	"discipline.name" => "Дисциплина",
        	"corriculum.title" => "Учебный план",
        	"term.name" => "Профили",
        	"person.fio" => "Авторы",
        	"method_practic_instructs" => "Методические указания к практическим занятиям",
        	"method_labor_instructs" => "Методические указания к лабораторным занятиям",
        	"method_project_instructs" => "Методические указания к курсовому проектированию",
        	"material_technical_supply" => "Материально-техническое обеспечение",
            "protocolsDep" => "Протоколы кафедры",
            "protocolsNMS" => "Протоколы НМС",
            "comment_file" => "Комментарий к файлу",
            "status_on_portal" => "Статус на портале",
            "status_workplan_library" => "БИБЛ",
            "status_workplan_lecturer" => "ПРЕПОД",
            "status_workplan_head_of_department" => "ЗАВ.КАФ.",
            "status_workplan_nms" => "НМС",
            "status_workplan_dean" => "ДЕКАН",
            "status_workplan_prorektor" => "ПРОРЕКТОР",
            "comment" => "Комментарий"
        );
    }

    protected function modelValidators() {
        return array(
            new CWorkPlanApproverModelOptionalValidator()
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
                foreach ($section->loadsDisplay->getItems() as $load) {
                    if ($load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE) {
                        foreach ($load->topicsDisplay as $topic) {
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
                foreach ($section->loadsDisplay->getItems() as $load) {
                    if ($load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK) {
                        if ($load->topicsDisplay->getCount() > 0) {
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
            foreach ($load->topicsDisplay->getItems() as $topic) {
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
    			foreach ($section->loadsDisplay->getItems() as $load) {
    				if ($load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE) {
    					foreach ($load->topicsDisplay as $topic) {
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
                foreach ($section->loadsDisplay->getItems() as $load) {
                    foreach ($load->technologiesDisplay->getItems() as $technology) {
                        $technologies->add($technology->getId(), $technology);
                    }
                }
            }
        }
        return $technologies;
    }
    
    /**
     * Вопросы по темам нагрузки самостоятельной работы из раздела
     * 
     * @return CArrayList
     */
    public function getSelfWorkQuestions() {
    	$selfWorkQuestions = new CArrayList();
    	/**
    	 * @var $category CWorkPlanContentCategory
    	 * @var $section CWorkPlanContentSection
    	 * @var $load CWorkPlanContentSectionLoad
    	 * @var $topic CWorkPlanContentSectionLoadTopic
    	*/
    	foreach ($this->categories->getItems() as $category) {
    		foreach ($category->sections->getItems() as $section) {
    			foreach ($section->loadsDisplay->getItems() as $load) {
    				if ($load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK) {
    					foreach ($load->topicsDisplay as $topic) {
    						$selfWorkQuestions->add($topic->getId(), $topic);
    					}
    
    				}
    			}
    		}
    	}
    	return $selfWorkQuestions;
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
    
    public function copy() {
    	/**
    	 * Клонируем саму рабочую программу
    	*/
    	$newPlan = parent::copy();
    	$newPlan->title = "Копия ".$newPlan->title;
    	/**
    	 * Клонируем профили рабочей программы
    	 * @var CTerm $profile
    	 */
    	foreach ($this->profiles->getItems() as $profile) {
    		$newPlan->profiles->add($profile->getId(), $profile->getId());
    	}
    	/**
    	 * Клонируем предшествующие дисциплины рабочей программы
    	 * @var CCorriculumDiscipline $disciplineBefore
    	 */
    	foreach ($this->disciplinesBefore->getItems() as $disciplineBefore) {
    		$newPlan->disciplinesBefore->add($disciplineBefore->getId(), $disciplineBefore->getId());
    	}
    	/**
    	 * Клонируем последующие дисциплины рабочей программы
    	 * @var CCorriculumDiscipline $disciplineBefore
    	 */
    	foreach ($this->disciplinesAfter->getItems() as $disciplineAfter) {
    		$newPlan->disciplinesAfter->add($disciplineAfter->getId(), $disciplineAfter->getId());
    	}
    	/**
    	 * Клонируем авторов рабочей программы
    	 * @var CPerson $author
    	 */
    	foreach ($this->authors->getItems() as $author) {
    		$newPlan->authors->add($author->getId(), $author->getId());
    	}
    	/**
    	 * Клонируем протоколы кафедры рабочей программы
    	 * @var CDepartmentProtocol $protocolsDep
    	 */
    	foreach ($this->protocolsDep->getItems() as $protocolsDep) {
    		$newPlan->protocolsDep->add($protocolsDep->getId(), $protocolsDep->getId());
    	}
    	/**
    	 * Клонируем протоколы НМС рабочей программы
    	 * @var CNMSProtocol $protocolsNMS
    	 */
    	foreach ($this->protocolsNMS->getItems() as $protocolsNMS) {
    		$newPlan->protocolsNMS->add($protocolsNMS->getId(), $protocolsNMS->getId());
    	}
    	$newPlan->save();
    	/**
    	 * Клонируем цели рабочей программы
    	 * @var CWorkPlanGoal $goal
    	 */
    	foreach ($this->goals->getItems() as $goal) {
    		$newGoal = $goal->copy();
    		$newGoal->plan_id = $newPlan->getId();
    		$newGoal->save();
    		/**
    		 * Клонируем задачи целей рабочей программы
    		 * @var CWorkPlanTask $task
    		 */
    		foreach ($goal->tasks->getItems() as $task) {
    			$newTask = $task->copy();
    			$newTask->plan_id = $newPlan->getId();
    			$newTask->goal_id = $newGoal->getId();
    			$newTask->save();
    		}
    	}
    	/**
    	 * Клонируем компетенции рабочей программы
    	 * @var CWorkPlanCompetention $competention
    	 */
    	foreach ($this->competentions->getItems() as $competention) {
    		$newCompetention = $competention->copy();
    		$newCompetention->plan_id = $newPlan->getId();
    		/**
    		 * Копируем умеет использовать из компетенций
    		 * @var CTerm $canUse
    		 */
    		foreach ($competention->canUse->getItems() as $canUse) {
    			$newCompetention->canUse->add($canUse->getId(), $canUse->getId());
    		}
    		$newCompetention->save();
    		/**
    		 * Копируем знания из компетенций
    		 * @var CTerm $knowledge
    		 */
    		foreach ($competention->knowledges->getItems() as $knowledge) {
    			$newKnowledge = $knowledge->copy();
    			$newKnowledge->competention_id = $newCompetention->getId();
    			$newKnowledge->save();
    		}
    		/**
    		 * Копируем умения из компетенций
    		 * @var CTerm $skill
    		 */
    		foreach ($competention->skills->getItems() as $skill) {
    			$newSkill = $skill->copy();
    			$newSkill->competention_id = $newCompetention->getId();
    			$newSkill->save();
    		}
    		/**
    		 * Копируем владения из компетенций
    		 * @var CTerm $experience
    		 */
    		foreach ($competention->experiences->getItems() as $experience) {
    			$newExperience = $experience->copy();
    			$newExperience->competention_id = $newCompetention->getId();
    			$newExperience->save();
    		}
    	}
    	/**
    	 * Клонируем семестры рабочей программы
    	 * @var CWorkPlanTerm $term
    	 */
    	$termsMapping = array();
    	foreach ($this->terms->getItems() as $term) {
    		$newTerm = $term->copy();
    		$newTerm->plan_id = $newPlan->getId();
    		$newTerm->save();
    		$termsMapping[$term->getId()] = $newTerm->getId();
    	}
    	 
    	/**
    	 * Клонируем категории рабочей программы
    	 * @var CWorkPlanContentCategory $categorie
    	 */
    	foreach ($this->categories->getItems() as $categorie) {
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
    			/**
    			 * Копируем рекомендуемую литературу из разделов
    			 * @var CTerm $control
    			 */
    			foreach ($section->recommendedLiterature->getItems() as $literature) {
    				$newSection->recommendedLiterature->add($literature->getId(), $literature->getId());
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
    			/**
    			 * Копируем фонд оценочных средств из разделов
    			 * @var CWorkPlanFundMarkType $fundMarkType
    			 */
    			foreach ($section->fundMarkTypes->getItems() as $fundMarkType) {
    				$newFundMarkType = $fundMarkType->copy();
    				$newFundMarkType->plan_id = $newPlan->getId();
    				$newFundMarkType->section_id = $newSection->getId();
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
    			 * Копируем расчётные задания из разделов
    			 * @var CWorkPlanCalculationTask $calculationTask
    			 */
    			foreach ($section->calculationTasks->getItems() as $calculationTask) {
    				$newCalculationTask = $calculationTask->copy();
    				$newCalculationTask->plan_id = $newPlan->getId();
    				$newCalculationTask->section_id = $newSection->getId();
    				$newCalculationTask->save();
    			}
    		}
    	}
    	/**
    	 * Клонируем темы курсовых и РГР рабочей программы
    	 * @var CWorkPlanProjectTheme $projectTheme
    	 */
    	foreach ($this->projects->getItems() as $projectTheme) {
    		$newProjectTheme = $projectTheme->copy();
    		$newProjectTheme->plan_id = $newPlan->getId();
    		$newProjectTheme->save();
    	}
    	/**
    	 * Клонируем самостоятельное изучение рабочей программы
    	 * @var CWorkPlanSelfEducationBlock $selfEducation
    	 */
    	foreach ($this->selfEducations->getItems() as $selfEducation) {
    		$newSelfEducation = $selfEducation->copy();
    		$newSelfEducation->plan_id = $newPlan->getId();
    		$newSelfEducation->save();
    	}
    	/**
    	 * Клонируем балльно-рейтинговую систему рабочей программы
    	 * @var CWorkPlanBRS $BRS
    	 */
    	foreach ($this->BRS->getItems() as $BRS) {
    		$newBRS = $BRS->copy();
    		$newBRS->plan_id = $newPlan->getId();
    		$newBRS->save();
    	}
    	/**
    	 * Клонируем оценочные средства рабочей программы
    	 * @var CWorkPlanMarkType $markTypes
    	 */
    	foreach ($this->markTypes->getItems() as $markTypes) {
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
    	 * @var CWorkPlanLiterature $literature
    	 */
    	foreach ($this->literature->getItems() as $literature) {
    		$newLiterature = $literature->copy();
    		$newLiterature->plan_id = $newPlan->getId();
    		$newLiterature->save();
    	}
    	/**
    	 * Клонируем программное обеспечение рабочей программы
    	 * @var CWorkPlanSoftware $software
    	 */
    	foreach ($this->software->getItems() as $software) {
    		$newSoftware = $software->copy();
    		$newSoftware->plan_id = $newPlan->getId();
    		$newSoftware->save();
    	}
    	/**
    	 * Клонируем доп. обеспечение рабочей программы
    	 * @var CWorkPlanAdditionalSupply $additionalSupply
    	 */
    	foreach ($this->additionalSupply->getItems() as $additionalSupply) {
    		$newAdditionalSupply = $additionalSupply->copy();
    		$newAdditionalSupply->plan_id = $newPlan->getId();
    		$newAdditionalSupply->save();
    	}
    	/**
    	 * Клонируем итоговый контроль рабочей программы
    	 * @var CWorkPlanFinalControl $finalControl
    	 */
    	foreach ($this->finalControls->getItems() as $finalControl) {
    		$newFinalControl = $finalControl->copy();
    		$newFinalControl->plan_id = $newPlan->getId();
    		$newFinalControl->save();
    	}
    	/**
    	 * Клонируем промежуточный контроль рабочей программы
    	 * @var CWorkPlanIntermediateControl $intermediateControl
    	 */
    	foreach ($this->intermediateControls->getItems() as $intermediateControl) {
    		$newIntermediateControl = $intermediateControl->copy();
    		$newIntermediateControl->plan_id = $newPlan->getId();
    		$newIntermediateControl->save();
    	}
    	/**
    	 * Клонируем вопросы к экзамену и зачету рабочей программы
    	 * @var CExamQuestion $question
    	 */
    	foreach ($this->questions->getItems() as $question) {
    		$newQuestion = $question->copy();
    		$newQuestion->plan_id = $newPlan->getId();
    		$newQuestion->save();
    	}
    	/**
    	 * Клонируем оценочные материалы рабочей программы
    	 * @var CWorkPlanEvaluationMaterial $materialOfEvaluation
    	 */
    	foreach ($this->materialsOfEvaluation->getItems() as $materialOfEvaluation) {
    		$newMaterialOfEvaluation = $materialOfEvaluation->copy();
    		$newMaterialOfEvaluation->plan_id = $newPlan->getId();
    		$newMaterialOfEvaluation->save();
    	}
    	/**
    	 * Клонируем оценочные критерии рабочей программы
    	 * @var CWorkPlanCriteriaOfEvaluation $criteria
    	 */
    	foreach ($this->criteria->getItems() as $criteria) {
    		$newCriteria = $criteria->copy();
    		$newCriteria->plan_id = $newPlan->getId();
    		$newCriteria->save();
    	}
    	return $newPlan;
    }
}