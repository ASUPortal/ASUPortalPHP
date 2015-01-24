<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 21.11.12
 * Time: 20:28
 * To change this template use File | Settings | File Templates.
 */
class CPrintController extends CFlowController {
    private $_isDebug = false;

    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление печатью по шаблону");

        parent::__construct();
    }
    public function actionIndex() {
        $this->renderView("_print/index.tpl");
    }
    public function actionPrint() {
        /**
    	 * Получаем обратно параметры контекста из запроса.
    	 * Мы получаем:
    	 * 1. Класс менеджера
    	 * 2. Метод менеджера для получения нужного объекта
    	 * 3. Идентификатор объекта
    	 * 4. Идентификатор печатной формы
    	 */
    	$managerClass = CRequest::getString("manager");
    	$managerMethod = CRequest::getString("method");
    	$objectId = CRequest::getInt("id");
    	$formId = CRequest::getInt("template");
    	/**
    	 * Получаем объект через менеджер
    	 */
    	$object = $managerClass::$managerMethod($objectId);
    	$form = CPrintManager::getForm($formId);
        /**
         * Берем объект анализатора в зависимости от формата шаблона
         */
        $writer = null;
        if ($form->form_format == "docx") {
            $writer = new PHPWord();
        } elseif ($form->form_format = "odt") {
            $writer = new CPHPOdt();
        }
        /**
         * Проверка на отладку. Если в шаблоне включена отладка, то
         * вместо вывода пользователю показываем всякую полезную для разработчика
         * информацию
         */
        $this->_isDebug = $form->debug == "1";
        /**
    	 * Загружаем шаблон
    	 */
    	$wordTemplate = $writer->loadTemplate(PRINT_TEMPLATES_DIR.$form->template_file);
        /**
         * Попробуем получить все описатели из документа, чтобы не думать об их порядке в БД
         */
        $fieldsFromTemplate = $wordTemplate->getFields();
        /**
         * Если включена отладка, то показываем все описатели
         */
        if ($this->_isDebug) {
            var_dump(array_keys($fieldsFromTemplate));
            /**
             * Это место для экспериментов и написания отладочного кода
             */
            $value = array();
            $reviewers = new CArrayList();
            $diploms = new CArrayList();
            /**
             * Здесь групповая печать, ходим кругами
            */
            foreach ($diploms->getItems() as $spec) {
            	/**
            	 * Получаем все дипломы, которые защищаются по выбранной
            	 * специальности
            	 */
            	foreach ($spec->diploms->getItems() as $diplom) {
            		$diploms->add($diplom->getId(), $diplom);
            	}
            	/**
            	 * Теперь собираем всех рецензентов в один массив.
            	 * К каждому рецензенту прицепляем дипломы, которые он
            	 * рецензировал
            	 */
            	foreach ($diploms->getItems() as $diplom) {
            		/**
            		 * Консультант
            		 */
            		if (!is_null($diplom->person)) {
            			$reviewer = $diplom->person;
            			$reviewerArr = new CArrayList();
            			if ($reviewers->hasElement($reviewer->getId())) {
            				$reviewerArr = $reviewers->getItem($reviewer->getId());
            			}
            			$reviewerArr->add($diplom->getId(), $diplom);
            			$reviewers->add($reviewer->getId(), $reviewerArr);
            		}
            	}
            }
            /**
             * Теперь выводим это в окончательный массив
             */
            $reviewerIndex = 0;
            foreach ($reviewers->getItems() as $reviewerId=>$diploms) {
            	$reviewerIndex++;
            	$isFirst = true;
            	foreach ($diploms->getItems() as $diplom) {
            		$dataRow = array();
            		/**
            		 * Для начала заполним результирующий массив пустыми строками
            		*/
            		for ($i = 0; $i <= 4; $i++) {
            			$dataRow[$i] = "";
            		}
            		/**
            		 * Фамилия и инициалы дипломника
            		 */
            		$dataRow[0] = "";
            		$dataRow[1] = "";
            		if (!is_null($diplom->student)) {
            			$student = $diplom->student;
            			$nv = "";
            			/**
            			 * ФИО
            			 */
            			$nv = $student->getName();
            			$dataRow[1] = $nv;
            		}
            		$dataRow[2] = "";
            		if (!is_null($diplom->student)) {
            			if (!is_null($diplom->student->getGroup())) {
            			$group = $diplom->student->getGroup()->getName();
            			$dataRow[2] = $group;
            			}
            		}
            		$dataRow[3] = "";
					$theme = $diplom->dipl_name;
					$dataRow[3] = $theme;
					$dataRow[4] = "";
					$prepod = CStaffManager::getPerson($reviewerId);
					if (!is_null($prepod)) {
						$nv = "";
						$nv = $prepod->getName();
						/**
						 * Степень
						*/
						if (!is_null($prepod->degree)) {
							$nv .= ", ".$prepod->degree->getValue();
						}
						/**
						 * Звание
						 */
						if (!is_null($prepod->title)) {
							$nv .= ", ".$prepod->title->getValue();
						}
						$dataRow[4] = $nv;
					}
            		$value[] = $dataRow;
            	}
            }
            $value = "_____";
            $plan = CIndPlanManager::getLoad(CRequest::getInt("plan"));
            if (!is_null($plan->year)) {
                $value = $plan->year->name;
            }

            var_dump($plan->year->name);
            // $this->debugTable($value);
        }
        /**
         * Еще один вариант. Надеюсь, этот заработает нормально
         */
        foreach ($fieldsFromTemplate as $fieldName=>$descriptors) {
            /**
             * Если поле из шаблона есть в наборе полей,
             * то вычисляем его. Перед вычислением проверяем,
             * есть ли привязанные к нему дочерние описатели. Если дочерние
             * описатели есть, то не вычисляем, так как вычислением дочерних
             * будет заниматься родительский
             */
            if (!is_null($form->formset->getFieldByName($fieldName))) {
                $field = $form->formset->getFieldByName($fieldName);
                if (is_null($field->parent)) {
                    foreach ($descriptors as $node) {
                        $xml = $this->processNode($node, $field, $object, $form);
                    }
                }
            }
        }
        $wordTemplate->setDocXML($xml);
        /**
         * Теперь стили, они отдельно обрабатываются
         */
        $fieldsFromTemplate = $wordTemplate->getStyleFields();
        if ($this->_isDebug) {
        	var_dump(array_keys($fieldsFromTemplate));
        }        
        $xml = "";
        foreach ($fieldsFromTemplate as $fieldName=>$descriptors) {
        	/**
        	 * Если поле из шаблона есть в наборе полей,
        	 * то вычисляем его. Перед вычислением проверяем,
        	 * есть ли привязанные к нему дочерние описатели. Если дочерние
        	 * описатели есть, то не вычисляем, так как вычислением дочерних
        	 * будет заниматься родительский
        	 */
        	if (!is_null($form->formset->getFieldByName($fieldName))) {
        		$field = $form->formset->getFieldByName($fieldName);
        		if (is_null($field->parent)) {
        			foreach ($descriptors as $node) {
        				$xml = $this->processNode($node, $field, $object, $form);
        			}
        		}
        	}
        }   
		if (count($fieldsFromTemplate) == 0) {
			$xml = $wordTemplate->getStyleXML();
		}
        $wordTemplate->setStyleXML($xml);     
        /**
         * При отладке не выгружаем обратно документ
         */
        if ($this->_isDebug) {
            exit;
        }
        /**
         * Сохраняем документ
         */
        $filename = date("dmY_Hns")."_".$form->template_file;
        $i = 0;
        while (file_exists(PRINT_DOCUMENTS_DIR.$filename)) {
            $filename = date("dmY_Hns")."_".$i."_".$form->template_file;
            $i++;
        }
        $wordTemplate->save(PRINT_DOCUMENTS_DIR.$filename);
        /**
         * Отдаем документ пользователю
         * Не отдаем, если у нас тут групповая печать
         */
        if (CRequest::getInt("noredirect") == "1") {
            echo json_encode(array(
                "filename" => PRINT_DOCUMENTS_DIR.$filename,
                "url" => PRINT_DOCUMENTS_DIR.$filename
            ));
        } else {
            $this->redirect(PRINT_DOCUMENTS_URL.$filename);
        }
    }
    private function processNode(DOMNode $node, CPrintField $field, $object, CPrintForm $form) {
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
            if ($field->type_id == "1" || $field->type_id == "0") {
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
            } elseif ($field->type_id == "2") {
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
    public function actionShowForms() {
        $formsetName = CRequest::getString("template");
        $formset = CPrintManager::getFormset($formsetName);
        if (!is_null($formset)) {
            $forms = new CArrayList();
            foreach ($formset->forms->getItems() as $form) {
                $forms->add($form->getId(), $form->title);
            }
            $this->showPickList($forms, get_class($this), "PrePrintWithTemplate");
        }
    }
    public function actionPrePrintWithTemplate() {
        if ($this->getSelectedInPickListDialog()->getCount() == 0) {
            return true;
        }
        $selectedForm = CPrintManager::getForm($this->getSelectedInPickListDialog()->getFirstItem());
        if (!is_null($selectedForm)) {
            /**
             * Если это форма без диалога параметров, то просто перекинем
             * пользователя на страницу генерации документа
             */
            if ($selectedForm->properties_show_dialog != "1") {
                $formset = $selectedForm->formset;
                $variables = $formset->computeTemplateVariables();
                /*
                $url = WEB_ROOT."_modules/_print/?action=print".
                    "&manager=".$variables['manager'].
                    "&method=".$variables['method'].
                    "&id=".$variables['id'].
                    "&template=".$selectedForm->getId();
                    */
                $url = WEB_ROOT."_modules/_print/?action=print".
                        "&template=".$selectedForm->getId();
                foreach (self::getStatefullBean()->getItems() as $key=>$value) {
                    $url .= "&".$key."=".$value;
                }
                $this->redirect($url);
            } else {
                // тут с диалогом, передадим ему управление
                self::getStatefullBean()->add("template", $selectedForm->getId());
                $this->redirectNextAction($selectedForm->properties_controller, $selectedForm->properties_method);
            }
        }
    }
    public function actionPrintWithBeanData() {
        $url = WEB_ROOT."_modules/_print/?action=print";
        foreach (self::getStatefullBean()->getItems() as $key=>$value) {
            $url .= "&".$key."=".$value;
        }
        $this->redirect($url);
    }
}
