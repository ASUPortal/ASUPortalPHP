<?php

class CDiplomPreviewCommissionForm extends CFormModel {
    public $commission;
    public function save() {
        $commission = $this->commission;
        $members = array();
        if (array_key_exists("members", $commission)) {
            $members = $commission["members"];
            unset($commission["members"]);
        }
        $commObj = new CDiplomPreviewComission();
        $commObj->setAttributes($commission);
        $commObj->save();
		
		$this->commission = $commObj;
		
        foreach (CActiveRecordProvider::getWithCondition(TABLE_DIPLOM_PREVIEW_MEMBERS, "comm_id=".$commObj->getId())->getItems() as $ar) {
            $ar->remove();
        }
        foreach ($members as $m) {
            if ($m !== 0) {
                $ar = new CActiveRecord(array(
                    "comm_id" => $commObj->getId(),
                    "kadri_id" => $m,
                    "id" => null
                ));
                $ar->setTable(TABLE_DIPLOM_PREVIEW_MEMBERS);
                $ar->insert();
            }
        }
    }
}