<?php

/**
 * Стратегия генерации имён файлов для учебных нагрузок
 *
 * Class CStudyLoadFilenameGenerationStrategy
 */
class CStudyLoadFilenameGenerationStrategy implements IPrintFilenameGenerationStrategy {
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
        $globalRequestVariables = CRequest::getString("id");
        $requestVariables = unserialize(urldecode($globalRequestVariables));
         
        $lecturer = CStaffManager::getPerson($requestVariables["kadri_id"]);
        $year = CTaxonomyManager::getYear($requestVariables["year_id"]);
        
        if (!CSettingsManager::getSettingValue("template_filename_translit")) {
            $lecturer = $lecturer->getNameShort();
            $year = $year->getValue();
        } else {
            $lecturer = CUtils::toTranslit($lecturer->getNameShort());
            $year = CUtils::toTranslit($year->getValue());
        }
        $filename = $lecturer." - ".$year.".odt";
        return $filename;
    }

}