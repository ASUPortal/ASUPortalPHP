<?php
    /**
     * Переделано.
     *
     * Здесь теперь хранятся только настройки для подключения к MySQL, остальные настройки
     * хранятся в таблице settings.
     */
    require_once("sql_connect_credentials.php");

    /**
     * Стартуем сессию. Для совместимости между версиями портала она стартуется здесь.
     * Хотя и раньше она здесь же стартовала
     */
    if (!isset($_SESSION)) {
        session_start();
    }

    /**
     * Соединяемся с БД. На всякий случай соединение сохраняем в отдельной
     * глобальной переменной, чтобы можно было из всех частей портала работать
     * с одни и тем же соединением
     */
    global $sql_connect;
    $sql_connect = mysql_connect($sql_host,$sql_login,$sql_passw);
    if(!$sql_connect) {
        echo '<div class=main>Не могу соединиться с сервером Базы Данных. <font color=red>Дальнейшая работа невозможна.</font></div>';
        exit();
    }

    if(!mysql_select_db($sql_base)) {
        echo '<div class=main>Не могу выбрать базу данных портала. <font color=red>Дальнейшая работа невозможна.</font></div>';
        exit();
    }
    mysql_query("SET NAMES utf8");
    mysql_query('SET SQL_LOG_BIN =1');
    if (!isset($files_path)) {
        $files_path = "";
    }
    //не считаем статистику запросов jQuery
    if (!isset($_GET['hidejq'])) {
        require ($files_path.'stats.php');
    }
    require_once($files_path."setup.php");
    require ($files_path.'funcs_php.php');