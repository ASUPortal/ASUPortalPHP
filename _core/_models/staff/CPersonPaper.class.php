<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 26.01.14
 * Time: 15:38
 * To change this template use File | Settings | File Templates.
 */

class CPersonPaper extends CActiveModel{
    protected $_table = TABLE_PERSON_DISSER;
    public $type = 0;

    public function fieldsProperty() {
        return array(
            'file_attach' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.CORE_DS."library".CORE_DS."anketa".CORE_DS."kandid".CORE_DS
            ),
            'date_begin' => array(
                'type' => FIELD_MYSQL_DATE,
                'format' => "d.m.Y"
            ),
            'date_out' => array(
                'type' => FIELD_MYSQL_DATE,
                'format' => "d.m.Y"
            ),
            'date_end' => array(
                'type' => FIELD_MYSQL_DATE,
                'format' => "d.m.Y"
            ),
            'dis_sov_date' => array(
                'type' => FIELD_MYSQL_DATE,
                'format' => "d.m.Y"
            ),
            'vak_date' => array(
                'type' => FIELD_MYSQL_DATE,
                'format' => "d.m.Y"
            )
        );
    }
}