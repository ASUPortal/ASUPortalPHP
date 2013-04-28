<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.04.13
 * Time: 12:55
 * To change this template use File | Settings | File Templates.
 */

class CSABCommissionForm extends CFormModel {
    public $commission;
    public function save() {
        $commission = $this->commission;
        $members = array();
        if (array_key_exists("members", $commission)) {
            $members = $commission["members"];
            unset($commission["members"]);
        }
        $commObj = new CSABCommission();
        $commObj->setAttributes($commission);
        $commObj->save();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_SAB_COMMISSION_MEMBERS, "commission_id=".$commObj->getId())->getItems() as $ar) {
            $ar->remove();
        }
        foreach ($members as $m) {
            $ar = new CActiveRecord(array(
                "commission_id" => $commObj->getId(),
                "person_id" => $m,
                "id" => null
            ));
            $ar->setTable(TABLE_SAB_COMMISSION_MEMBERS);
            $ar->insert();
        }
    }
}