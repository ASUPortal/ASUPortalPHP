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
 *
 * @property CTerm discipline
 * @property CArrayList profiles
 */

class CWorkPlan extends CActiveModel{
    protected $_table = TABLE_WORK_PLANS;
    protected $_discipline;
    protected $_profiles;

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
            )
        );
    }
}