<?php
/**
 * Член комиссии по предзащите ВКР
 */
class CSABCommissionMember extends CActiveModel{
    protected $_table = TABLE_SAB_COMMISSION_MEMBERS;

    protected function relations() {
        return array(
			"commission" => array(
				"relationPower" => RELATION_HAS_ONE,
				"storageProperty" => "_comm",
				"storageField" => "commission_id",
				"managerClass" => "CSABManager",
				"managerGetObject" => "getCommission"
			),
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "person_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "commission_id" => "Комиссия",
            "person_id" => "Член комиссии",
            "date_preview" => "Дата защиты",
            "is_member" => "Явка/неявка",
            "comment" => "Причина неявки"
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
                "person_id"
            )
        );
    }

}