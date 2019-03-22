<?php
    require_once("setup.php");

//session_start();

if (!isset($files_path)) {$files_path='';}
if (!isset($server_encoding)) {$server_encoding='';}
if (!isset($browser_encoding)) {$browser_encoding='';}


include $files_path.'date_time.php';

//---------------------------------------
if (isset($use_benchmark) && $use_benchmark) {

	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$root_folder.'_modules/benchmark/library/Timer.php');
	$timer = new Benchmark_Timer();
	$timer->start();
	$timer->setMarker('Mark1');
}
//---------------------------------------
//
if (!$site_blocked) {include $files_path.'week.php';}

$metaKeywords='';
$pg_author='';
$pg_description='';

$q_val='select pu.`description`,pu.`meta_keywords`, pu.`title`,u.fio_short as author
    from `pg_uploads` pu left join users u on u.id=pu.user_id_insert
    where pu.`name` like "'. $curpage.'"';
$pg_stat=getRowSqlVar($q_val);
if (isset($pg_stat[0])) {
$pg_stat=$pg_stat[0];
//print_r($pg_stat);
//echo $q_val;
$metaKeywords=$pg_stat['meta_keywords'];

$pg_author=$pg_stat['author'];
$pg_description=$pg_stat['description'];
}
//определяем название страницы из БД
if (isset($pg_stat['title']) && trim($pg_stat['title'])!='') $pg_title=$pg_stat['title'];


if (!isset($head_title)) {
    $head_title='';    
    }
 

if (isset($pg_title)) {
    if (trim($pg_title)!='') {  // заголовок страницы
        $head_title=$head_title.''.$pg_title;
    }
}

if ($head_title=='') $head_title=$comp_title;
else $head_title.=!strstr($head_title,$comp_title)?'. '.$comp_title:'';

if (!isset($bodyOnLoad) && !isset($_GET['wap'])) {$bodyOnLoad=' ';}

//для отрисовки закругленных таблиц
$tab_begin='<table width=160 cellspacing=0 cellpadding=0 class=round_table align=center valign="top" border=0>
   <tr height=18>
   <td width=10></td>
   <td width=135></td>
   <td width=15></td></tr>';
$tab_end='<tr height=18>
   <td width=10></td>
   <td width=135></td>
   <td width=15></td></tr></table>';

//---------------------------------------------------------------------------------------
$leftColDispl='';	//стиль отражения лев.панели (глав.меню.. статистика)
$rightColDispl='';	//стиль отражения прав.панели (глав.меню.. статистика)

if (isset ($_COOKIE['leftMainCol'])) {$leftColDispl=$_COOKIE['leftMainCol'];$rightColDispl=$leftColDispl;}

