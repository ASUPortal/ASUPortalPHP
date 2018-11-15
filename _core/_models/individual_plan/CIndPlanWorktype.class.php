<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 29.07.13
 * Time: 19:57
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanWorktype extends CActiveModel {
    protected $_table = TABLE_IND_PLAN_WORKTYPES;

    public static function getCategories() {
        return array(
            CIndPlanPersonWorkType::STUDY_AND_METHODICAL_LOAD => "Учебная работа",
            CIndPlanPersonWorkType::SCIENTIFIC_METHODICAL_LOAD => "Научно-исследовательская работа",
            CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD => "Учебно-методическая, научно-методическая и воспитательная работа",
            CIndPlanPersonWorkType::ORGANIZATIONAL_AND_METHODICAL_LOAD => "Организационно-методическая работа",
            CIndPlanPersonWorkType::ASPIRANTS_LOAD => "Подготовка кадров высшей квалификации в аспирантуре"
        );
    }
    /**
     * Возможно ли вычисление
     *
     * @return bool
     */
    public function isAutcomputable() {
        return !($this->completion_planned == "" | $this->completion_completed == "");
    }
    public function computeCompletion(CPerson $person, CTerm $year) {
        return eval($this->completion_completed);
    }
    public function computePlannedHours(CPerson $person, CTerm $year) {
        return eval($this->completion_planned);
    }
}