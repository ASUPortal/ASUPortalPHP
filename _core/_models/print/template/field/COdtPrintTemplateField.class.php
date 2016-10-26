<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:50
 */

/**
 * Класс для работы с полями в XML-документе,
 * построенном на основе ODT-шаблона
 * 
 * Class COdtPrintTemplateField
 */
class COdtPrintTemplateField implements IPrintTemplateField {
    private $name;
    private $descriptors;
    private $isStyleField;

    function __construct($name, $descriptors, $isStyleField) {
        $this->name = $name;
        $this->descriptors = $descriptors;
        $this->isStyleField = $isStyleField;
    }

    /**
     * Название поля
     *
     * @return String
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Весь код ниже специфичен для работы с полями в XML-документе,
     * построенном на основе ODT-шаблона. Вот пусть здесь он и остается
     */
    
    /**
     * Установить значение поля
     *
     * @param CPrintField $field
     * @param CModel $object
     * @param CPrintForm $form
     * @return string
     */
    public function setValue(CPrintField $field, CModel $object, CPrintForm $form, IPrintTemplate $template) {
		$descriptors = $this->descriptors;
		$isStyleField = $this->isStyleField;
		/**
		 * Если поле из шаблона есть в наборе полей,
		 * то вычисляем его. Перед вычислением проверяем,
		 * есть ли привязанные к нему дочерние описатели. Если дочерние
		 * описатели есть, то не вычисляем, так как вычислением дочерних
		 * будет заниматься родительский
		*/
		if (is_null($field->parent)) {
			foreach ($descriptors as $node) {
				$xml = $this->processNode($node, $field, $object, $form);
			}
			/**
			 * Устанавливаем xml и стили odt документа
			 */
			if (!$isStyleField) {
				$template->setDocXML($xml);
			} else {
				$template->setStyleXML($xml);
			}
		}
    }

