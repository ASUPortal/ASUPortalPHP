<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 11.11.12
 * Time: 21:29
 * To change this template use File | Settings | File Templates.
 */
class CExamGroupAdd extends CFormModel {
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            'speciality_id' => 'Специальность',
            'course' => 'Курс',
            'year_id' => 'Учебный год',
            'category_id' => 'Категория вопроса',
            'discipline_id' => 'Дисциплина',
            'text' => 'Тексты вопросов, каждый с новой строки'
        );
    }
}
