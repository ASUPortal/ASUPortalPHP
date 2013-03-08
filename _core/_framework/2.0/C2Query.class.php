<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 04.01.13
 * Time: 15:56
 *
 * Вторая версия объекта-запроса. С поддержкой транзакционности.
 */
class C2Query {
    public function __construct() {

    }

    /**
     * Запрос, заданный пользователем
     *
     * @param string $queryString
     * @return C2QueryCustom
     */
    public function query($queryString = ""){
        $query = new C2QueryCustom($this);
        $query->setQueryString($queryString);
        return $query;
    }

    /**
     * Запрос на выборку данных
     *
     * @param $fields
     * @param $table
     * @param string $alias
     * @return C2QuerySelect
     */
    public function select($fields, $table, $alias = "") {
        $query = new C2QuerySelect($this, $fields, $table, $alias);
        return $query;
    }
}
