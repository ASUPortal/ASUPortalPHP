<?php
    // <abarmin date="04.05.2012">
    // будьте прокляты все, кто писал этот портал до меня

    // соединялка с базой
    require_once("sql_connect_credentials.php");
    require_once("core_classloader.php");
    /**
     * Перекидываем настройки соединения с БД в константы
     */
    define("DB_HOST", $sql_host);
    define("DB_USER", $sql_login);
    define("DB_PASSWORD", $sql_passw);
    define("DB_DATABASE", $sql_base);
    /**
     * Перекидываем настройки соединения с БД Протокол в константы
     */
    define("LOG_DB_HOST", $sql_stats_host);
    define("LOG_DB_USER", $sql_stats_login);
    define("LOG_DB_PASSWORD", $sql_stats_passw);
    define("LOG_DB_DATABASE", $sql_stats_base);
    define("LOG_TABLE_STATS", "stats");
    /**
     * Конфигурация всего приложения
     */
    $config = array(
        "preload" => array(
            "CArrayList",
            "CBaseController",
            "Smarty",
            "CSetting",
            "CSettingsManager"
        ),
        "components" => array(
            "cache" => array(
                "class" => "CCacheMemcache"
            ),
            "search" => array(
                "class" => "CSolrManager",
                "sources" => array(
                    array(
                        "class" => "CSearchSourceFTP",
                        "id" => "ftp_portal",
                        "server" => "ftp_server",
                        "login" => "ftp_server_user",
                        "password" => "ftp_server_password",
                        "path" => "path_for_indexing_files_from_ftp",
                        "suffix" => "formats_files_for_indexing"
                    ),
                    array(
                        "class" => "CSearchSourceLocal",
                        "id" => "local_files",
                        "path" => "path_for_indexing_files",
                        "suffix" => "formats_files_for_indexing"
                    ),
                    array(
                        "class" => "CSearchSourceSamba",
                        "id" => "samba_na_235"
                    )
                )
	        ),
	        "beans" => array(
	            "class" => "CBeanManager",
	            "cacheDir" => CORE_CWD.CORE_DS.'tmp'.CORE_DS.'beans'.CORE_DS
            )
        ),
        "smarty" => array(
            "cacheEnabled" => false
        )
    );
    /**
     * Особые таксономии
     */
    define("TAXONOMY_DEPARTMENT_ROLES", "department_roles");
    define("TAXONOMY_COMPETENTIONS", "corriculum_competentions");
    define("TAXONOMY_SPECIALITY", "specialities");
    /**
     * Импорт отсальных констант. Там уже можно использовать настройки
     * из базы данных, так как автолоадер уже запущен
     */
    /**
     * Константы пагинатора
     */
    define("PAGINATION_ALL", "all");
    define("PAGINATION_DEFAULT", 20);
    /**
     * Константы для диссертаций
     */
    define("DISSER_DOCTOR", "доктор");
    define("DISSER_PHD", "кандидат");
    define("DISSER_DEGREE", "степень");
    /**
     * Стандартные действия
     */
    define("ACTION_INDEX", "index");
    /**
     * Уровни доступа константами
     */
    define("ACCESS_LEVEL_NO_ACCESS", 0);
    define("ACCESS_LEVEL_READ_OWN_ONLY", 1);
    define("ACCESS_LEVEL_READ_ALL", 2);
    define("ACCESS_LEVEL_WRITE_OWN_ONLY", 3);
    define("ACCESS_LEVEL_WRITE_ALL", 4);
    /**
     * Запуск приложения. Инициализация автозагружаемых классов и кэша
     */
    CApp::createApplication($config)->run();
    // Типы участия на кафедре
    define("TYPE_PPS", "профессорско-преподавательский состав");
    define("TYPE_REVIEWER", "рецензент");
    define("TYPE_REVIEWER_ARCHIVE", "архив рецензент");
    define("USER_TYPE_ADMIN", "администратор");
    define("HEAD_OF_DEPARTMENT", "2");

    // типы связей
    define("RELATION_HAS_ONE", "1");
    define("RELATION_HAS_MANY", "many");
    define("RELATION_COMPUTED", "computed");
    define("RELATION_MANY_TO_MANY", "many_to_many");

    /**
     * Типы полей.
     * Для загрузки файлов и других корректировок
     */
    define("FIELD_UPLOADABLE", "uploadable");
    define("FIELD_MYSQL_DATE", "mysql_date");

    // сообщения об ошибках
    define("ERROR_FIELD_REQUIRED", "Поле \"%name%\" обязательно для заполнения");
    define("ERROR_FIELD_NUMERIC", "Значение поля \"%name%\" должно быть числовым");
    define("ERROR_FIELD_SELECTED", "Значение поля \"%name%\" должно быть выбрано из списка");
    define("ERROR_FIELD_NOT_A_DATE", "Значение в поле \"%name%\" должно быть датой в формате дд.мм.гггг");
    define("ERROR_FIELD_IS_IMAGE", "Значение поля \"%name%\" должно быть изображением");
    define("ERROR_INSUFFICIENT_ACCESS_LEVEL", "У Вас недостаточно прав для доступа к данной задаче");

    // события валидации
    define("VALIDATION_EVENT_READ", "onRead");
    define("VALIDATION_EVENT_UPDATE", "onUpdate");
    define("VALIDATION_EVENT_CREATE", "onCreate");
    define("VALIDATION_EVENT_REMOVE", "onDelete");

    define("WEB_ROOT", CSettingsManager::getSettingValue("web_root"));
    define("ROOT_FOLDER", CSettingsManager::getSettingValue("root_folder"));
    define("PRINT_DOCUMENTS_URL", WEB_ROOT."/tmp/print/");
    define("ZIP_DOCUMENTS_URL", WEB_ROOT."/tmp/zip/");
    define("ADMIN_EMAIL", CSettingsManager::getSettingValue("admin_email"));

    define("APP_DEBUG", true);
    /**
     * Путь к библиотекам jQuery на сервере
     */
    define("JQUERY_UI_JS_PATH", "_core/jquery-ui-1.8.20.custom.min.js");
    define("JQUERY_UI_CSS_PATH", "_core/jUI/jquery-ui-1.8.2.custom.css");
    /**
     * Текущая значковая тема
     */
    if (CSettingsManager::getSettingValue("icon_theme") == "") {
        define("ICON_THEME", "tango");
    } else {
        define("ICON_THEME", CSettingsManager::getSettingValue("icon_theme"));
    }
    /**
     * Адрес подсистемы масштабирования изображений
     */
    define("IMAGE_RESIZER_URL", WEB_ROOT."_modules/_thumbnails/index.php");
    /**
     * Роли пользователей
     */
    define("ROLE_NEWS_ADD", "news_add");
    /**
     * Адрес страницы авторизации
     */
    define("NO_ACCESS_URL", WEB_ROOT."p_administration.php");
    /**
     * Псевдонимы ролей пользователей
     *
     * ROLE_PAGES_ADMIN - администратор пользовательских страниц
     */
    define("ROLE_PAGES_ADMIN", "pages_admin");
    define("ROLE_GRANTS_ADMIN", "grants_admin");
    /**
     * Кастомный обработчик ошибок
     */
    if (APP_DEBUG) {
        set_error_handler("customErrorHandler");
        set_exception_handler("customExceptionHandler");
    }
    function customErrorHandler($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            // Этот код ошибки не включен в error_reporting
            return;
        }

        switch ($errno) {
            case E_USER_ERROR:
                echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
                echo "  Фатальная ошибка в строке $errline файла $errfile";
                echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                echo "Завершение работы...<br />\n";
                exit(1);
                break;

            case E_USER_WARNING:
                echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
                break;

            case E_USER_NOTICE:
                echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
                break;

            default:
                echo "Неизвестная ошибка: [$errno] $errstr<br />\n";
                break;
        }

        echo '<table border="1" cellpadding="0" cellspacing="0" style="font-size: 12px; font-family: verdana; ">';
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $arr) {
            echo '<tr>';
            foreach ($arr as $value) {
                echo '<td>'.$value.'</td>';
            }
            echo '<tr>';
        }
        echo '</table>';
    }
    function customExceptionHandler($exception) {
        echo "Неперехватываемое исключение: " , $exception->getMessage(), "\n";
    }