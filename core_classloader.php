<?php
    require_once("core_constants.php");
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
        CORE_CWD.CORE_DS.'_core'.CORE_DS.'_external'.CORE_DS.'phpmailer'.CORE_DS,
        SMARTY_DIR,
        PHPMAILER_DIR,
        PRINT_ENGINE_WORD
    );
    /**
     * Сделаем небольшую оптимизацию для загрузки классов
     * Это еще более новая версия, с рекурсивной загружалкой
     */
    global $classAutoloadMapping;
    $classAutoloadMapping = array();

    function scanFolderForClasses($folder, &$import) {
        global $classAutoloadMapping;

        $folderHandler = opendir($folder);
        while (false !== ($file = readdir($folderHandler))) {
            if ($file != "." && $file != "..") {
                if (is_dir($folder.CORE_DS.$file)) {
                    $import[] = $folder.CORE_DS.$file;
                    scanFolderForClasses($folder.CORE_DS.$file, $import);
                } elseif (is_file($folder.CORE_DS.$file)) {
                    $filename = $folder.CORE_DS.$file;
                    if (mb_substr($file, mb_strlen($file) - 10) == ".class.php") {
                        $className = mb_substr($file, 0, mb_strlen($file) - 10);
                        $classAutoloadMapping[$className] = $filename;
                    }
                }
            }
        }
    }
    /**
     * Добавляем подпапки
     */
    $subfoldersToLoad = array(
        "_models" => CORE_CWD.CORE_DS.'_core'.CORE_DS.'_models',
        "_controllers" => CORE_CWD.CORE_DS.'_core'.CORE_DS.'_controllers',
        "_framework" => CORE_CWD.CORE_DS.'_core'.CORE_DS.'_framework',
        "_services" => CORE_CWD.CORE_DS.'_core'.CORE_DS.'_services',
    );
    foreach ($subfoldersToLoad as $folder) {
        scanFolderForClasses($folder, $import);
    }
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
        global $classAutoloadMapping;
        //
        $hasFile = false;
        if (array_key_exists($className, $classAutoloadMapping)) {
            if (file_exists($classAutoloadMapping[$className])) {
                $hasFile = true;
                require_once($classAutoloadMapping[$className]);
                return true;
            }
        }
        foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
            if (file_exists($path.$className.".class.php")) {
                $classAutoloadMapping[$className] = $path.$className.".class.php";
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