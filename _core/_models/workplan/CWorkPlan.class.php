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
 *
 * @property CTerm discipline
 * @property CArrayList profiles
 * @property CArrayList goals
 * @property CArrayList tasks
 * @property CArrayList competentions
 */

class CWorkPlan extends CActiveModel implements IVersionControl{
    protected $_table = TABLE_WORK_PLANS;
    protected $_discipline;
    protected $_profiles;
    protected $_goals;
    protected $_tasks;
    protected $_competentions;
    protected $_disciplinesBefore;
    protected $_disciplinesAfter;

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
                "managerClass" => "CWorkPlan",
                "managerGetObject" => "getWorkplanGoal"
            ),
            "tasks" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_tasks",
                "storageTable" => TABLE_WORK_PLAN_TASKS,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CWorkPlan",
                "managerGetObject" => "getWorkplanTask"
            ),
            "competentions" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_competentions",
                "storageTable" => TABLE_WORK_PLAN_COMPETENTIONS,
                "storageCondition" => "plan_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CWorkPlanManager",
                "managerGetObject" => "getWorkplanCompetention"
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
            )
        );
    }

    public static function getWorkplanGoal($id) {
        $result = "";
        $ar = CActiveRecordProvider::getById(TABLE_WORK_PLAN_GOALS, $id);
        if (!is_null($ar)) {
            $result = $ar->getItemValue("goal");
        }
        return $result;
    }

    public static function getWorkplanTask($id) {
        $result = "";
        $ar = CActiveRecordProvider::getById(TABLE_WORK_PLAN_TASKS, $id);
        if (!is_null($ar)) {
            $result = $ar->getItemValue("task");
        }
        return $result;
    }

    public function updateWithJsonString($jsonString) {
        $data = parent::updateWithJsonString($jsonString);
        $id = $data["id"];
        // почистим связанные таблицы
        CActiveRecordProvider::removeWithCondition(TABLE_WORK_PLAN_GOALS, "plan_id=".$id);
        CActiveRecordProvider::removeWithCondition(TABLE_WORK_PLAN_TASKS, "plan_id=".$id);
        CActiveRecordProvider::removeWithCondition(TABLE_WORK_PLAN_COMPETENTIONS, "plan_id=".$id);
        CActiveRecordProvider::removeWithCondition(TABLE_WORK_PLAN_SKILLS, "plan_id=".$id);
        CActiveRecordProvider::removeWithCondition(TABLE_WORK_PLAN_EXPERIENCES, "plan_id=".$id);
        CActiveRecordProvider::removeWithCondition(TABLE_WORK_PLAN_KNOWLEDGES, "plan_id=".$id);
        // добавим данные обратно
        // цели
        /**
         * @var CActiveRecord $ar
         */
        foreach ($data["goals"] as $goal) {
            $ar = new CActiveRecord(array(
                "id" => null,
                "plan_id" => $id,
                "goal" => $goal
            ));
            $ar->setTable(TABLE_WORK_PLAN_GOALS);
            $ar->insert();
        }
        // задачи
        foreach ($data["tasks"] as $task) {
            $ar = new CActiveRecord(array(
                "id" => null,
                "plan_id" => $id,
                "task" => $task
            ));
            $ar->setTable(TABLE_WORK_PLAN_TASKS);
            $ar->insert();
        }
        // компетенции
        foreach ($data["competentions"] as $cData) {
            $compAr = new CActiveModel(new CActiveRecord(array(
                "id" => null,
                "plan_id" => $id,
                "competention_id" => $cData["competention_id"]
            )));
            $compAr->getRecord()->setTable(TABLE_WORK_PLAN_COMPETENTIONS);
            $compAr->save();
            // знания
            foreach ($cData["knowledges"] as $knowledge) {
                $ar = new CActiveRecord(array(
                    "id" => null,
                    "plan_id" => $id,
                    "competention_id" => $compAr->getId(),
                    "knowledge_id" => $knowledge["id"]
                ));
                $ar->setTable(TABLE_WORK_PLAN_KNOWLEDGES);
                $ar->insert();
            }
            // умения
            foreach ($cData["skills"] as $skill) {
                $ar = new CActiveRecord(array(
                    "id" => null,
                    "plan_id" => $id,
                    "competention_id" => $compAr->getId(),
                    "skill_id" => $skill["id"]
                ));
                $ar->setTable(TABLE_WORK_PLAN_SKILLS);
                $ar->insert();
            }
            // навыки
            foreach ($cData["experiences"] as $exp) {
                $ar = new CActiveRecord(array(
                    "id" => null,
                    "plan_id" => $id,
                    "competention_id" => $compAr->getId(),
                    "experience_id" => $exp["id"]
                ));
                $ar->setTable(TABLE_WORK_PLAN_EXPERIENCES);
                $ar->insert();
            }
        }
    }
}