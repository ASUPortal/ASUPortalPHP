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
		$person = $object->getNameShort();
		//$person = CUtils::toTranslit($object->getNameShort());
		$load = CIndPlanManager::getLoad(CRequest::getInt("planId"));
		$year = "";
		if (!is_null(CTaxonomyManager::getYear($load->year_id))) {
			$year = CTaxonomyManager::getYear($load->year_id)->getValue();
		}
		$typeLoad = $load->getType();
		//$typeLoad = CUtils::toTranslit($load->getType());
		$filename = $person." - ".$year." (".$typeLoad.").odt";
		return $filename;
    }

}