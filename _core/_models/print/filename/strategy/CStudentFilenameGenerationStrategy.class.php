<?php

/**
 * Стратегия генерации имён файлов для студентов
 *
 * Class CStudentFilenameGenerationStrategy
 */
class CStudentFilenameGenerationStrategy implements IPrintFilenameGenerationStrategy {
    private $form;
    private $object;

    function __construct(CPrintForm $form, CModel $object){
        $this->form = $form;
        $this->object = $object;
    }


    /**
     * Сгенерировать имя файла
     *
     * @return String
     */
    public function getFilename() {
        $object = $this->object;
        if (!CSettingsManager::getSettingValue("template_filename_translit")) {
            $student = $object->getName();
            $group = $object->group->getName();
        } else {
            $student = CUtils::toTranslit($object->getName());
            $group = CUtils::toTranslit($object->group->getName());
        }
        $filename = $student." - (".$group.").odt";
        return $filename;
    }

}