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
        $student = "";
        if (!CSettingsManager::getSettingValue("template_filename_translit")) {
        	if (get_class($object) == "CStudentGroup") {
        		$group = $object->getName();
        	} elseif (get_class($object) == "CStudent") {
        		$student = $object->getName();
        		$group = $object->group->getName();
        	}
        } else {
            if (get_class($object) == "CStudentGroup") {
            	$group = CUtils::toTranslit($object->getName());
            } elseif (get_class($object) == "CStudent") {
            	$student = CUtils::toTranslit($object->getName());
            	$group = CUtils::toTranslit($object->group->getName());
            }
        }
        if ($student != "") {
        	$filename = $student." - (".$group.").odt";
        } else {
        	$filename = $group.".odt";
        }
        return $filename;
    }

}