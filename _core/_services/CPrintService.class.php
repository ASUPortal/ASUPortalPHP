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
            $field = $this->getFieldValue($templateField->getName(), $object, $form);
            $templateField->setValue($field, $object, $form, $template);
        }
        /**
         * Сохраняем документ
         */
        $filename = $this->getFilename($form, $object);
        $template->save(PRINT_DOCUMENTS_DIR.$filename);
        /**
         * При отладке не выгружаем обратно документ
         */
        if ($this->_isDebug) {
            exit;
        }
        return $filename;
    }

    /**
     * Получить объект описателя поля
     *
     * @param $fieldName
     * @param CModel $object
     * @param CPrintForm $form
     * @return CPrintField
     */
    public function getFieldValue($fieldName, CModel $object, CPrintForm $form) {
		/**
		 * Обрабатываем описатели из базы данных
		 */
		if (!is_null($form->formset->getFieldByName($fieldName))) {
			$field = $form->formset->getFieldByName($fieldName);
		}
		/**
		 * Обрабатываем описатели-классы
		 */
		elseif (mb_strpos($fieldName, ".class") !== false) {
			/**
			 * Это новый описатель, параметры которого хранятся в отдельном классе
			 */
			$classFieldName = CUtils::strLeft($fieldName, ".class");
			/**
			 * @var $classField IPrintClassField
			 */
			if (class_exists($classFieldName)) {
				$classField = new $classFieldName();
			} else {
				throw new Exception("Класс ".$classFieldName." не объявлен в системе!");
			}
			if (!is_a($classField, "IPrintClassField")) {
				throw new Exception("Класс ".$classField." не реализует интерфейс IPrintClassField");
			}
			/**
			 * Дабы не ломать уже имеющуюся структуру работать будем через
			 * класс-адаптер
			 */
			$field = new CPrintClassFieldToFieldAdapter($classField, $object);
		} else {
			throw new Exception("Описатель ".$fieldName." не найден в системе!");
		}
		return $field;
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
        } elseif ($strategyClass == "CWorkPlan") {
            $strategy = new CWorkPlanFilenameGenerationStrategy($form, $object);
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