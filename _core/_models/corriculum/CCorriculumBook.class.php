<?php

class CCorriculumBook extends CActiveModel{
    protected $_table = TABLE_CORRICULUM_BOOKS;
    
    public function attributeLabels() {
    	return array(
    		"book_name" => "Название книги"
    	);
    }
    
}