<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:26
 */
class CPrintService {
    /**
     * @param CPrintForm $form
     * @throws Exception
     * @return IPrintTemplateWriter
     */
    public function getTemplateWriter(CPrintForm $form, CModel $object) {
        if (CStringUtils::equalsIgnoreCase($form->form_format, "odt")) {
            return new COdtPrintTemplateWriter($form, $object);
        } else if (CStringUtils::equalsIgnoreCase($form->form_format, "docx")) {
            return new CDocxPrintTemplateWriter($form, $object);
        } else if (CStringUtils::equalsIgnoreCase($form->form_format, "html")) {
            return new CHtmlPrintTemplateWriter($form, $object);
        } else {
            throw new Exception("Unsupported template format " . $form->form_format);
        }
    }

    /**
     * Напечатать модель по указанному шаблону
     *
     * @param CPrintForm $form
     * @param CModel $object
     * @throws Exception
     */
    public function printTemplate(CPrintForm $form, CModel $object) {
        /**
         * Загружаем шаблон
         */
        $writer = $this->getTemplateWriter($form, $object);
        /**
         * Загружаем шаблон печатной формы
         */
        $template = $writer->loadTemplate();
        /**
         * Проверка на отладку. Если в шаблоне включена отладка, то
         * вместо вывода пользователю показываем всякую полезную для разработчика
         * информацию
         */
        $this->_isDebug = $form->debug == "1";
        /**
         * Попробуем получить все описатели из документа, чтобы не думать об их порядке в БД
         */
        $fieldsFromTemplate = $template->getFields();
        /**
         * Если включена отладка, то показываем все описатели
         */
        if ($this->_isDebug) {
            var_dump(array_keys($fieldsFromTemplate));
            /**
             * Это место для экспериментов и написания отладочного кода
             */
            $value = array();
            $this->debugTable($value);
        }
        /**
         * Вычисляем все поля
         *
         * @var IPrintTemplateField $templateField
         */
        foreach ($fieldsFromTemplate as $templateField) {
            $value = $templateField->getEvaluateValue($object);
            $templateField->setValue($value, $template);
        }
        /**
         * В HTML-шаблонах заменяем изображения на 64-разрядный код
         */
        if (CStringUtils::equalsIgnoreCase($form->form_format, "html")) {
            $template->replaceImage64encoded();
        }
        /**
         * Сохраняем документ
         */
        $filename = $this->getFilename($form, $object);
        $writer->save($template, $filename);
        /**
         * При отладке не выгружаем обратно документ
         */
        if ($this->_isDebug) {
            exit;
        }
        return $filename;
    }
    
    /**
     * Получить название файла
     *
     * @param CPrintForm $form
     * @param CModel $object
     * @return String
     */
    private function getFilename(CPrintForm $form, $object) {
        $strategyClass = $form->filename_generation_strategy;
        $strategy = null;
        if (CStringUtils::isBlank($strategyClass)) {
            $strategy = new CDefaultFilenameGenerationStrategy($form);
        } elseif ($strategyClass == "CWorkPlanFilenameGenerationStrategy") {
            $strategy = new CWorkPlanFilenameGenerationStrategy($form, $object);
        } elseif ($strategyClass == "CIndPlanFilenameGenerationStrategy") {
            $strategy = new CIndPlanFilenameGenerationStrategy($form, $object);
        } elseif ($strategyClass == "CIndPlanPrintGroupFilenameGenerationStrategy") {
            $strategy = new CIndPlanPrintGroupFilenameGenerationStrategy($form, $object);
        } elseif ($strategyClass == "CCourseProjectFilenameGenerationStrategy") {
            $strategy = new CCourseProjectFilenameGenerationStrategy($form, $object);
        } elseif ($strategyClass == "CCourseProjectTaskFilenameGenerationStrategy") {
            $strategy = new CCourseProjectTaskFilenameGenerationStrategy($form, $object);
        } else {
            throw new Exception("Стратегия именования файла ".$strategyClass." не определена!");
        }
        return $strategy->getFilename();
    }
    
    /**
     * Печать таблицы для отладки
     *
     * @param array $value
     */
    private function debugTable($value = array()) {
		echo '<table width="100%" cellpadding="2" cellspacing="0" border="1">';
		foreach ($value as $row) {
			echo '<tr>';
			foreach ($row as $cell) {
				echo '<td>'.$cell.'</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	}
}