$head='
<!DOCTYPE html>
<html>
<head>
<title>'.del_HTMLTags($head_title).'</title>
<meta charset="UTF-8" />
<meta name="author" content="'.$pg_author.'">
<meta name="keywords" content="'.$metaKeywords.'">
<meta name="DESCRIPTION" content="'.$pg_description.'">
<script language="javascript" src="'.$web_root.'scripts/function.js" type="text/javascript"> </script>';

    // <abarmin date="06.05.2012">
    // это все надо переписать.
    if (defined("CORE_ENABLED")) {
        if (isset($controller)) {
            foreach ($controller->getJSIncludes()->getItems() as $item) {
                // переписать с использованием класса CHtml
                $head .= '<script language="javascript" src="'.$web_root.'scripts/'.$item.'" type="text/javascript"> </script>';
            }
            if ($controller->getJSInlineIncludes()->getCount() > 0) {
                $head .= "<script>";
                foreach ($controller->getJSInlineIncludes()->getItems() as $item) {
                    $head .= $item;
                }
                $head .= "</script>";
            }
            foreach($controller->getCSSIncludes()->getItems() as $item) {
                $head .= '<LINK href="'.$web_root.'css/'.$item.'" rel="stylesheet" type="text/css">';
            }
        } else {
            $head .= '<script language="javascript" src="'.$web_root.'scripts/'.CSettingsManager::getSettingValue("jquery_path").'" type="text/javascript"> </script>';
            $head .= '<script language="javascript" src="'.$web_root.'scripts/'.CSettingsManager::getSettingValue("jquery_migrate_path").'" type="text/javascript"> </script>';
            $head .= '<script language="javascript" src="'.$web_root.'scripts/'.CSettingsManager::getSettingValue("bootstrap_custom_path")."js/bootstrap.js".'" type="text/javascript"> </script>';
            $head .= '<script language="javascript" src="'.$web_root.'scripts/_core/core.js" type="text/javascript"> </script>';
            $head .= '<script language="javascript" src="'.$web_root.'scripts/_core/jquery.cookie.js" type="text/javascript"> </script>';
            $head .= '<script language="javascript" src="'.$web_root.'scripts/_core/jquery.ajax.js" type="text/javascript"> </script>';
            $head .= '<link href="'.$web_root.'scripts/'.CSettingsManager::getSettingValue("bootstrap_custom_path")."css/bootstrap.css".'" rel="stylesheet" type="text/css">';
            $head .= '<link href="'.$web_root.'css/_core/core.css" rel="stylesheet" type="text/css">';
        }
    } else {
        $head .= '<script language="javascript" src="'.$web_root.'scripts/'.CSettingsManager::getSettingValue("jquery_path").'" type="text/javascript"> </script>';
        $head .= '<script language="javascript" src="'.$web_root.'scripts/'.CSettingsManager::getSettingValue("jquery_migrate_path").'" type="text/javascript"> </script>';
        $head .= '<script language="javascript" src="'.$web_root.'scripts/'.CSettingsManager::getSettingValue("bootstrap_custom_path")."js/bootstrap.js".'" type="text/javascript"> </script>';
        $head .= '<script language="javascript" src="'.$web_root.'scripts/_core/core.js" type="text/javascript"> </script>';
        $head .= '<script language="javascript" src="'.$web_root.'scripts/_core/jquery.cookie.js" type="text/javascript"> </script>';
        $head .= '<script language="javascript" src="'.$web_root.'scripts/_core/jquery.ajax.js" type="text/javascript"> </script>';
        $head .= '<link href="'.$web_root.'scripts/'.CSettingsManager::getSettingValue("bootstrap_custom_path")."css/bootstrap.css".'" rel="stylesheet" type="text/css">';
        $head .= '<link href="'.$web_root.'css/_core/core.css" rel="stylesheet" type="text/css">';
    }
    /**
     * Агапов, чтоб ты сдох!
     *
     * Подключаем модуль главного меню, он возвращает строку
     * $menu_str с кодом самого меню
     */
    include '_modules/left_menu/index.php';
    /**
     * Дружественные сайты
     */
    $friends = '
    <div align=center class=middle> Дружественные сайты:</div>'.$tab_begin.'
        <tr class=round_table><td colspan=3 align=center class=baner_img><a href=http://www.ugatu.ac.ru title="Официальный сайт УГАТУ"  target="_blank">
                           <img src='.$web_root.'images/design/blocks/block4.gif alt="Cайт УГАТУ" border=1></a></td></tr>
        <tr class=round_table><td colspan=3 align=center class=baner_img><a href=http://www.businessstudio.ru title="Управление бизнесом, бизнес-моделирование, бизнес-процесс, описание бизнес-процессов, оптимизация бизнес-процессов&nbsp;&mdash;&nbsp;Business Studio"  target="_blank">
                           <img src='.$web_root.'images/design/blocks/block8.gif alt="Cайт Business Studio" border=0 style="background-color:White;"></a></td></tr>
        <tr class=round_table><td colspan=3 align=center class=baner_img><a href=http://erp4students.ru title="Программа дистанционного обучения решениям SAP для студентов России и стран СНГ, организуемую университетом Дуйсбург-Эссена (Германия)   erp4students"  target="_blank">
                           <img src='.$web_root.'images/design/blocks/block9.gif alt="erp4students" border=0 style="background-color:White;"></a></td></tr>
        <tr class=round_table><td colspan=3 align=center class=baner_img><a href=http://www.ugatu.net title="Портал студгородка УГАТУ"  target="_blank">
                           <img src='.$web_root.'images/design/blocks/block5.gif alt="Cтудгородок УГАТУ" border=1></a></td>
	<tr class=round_table><td colspan=3 align=center class=baner_img><a href=https://futu.ru title="
