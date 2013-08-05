<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 05.08.13
 * Time: 20:57
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanPersonLoadTeaching extends CFormModel {
    public $year_id;
    public $kadri_id;

    protected $_plan = null;
    protected $_fact = null;

    /**
     * @return CArrayList|null
     */
    public function getPlan() {
        if (is_null($this->_plan)) {
            $this->_plan = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_LOAD_TEACHING_PLAN,
                     "id_kadri=".$this->kadri_id." AND id_year=".$this->year_id)->getItems() as $ar) {
                $load = new CIndPlanPersonLoadTeachingPlan($ar);
                $this->_plan->add($load->getId(), $load);
            }
        }
        return $this->_plan;
    }

    /**
     * @return CArrayList|null
     */
    public function getFact() {
        if (is_null($this->_fact)) {
            $this->_fact = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_LOAD_TEACHING_FACT,
                "id_kadri=".$this->kadri_id." AND id_year=".$this->year_id)->getItems() as $ar) {
                $load = new CIndPlanPersonLoadTeachingFact($ar);
                $this->_fact->add($load->getId(), $load);
            }
        }
        return $this->_fact;
    }
}