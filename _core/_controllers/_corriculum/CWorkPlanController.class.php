<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.03.15
 * Time: 21:49
 */

class CWorkPlanController extends CBaseController{
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


    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("wp.*")
            ->from(TABLE_WORK_PLANS." as wp");
        $paginated = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $plan = new CWorkPlan($ar);
            $paginated->add($plan->getId(), $plan);
        }
        $this->addActionsMenuItem(array(
            array(
                "title" => "Добавить",
                "link" => "?action=add",
                "icon" => "actions/list-add.png"
            ),
        ));
        $this->setData("plans", $paginated);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_corriculum/_workplan/workplan/index.tpl");
    }
    public function actionDelete() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $discipline = $plan->corriculum_discipline_id;
        $plan->remove();
        $this->redirect("disciplines.php?action=edit&id=".$discipline);
    }
    public function actionAdd() {
        /**
         * получим дисциплину, по которой делаем рабочую программу
         * @var CCorriculumDiscipline $discipline
         */
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("id"));
        $corriculum = $discipline->cycle->corriculum;
        //
        $plan = new CWorkPlan();
        $plan->title = "Наименование не указано";
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
        $plan->year = date("Y");
        $plan->authors = new CArrayList();
        $plan->authors->add(CSession::getCurrentPerson()->getId(), CSession::getCurrentPerson()->getId());
        // место дисциплины в структуре плана
        if (!is_null($discipline->cycle)) {
            $plan->position = "Дисциплина относится к базовой части учебного цикла ".$discipline->cycle->title ;
        }
        $plan->save();
        /**
         * Приготовимся копировать нагрузку.
         * Для этого соберем структуру данных вида
         *
         * семестр {
         *  вид нагрузки,
         *  значение
         * }
         *
         * Это нужно для того, чтобы во всех семестрах нагрузка
         * одного типа была в одном и том же порядке
         *
         * @var CCorriculumDisciplineSection $section
         */
        $preparedData = array();
        $types = array();
        foreach ($discipline->sections->getItems() as $section) {
            /**
             * @var CCorriculumDisciplineLabor $labor
             */
            foreach ($section->labors->getItems() as $labor) {
                $sectionTitle = $section->title;
                if (!array_key_exists($sectionTitle, $preparedData)) {
                    $preparedData[$sectionTitle] = array();
                }
                $sectionData = $preparedData[$sectionTitle];
                if (!array_key_exists($labor->type_id, $sectionData)) {
                    $sectionData[$labor->type_id] = 0;
                }
                if (!in_array($labor->type_id, $types)) {
                    $types[] = $labor->type_id;
                }
                $sectionData[$labor->type_id] += $labor->value;
                $preparedData[$sectionTitle] = $sectionData;
            }
        }
        /**
         * @var CCorriculumDisciplineLabor $labor
         */
        foreach ($discipline->labors->getItems() as $labor) {
            $sectionTitle = 'Семестр не указан';
            if (!array_key_exists($sectionTitle, $preparedData)) {
                $preparedData[$sectionTitle] = array();
            }
            $sectionData = $preparedData[$sectionTitle];
            if (!array_key_exists($labor->type_id, $sectionData)) {
                $sectionData[$labor->type_id] = 0;
            }
            if (!in_array($labor->type_id, $types)) {
                $types[] = $labor->type_id;
            }
            $sectionData[$labor->type_id] += $labor->value;
            $preparedData[$sectionTitle] = $sectionData;
        }
        /**
         * Теперь преобразуем это в структуру, удобную для загрузки, а именно,
         * дополним каждый семестр недостающими видами нагрузки и
         * отсортируем их в правильном порядке.
         *
         * Будем действовать так:
         * 1. Создадим семестры
         */
        $sectionData = array();
        foreach ($preparedData as $section=>$data) {
            $laborData = array();
            foreach ($types as $type) {
                $laborData[$type] = 0;
            }
            $sectionData[$section] = $laborData;
        }
        /**
         * 2. Наполним их нагрузкой
         */
        foreach ($preparedData as $section=>$data) {
            $sd = $sectionData[$section];
            foreach ($data as $type=>$value) {
                $sd[$type] = $value;
            }
            $sectionData[$section] = $sd;
        }
        /**
         * Из полученной структуры соберем нагрузку
         */
        foreach ($sectionData as $section=>$data) {
            $planTerm = new CWorkPlanTerm();
            $planTerm->plan_id = $plan->getId();
            $planTerm->number = $section;
            $planTerm->save();
            foreach ($data as $type=>$value) {
                $termLoad = new CWorkPlanTermLoad();
                $termLoad->term_id = $planTerm->getId();
                $termLoad->type_id = $type;
                $termLoad->value = $value;
                $termLoad->save();
            }
        }
        $this->redirect("?action=edit&id=".$plan->getId());
    }
    public function actionEdit() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "disciplines.php?action=edit&id=".$plan->corriculum_discipline_id,
                "icon" => "actions/edit-undo.png"
            ),
        ));
        $this->setData("plan", $plan);

        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addCSSInclude("_modules/_redactor/redactor.css");
        $this->addJSInclude("_modules/_redactor/redactor.min.js");

        $this->renderView("_corriculum/_workplan/workplan/edit.tpl");
    }
    public function actionSave() {
        $plan = new CWorkPlan();
        $plan->setAttributes(CRequest::getArray($plan->getClassName()));
        if ($plan->validate()) {
            $plan->save();
            if ($this->continueEdit()) {
                $this->redirect("workplans.php?action=edit&id=".$plan->getId());
            } else {
                $this->redirect("disciplines.php?action=edit&id=".$plan->corriculum_discipline_id);
            }
        }
    }
}