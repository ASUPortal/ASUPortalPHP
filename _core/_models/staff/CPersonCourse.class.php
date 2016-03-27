<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.04.13
 * Time: 8:52
 * To change this template use File | Settings | File Templates.
 */

class CPersonCourse extends CActiveModel {
    protected $_table = TABLE_PERSON_COURCES;

    public function fieldsProperty() {
        return array(
            'file_attach' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.CORE_DS."library".CORE_DS."anketa".CORE_DS."obrazov".CORE_DS
            )
        );
    }

    /**
     * @return string
     */
    public function getPeriod() {
        $res = "";
        if ($this->date_start !== "" and $this->date_start !== "0000-00-00") {
            $res .= "с ".date("d.m.Y", strtotime($this->date_start));
        }
        if ($this->date_end !== "" and $this->date_end !== "0000-00-00") {
            $res .= " по ".date("d.m.Y", strtotime($this->date_end));
        }
        return $res;
    }
}