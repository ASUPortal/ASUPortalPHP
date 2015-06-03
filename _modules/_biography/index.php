<?php
/**
 * Приходится подстраиваться под существующий подход портала.
 * Ну да ладно, это еще не так страшно, как до этого было
 */
require_once("../../core.php");
mysql_query("SET NAMES UTF8");

$controller = new CBiographyController();