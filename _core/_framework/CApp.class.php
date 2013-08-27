<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 07.08.12
 * Time: 16:30
 * To change this template use File | Settings | File Templates.
 */
class CApp {
    private static $_inst = null;
    private $_config = null;
    private $_cache = null;
    private static $_log = array();
    private $_dbLegacyConnection = null;
    private $_dbConnection = null;
    private $_dbLogConnection = null;
    /**
     * @param array $config
     */
    private function __construct(array $config) {
        $this->_config = $config;
    }
    /**
     * Синглтон приложения
     *
     * @static
     * @param array $config
     * @return CApp
     */
    public static function createApplication(array $config) {
        if (is_null(self::$_inst)) {
            self::$_inst = new CApp($config);
        }
        return self::$_inst;
    }
    /**
     * @static
     * @return CApp
     */
    public static function getApp() {
        return self::$_inst;
    }
    public function run() {
        // @todo перенести код из конструктора базового контроллера сюда
        // прелоад классов
        if (array_key_exists("preload", $this->_config)) {
            $preload = $this->_config["preload"];
            foreach($preload as $p) {
                classAutoload($p);
            }
        }
        /**
         * Соединение с базой данных в режиме совместимости.
         * Используется для работы старого портала
         */
        $this->_dbLegacyConnection = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Невозможно установить соединение с БД");
        mysql_select_db(DB_DATABASE) or die("Невозможно обратиться к базе данных ".DB_DATABASE);
        mysql_query("SET NAMES UTF8");
        mysql_query('SET SQL_LOG_BIN =1');
        // внутренняя кодировка приложений UTF-8
        mb_internal_encoding("UTF-8");
        /**
         * Стартуем сессию. Еще она стартует в sql_connect.php, но здесь при случае тоже может
         */
        if (!isset($_SESSION)) {
            session_start();
        }
        /**
         * Считаем разнообразную статистику
         */
        $this->logUserActivity();
    }

    /**
     * Протоколирование действий пользователя
     */
    private function logUserActivity() {

    }

    /**
     * Соединение с рабочей базой портала
     *
     * @return null|PDO
     */
    public function getDbConnection() {
        if (is_null($this->_dbConnection)) {
            $this->_dbConnection = new PDO(
                "mysql:host=".$sql_host.";dbname=".$sql_base,
                $sql_login,
                $sql_passw,
                array(
                    PDO::ATTR_PERSISTENT => true
                )
            );
        }
        return $this->_dbConnection;
    }

    /**
     * Соединение с БД Протокол
     *
     * @return null|PDO
     */
    private function getDbLogConnection() {
        if (is_null($this->_dbLogConnection)) {
            $this->_dbLogConnection = new PDO(
                "mysql:host=".$sql_stats_host.";dbname=".$sql_stats_base,
                $sql_stats_login,
                $sql_stats_passw,
                array(
                    PDO::ATTR_PERSISTENT => true
                )
            );
        }
        return $this->_dbLogConnection;
    }
    /**
     * Работа с кешем
     *
     * @return CCache
     */
    public function getCache() {
        if (is_null($this->_cache)) {
            if (array_key_exists("cache", $this->_config)) {
                $cacheConfig = $this->_config["cache"];
                $cacheClass = $cacheConfig["class"];
                $this->_cache = new $cacheClass($cacheConfig);
            }
        }
        return $this->_cache;
    }
    public function getConfig() {
        return $this->_config;
    }
}
