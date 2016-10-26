<?php

/**
 * Класс для работы с полями DOCX-документа
 *
 * Class CDocxPrintTemplateField
 */
class CDocxPrintTemplateField implements IPrintTemplateField {
	/**
	 * Название поля
	 *
	 * @return String
	 */
	public function getName();
	
	/**
	 * Установить значение поля
	 *
	 * @param CPrintField $field
	 * @param CModel $object
	 * @param CPrintForm $form
	 * @param IPrintTemplate $template
	 * @return string
	*/
	public function setValue(CPrintField $field, CModel $object, CPrintForm $form, IPrintTemplate $template);
}