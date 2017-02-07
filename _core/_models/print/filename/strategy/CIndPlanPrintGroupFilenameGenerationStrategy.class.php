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
		$name = $person->getNameShort();
		//$name = CUtils::toTranslit($person->getNameShort());
		$year = "";
		if (!is_null(CTaxonomyManager::getYear($load->year_id))) {
			$year = CTaxonomyManager::getYear($load->year_id)->getValue();
		}
		$typeLoad = $load->getType();
		//$typeLoad = CUtils::toTranslit($load->getType());
		$filename = $name." - ".$year." (".$typeLoad.").odt";
		return $filename;
    }

}