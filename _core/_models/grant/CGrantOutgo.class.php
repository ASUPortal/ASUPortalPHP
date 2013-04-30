<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 30.04.13
 * Time: 22:17
 * To change this template use File | Settings | File Templates.
 */

class CGrantOutgo extends CActiveModel{
    protected $_table = TABLE_GRANT_OUTGOES;
    protected $_category = null;

    public function attributeLabels() {
        return array(
            "category_id" => "Статья расхода",
            "value" => "Сумма"
        );
    }

    public function validationRules() {
        return array(
            "required" => array(
                "value"
            ),
            "selected" => array(
                "category_id"
            )
        );
    }

    public function relations() {
        return array(
            "category" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_category",
                "storageField" => "category_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            )
        );
    }
}