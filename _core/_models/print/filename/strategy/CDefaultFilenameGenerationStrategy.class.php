<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 21:04
 */

/**
 * Стандартная стратегия генерации имен файлов
 *
 * Class CDefaultFilenameGenerationStrategy
 */
class CDefaultFilenameGenerationStrategy implements IPrintFilenameGenerationStrategy {
    private $form;

    function __construct(CPrintForm $form){
        $this->form = $form;
    }


    /**
     * Сгенерировать имя файла
     *
     * @return String
     */
    public function getFilename() {
        $filename = date("dmY_Hns")."_".$this->form->template_file;
        $i = 0;
        while (file_exists(PRINT_DOCUMENTS_DIR.$filename)) {
            $filename = date("dmY_Hns")."_".$i."_".$this->form->template_file;
            $i++;
        }
        return $filename;
    }

}