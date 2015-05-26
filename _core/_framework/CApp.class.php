<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 07.08.12
 * Time: 16:30
 * To change this template use File | Settings | File Templates.
 *
 * @property CCache cache
 */
class CApp extends CComponent{
    private static $_inst = null;
    private $_config = null;
    private $_cache = null;
    private static $_log = array();
    private $_dbLogConnection = null;

    protected $preload = array();
    protected $components = array();
    protected $smarty = null;
    /**
     * @param array $config
     */
    public function __construct(array $config) {
        $this->_config = $config;

        parent::__construct($config);
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
        foreach($this->preload as $p) {
            classAutoload($p);
        }
        // инициализируем соединение с базой, если этого еще не сделано
        global $sql_connect;
        if (is_null($sql_connect)) {
            $sql_connect = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Невозможно установить соединение с БД");
            mysql_select_db(DB_DATABASE, $sql_connect) or die("Невозможно обратиться к базе данных ".DB_DATABASE);
            mysql_query("SET NAMES UTF8", $sql_connect);
        }
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
        $query = new CQuery($this->getDbLogConnection());
        $query->insert(LOG_TABLE_STATS, array(
            "url" => basename($_SERVER["SCRIPT_NAME"]),
            "host_ip" => $_SERVER["REMOTE_ADDR"],
            "port" => $_SERVER["REMOTE_PORT"],
            "agent" => $_SERVER["HTTP_USER_AGENT"],
            "user_name" => (is_null(CSession::getCurrentUser()) ? 0 : CSession::getCurrentUser()->getId()),
            "q_string" => $_SERVER['QUERY_STRING'],
            "referer" => (array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : 0),
            "is_bot" => (CUtils::isHTTPRefererIsBot() ? 1 : 0)
        ));
        $query->execute();

    }

    /**
     * Соединение с рабочей базой портала
     *
     * @return resource
     */
    public function getDbConnection() {
        global $sql_connect;
        return $sql_connect;
    }

    /**
     * Соединение с БД Протокол
     *
     * @return PDO
     */
    public function getDbLogConnection() {
        if (is_null($this->_dbLogConnection)) {
            try {
                $this->_dbLogConnection = new PDO(
                    "mysql:host=".LOG_DB_HOST.";dbname=".LOG_DB_DATABASE,
                    LOG_DB_USER,
                    LOG_DB_PASSWORD,
                    array(
                        PDO::ATTR_PERSISTENT => true,
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
                    )
                );
            } catch (PDOException $e) {
                die("Подключение к БД Протокол не удалось: ".$e->getMessage());
            }
        }
        return $this->_dbLogConnection;
    }
    public function getConfig() {
        return $this->_config;
    }

    /**
     * Автозагрузка компонентов приложений
     *
     * @param $name
     * @return CComponent
     * @throws Exception
     */
    public function __get($name) {
        // пробуем загрузить компонент, к которому пользователь обратился
        if (!array_key_exists($name, $this->components)) {
            throw new Exception("Приложение не поддерживает компонент ".$name);
        }
        // если компонент еще не был инициализирован, то в свойстве лежит
        // массив, а не объект
        if (is_array($this->components[$name])) {
            $config = $this->components[$name];
            $class = $config["class"];
            unset($config["class"]);
            $object = new $class($config);
            $this->components[$name] = $object;
        }
        return $this->components[$name];
    }
}
