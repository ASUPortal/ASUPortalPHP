<?php
/**
 * Класс для страницы преподавателей
 */
class CLecturer extends CActiveModel{
    protected $_table = TABLE_USERS;
    
    /**
     * Наличие биографии
     */
    public function getBiog() {
    	$query = new CQuery();
    	$query->select("biog.*")
	    	->from(TABLE_BIOGRAPHY." as biog")
	    	->condition("biog.user_id=".$this->id);
    	$biog = $query->execute()->getCount();
    	
    	return $biog;
    }
    /**
     * Количество дипломников
     */
    public function getDiplCount() {
    	$query = new CQuery();
    	$query->select("diplom.*")
    	->from(TABLE_DIPLOMS." as diplom")
    	->leftJoin(TABLE_PERSON." as person", "diplom.kadri_id=person.id")
    	->leftJoin(TABLE_USERS." as users", "users.kadri_id=person.id")
    	->condition("users.id=".$this->id." and users.kadri_id>0");
    	$count = $query->execute()->getCount();
    	 
    	return $count;
    }
    /**
     * Количество предметов
     */
    public function getDocCount() {
    	$query = new CQuery();
    	$query->select("doc.*")
    	->from(TABLE_LIBRARY_DOCUMENTS." as doc")
    	->condition("doc.user_id=".$this->id);
    	$count = $query->execute()->getCount();
    
    	return $count;
    }
    /**
     * Количество объявлений
     */
    public function getNewsCount() {
    	$query = new CQuery();
    	$query->select("news.*")
    	->from(TABLE_NEWS." as news")
    	->condition("news.user_id_insert=".$this->id);
    	$count = $query->execute()->getCount();
    
    	return $count;
    }
    /**
     * Наличие расписания
     */
    public function getTime() {
    	$query = new CQuery();
    	$query->select("time.*")
    	->from(TABLE_SCHEDULE." as time")
    	->condition("time.id=".$this->id." and time.year=".CUtils::getCurrentYear()->getId()." and time.month=".CUtils::getCurrentYearPart()->getId());
    	$count = $query->execute()->getCount();
    
    	return $count;
    }

}