<?php

class CQuestionManager {
	private static $_cacheQuestion = null;
    private static $_cacheQuestionStatus = null;
    
    private static function getCacheQuestion() {
    	if (is_null(self::$_cacheQuestion)) {
    		self::$_cacheQuestion = new CArrayList();
    	}
    	return self::$_cacheQuestion;
    }
    
    public static function getQuestion($key) {
    	if (!self::getCacheQuestion()->hasElement($key)) {
    		$ar = CActiveRecordProvider::getById(TABLE_QUESTION_TO_USERS, $key);
    		if (!is_null($ar)) {
    			$quest = new CQuestion($ar);
    			self::getCacheQuestion()->add($quest->getId(), $quest);
    		}
    	}
    	return self::getCacheQuestion()->getItem($key);
    }

    public static function getCacheQuestionStatus() {
    	if (is_null(self::$_cacheQuestionStatus)) {
    		self::$_cacheQuestionStatus = new CArrayList();
    		foreach (CActiveRecordProvider::getAllFromTable(TABLE_QUESTION_STATUS)->getItems() as $item) {
    			$term = new CTerm($item);
    			self::$_cacheQuestionStatus->add($term->getId(), $term);
    		}
    	}
    	return self::$_cacheQuestionStatus;
    }

    public static function getQuestionStatus($key) {
    	return self::getCacheQuestionStatus()->getItem($key);
    }
    
}