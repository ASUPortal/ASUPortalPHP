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
    private $_dbConnection = null;
    private static $_log = array();
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
        // инициализируем соединение с базой, если этого еще не сделано
        global $sql_connect;
        if (is_null($sql_connect)) {
            $sql_connect = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Невозможно установить соединение с БД");
            mysql_select_db(DB_DATABASE) or die("Невозможно обратиться к базе данных ".DB_DATABASE);
            mysql_query("SET NAMES UTF8");
        }
        // внутренняя кодировка приложений UTF-8
        mb_internal_encoding("UTF-8");
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

    /**
     * Соединение с базой данных
     *
     * @return C2DbConnection
     */
    public function getDb() {
        if (is_null($this->_dbConnection)) {
            $this->_dbConnection = new C2DbConnection(DB_HOST, DB_USER, DB_PASSWORD);
        }
        return $this->_dbConnection;
    }

    /**
     * @param $category
     * @param $message
     * @param array $params
     * @param null $source
     * @param null $language
     * @return string
     */
    public static function t($category,$message,$params=array(),$source=null,$language=null) {
        return $params!==array() ? strtr($message,$params) : $message;
    }
    public static function trace($msg,$category='application') {
        if(APP_DEBUG)
            self::log($msg,$category);
    }
    public static function log($msg, $category) {
        self::$_log[] = array(
            "message" => $msg,
            "category" => $category
        );
    }
}
