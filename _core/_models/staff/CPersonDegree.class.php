<?php
/**
 * Ученая степень. Используется в карточке преподавателя
 */
class CPersonDegree extends CPersonPaper {
    public $type = 3;
    public $disser_type = DISSER_DEGREE;
    protected $_degree = null;

    public function relations() {
        return array(
            "degree" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_degree",
                "storageField" => "degree_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTitle"
            ),
        );
    }

    public function attributeLabels() {
        return array(
            "study_form_id" => "Звание",
            "tema" => "Область знания",
            "god_zach" => "Год присвоения",
            "dis_sov_date" => "Дата решения совета",
            "dis_sov_num" => "Номер решения совета",
            "doc_seriya" => "Свидетельство серия",
            "doc_num" => "Свидетельсвто номер",
            "comment" => "Комментарий",
            "file_attach" => "Скан"
        );
    }

    public function fieldsProperty() {
        return array(
            'file_attach' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.'/library/anketa/kandid/'
            ),
            'dis_sov_date' => array(
                'type' => FIELD_MYSQL_DATE,
                'format' => "d.m.Y"
            )
        );
    }
}