    /**
     * Удалить из дерева элементов все элементы, у которых
     * локальное имя соответствует указанному
     *
     * @param DOMNode $node
     * @param $name
     * @return DOMNode
     */
    private function removeChildsByName(DOMNode $node, $name) {
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                if ($child->localName == $name) {
                    $node->removeChild($child);
                } else {
                    $this->removeChildsByName($child, $name);
                }
            }
        }
        return $node;
    }

    /**
     * @param DOMNode $node
     * @param $name
     * @return DOMNode
     */
    private function getFirstChildByName(DOMNode $node, $name) {
        foreach ($node->childNodes as $child) {
            if ($child->localName == $name) {
                return $child;
            } else {
                return $this->getFirstChildByName($child, $name);
            }
        }
    }

    /**
     * Ищем внутри указанного DOM-узла все дочерние с указанным
     * именем.
     *
     * @param DOMNode $node
     * @param $name
     * @return array
     */
    private function getElementsByName(DOMNode $node, $name) {
        $res = array();
        foreach ($node->childNodes as $child) {
            /**
             * Поищем на текущем уровне
             */
            if ($child->localName == $name) {
                $res[$node->textContent] = $child;
            }
            /**
             * Поищем на уровне ниже
             */
            if ($child->hasChildNodes()) {
                $new = $this->getElementsByName($child, $name);
                foreach ($new as $item) {
                    $res[$item->textContent] = $item;
                }
            }
        }
        return $res;
    }
    
    /**
     * @param DOMNode $node
     * @param CPrintField $field
     * @param $object
     * @param CPrintForm $form
     * @return string
     */
    private function processNode(DOMNode $node, CPrintField $field, $object, CPrintForm $form) {
        $this->_isDebug = $form->debug == "1";
        $doc = $node->ownerDocument;
        /**
         * Определим поля двух типов: простые и сложные. Простые нужны для
         * вывода информации, сложные для вывода частей документа по шаблону,
         * например, печать билетов - в документе указывается шаблон одного билета,
         * а затем он размножается на все билеты. Простые поля не содержат подполей,
         * это будет первым отличительным признаком, по нему и разделим.
         */
        if ($field->children->getCount() == 0) {
            /**
             * Это простое поле. Простые поля бывают трех типов:
             * 1. Просто вывод текста (по умолчанию)
             * 2. Вывод таблицы
             */
            if ($field->getFieldType() == "text") {
                $debug = array();
                /**
                 * Это вывод просто текста. Мы заменяем DOMNode текстом, который
                 * получается при вычислении данного описателя
                 */
                $parent = $node->parentNode;
                /**
                 * Собираем отладочную инфу
                 */
                $debug[0] = $doc->saveXML($parent);
                /**
                 * Заменяем пользовательское поле просто текстовой нодой
                 */
                // echo $field->alias.", ";
                try {
                    $newNode = $doc->createTextNode($field->evaluateValue($object));
                } catch (Exception $e) {
                    echo "Возникла ошибка ".$e->getMessage()." в поле ".$field->alias;
                }
                $parent->replaceChild($newNode, $node);
                /**
                 * На случай отладки показываем что было до и что стало после
                 */
                if ($this->_isDebug) {
                    $debug[1] = $doc->saveXML($parent);
                    var_dump($debug);
                }
                /**
                 * Сохраняем
                 */
                return $doc->saveXML();
            } elseif ($field->getFieldType() == "table") {
                $debug = array();
                /**
                 * Это вывод таблицы. Описатель поля должен стоять в первой ячейке
                 * нужной таблицы
                 *
                 * Для начала нам нужно определить строку в таблице, в которой стоит
                 * описатель
                 */
                $row = $node->parentNode;
                while ($row->localName !== "table-row") {
                    $row = $row->parentNode;
                }
                /**
                 * Получаем таблицу, к которой привязана эта строка.
                 * Именно с таблицей мы и будем работать. Попутно собираем
                 * отладочную инфу
                 */
                $table = $row->parentNode;
                $debug[0] = $doc->saveXML($table);
                /**
                 * Просматриваем дочерние элементы полученной строки.
                 * Если среди них есть пользовательский описатель поля, то
                 * удаляем его
                 */
                $this->removeChildsByName($row, "user-field-get");
                $debug[1] = $doc->saveXML($table);
                /**
                 * Теперь ищем все ячейке в выбранной строке. Мы будем их клонировать
                 * и заполнять данными из результирующего массива
                 */
                $arr = $field->evaluateValue($object);
                foreach ($arr as $data_row) {
                    $i = 0;
                    /**
                     * Клонируем исходную строку
                     */
                    $newRow = $row->cloneNode(true);
                    foreach ($newRow->childNodes as $newCell) {
                        /**
                         * Получим значение, которое мы должны будем положить в
                         * ячейку новой таблицы
                         */
                        $cellValue = "";
                        if (array_key_exists($i, $data_row)) {
                            $cellValue = $data_row[$i];
                        }
                        /**
                         * В ячейке таблице получем элемент с локальным именем p -
                         * в него и нужно добавлять текст
                         */
                        $p = $this->getFirstChildByName($newCell, "p");
                        if (!is_null($p)) {
                            $cellText = $doc->createTextNode($cellValue);
                            $p->appendChild($cellText);
                        }
                        $i++;
                    }
                    /**
                     * Добавляем новую строку перед исходной, она будет
                     * постепенно двигаться вниз, а затем мы ее удалим
                     */
                    $table->insertBefore($newRow, $row);
                }
                /**
                 * Удаляем из таблицы исходную строку
                 */
                $table->removeChild($row);
                if ($this->_isDebug) {
                    $debug[2] = $doc->saveXML($table);
                    var_dump($debug);
                }
                /**
                 *  Сохраняем
                 */
                return $doc->saveXML();
            }
        } else {
            /**
             * Это место, где вычисляются групповые описатели.
             * Посмотрим-с
             */
            $items = $field->evaluateValue($object);
            /**
             * Сразу отрубаем, если описатель возвращает не тот тип
             * данных. Можно было бы тянуть вторую часть else, но
             * как-то совсем не хочется.
             */
            if (strtoupper(get_class($items)) !== "CARRAYLIST") {
                trigger_error("Групповой описатель items должен возвращать объект типа CArrayList с дочерними элементами, сейчас там ".get_class($items), E_USER_ERROR);
                var_dump($field);
                exit;
            }
            /**
             * Ищем родительский элемент указанного типа, в котором
             * стоит наш групповой описатель. Когда найдем это будет
             * нода, которую будем множить для каждого элемента
             * массива с данными
             */
            $nodeType = $field->parent_node;
            $parentNode = $node->parentNode;
            while ($parentNode->localName !== $nodeType) {
                $parentNode = $parentNode->parentNode;
            }
            /**
             * Теперь для каждого элемента из результатов выполнения
             * родительского описателя будем делать копию родительской
             * ноды, извлекать внедренные в нее описатели и вычислять.
             */
            foreach ($items->getItems() as $item) {
                $debug = array();
                /**
                 * Делаем копию ноды для текущего элемента
                 */
                $newNode = $parentNode->cloneNode(true);
                $debug[0] = $doc->saveXML($newNode);
                /**
                 * Теперь выделяем описатели
                 */
                $childNodes = $this->getElementsByName($newNode, "user-field-get");
                /**
                 * Удаляем из списка описателей родительский описатель,
                 * он там стоял для того, чтобы указывать на начало
                 * блока групповых описателей
                 */
                foreach ($childNodes as $key=>$node) {
                    if ($key == $field->alias) {
                        unset($childNodes[$key]);
                        $node->parentNode->removeChild($node);
                    }
                }
                /**
                 * Теперь вычисляем каждый описатель
                 */
                foreach ($childNodes as $fieldName=>$node) {
                    if (!is_null($form->formset->getFieldByName($fieldName))) {
                        $childField = $form->formset->getFieldByName($fieldName);
                        $this->processNode($node, $childField, $item, $form);
                    }
                }
                $debug[1] = $doc->saveXML($newNode);
                /**
                 * Добавим перед родительской нодой полученную
                 */
                $parentNode->parentNode->insertBefore($newNode, $parentNode);
                /**
                 * Показываем отладочную информацию, если это необходимо
                 */
                if ($this->_isDebug) {
                    var_dump($childNodes);
                    var_dump($debug);
                }
            }
            /**
             * Удаляем из документа исходный узел с шаблоном
             */
            $parentNode->parentNode->removeChild($parentNode);
            /**
             * Сохраняем результаты
             */
            return $doc->saveXML();
        }
    }
}