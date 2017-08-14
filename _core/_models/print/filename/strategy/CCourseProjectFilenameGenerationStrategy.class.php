<?php

/**
 * Стратегия генерации имён файлов для курсового проектирования
 *
 * Class CCourseProjectFilenameGenerationStrategy
 */
class CCourseProjectFilenameGenerationStrategy implements IPrintFilenameGenerationStrategy {
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
            $group = $object->group->getName();
            $discipline = $object->discipline->getValue();
        } else {
            $group = CUtils::toTranslit($object->group->getName());
            $discipline = CUtils::toTranslit($object->discipline->getValue());
        }
        $filename = $group." - (".$discipline.").odt";
        return $filename;
    }

}