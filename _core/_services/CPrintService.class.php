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
    private function getTemplateWriter(CPrintForm $form) {
        if (CStringUtils::equalsIgnoreCase($form->form_format, "odt")) {
            return new COdtPrintTemplateWriter($form);
        } else if (CStringUtils::equalsIgnoreCase($form->form_format, "docx")) {
            return new CDocxPrintTemplateWriter($form);
        } else if (CStringUtils::equalsIgnoreCase($form->form_format, "html")) {
            return new CHtmlPrintTemplateWriter($form);
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
        $writer = $this->getTemplateWriter($form);
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
            $value = $this->getFieldValue($templateField->getName(), $object);
            $templateField->setValue($value);
        }
        /**
         * Сохраняем документ
         */
        $filename = $this->getFilename($template);
        $writer->save($template, $filename);
        /**
         * При отладке не выгружаем обратно документ
         */
        if ($this->_isDebug) {
            exit;
        }
        return $filename;
    }

    private function getFieldValue($fieldName, CModel $object) {
        /**
         * Здесь должен остаться код, который:
         * 1. Берет описатель по имени, если не находит, выбрасывает исключение
         * 2. Вычисляет значение описателя
         * 3. Возвращает его
         */

//        /**
//         * Еще один вариант. Надеюсь, этот заработает нормально
//         */
//        foreach ($fieldsFromTemplate as $fieldName=>$descriptors) {
//            /**
//             * Если поле из шаблона есть в наборе полей,
//             * то вычисляем его. Перед вычислением проверяем,
//             * есть ли привязанные к нему дочерние описатели. Если дочерние
//             * описатели есть, то не вычисляем, так как вычислением дочерних
//             * будет заниматься родительский
//             */
//            if (!is_null($form->formset->getFieldByName($fieldName))) {
//                $field = $form->formset->getFieldByName($fieldName);
//                if (is_null($field->parent)) {
//                    foreach ($descriptors as $node) {
//                        $xml = $this->processNode($node, $field, $object, $form);
//                    }
//                }
//            } elseif (mb_strpos($fieldName, ".class") !== false) {
//                /**
//                 * Это новый описатель, параметры которого хранятся в отдельном классе
//                 */
//                $classFieldName = CUtils::strLeft($fieldName, ".class");
//                /**
//                 * @var $classField IPrintClassField
//                 */
//                $classField = new $classFieldName();
//                if (!is_a($classField, "IPrintClassField")) {
//                    throw new Exception("Класс ".$classField." не реализует интерфейс IPrintClassField");
//                }
//                /**
//                 * Дабы не ломать уже имеющуюся структуру работать будем через
//                 * класс-адаптер
//                 */
//                $adapter = new CPrintClassFieldToFieldAdapter($classField, $object);
//                foreach ($descriptors as $node) {
//                    $xml = $this->processNode($node, $adapter, $object, $form);
//                }
//            }
//        }
    }

    private function getFilename(CPrintForm $form) {
        $strategyClass = $form->filename_generation_strategy;
        $strategy = null;
        if (CStringUtils::isBlank($strategyClass)) {
            $strategy = new CDefaultFilenameGenerationStrategy($form);
        } else {
            // тут загрузить указанную стратегию
        }
        return $strategy->getFilename();
    }
}