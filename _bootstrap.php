<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 26.05.15
 * Time: 23:40
 *
 * Скрипт предзагружает файлы портала для ускорения работы под нагрузкой
 *
 * Сначала проверим, что расширение есть, затем убьем старый кэш, а уже
 * затем загрузим новый. Грузить будем только _core, так как там самый жесткач
 */
if (!function_exists("opcache_compile_file")) {
    die("Для предзагрузки файлов необходимо включить расширение OPCache");
}

function preload($folder, &$files = array()) {
    if (!is_dir($folder)) {
        return null;
    }
    $handle = opendir($folder);
    while (($file = readdir($handle)) !== false) {
        if ($file != "." && $file != "..") {
            $fullPath = $folder . DIRECTORY_SEPARATOR . $file;
            if (is_dir($fullPath)) {
                preload($fullPath, $files);
            } else if (substr($file, strlen($file) - 4) == ".php") {
                $files[] = $fullPath;
            }
        }
    }
    return $files;
}

echo '--------------------------<br>';
echo 'Очищаем старый кэш...';
// opcache_reset();
echo 'OK<br>';
echo '--------------------------<br>';

echo '--------------------------<br>';
echo 'Статус кэша:<br>';
var_dump(opcache_get_status());
echo '--------------------------<br>';

$foldersToLoad = array(
    "_core",
    "_modules"
);

$coreFolder = str_replace("_bootstrap.php", "", __FILE__);

echo '--------------------------<br>';
echo 'Загружаем:<br>';
foreach ($foldersToLoad as $folder) {
    foreach (preload($folder) as $file) {
        echo $file;
        if (@opcache_compile_file($file)) {
            echo '...<font color="green">OK</font><br>';
        } else {
            echo '...<font color="red">FAILED</font><br>';
        }
    }
}
echo '--------------------------<br>';
