<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.04.13
 * Time: 8:31
 * To change this template use File | Settings | File Templates.
 */

class CPersonDiplom extends CActiveModel{
    protected $_table = TABLE_PERSON_DIPLOMS;

    public function fieldsProperty() {
        return array(
            'file_attach' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.CORE_DS."library".CORE_DS."anketa".CORE_DS."obrazov".CORE_DS
            )
        );
    }

    public function getTypes() {
        return array(
            "высшее" => "Высшее",
            "неполное высшее" => "Неполное высшее"
        );
    }
}