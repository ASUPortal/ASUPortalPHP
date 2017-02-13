<?php

/**
 * Стратегия генерации имён файлов индивидуальных планов для массовой печати
 *
 * Class CIndPlanPrintGroupFilenameGenerationStrategy
 */
class CIndPlanPrintGroupFilenameGenerationStrategy implements IPrintFilenameGenerationStrategy {
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
        $load = CIndPlanManager::getLoad($object->getId());
        $person = $load->person;
        $year = "";
        if (!is_null($load->year)) {
            $year = $load->year->getValue();
        }
        if (!CSettingsManager::getSettingValue("template_filename_translit")) {
            $name = $person->getNameShort();
            $typeLoad = $load->getType();
        } else {
            $name = CUtils::toTranslit($person->getNameShort());
            $typeLoad = CUtils::toTranslit($load->getType());
        }
        $filename = $name." - ".$year." (".$typeLoad.").odt";
        return $filename;
    }

}