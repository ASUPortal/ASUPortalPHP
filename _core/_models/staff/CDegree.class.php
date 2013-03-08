<?php
/**
 * Ученая степень. Используется в карточке преподавателя
 */
class CDegree extends CActiveModel {
    protected $_table = TABLE_PERSON_DISSER;
    protected $_degree = null;
    public static function getClassName() {
        return __CLASS__;
    }
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

    /**
     * Переопределяем соответствия полей БД и свойств объекта
     *
     * @return array
     */
    public function fieldsMapping() {
        return array(
            'year'          => 'god_zach',
            'decision_date' => 'dis_sov_date',
            'decision_num'  => 'dis_sov_num',
            'doc_series'    => 'doc_seriya',
            'doc_num'       => 'doc_num',
            'degree_id'     => 'study_form_id',
            'subject'       => 'tema',
            'person_id'     => 'kadri_id',
            'file'          => 'file_attach'
        );
    }
    public function fieldsProperty() {
        return array(
            'file' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => CORE_CWD.'/library/anketa/kandid/'
            )
        );
    }
}
