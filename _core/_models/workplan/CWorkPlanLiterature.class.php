<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 20.06.15
 * Time: 20:42
 *
 * @property int plan_id
 * @property int type
 * @property int book_id
 * @property CArrayList books
 *
 */
class CWorkPlanLiterature extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_LITERATURE;

    protected function relations() {
        return array(
        	"book" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "book_id",
        		"targetClass" => "CTerm"
        	),
        	"plan" => array(
        		"relationPower" => RELATION_HAS_ONE,
        		"storageField" => "plan_id",
        		"targetClass" => "CWorkPlan"
        	),
        	"books" => array(
        		"relationPower" => RELATION_MANY_TO_MANY,
        		"storageProperty" => "_books",
        		"joinTable" => TABLE_WORK_PLAN_BOOKS,
        		"leftCondition" => "literature_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
        		"rightKey" => "book_id",
        		"managerClass" => "CBaseManager",
        		"managerGetObject" => "getCorriculumBook"
        	)
        );
    }

    public function attributeLabels() {
        return array(
            "book_id" => "Книга",
            "type" => "Тип",
            "ordering" => "Порядковый номер",
        	"books" => "Книги"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "type"
            )
        );
    }


}