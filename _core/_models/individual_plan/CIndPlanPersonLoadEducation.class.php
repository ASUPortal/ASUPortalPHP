<?php
class CIndPlanPersonLoadEducation extends CActiveModel {
    protected $_table = TABLE_IND_PLAN_LOAD_EDUCATION;

    public $id_kadri;
    public $id_otmetka = 0;

    protected $_group = null;
    protected $_worktype = null;

    protected function relations() {
        return array(
            "group" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_group",
                "storageField" => "id_study_groups",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudentGroup"
            ),
            "worktype" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_worktype",
                "storageField" => "id_vidov_rabot",
                "managerClass" => "CIndPlanManager",
                "managerGetObject" => "getWorktype"
            )
        );
    }

    /**
     * @return string
     */
    public function getMark() {
        if ($this->id_otmetka == "1") {
            return "Да";
        }
        return "Нет";
    }
}