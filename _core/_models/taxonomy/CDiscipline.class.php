<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 10.06.12
 * Time: 10:53
 * To change this template use File | Settings | File Templates.
 */
class CDiscipline extends CActiveModel {
	protected $_table = TABLE_DISCIPLINES;
    private $_questions = null;
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
}
