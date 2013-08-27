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
     * Вся работа с БД отсюда уехала в CApp->run()
     * Статистика из stat.php уехала туда же
     */

    if (!isset($files_path)) {
        $files_path = "";
    }
    require_once($files_path."setup.php");
    require ($files_path.'funcs_php.php');