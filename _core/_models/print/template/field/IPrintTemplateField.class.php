<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:47
 */

/**
 * Поле шаблона печатной формы
 *
 * Interface IPrintTemplateField
 */
interface IPrintTemplateField {
    /**
     * Название поля
     *
     * @return String
     */
    public function getName();
    
	/**
	 * Вычислить значение поля
	 *
	 * @param $object
	 * @return String/Array
	 */
	public function getEvaluateValue($object);

	/**
	 * Установить значение поля
	 *
	 * @param String $value
	 * @param IPrintTemplate $template
	 */
	public function setValue($value, IPrintTemplate $template);
}