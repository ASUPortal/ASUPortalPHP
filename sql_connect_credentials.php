<?php
/**
 * Параметры подключения к MySQL. Вынесено из sql_connect, так как sql_connect
 * подключается где ни попадя и содержит в себе не только настройки для подключения,
 * но и кучу всякого разного хлама, которого по логике названия там быть не должно
 */

$sql_host = getenv("DB_HOST");
$sql_base = getenv("DB_NAME");

$sql_login = getenv("DB_USER");
$sql_passw = getenv("DB_PASS");

$sql_stats_base = getenv("DB_NAME_STATS");
$sql_stats_login = getenv("DB_USER");
$sql_stats_passw = getenv("DB_PASS");
$sql_stats_host = getenv("DB_HOST");