<?php

class CFilialGoing extends CActiveModel{
    protected $_table = TABLE_FILIAL_GOING;
    
    protected function relations() {
    	return array(
    		"person" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_person",
    			"storageField" => "kadri_id",
    			"managerClass" => "CStaffManager",
    			"managerGetObject" => "getPerson"
    		),
            "filial" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_filial",
                "storageField" => "filial_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getFilial"
            ),
    		"filial_act" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_filial_act",
    			"storageField" => "filial_act_id",
    			"managerClass" => "CTaxonomyManager",
    			"managerGetObject" => "getFilialAct"
    		),
    		"transport" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_transport",
    			"storageField" => "transport_type_id",
    			"managerClass" => "CTaxonomyManager",
    			"managerGetObject" => "getTrasport"
    		)
    	);
    }
    
    public function attributeLabels() {
        return array(
            "kadri_id" => "ФИО",
        	"person.fio" => "ФИО",
        	"filial_id" => "Место",
        	"filial.name" => "Место",
            "day_cnt" => "Суток",
        	"day_start" => "Дата начала",
        	"day_end" => "Дата окончания",
        	"filial_act_id" => "Цель",
        	"filial_act.name" => "Цель",
        	"transport_type_id" => "Вид проезда",
        	"transport.name" => "Вид проезда",
        	"hours_cnt" => "Часов",
        	"comment" => "Комментарий"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "kadri_id"
            )
        );
    }
    public function fieldsProperty() {
    	return array(
    		"day_start" => array(
    			"type" => FIELD_MYSQL_DATE,
    			"format" => "d.m.Y"
    		),
    		"day_end" => array(
    			"type" => FIELD_MYSQL_DATE,
    			"format" => "d.m.Y"
    		)
    	);
    }
    
}