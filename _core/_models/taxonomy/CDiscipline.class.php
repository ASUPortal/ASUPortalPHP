<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 10.06.12
 * Time: 10:53
 * To change this template use File | Settings | File Templates.
 */
class CDiscipline extends CTerm {
	protected $_table = TABLE_DISCIPLINES;
	protected $_books = null;
    private $_questions = null;
    
    protected function relations() {
    	return array(
    		"books" => array(
    			"relationPower" => RELATION_MANY_TO_MANY,
    			"storageProperty" => "_books",
    			"joinTable" => TABLE_DISCIPLINES_BOOKS,
    			"leftCondition" => "subject_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
    			"rightKey" => "book_id",
    			"managerClass" => "CBaseManager",
    			"managerGetObject" => "getCorriculumBook"
    		)
    	);
    }
    
    public function attributeLabels() {
    	return array(
    		"name" => "Название",
    		"library_code" => "Код из библиотеки",
    		"name_from_library" => "Название из библиотеки"
    	);
    }
    
    /**
     * Вопросы, которые по данной дисциплине есть
     *
     * @return CArrayList
     */
    public function getQuestions() {
        if (is_null($this->_questions)) {
            $this->_questions = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_SEB_QUESTIONS, "discipline_id=".$this->getId())->getItems() as $ar) {
                $q = new CSEBQuestion($ar);
                $this->_questions->add($q->getId(), $q);
            }
        }
        return $this->_questions;
    }
    /**
     * 
     * @return string
     */
    public function getAlias() {
        if ($this->getRecord()->getItemValue("name_short") != "") {
            return $this->getRecord()->getItemValue("name_short");
        } else {
            return $this->getRecord()->getItemValue("name");
        }
    }
}
