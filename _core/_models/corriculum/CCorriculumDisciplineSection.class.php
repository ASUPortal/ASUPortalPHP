<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.05.15
 * Time: 22:32
 *
 * @property int discipline_id
 * @property CArrayList labors
 */
class CCorriculumDisciplineSection extends CActiveModel{
    protected $_table = TABLE_CORRICULUM_DISCIPLINE_SECTIONS;

    protected function relations() {
        return array(
            'labors' => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_CORRICULUM_DISCIPLINE_LABORS,
                "storageCondition" => "section_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => 'CCorriculumDisciplineLabor'
            )
        );
    }


    public function attributeLabels(){
        return array(
            "title" => "Номер семестра",
            'sectionIndex' => "Порядковый номер (для сортировки)"
        );
    }

    protected function validationRules() {
        return array(
            'required' => array(
                'title'
            )
        );
    }


}