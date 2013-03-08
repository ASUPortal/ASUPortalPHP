<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 05.05.12
 * Time: 15:21
 * To change this template use File | Settings | File Templates.
 */
class CLog {
    private static $_records;
    private static $_queryTime = 0;
    /**
     * @return CArrayList
     */
    private static function getRecords() {
        if (is_null(self::$_records)) {
            self::$_records = new CArrayList();
        }
        return self::$_records;
    }

    /**
     * Нужно для учета времени выполнения запрсосов
     *
     * @param $time
     */
    public static function addQueryTime($time) {
        self::$_queryTime = self::$_queryTime + $time;
    }
    /**
     * Добавление записи в лог
     * 
     * @param $event
     */
    public static function writeToLog($event) {
        self::getRecords()->add(self::getRecords()->getCount(), $event);
    }
    /**
     * Вывод содержимого лока
     */
    public static function dump($showQueries = false) {
        if ($showQueries) {
            echo '<ul>';
            foreach(self::getRecords()->getItems() as $i) {
                echo '<li>'.$i.'</li>';
            }
            echo '</ul>';
        }
        echo "<br>";
        echo "Использование памяти: ".(memory_get_usage() / 1048576)."Mb";
        echo "<br>";
        echo "Выполнение запросов: ".self::$_queryTime." c. (".self::getRecords()->getCount().")";
    }
}
