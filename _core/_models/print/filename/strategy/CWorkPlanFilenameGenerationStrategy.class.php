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
		$discipline = "";
		if (!is_null($object->discipline)) {
			//$discipline = CUtils::toTranslit($object->discipline->getValue());
			$discipline = $object->discipline->getValue();
		}
		$authors = array();
		if (!is_null($object->authors)) {
			foreach ($object->authors->getItems() as $author) {
				$authors[] = $author->getNameShort();
			}
		}
		//$author = CUtils::toTranslit(implode(", ", $authors));
		$author = implode(", ", $authors);
		$filename = $author." - ".$discipline.".odt";
		return $filename;
    }

}