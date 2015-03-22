<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.03.15
 * Time: 21:51
 */

class CWorkPlanManager {
    /**
     * @param $id
     * @return CWorkPlan
     */
    public static function getWorkplan($id) {
        $plan = null;
        $ar = CActiveRecordProvider::getById(TABLE_WORK_PLANS, $id);
        if (!is_null($ar)) {
            $plan = new CWorkPlan($ar);
        }
        return $plan;
    }

    /**
     * @param $id
     * @return CWorkPlanCompetention
     */
    public static function getWorkplanCompetention($id) {
        $competention = null;
        $ar = CActiveRecordProvider::getById(TABLE_WORK_PLAN_COMPETENTIONS, $id);
        if (!is_null($ar)) {
            $competention = new CWorkPlanCompetention($ar);
        }
        return $competention;
    }
}