<?php
/**
 * Член комиссии по предзащите ВКР
 */
class CDiplomPreviewCommissionMember extends CActiveModel{
    protected $_table = TABLE_DIPLOM_PREVIEW_MEMBERS;

    protected function relations() {
        return array(
			"commission" => array(
				"relationPower" => RELATION_HAS_ONE,
				"storageProperty" => "_comm",
				"storageField" => "comm_id",
				"managerClass" => "CSABManager",
				"managerGetObject" => "getPreviewCommission"
			),
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "kadri_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "comm_id" => "Комиссия",
            "kadri_id" => "Член комиссии",
            "date_preview" => "Дата предзащиты",
            "is_member" => "Является участником?",
            "comment" => "Комментарий"
        );
    }
    
    public function fieldsProperty() {
    	return array(
    		"date_preview" => array(
    				"type"  => FIELD_MYSQL_DATE,
    				"format" => "d.m.Y"
    		)
    	);
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "kadri_id"
            )
        );
    }

}