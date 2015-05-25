<?php

class CScienceSpeciality extends CTerm {
    protected $_table = TABLE_SCIENCE_SPECIALITIES;

    public function attributeLabels() {
        return array(
            "name" => "Название",
        	"name_short" => "Код специальности по ВАК",
            "comment" => "Комментарий"
        );
    }
}
