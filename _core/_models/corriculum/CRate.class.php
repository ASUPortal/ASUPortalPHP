<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 15.06.13
 * Time: 21:13
 * To change this template use File | Settings | File Templates.
 */

class CRate extends CActiveModel{
    protected $_table = TABLE_RATES;
    protected $_category;
    protected $_year;

    public $title;
    public $alias;
    public $value;

    public function attributeLabels() {
        return array(
            "title" => "Название",
            "alias" => "Псевдоним",
            "category_id" => "Категория",
            "year_id" => "Год",
            "value" => "Значение"
        );
    }

    protected function relations() {
        return array(
            "category" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_category",
                "storageField" => "category_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "year" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_year",
                "storageField" => "year_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getYear"
            )
        );
    }

    public function validationRules() {
        return array(
            "required" => array(
                "title"
            ),
            "selected" => array(
                "category_id",
                "year_id"
            )
        );
    }
}