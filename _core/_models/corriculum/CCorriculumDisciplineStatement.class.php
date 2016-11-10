<?php

/**
 * Class CCorriculumDisciplineStatement
 * 
 * @property int discipline_id
 * 
 * Заявка на учебную литературу
 */
class CCorriculumDisciplineStatement extends CActiveModel {
    protected $_table = TABLE_CORRICULUM_DISCIPLINE_STATEMENTS;
    protected $_discipline = null;
    
    protected function relations() {
    	return array(
    		"discipline" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_discipline",
    			"storageField" => "discipline_id",
    			"managerClass" => "CCorriculumsManager",
    			"managerGetObject" => "getDiscipline"
    		)
    	);
    }
    public function attributeLabels() {
    	return array(
    		"author" => "Автор",
    		"book_name" => "Название книги",
    		"publishing" => "Издательство",
    		"year_of_publishing" => "Год издания",
    		"grif" => "Гриф",
    		"count_of_copies" => "Количество экземляров",
    		"literature_type" => "Учебник является литературой"
    	);
    }
    
}