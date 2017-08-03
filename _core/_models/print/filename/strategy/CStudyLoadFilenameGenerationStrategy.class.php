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
         
        $lecturer = CStaffManager::getPerson(CRequest::getInt("kadri_id"));
        if (is_null($lecturer)) {
            $params = unserialize(urldecode(CRequest::getString("id")));
            if (array_key_exists("kadri_id", $params)) {
                $lecturer = CStaffManager::getPerson($params["kadri_id"]);
            }
        }
        $year = CTaxonomyManager::getYear(CRequest::getInt("year_id"));
        if (is_null($year)) {
            $params = unserialize(urldecode(CRequest::getString("id")));
            if (array_key_exists("year_id", $params)) {
                $year = CTaxonomyManager::getYear($params["year_id"]);
            }
        }
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