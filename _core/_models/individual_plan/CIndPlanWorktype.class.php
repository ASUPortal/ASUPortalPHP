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
            2 => "Учебно- и организационно-методическая работа",
            3 => "Научно-методическая и госбюджетная научно-исследовательская работа",
            4 => "Учебно-воспитательная работа"
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