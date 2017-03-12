<?php

class CDepProtocolVisit extends CActiveModel {
    protected $_table = TABLE_DEP_PROTOCOL_VISIT;

    protected $_protocol = null;

    public function relations() {
        return array(
            "protocol" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_protocol",
                "storageField" => "protocol_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getDepProtocol"
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
    			"kadri_id" => "Преподаватель",
    			"visit_type" => "Посещение",
    			"matter_text" => "Причина отсутствия"
    	);
    }
    
    protected function validationRules() {
    	return array(
    		"required" => array(
    			"protocol_id",
    			"kadri_id"
    		)
    	);
    }
    
}