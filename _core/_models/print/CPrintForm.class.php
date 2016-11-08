<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 21.11.12
 * Time: 21:42
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class CPrintForm
 * @property String form_format
 * @property String template_file
 * @property String filename_generation_strategy;
 */
class CPrintForm extends CActiveModel{
    protected $_table = TABLE_PRINT_FORMS;
    protected $_formset = null;

    protected function relations() {
        return array(
            "formset" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_formset",
                "storageField" => "formset_id",
                "managerClass" => "CPrintManager",
                "managerGetObject" => "getFormset"
            ),
        );
    }
    public static function getClassName() {
        return __CLASS__;
    }
    public function fieldsProperty() {
        return array(
            'template_file' => array(
                'type'  => FIELD_UPLOADABLE,
                'upload_dir' => PRINT_TEMPLATES_DIR
            )
        );
    }
    public function attributeLabels() {
        return array(
            "title" => "Название",
            "alias" => "Короткое название",
            "description" => "Описание",
            "formset_id" => "Набор форм",
            "form_format" => "Формат",
            "template_file" => "Файл шаблона",
            "filename_generation_strategy" => "Стратегия генерации имени файла",
            "isActive" => "Активен",
            "debug" => "Режим отладки"
        );
    }
}
