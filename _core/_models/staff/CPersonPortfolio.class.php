<?php
/**
 * Портфолио. Используется в карточке преподавателя
 */
class CPersonPortfolio extends CPersonPaper {
    public $type = 4;
    public $disser_type = DISSER_PORTFOLIO;

    public function attributeLabels() {
        return array(
            "tema" => "Название",
            "comment" => "Комментарий",
            "file_attach" => "Файл"
        );
    }

    public function fieldsProperty() {
        return array(
            'file_attach' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.'/library/anketa/kandid/'
            )
        );
    }
}
