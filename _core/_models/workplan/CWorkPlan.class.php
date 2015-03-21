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
 */

class CWorkPlan extends CActiveModel implements IVersionControl{
    protected $_table = TABLE_WORK_PLANS;
    protected $_discipline;
    protected $_profiles;
    protected $_goals;
    protected $_tasks;

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
        // цели
        /**
         * @var CActiveRecord $ar
         */
        foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_GOALS, "plan_id=".$id)->getItems() as $ar) {
            $ar->remove();
        }
        foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_TASKS, "plan_id=".$id)->getItems() as $ar) {
            $ar->remove();
        }
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
    }
}