<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.11.16
 * Time: 13:51
 */

/**
 * Класс для работы с текстовыми полями HTML-документа
 *
 * Class CHtmlTextPrintTemplateField 
 */
class CHtmlTextPrintTemplateField extends CHtmlPrintTemplateField {
	
	/**
	 * Название поля
	 *
	 * @return String
	 */
	public function getName() {
		return $this->_field->alias;
	}
	
	/**
	 * Вычислить значение поля
	 *
	 * @param $object
	 * @return String/Array
	 */
	public function getEvaluateValue($object) {
		return $this->_field->evaluateValue($object);
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