Группа компаний «Информация Будущего»
ДИСТРИБЬЮТОР ИНФОРМАЦИОННО-ПРАВОВОГО КОНСОРЦИУМА «КОДЕКС»
"  target="_blank">
                           <img src='.$web_root.'images/design/blocks/block7.gif alt="Группа компаний «Информация Будущего»" border=1></a></td></tr>
        <tr class=round_table><td colspan=3 align=center class=baner_img><a href=http://www.library.ugatu.ac.ru/index.html title="Библиотека,электронный каталог УГАТУ" target="_blank">
                           <img src='.$web_root.'images/design/blocks/block6.gif alt="Библиотека УГАТУ" border=1></a></td></tr>'.$tab_end;
    /**
     * Статистика за неделю
     */
    $query='select
    (select count(*) from news) as new_cnt ,
    (select count(*) from users where status="преподаватель") as lect_cnt,
    (select count(*) from documents) as subj_cnt,
    (select count(*) from files where nameFolder not like "gost%") as files_cnt,
    (select count(*) from files where nameFolder like "gost%") as docs_cnt
    ';
    $res=mysql_query($query);
    $a=mysql_fetch_array($res);

    $statistic = '
    <div align=center class=middle> Общая статистика:</div>
    <ul class=text>
    <li><a href="'.$web_root.'index.php">новостей: <b>'.$a['new_cnt'].'</b></a></li>
    <li><a href="'.$web_root.'_modules/_lecturers/index.php">преподавателей: <b>'.$a['lect_cnt'].'</b></a> </li>
    <li><a href="'.$web_root.'_modules/_library/index.php">предметов:  <b>'.$a['subj_cnt'].'</b></a></li>
    <li><a href="'.$web_root.'_modules/_library/index.php">учебных файлов:  <b>'.$a['files_cnt'].'</b></a></li>
    <li><a href="'.$web_root.'p_gost_docs_view.php">документов:  <b>'.$a['docs_cnt'].'</b></a></li>
    </ul>
    ';

    if ($count_files!=0 || $count_notice!=0 || $count_news!=0 || 1>0) {
        $statistic .= '<div class=middle>Статистика за неделю: </div><ul class=TEXT>';
        if ($count_files!=0 || 1>0) {
            $statistic .= '<li><a href="'.$web_root.'_modules/_library/index.php">новых файлов:<b>'.$count_files.'</b> </a> </li>';
        }
        if ($count_notice!=0 || 1>0) {
            $statistic .= '<li><a href="'.$web_root.'p_notice.php">новых объявлений: <b>'.$count_notice.'</b></a></li>';
        }
        if ($count_news!=0 || 1>0) {
            $statistic .= '<li><a href="'.$web_root.'index.php">новых новостей: <b> '.$count_news.'</b></a></li>';
        }
        $statistic .= '</ul>';
    }
    /**
     * Сообщество вКонтакте
     */
    $vkWidget = '<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?31"></script>
        <!-- VK Widget -->
        <div id="vk_groups"></div>
        <script type="text/javascript">
        try {VK.Widgets.Group("vk_groups", {mode: 0, width: "156", height: "290"}, 24892947);}
        catch (e) { document.write("<p class=warning>нет интернет-соединения</p>"); }
    </script>';
    /**
     * Поиск
     */
    $search='
        <FORM NAME="searchF" id="searchF" METHOD="get" ACTION="'.$web_root.'p_search_detail.php">
        <INPUT TYPE="text" NAME="q" SIZE=20 VALUE="" MAXLENGTH=160 placeholder="Поиск">
        <br>
        <INPUT TYPE=SUBMIT VALUE="Найти" style="display:none;">
        <INPUT TYPE="hidden" NAME="r1" VALUE="on">
        <INPUT TYPE="hidden" NAME="r2" VALUE="on">
        <INPUT TYPE="hidden" NAME="r3" VALUE="on">
        <INPUT TYPE="hidden" NAME="r4" VALUE="on">
        </FORM>';
    /**
     * Заголовок сайта
     */
    $logo = '
        <div class="container-fluid asu_header">
            <div class="row-fluid">
                <div class="span2">
                    <a href="/"><div class="asu_header_asu_logo"></div></a>
                </div>

                <div class="span8 asu_header_content">
                    <p>'.$portal_title.'</p>
                    <p>'.$dateTimeOut.'</p>
                </div>

                <div class="span2">
                </div>
            </div>
        </div>
    ';
