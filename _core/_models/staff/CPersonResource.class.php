<?php
/**
 * Научный ресурс
 */

class CPersonResource extends CActiveModel {
    protected $_table = TABLE_PERSON_RESOURCES;
    
    public function relations() {
    	return array(
    		"person" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageField" => "person_id",
    			"targetClass" => "CPerson"
    		),
    		"resource" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "resource_id",
                "targetClass" => "CTerm"
            )
    	);
    }

    public function attributeLabels() {
    	return array(
    		"person_id" => "Сотрудник",
    		"author_id" => "ID автора",
    		"resource_id" => "Ресурс"
    	);
    }
    
    protected function validationRules() {
    	return array(
    		"selected" => array(
    			"resource_id"
    		)
    	);
    }
}