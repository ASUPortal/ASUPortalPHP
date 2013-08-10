<?php
class CIndPlanPersonLoadOrg extends CActiveModel {
    protected $_table = TABLE_IND_PLAN_LOAD_ORGANIZATIONAL;

    protected $_worktype = null;
    public $id_otmetka = 0;

    protected function relations() {
        return array(
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