<?php

class CPublicationByTypes extends CActiveModel {
    protected $_table = TABLE_PUBLICATIONS_TYPES;
    
    public function attributeLabels() {
    	return array(
    			"name" => "Название",
    			"weight" => "Вес"
    	);
    }
}
