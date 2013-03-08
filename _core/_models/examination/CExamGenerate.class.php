<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 11.11.12
 * Time: 21:48
 * To change this template use File | Settings | File Templates.
 */
class CExamGenerate extends CFormModel{
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            'speciality_id' => 'Специальность',
            'course' => 'Курс',
            'year_id' => 'Год',
            'discipline_id' => 'Дисциплина',
            'category_id' => 'Категория вопроса',
            'approver_id' => 'Утвердил',
            'protocol_id' => 'Протокол заседания кафедры',
            'number' => 'Число билетов'
        );
    }
}
