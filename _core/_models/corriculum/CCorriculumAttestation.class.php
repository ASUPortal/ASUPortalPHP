<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CCorriculumAttestation
 *
 * @author startsev
 */
class CCorriculumAttestation extends CActiveModel{
    protected $_table = TABLE_CORRICULUM_ATTESTATIONS;
    protected $_type = null;
    
    public function attributeLabels() {
        return array(
            "type_id" => "Тип",
            "alias" => "Короткое имя для поиска",
            "length" => "Длительность (недель)",
            "length_hours" => "Длительность (в часах)",
            "length_credits" => "Длительность (зачетных единицы)",
            "discipline_id" => "Дисциплина"
        );
    } 
    
    public function relations() {
        return array(
            "type" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_type",
                "storageField" => "type_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "discipline" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_discipline",
                "storageField" => "discipline_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiscipline"
            )
        );
    }
}