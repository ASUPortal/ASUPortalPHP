<?php
require_once("core.php");

//Адрес FTP-сервера
$ftp_server = CSettingsManager::getSettingValue("ftp_server");

//Пользователь
$ftp_user = CSettingsManager::getSettingValue("ftp_server_user"); 

//Пароль
$ftp_password = CSettingsManager::getSettingValue("ftp_server_password"); 

//задаем время исполнения скрипта, равное 120 с
set_time_limit(120);

//Пытаемся установить соединение с FTP-сервером
$link = ftp_connect($ftp_server);
if(!$link) exit ("К сожалению, не удается установить соединение с FTP-сервером $ftp_server");

//Осуществляем регистрацию на сервере
$login = ftp_login($link, $ftp_user, $ftp_password);
if(!$login) exit ("К сожалению, не удается зарегистрироваться на сервере. Проверьте регистрационные данные");