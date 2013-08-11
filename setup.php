<?php
/**
 * Все это уехало в визуальные настройки портала, здесь старые значения и
 * присвоения настроек
 *
 * /_modules/_configuration/
 */

    require_once("core.php");

    $comp_title = CSettingsManager::getSettingValue("comp_title");
    $portal_title = CSettingsManager::getSettingValue("portal_title");
    $admin_email = CSettingsManager::getSettingValue("admin_email");
    $root_folder = CSettingsManager::getSettingValue("root_folder");
    $web_root = CSettingsManager::getSettingValue("web_root");
    $left_logo_href = CSettingsManager::getSettingValue("left_logo_href");
    $left_logo_title = CSettingsManager::getSettingValue("left_logo_title");
    $left_logo_img = CSettingsManager::getSettingValue("left_logo_img");
    $right_logo_href = CSettingsManager::getSettingValue("right_logo_href");
    $right_logo_title = CSettingsManager::getSettingValue("right_logo_title");
    $right_logo_img = CSettingsManager::getSettingValue("right_logo_img");
    $theme_folder = CSettingsManager::getSettingValue("theme_folder");
    $site_blocked = CSettingsManager::getSettingValue("site_blocked");
    $external_link = CSettingsManager::getSettingValue("external_link");
    date_default_timezone_set(CSettingsManager::getSettingValue("default_timezone"));
    $dayBegin = CSettingsManager::getSettingValue("dayBegin");
    $saveLogin = CSettingsManager::getSettingValue("saveLogin");
    $hide_personal_data = CSettingsManager::getSettingValue("hide_personal_data");
    $timeHcorrect = CSettingsManager::getSettingValue("timeHcorrect");
    $server_name = CSettingsManager::getSettingValue("server_name");
    $upload_max_filesize = CSettingsManager::getSettingValue("upload_max_filesize");
    $server_encoding = CSettingsManager::getSettingValue("server_encoding");
    $browser_encoding = CSettingsManager::getSettingValue("browser_encoding");
    $del_allow = CSettingsManager::getSettingValue("del_allow");
    $onEditRemain = CSettingsManager::getSettingValue("onEditRemain");
    $search_server_ip = CSettingsManager::getSettingValue("search_server_ip");
    $daysBetweenNY = CSettingsManager::getSettingValue("daysBetweenNY");
    $mirror_url_name = CSettingsManager::getSettingValue("mirror_url_name");
    $inner_url_name = CSettingsManager::getSettingValue("inner_url_name");
    $main_site_name = CSettingsManager::getSettingValue("main_site_name");
    $redirect2mainSite = CSettingsManager::getSettingValue("redirect2mainSite");
    $hide_person_data_text = CSettingsManager::getSettingValue("hide_person_data_text");
    $hide_person_data_task = CSettingsManager::getSettingValue("hide_person_data_task");
    $hide_person_data_rule = CSettingsManager::getSettingValue("hide_person_data_rule");
    $hide_kadri_tasks = CSettingsManager::getSettingValue("hide_kadri_tasks");
    $hide_student_tasks = CSettingsManager::getSettingValue("hide_student_tasks");
    $hide_kadri_students_tasks_text = CSettingsManager::getSettingValue("hide_kadri_students_tasks_text");
    $saveLogin_cook = CSettingsManager::getSettingValue("saveLogin_cook");
    $folder_poll = CSettingsManager::getSettingValue("folder_poll");
    $typepoll =  CSettingsManager::getSettingValue("typepoll");
    $useMantis = CSettingsManager::getSettingValue("useMantis");
    $sso_salt = CSettingsManager::getSettingValue("sso_salt");
    $useMoodle = CSettingsManager::getSettingValue("useMoodle");
    $moodlePath = CSettingsManager::getSettingValue("moodlePath");
    $useIntegManager = CSettingsManager::getSettingValue("useIntegManager");
    $use_benchmark = CSettingsManager::getSettingValue("use_benchmark");
    /**
     * Отправка сообщений через SMTP
     */
    define("MAIL_SMTP_ENABLED", CSettingsManager::getSettingValue("smtp_enabled"));
    define("MAIL_SMTP_HOST", CSettingsManager::getSettingValue("smtp_host"));
    define("MAIL_SMTP_USER", CSettingsManager::getSettingValue("smtp_user"));
    define("MAIL_SMTP_PASS", CSettingsManager::getSettingValue("smtp_pass"));
    define("MAIL_SMTP_AUTH", CSettingsManager::getSettingValue("smtp_auth"));
    define("MAIL_SMTP_FROM", CSettingsManager::getSettingValue("smtp_from"));
    /**
     * Определение текущей страницы
     */
    $curpage = preg_replace('/^\//','',str_replace('/'.$root_folder,'',$_SERVER["PHP_SELF"]));
    if (!isset($files_path)) {
        $files_path='';
    }
    // Параметры нового года
    //--------------------------------------
    //начало НГ-периода 2 недели до 01 января
    $beforeNY  = mktime(0, 0, 0, 12 , 31-$daysBetweenNY, date("Y"));
    //конеч НГ-периода 2 недели после 01 января
    $afterNY  = mktime(0, 0, 0, 1 , $daysBetweenNY, date("Y"));

    $cur_date=mktime(0, 0, 0, date("m") , date("d"), date("Y"));

    $daysBefNY=($cur_date-$beforeNY)/3600/24;
    $daysAfNY=($afterNY-$cur_date)/3600/24;
    //меняем логотим кафедры в заголовке + добавление мишуры
    if ( ($daysBefNY>0 && $daysBefNY<$daysBetweenNY) || ($daysAfNY>0 && $daysAfNY<$daysBetweenNY) ) {
        $new_year=true;
    } else {
        $new_year=false;
    }