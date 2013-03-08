<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 17:23
 * Сообщение об ошибке базы данных
 */
class C2DbException extends C2Exception {
    /**
     * @var mixed the error info provided by a PDO exception. This is the same as returned
     * by {@link http://www.php.net/manual/en/pdo.errorinfo.php PDO::errorInfo}.
     * @since 1.1.4
     */
    public $errorInfo;

    /**
     * Constructor.
     * @param string $message PDO error message
     * @param integer $code PDO error code
     * @param mixed $errorInfo PDO error info
     */
    public function __construct($message,$code=0,$errorInfo=null)
    {
        $this->errorInfo=$errorInfo;
        parent::__construct($message,$code);
    }
}
