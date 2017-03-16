<?php
    /**
     * Базовые константы
     */
    define('CORE_DS', DIRECTORY_SEPARATOR);
    define('CORE_CWD', str_replace(CORE_DS."core_constants.php", "", __FILE__));
    define("CACHE_DIR", CORE_CWD.CORE_DS."tmp".CORE_DS."cache");
    define('SMARTY_FOLDER', CORE_CWD.'/_core/_external/smarty');
    define('SMARTY_DIR', SMARTY_FOLDER.'/libs/');
    define('VIEWS_DIR', CORE_CWD.CORE_DS.'_core'.CORE_DS.'_views'.CORE_DS);
    define('TEMPLATES_DIR', CORE_CWD.CORE_DS.'_core'.CORE_DS.'_templates'.CORE_DS);
    define('CORE_ENABLED', true);
    define("JSON_CONTROLLERS_DIR", CORE_CWD.CORE_DS.'_core'.CORE_DS.'_json_controllers'.CORE_DS);
    define('AJAX_VIEW', "_ajax.html.php");
    define('SMARTY_TEMPLATES', VIEWS_DIR);
    define('SMARTY_COMPILE', CORE_CWD.CORE_DS.'tmp'.CORE_DS.'smarty'.CORE_DS);
    define('SMARTY_CACHE', CORE_CWD.CORE_DS.'tmp'.CORE_DS.'smarty'.CORE_DS);
    define("PHPMAILER_DIR", CORE_CWD.'/_core/_external/phpmailer');
    define("PRINT_ENGINE_WORD", CORE_CWD.CORE_DS.'_core'.CORE_DS.'_external'.CORE_DS.'phpword'.CORE_DS);
    define("PRINT_TEMPLATES_DIR", CORE_CWD.CORE_DS.'library'.CORE_DS.'templates'.CORE_DS);
    define("PRINT_DOCUMENTS_DIR", CORE_CWD.CORE_DS.'tmp'.CORE_DS.'print'.CORE_DS);
    define("ZIP_DOCUMENTS_DIR", CORE_CWD.CORE_DS.'tmp'.CORE_DS.'zip'.CORE_DS);
    define("TIMTHUMB_CACHE", CORE_CWD.CORE_DS.'tmp'.CORE_DS.'timthumb'.CORE_DS);