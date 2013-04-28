<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.04.13
 * Time: 8:52
 * To change this template use File | Settings | File Templates.
 */

class CPersonCourse extends CActiveModel {
    protected $_table = TABLE_PERSON_DIPLOMS;

    /**
     * @return string
     */
    public function getPeriod() {
        $res = "";
        if ($this->date_start !== "") {
            $res .= "с ".date("d.m.Y", strtotime($this->date_start));
        }
        if ($this->date_end !== "") {
            $res .= " по ".date("d.m.Y", strtotime($this->date_end));
        }
        return $res;
    }
}