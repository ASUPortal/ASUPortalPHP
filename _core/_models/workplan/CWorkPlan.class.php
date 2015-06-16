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
 * @property int author_id
 * @property string year
 * @property string intended_for // предназначена для
 * @property string position
 * @property string project_description
 *
 * @property CTerm discipline
 * @property CArrayList profiles
 * @property CArrayList goals
 * @property CArrayList tasks
 * @property CArrayList competentions
 * @property CArrayList sections
 * @property CArrayList terms
 */
class CWorkPlan extends CActiveModel {
    protected $_table = TABLE_WORK_PLANS;

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
            "sections" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_sections",
                "storageTable" => TABLE_WORK_PLAN_CONTENT_SECTIONS,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanContentSection"
            ),
            "terms" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_terms",
                "storageTable" => TABLE_WORK_PLAN_TERMS,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "title" => "Наименование",
            "department_id" => "Кафедра",
            "approver_post" => "Должность утверждающего",
            "approver_name" => "Утверждающий",
            "direction_id" => "Направление подготовки",
            "profiles" => "Профили",
            "qualification_id" => "Квалификация",
            "edufaction_form_id" => "Форма обучения",
            "year" => "Год",
            "intended_for" => "Предназначено для",
            "author_id" => "Автор",
            "position" => "Место дисциплины",
            "disciplinesBefore" => "Предшествующие дисциплины",
            "disciplinesAfter" => "Последующие дисциплины",
            "project_description" => "Курсовой проект"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "title",
                "year"
            ),
            "selected" => array(
                "department_id",
                "author_id",
                "direction_id",
                "qualification_id",
                "education_form_id"
            )
        );
    }


}