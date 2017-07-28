<?php

/**
 * Стратегия генерации имён файлов заданий для курсового проектирования
 *
 * Class CCourseProjectTaskFilenameGenerationStrategy
 */
class CCourseProjectTaskFilenameGenerationStrategy implements IPrintFilenameGenerationStrategy {
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
            $student = $object->student->getName();
            $group = $object->courseProject->group->getName();
        } else {
            $student = CUtils::toTranslit($object->student->getName());
            $group = CUtils::toTranslit($object->courseProject->group->getName());
        }
        // заменяем символы табуляции в строке имени студента на пробелы
        $student = str_replace(chr(9), " ", $student);
		$filename = $student." - (".$group.").odt";
		return $filename;
    }

}