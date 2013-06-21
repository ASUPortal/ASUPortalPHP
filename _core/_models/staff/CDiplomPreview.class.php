<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 24.02.13
 * Time: 18:16
 * To change this template use File | Settings | File Templates.
 */
class CDiplomPreview extends CActiveModel {
    protected $_table = TABLE_DIPLOM_PREVIEWS;
    protected $_commission;

    public function relations() {
        return array(
            "commission" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_commission",
                "storageField" => "comm_id",
                "managerClass" => "CSABManager",
                "managerGetObject" => "getPreviewCommission"
            )
        );
    }

    public function getPreviewDate() {
        return date("d.m.Y", strtotime($this->date_preview));
    }
}
