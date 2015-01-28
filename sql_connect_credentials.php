<?php
/**
 * Параметры подключения к MySQL. Вынесено из sql_connect, так как sql_connect
 * подключается где ни попадя и содержит в себе не только настройки для подключения,
 * но и кучу всякого разного хлама, которого по логике названия там быть не должно
 */

$sql_host='localhost';
$sql_base='asu';

$sql_login='root';
$sql_passw='';

//$sql_login='root';
//$sql_passw='';

$sql_stats_base = 'stats';
$sql_stats_login = 'root';
$sql_stats_passw = '';
$sql_stats_host = 'localhost';