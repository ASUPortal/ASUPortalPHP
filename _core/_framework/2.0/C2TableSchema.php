<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 16:56
 * Объект для представления метаданных о таблице
 */
class C2TableSchema {
    /**
     * Название таблицы
     *
     * @var string
     */
    public $schemaName;
    /**
     * Название таблицы
     *
     * @var string
     */
    public $name;
    /**
     * Версия названия таблицы с кавычками
     *
     * @var string
     */
    public $rawName;
    /**
     * Первичный ключ таблицы
     *
     * @var string|array
     */
    public $primaryKey;
    /**
     * Название последовательности для генерации первичного ключа.
     * null если не используется
     *
     * @var string
     */
    public $sequenceName;
    /**
     * Внешние ключи в таблице
     *
     * @var array
     */
    public $foreignKeys = array();
    /**
     * Все столбцы из таблицы
     *
     * @var array
     */
    public $columns = array();

    /**
     * Получить объект метаданных о столбце по названию столбца
     * null если не нашлось
     *
     * @param $name
     * @return CDbColumnSchema
     */
    public function getColumn($name) {
        return isset($this->columns[$name]) ? $this->columns[$name] : null;
    }

    /**
     * Массив с названиями всех столбцов
     *
     * @return array
     */
    public function getColumnNames() {
        return array_keys($this->columns);
    }
}
