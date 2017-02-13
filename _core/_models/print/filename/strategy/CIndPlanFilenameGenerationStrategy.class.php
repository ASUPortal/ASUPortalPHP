<?php

/**
 * Стратегия генерации имён файлов индивидуальных планов
 *
 * Class CIndPlanFilenameGenerationStrategy
 */
class CIndPlanFilenameGenerationStrategy implements IPrintFilenameGenerationStrategy {
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
        $load = CIndPlanManager::getLoad(CRequest::getInt("planId"));
        $year = "";
        if (!is_null($load->year)) {
            $year = $load->year->getValue();
        }
        if (!CSettingsManager::getSettingValue("template_filename_translit")) {
            $person = $object->getNameShort();
            $typeLoad = $load->getType();
        } else {
            $person = CUtils::toTranslit($object->getNameShort());
            $typeLoad = CUtils::toTranslit($load->getType());
        }
        $filename = $person." - ".$year." (".$typeLoad.").odt";
        return $filename;
    }

}