<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.03.15
 * Time: 21:51
 */

class CWorkPlanManager {
    public static function getWorkplan($id) {
        $plan = null;
        $ar = CActiveRecordProvider::getById(TABLE_WORK_PLANS, $id);
        if (!is_null($ar)) {
            $plan = new CWorkPlan($ar);
        }
        return $plan;
    }
}