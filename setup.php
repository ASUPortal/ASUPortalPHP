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
 * //------------------------  интеграция с модулями
$useIntegManager=true;   // использовать менеджер интеграции различных систем
//  отражение производительности
$use_benchmark=true;   // выводить време генерации страницы
 */

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
    //------------------------------------------------------
//----- наименование компании ------
/*
$comp_title='Портал АСУ';    //добавляется к заголовкам (title,<h4>) всех страниц
$portal_title='Официальный портал кафедры АСУ УГАТУ';   //наименование компании, вверху страницы
$admin_email='smart_newline@mail.rb.ru';

$left_logo_href="index.php";
$left_logo_title="Портал кафедры АСУ";
$left_logo_img="left_logo_asu2.gif";

$right_logo_href="http://www.ugatu.ac.ru";
$right_logo_title="Портал УГАТУ";
$right_logo_img="right_logo.gif";

//----------тема оформления---------
$theme_folder="blue";   //gold


//----------------------------------
$site_blocked=false;

$external_link=false;    //разрешать внешние ресурсы, т.к. работают тока при инете
/*	внешние ресурсы: 
					-прогноз погоды (правый стобец), 
					-рекламный баннер ueuo.com (низ центр. стобца), 
					-новости образования (левый столбец), 
					-счетчик Spylog (правый столбец) */

/*
date_default_timezone_set ($timezone_identifier='Asia/Yekaterinburg');
$dayBegin=mktime(0,0,0,2,13,2012);
//дата начала полугодия для отсчета недель, формата ч/м/с  м/д/гггг без нулей

$server_name="http://".$_SERVER["SERVER_ADDR"]."/";	//имя сервера для отображения графики на страницах ошибок 403,404
$curpage=str_replace('/'.$root_folder,'',$_SERVER["PHP_SELF"]);     //имя текущей страницы без корневого пути
$curpage=preg_replace('/^\//','',$curpage);

//$files_path=$server_name.$root_folder;
if (!isset($files_path)) {$files_path='';}
//$files_path='';//$server_name.$root_folder; на абс.путь с url не менять-> не работают include !!!!

$upload_max_filesize=30;	//in MB	  максимальным объем загружаемого файла, 
//+ см. в файле php.ini upload_max_filesize=100M,post_max_size = 100M  (пока 100Мб)
// требуется библиотека кодировок mb_string
$server_encoding='KOI8-R';	// кодировка файлов на сервере, по умолчанию (исп.в публикациях)
$browser_encoding='Windows-1251';	// кодировка для просмотра в браузере пользователя, по умолчанию (исп.в публикациях)

//$timeHcorrect=0;		//поправка в часах по часовому поясу

$del_allow=false;		//разрешать удаление элементов справочников...

$onEditRemain=false;		//после редактирования записи оставаться на ней, не возвращаться в список

$search_server_ip="";	//адрес сервера поиска

//--------------------------------------
$daysBetweenNY=14;	//число дней до и после НГ показа оформления
//начало НГ-периода 2 недели до 01 января
$beforeNY  = mktime(0, 0, 0, 12 , 31-$daysBetweenNY, date("Y"));
//конеч НГ-периода 2 недели после 01 января
$afterNY  = mktime(0, 0, 0, 1 , $daysBetweenNY, date("Y"));

$cur_date=mktime(0, 0, 0, date("m") , date("d"), date("Y"));

$daysBefNY=($cur_date-$beforeNY)/3600/24;
$daysAfNY=($afterNY-$cur_date)/3600/24;
if ( ($daysBefNY>0 && $daysBefNY<$daysBetweenNY) || ($daysAfNY>0 && $daysAfNY<$daysBetweenNY) )
//меняем логотим кафедры в заголовке + добавление мишуры
{ $new_year=true;	}
else {$new_year=false;}
//------------------------------------------------------
$mirror_url_name='asu-ugatu.ueuo.com';	//подсказка в определении размера файла
$inner_url_name='10.61.2.63';			//подсказка в определении размера файла
$main_site_name='asu.ugatu.ac.ru';      //адрес основного сайта
$redirect2mainSite=false;                //переходить на основной сайт по умолчанию
$hide_personal_data=false;       //скрывать персональные данные на портале для гостевых пользователей без авторизации
$hide_person_data_text='<a  class=text style="color:#999999;" title="доступно после авторизации">скрыто</a>';
$hide_person_data_task='<div class=warning>Для просмотра раздела Вам необходимо пройти <a href="p_administration.php?url='.$curpage.'">авторизацию</a></div>';
$hide_person_data_rule=$hide_personal_data && (!isset($_SESSION['auth']) || $_SESSION['auth']!=1);
$hide_kadri_tasks=false;    //скрытие задач, которые связаны с данными таблицы Сотрудники, например при удалении этих таблиц
$hide_student_tasks=false;    //скрытие задач, которые связаны с данными таблицы  Студенты, например при удалении этих таблиц
$hide_kadri_students_tasks_text='<h1 class=warning>задача не доступна в режиме скрытия персональных данных</h1>';

$saveLogin=true;            //запоминание авторизации пользователя на 2 недели, автоматически продляется при каждом входе
$saveLogin_cook='portal_hash_'.preg_replace('/\W/','',$root_folder);    // cookiе хранения авторизации

//------------------------  интеграция с модулями
//----опросы, голосования
$folder_poll='asupoll/';
$typepoll='rnd_poll';	//rnd_poll last_poll
//----Mantis система контроля ошибок, файл интеграции _mantis_integ.php
$useMantis=false;
$sso_salt=' sbn%3**d ws';   //симметричное шифрование паролей в таблице SSO, не менять иначе пароли м\б утеряны и потребуется их повторная перепривязка к сервисам
$useMoodle=false;
$moodlePath='http://moodle.ugatu.su';//$server_name.'moodle';

$useIntegManager=true;   // использовать менеджер интеграции различных систем
//  отражение производительности
$use_benchmark=true;   // выводить време генерации страницы

/*
//основные цвета
#ссссff светло фиолетовый	- верхнее авториз.меню,  небольших областей
#2020bd	синий насыщенный цвет	-левое меню, "шапка" шаблона
#dfefff	светло сений		-основная таблица списочной формы элементов
#ebeaff	светло фиолетовый	-подложка форм
*/
?>