<?php

/**
 * Стратегия генерации имён файлов рабочих программ
 *
 * Class CWorkPlanFilenameGenerationStrategy
 */
class CWorkPlanFilenameGenerationStrategy implements IPrintFilenameGenerationStrategy {
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
            eval(CSettingsManager::getSettingValue("codeForWorkPlanFilenameGeneration"));
            $filename = $value.".odt";
        } else {
            eval(CSettingsManager::getSettingValue("codeForWorkPlanFilenameGeneration"));
            $filename = CUtils::toTranslit($value).".odt";
        }
        return $filename;
    }

}