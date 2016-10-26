<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 17.10.16
 * Time: 20:29
 */

/**
 * Шаблон печатной формы
 *
 * Interface IPrintTemplate
 */
interface IPrintTemplate {
	/**
	 * Сохранить шаблон печатной формы
	 *
	 * @param String $filename
	 * @return String
	*/
	public function save($filename);
	/**
	 * Удалить временный файл печатной формы
	 */
	public function deleteTempFile();
    /**
     * Получить поля из шаблона
     *
     * @return IPrintClassField[]
     */
    public function getFields();
}