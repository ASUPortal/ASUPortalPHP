<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.11.16
 * Time: 13:51
 */

/**
 * Класс для работы с табличными полями HTML-документа
 *
 * Class CHtmlTablePrintTemplateField 
 */
class CHtmlTablePrintTemplateField extends CHtmlPrintTemplateField {
	private $_object;
	
	/**
	 * Название поля
	 *
	 * @return String
	 */
	public function getName() {
		return $this->_field->alias;
	}
	
	/**
	 * Объект описателя
	 *
	 * @return $field
	 */
	public function getField() {
		$object = $this->_object;
		/**
		 * Обрабатываем описатели из базы данных
		 * @var CPrintField $field
		 */
		if (!is_null(CPrintManager::getField($this->getName()))) {
			$field = CPrintManager::getField($this->getName());
		}
		/**
		 * Получаем описатели-классы
		 * @var IPrintClassField $field
		 */
		if (!is_null(CPrintManager::getPrintClassField($this->getName(), $object))) {
			$field = CPrintManager::getPrintClassField($this->getName(), $object);
		}
		return $field;
	}
	
	/**
	 * Вычислить значение поля
	 *
	 * @param $object
	 * @return String/Array
	 */
	public function getEvaluateValue($object) {
		$this->_object = $object;
		$field = $this->getField();
		$arr = $this->_field->evaluateValue($object);
		$evaluate = "";
		foreach ($arr as $items) {
			for ($i=0; $i<=count($arr[0])-1; $i++) {
				$evaluate .= '<td colspan="'.$field->getColSpan().'" rowspan="'.$field->getRowSpan().'" align="left" style="border: 1px solid #000000; padding: 0.1cm"><p class="western" align="left" style="page-break-before: auto">';
				$evaluate .= $items[$i].'</p></td>';
			}
			$evaluate .= '</tr>';
		}
		return $evaluate;
	}
	
	/**
	 * Установить значение поля
	 * 
	 * @param String $value
	 * @param IPrintTemplate $template
	 */
	public function setValue($value, IPrintTemplate $template) {
		/**
		 * Получаем название описателя в шаблоне
		 */
		$fieldAlias = $this->getName();
		/**
		 * Получаем временный файл
		 */
		$tempFile = $template->_tempFileName;
		/**
		 * Открываем временный файл для получения текущего содержимого
		 */
		$current = file_get_contents($tempFile);
		/**
		 * Заменяем найденные названия описателей в файле на результат вычисления описателя
		 */
		$bodytag = str_replace($fieldAlias, $value, $current);
		/**
		 * Пишем изменения во временный файл
		*/
		file_put_contents($tempFile, $bodytag);
	}
}