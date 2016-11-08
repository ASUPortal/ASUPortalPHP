<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:50
 */

/**
 * Класс для работы с полями HTML-документа
 *
 * Class CHtmlPrintTemplateField
 */
class CHtmlPrintTemplateField implements IPrintTemplateField {
	private $name;
	
	function __construct($name) {
		$this->name = $name;
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
	 * Установить значение поля
	 *
	 * @param CPrintField $field
	 * @param CModel $object
	 * @param CPrintForm $form
	 * @param IPrintTemplate $template
	 * @return string
	*/
	public function setValue(CPrintField $field, CModel $object, CPrintForm $form, IPrintTemplate $template) {
		/**
		 * Получаем название описателя в шаблоне
		 */
		$fieldAlias = $field->alias;
		/**
		 * Получаем временный файл
		 */
		$tempFile = $template->_tempFileName;
		/**
		 * Открываем временный файл для получения текущего содержимого
		 */
		$current = file_get_contents($tempFile);
		/**
		 * Обрабатываем описатели
		 */
		if ($field->getFieldType() == "text") {
			/**
			 * Вычисляем текстовый описатель
			 */
			$evaluate = $field->evaluateValue($object);
			/**
			 * Заменяем найденные названия описателей в файле на результат вычисления описателя
			*/
			$bodytag = str_replace($fieldAlias, $evaluate, $current);
			/**
			 * Пишем изменения во временный файл
			*/
			file_put_contents($tempFile, $bodytag);
		} elseif ($field->getFieldType() == "table") {
			/**
			 * Вычисляем табличный описатель
			 */
			$arr = $field->evaluateValue($object);
			$evaluate = "";
			foreach ($arr as $items) {
				for ($i=0; $i<=count($arr[0])-1; $i++) {
					if (mb_strpos($fieldAlias, "CWorkPlanSection") !== false and mb_strpos($fieldAlias, "StudyTypes") === false) {
						$evaluate .= '<td colspan="5" align="left" style="border: 1px solid #000000; padding: 0.1cm">
							    		<p class="western" align="left" style="page-break-before: auto">';
					} else {
						$evaluate .= '<td align="left" style="border: 1px solid #000000; padding: 0.1cm">
							    		<p class="western" align="left" style="page-break-before: auto">';
					}
					$evaluate .= $items[$i].'</p></td>';
				}
				$evaluate .= '</tr>';
			}
			/**
			 * Заменяем найденные названия описателей в файле на результат вычисления описателя
			 */
			$bodytag = str_replace($fieldAlias, $evaluate, $current);
			/**
			 * Пишем изменения в файл
			*/
			file_put_contents($tempFile, $bodytag);
		}
	}
}