<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 05.08.13
 * Time: 20:54
 * To change this template use File | Settings | File Templates.
 */

class CIndPlanPersonLoad extends CActiveModel{
    protected $_table = TABLE_IND_PLAN_LOADS;
    protected $_person = null;
    protected $_works = null;
    public $person_id;

    public function relations() {
        return array(
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "person_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "works" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_works",
                "storageTable" => TABLE_IND_PLAN_WORKS,
                "storageCondition" => "load_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "managerClass" => "CIndPlanManager",
                "managerGetObject" => "getWork"
            )
        );
    }

    /**
     * @param $type
     * @return CArrayList
     */
    public function getWorksByType($type) {
        $result = new CArrayList();
        foreach ($this->works->getItems() as $work) {
            if ($work->work_type == $type) {
                $result->add($work->getId(), $work);
            }
        }
        return $result;
    }
}