<?php

class CPublicationByPersons extends CActiveModel {
    protected $_table = TABLE_PUBLICATION_BY_PERSONS;
    protected $_author = null;
    protected $_izdan = null;

    public function relations() {
        return array(
        	"author" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_author",
        		"storageField" => "kadri_id",
        		"managerClass" => "CStaffManager",
        		"managerGetObject" => "getPerson"
        	),
        	"izdan" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageProperty" => "_izdan",
        		"storageField" => "izdan_id",
        		"managerClass" => "CStaffManager",
        		"managerGetObject" => "getPublication"
        	),
        );
    }

}
