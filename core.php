<?php
    // <abarmin date="04.05.2012">
    // будьте прокляты все, кто писал этот портал до меня

    // соединялка с базой
    require_once("sql_connect_credentials.php");
    /**
     * Базовые константы
     */
    define('CORE_DS', DIRECTORY_SEPARATOR);
    define('CORE_CWD', str_replace(CORE_DS."core.php", "", __FILE__));
    define('SMARTY_FOLDER', CORE_CWD.'/_core/_external/smarty');
    define('SMARTY_DIR', SMARTY_FOLDER.'/libs/');
    define('VIEWS_DIR', CORE_CWD.CORE_DS.'_core'.CORE_DS.'_views'.CORE_DS);
    define('TEMPLATES_DIR', CORE_CWD.CORE_DS.'_core'.CORE_DS.'_templates'.CORE_DS);
    define('CORE_ENABLED', true);
    define("JSON_CONTROLLERS_DIR", CORE_CWD.CORE_DS.'_core'.CORE_DS.'_json_controllers'.CORE_DS);
    define('AJAX_VIEW', "_ajax.html.php");
    define('SMARTY_TEMPLATES', VIEWS_DIR);
    define('SMARTY_COMPILE', SMARTY_FOLDER.'/compile/');
    define('SMARTY_CACHE', SMARTY_FOLDER.'/cache/');
    define("PHPMAILER_DIR", CORE_CWD.'/_core/_external/phpmailer');
    define("PRINT_ENGINE_WORD", CORE_CWD.CORE_DS.'_core'.CORE_DS.'_external'.CORE_DS.'phpword'.CORE_DS);
    define("PRINT_TEMPLATES_DIR", CORE_CWD.CORE_DS.'library'.CORE_DS.'templates'.CORE_DS);
    /**
     * Перекидываем настройки соединения с БД в константы.
     */
    define("DB_HOST", $sql_host);
    define("DB_USER", $sql_login);
    define("DB_PASSWORD", $sql_passw);
    define("DB_DATABASE", $sql_base);
    /**
     * Директории, из которых выполняется автолоад классов
     * и выполняется поиск
     */
    $import = array(
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_widgets'.CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_interfaces'.CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_controllers'.CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_framework'.CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_mailer'.CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."help".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."corriculum".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."menu".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."staff".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."taxonomy".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."calendar".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."news".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."state_examination".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."state_attestation".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."rating".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."examination".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."print".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."gradebook".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."acl".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."dashboard".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."configuration".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."library".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."grant".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."page".CORE_DS,
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models'.CORE_DS."mail".CORE_DS,
        SMARTY_DIR,
        PHPMAILER_DIR,
        PRINT_ENGINE_WORD
    );
    /**
     * Конфигурация всего приложения
     */
    $config = array(
        "preload" => array(
            "CArrayList",
            "CBaseController",
            "Smarty",
            "CSetting",
            "CSettingManager"
        ),
        "cache" => array(
            "class" => "CCacheDummy",
            "cacheDir" => CORE_CWD.CORE_DS.'_core'.CORE_DS.'_cache'.CORE_DS,
            "timeout" => 360
        ),
        "smarty" => array(
            "cacheEnabled" => false
        )
    );
    /**
     * Автолоадер
     */
    foreach ($import as $i) {
        set_include_path(get_include_path().PATH_SEPARATOR.$i);
    }
    set_include_path(get_include_path().PATH_SEPARATOR.JSON_CONTROLLERS_DIR);
    spl_autoload_extensions(".class.php");
    spl_autoload_register('classAutoload');

    function classAutoload($className) {
        $hasFile = false;
        foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
            if (file_exists($path.$className.".class.php")) {
                $hasFile = true;
                require_once($className.".class.php");
                break;
            }
        }
        if (!$hasFile) {
            // логгируем ошибку, выходим.
            // пока оставим так
        }
    }
    /**
     * Запуск приложения. Инициализация автозагружаемых классов и кэша
     */
    CApp::createApplication($config)->run();
    /**
     * Импорт отсальных констант. Там уже можно использовать настройки
     * из базы данных, так как автолоадер уже запущен
     */
    // Таблицы
    define("TABLE_RESOURCES", "pl_resources");
    define("TABLE_CALENDARS", "pl_calendars");
    define("TABLE_PERSON", "kadri");
    define("TABLE_USERS", "users");
    define("TABLE_EVENT_MEMBERSHIP", "pl_event_membership");
    define("TABLE_EVENTS", "pl_events");
    define("TABLE_POSTS", "dolgnost");
    define("TABLE_TYPES", "person_types");
    define("TABLE_MENUS", "menu");
    define("TABLE_MENU_ITEMS", "menu_items");
    define("TABLE_MENU_ITEMS_ACCESS", "menu_items_access");
    define("TABLE_SEB_QUESTIONS", "seb_question");
    define("TABLE_SEB_TICKETS", "seb_ticket");
    define("TABLE_DISCIPLINES", "subjects");
    define("TABLE_SPECIALITIES", "specialities");
    define("TABLE_DEPARTMENT_PROTOCOLS", "protocols");
    define("TABLE_YEARS", "time_intervals");
    define("TABLE_YEAR_PARTS", "time_parts");
    define("TABLE_SEB_QUSTIONS_IN_TICKETS", "seb_question_in_ticket");
    define("TABLE_STUDENT_GROUPS", "study_groups");
    define("TABLE_STUDENTS", "students");
    define("TABLE_DIPLOMS", "diploms");
    define("TABLE_DIPLOM_PREVIEWS", "diplom_previews");
    define("TABLE_DIPLOM_CONFIRMATIONS", "diplom_confirms");
    define("TABLE_DIPLOM_PREVIEW_COMISSIONS", "diplom_preview_committees");
    define("TABLE_PRACTICE_PLACES", "pract_places");
    define("TABLE_LANGUAGES", "language");
    define("TABLE_MARKS", "study_marks");
    define("TABLE_SEB_PROTOCOLS", "seb_protocol");
    define("TABLE_SEB_PROTOCOL_MEMBERS", "seb_protocol_members");
    define("TABLE_USER_GROUPS", "user_groups");
    define("TABLE_USER_IN_GROUPS", "user_in_group");
    define("TABLE_USER_GROUPS_HIERARCHY", "user_groups_hierarchy");
    define("TABLE_USER_ROLES", "tasks");
    define("TABLE_USER_GROUP_HAS_ROLES", "task_in_group");
    define("TABLE_USER_HAS_ROLES", "task_in_user");
    define("TABLE_CORRICULUMS", "pl_corriculum");
    define("TABLE_CORRICULUM_DISCIPLINES", "pl_corriculum_disciplines");
    define("TABLE_CORRICULUM_DISCIPLINE_LABORS", "pl_corriculum_discipline_labors");
    define("TABLE_CORRICULUM_DISCIPLINE_CONTROLS", "pl_corriculum_discipline_controls");
    define("TABLE_CORRICULUM_DISCIPLINE_HOURS", "pl_corriculum_discipline_hours");
    define("TABLE_CORRICULUM_CYCLES", "pl_corriculum_cycles");
    define("TABLE_CORRICULUM_PRACTICES", "pl_corriculum_practices");
    define("TABLE_TAXONOMY", "taxonomy");
    define("TABLE_TAXONOMY_TERMS", "taxonomy_terms");
    define("TABLE_EDUCATION_FORMS", "study_forms");
    define("TABLE_RATING_INDEXES", "rating_index");
    define("TABLE_PERSON_RATINGS", "rating_person_indexes");
    define("TABLE_PASSWORD_RECOVERY_REQUESTS", "user_password_requests");
    define("TABLE_NOTIFICATION_TEMPLATES", "template_notification");
    define("TABLE_STAFF_ORDERS", "orders");
    define("TABLE_PUBLICATION_BY_PERSONS", "works");
    define("TABLE_PUBLICATIONS", "izdan");
    define("TABLE_TITLES", "zvanie");
    define("TABLE_SETTINGS", "settings");
    define("TABLE_RATING_INDEX_VALUES", "rating_index_value");
    define("TABLE_HELP", "help");
    define("TABLE_DEGREES", "stepen");
    define("TABLE_PERSON_DISSER", "disser");
    define("TABLE_EXAMINATION_QUESTIONS", "questions");
    define("TABLE_EXAMINATION_TICKETS", "questions_tickets");
    define("TABLE_EXAMINATION_QUESTIONS_IN_TICKETS", "questions_tickets_questions");
    define("TABLE_ORDER_TYPES", "order_type");
    define("TABLE_ORDER_MONEY_TYPES", "order_type_money");
    define("TABLE_PRINT_FORMSETS", "print_formset");
    define("TABLE_PRINT_FORMS", "print_form");
    define("TABLE_PRINT_FIELDS", "print_field");
    define("TABLE_STUDENTS_ACTIVITY", "study_activity");
    define("TABLE_STUDENTS_CONTROL_TYPES", "study_act");
    define("TABLE_GRADEBOOKS", "study_gradebook");
    define("TABLE_DASHBOARD", "dashboard");
    define("TABLE_USER_SETTINGS", "user_settings");
    define("TABLE_MESSAGES", "mails");
    define("TABLE_ACL_TABLES", "acl_tables");
    define("TABLE_ACL_DEFAULTS", "acl_defaults");
    define("TABLE_GENDERS", "pol");
    define("TABLE_USATU_ORDERS", "orders_dep");
    define("TABLE_USATU_ORDER_TYPES", "orders_dep_type");
    define("TABLE_NEWS", "news");
    define("TABLE_LIBRARY_DOCUMENTS", "documents");
    define("TABLE_LIBRARY_FILES", "files");
    define("TABLE_GRANTS", "grants");
    define("TABLE_GRANT_MEMBERS", "grant_members");
    define("TABLE_GRANT_ATTACHMENTS", "grant_attachments");
    define("TABLE_GRANT_PERIODS", "grant_periods");
    define("TABLE_GRANT_MONEY", "grant_money");
    define("TABLE_GRANT_EVENTS", "grant_events");
    define("TABLE_TAXONOMIES_LEGACY", "sprav_links");
    define("TABLE_PAGES", "pg_uploads");
    define("TABLE_SUBSCRIPTIONS", "subscriptions");
    define("TABLE_PERSON_CHILDREN", "kadri_childs");
    define("TABLE_PERSON_BY_TYPES", "kadri_in_ptypes");
    define("TABLE_PERSON_DIPLOMS", "obrazov");
    define("TABLE_PERSON_COURCES", "courses");
    define("TABLE_SAB_COMMISSIONS", "sab_commission");
    define("TABLE_SAB_COMMISSION_MEMBERS", "sab_commission_members");
    define("TABLE_SAB_COMMISSION_DIPLOMS", "sab_commission_diploms");
    define("TABLE_SAB_PERSON_ORDERS", "sab_person_orders");

    // суффиксы таблиц доступа
    define("ACL_ENTRIES", "_access_entries");
    define("ACL_USERS", "_access_users");
    define("ACL_ENTRY_USER", "1");
    define("ACL_ENTRY_GROUP", "2");
    define("ACL_LEVEL_READER", "1");
    define("ACL_LEVEL_AUTHOR", "2");

    // Типы участия на кафедре
    define("TYPE_PPS", "профессорско-преподавательский состав");
    define("TYPE_REVIEWER", "рецензент");
    define("TYPE_REVIEWER_ARCHIVE", "архив рецензент");
    define("USER_TYPE_ADMIN", "администратор");

    // типы связей
    define("RELATION_HAS_ONE", "1");
    define("RELATION_HAS_MANY", "many");
    define("RELATION_COMPUTED", "computed");
    define("RELATION_MANY_TO_MANY", "many_to_many");

    // типы полей
    define("FIELD_UPLOADABLE", "uploadable");

    // сообщения об ошибках
    define("ERROR_FIELD_REQUIRED", "Поле \"%name%\" обязательно для заполнения");
    define("ERROR_FIELD_NUMERIC", "Значение поля \"%name%\" должно быть числовым");
    define("ERROR_FIELD_SELECTED", "Значение поля \"%name%\" должно быть выбрано из списка");

    define("WEB_ROOT", CSettingsManager::getSettingValue("web_root"));
    define("ROOT_FOLDER", CSettingsManager::getSettingValue("root_folder"));
    define("PRINT_DOCUMENTS_URL", WEB_ROOT."/library/templates/");
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
    define("NO_ACCESS_URL", WEB_ROOT."/p_administration.php");
    /**
     * Псевдонимы ролей пользователей
     *
     * ROLE_PAGES_ADMIN - администратор пользовательских страниц
     */
    define("ROLE_PAGES_ADMIN", "pages_admin");
    define("ROLE_GRANTS_ADMIN", "grants_admin");