// </abarmin>

$head .= '
</script>
<LINK href="'.$web_root.'_themes/'.$theme_folder.'/styles.css" rel="stylesheet" type="text/css">
<link rel="icon" type="image/vnd.microsoft.icon" href="'.$web_root.'_themes/'.$theme_folder.'/favicon.ico">
<link rel="shortcut icon" href="'.$web_root.'_themes/'.$theme_folder.'/favicon.ico">

</head>
<body '.$bodyOnLoad.'>
<div id="overlay"></div>
<script language="JavaScript">main_path="'.$web_root.'";</script>

'.$logo.'

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2" id="asu_body_menu">
            <div class="row">
                <div class="span12">
                '.$menu_str.'
                </div>
            </div>
            <div class="row">
                <div class="span12">
                '.$friends.'
                </div>
            </div>
            <div class="row">
                <div class="span12">
                '.$statistic.'
                </div>
            </div>
        </div>

        <div class="span10" id="asu_body_content">
            <div class="row">';

    /**
     * Страница с правой колонокой и без правой колонки
     * (флаг ставится в master_page_short.php
     */
    if (!defined("NO_RIGHT_COLUMN")) {
        $head .= '<div class="span10">';
    } else {
        $head .= '<div class="span12">';
    }

    $head_wap=$head;

//------------------------------------------------------------------

/**
 * Конец блока с основным контентом. Если есть правая колонка,
 * то она добавляется здесь
 */
$end1 = '</div>';
if (!defined("NO_RIGHT_COLUMN")) {
    $end1 .= '
                <div class="span2">
                    <div class="row">
                        <div class="span12">
                            '.$search.'
                        </div>
                    </div>

                    <div class="row">
                        <div class="span12">
                            '.$vkWidget.'
                        </div>
                    </div>

                </div>
';
}
$end2 = '
                </div>
            </div>
        </div>
    </div>
';

/**
 * ------------------------------------------------------------------------------
 * Какая-то фигня, может кто удалит
 * ------------------------------------------------------------------------------
 */
//краткий заголовок без граф.элементов эталон.страницы, используется при печате, экспорте, выводе больших форм
$head1='<html>
<head>
<title>'.del_HTMLTags($head_title).'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="author" content="'.$pg_author.'">
<meta name="keywords" content="'.$metaKeywords.'">
<meta name="DESCRIPTION" content="'.$pg_description.'">
<script language="javascript" src="'.$web_root.'scripts/function.js" type="text/javascript"></script>
<script language="javascript" src="'.$web_root.'scripts/jquery-1.3.2.min.js" type="text/javascript"></script>';

$head1.='<style type="text/css" media="all">
@import url('.$server_name.$root_folder.'_themes/'.$theme_folder.'/styles.css);
</style>
</head>
<body style="background-color:#ffffff;"  '.$bodyOnLoad.'>';

//------------------------------------------------------------------
$head_wap=$head1.$logo.'
<style>
.main {font-size: 10pt;font-weight: bold;font-family: Arial;}
</style>
<script type="text/javascript">
//добавляем ко все ссылкам атрибут wap для открытия в сокращенном (wap) виде (jquery)
//не применяется для ссылок mailto & class=not_wap
 $(document).ready(function(){

                $("a").not(".not_wap").click(function(){
		 var curHref=$(this).attr("href");
			 if (curHref.indexOf("?")==-1) {window.location.href=curHref+"?wap";}
			 else {window.location.href=curHref+"&wap";}
		 return false;
		 });
 });
</script>
<select name=main_menu style="width:150;" onChange="javascript:window.location.href=this.options[this.selectedIndex].value;">';


$head_wap.='</select> <a href="?'.reset_param_name($_SERVER['QUERY_STRING'],'wap').'" title="вернуться в обычный режим просмотра страницы" class="not_wap"> Обычный режим </a></div>';
