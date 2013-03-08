<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 17.10.12
 * Time: 20:13
 * To change this template use File | Settings | File Templates.
 */
class CPersonRatingAutofillForm extends CFormModel{
    public function attributeLabels() {
        return array(
            'persons' => 'Сотрудники',
            'year_id' => 'Год'
        );
    }
    public static function getClassName() {
        return __CLASS__;
    }
}
