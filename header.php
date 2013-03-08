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
if (!$site_blocked) {include $files_path.'week.php';include $files_path.'holidays_main.php';}

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

$left_menu=Array();



if (!isset($bodyOnLoad) && !isset($_GET['wap'])) {$bodyOnLoad=' ';}

$extLink1='';$extLink2='';$extLink3='';

if ($external_link) {

//левый блок, между гл.меню и "друж.сайты"
$extLink1='<hr><div align=center class=middle> Образование в России:</div><br>
<script language="JavaScript" src="http://pics.rbc.ru/js/5ballov_newsline.js"></script>
<table border=0 width=170 cellspacing=0 cellpadding=0 valign="top"><tr><td width="5"></td><td class="TEXT">
    <script> print_5ballov_news(5, "_blank");</script></td></tr></table><hr>';

//правый блок, между голосованием и "образ.сайты"
$extLink2='';

//правый блок, счетчики
$extLink3='<div align=center>'; 

$extLink3.='<a href="http://www.cys.ru/"><img src="http://www.cys.ru/button.png?url=asu-ugatu.ueuo.com" width="88" height="31"></a>';

$extLink3.='<a href="http://u6514.65.spylog.com/cnt?cid=651465&f=3&p=0" target="_blank">
    <img src="http://u6514.65.spylog.com/cnt?cid=651465&p=0" alt=SpyLOG border=0 width=88 height=31 ></a>';

$extLink3.='<!--Rating@Mail.ru counter-->
    <script language="javascript" type="text/javascript"><!--
    d=document;var a=\'\';a+=\';r=\'+escape(d.referrer);js=10;//--></script>
    <script language="javascript1.1" type="text/javascript"><!--
    a+=\';j=\'+navigator.javaEnabled();js=11;//--></script>
    <script language="javascript1.2" type="text/javascript"><!--
    s=screen;a+=\';s=\'+s.width+\'*\'+s.height;
    a+=\';d=\'+(s.colorDepth?s.colorDepth:s.pixelDepth);js=12;//--></script>
    <script language="javascript1.3" type="text/javascript"><!--
    js=13;//--></script><script language="javascript" type="text/javascript"><!--
    d.write(\'<a href="http://top.mail.ru/jump?from=1662342" target="_top">\'+
    \'<img src="http://dd.c5.b9.a1.top.mail.ru/counter?id=1662342;t=130;js=\'+js+
    a+\';rand=\'+Math.random()+\'" alt="Рейтинг@Mail.ru" border="0" \'+
    \'height="40" width="88"><\/a>\');if(11<js)d.write(\'<\'+\'!-- \');//--></script>
    <noscript><a target="_top" href="http://top.mail.ru/jump?from=1662342">
    <img src="http://dd.c5.b9.a1.top.mail.ru/counter?js=na;id=1662342;t=130" 
    height="40" width="88" border="0" alt="Рейтинг@Mail.ru"></a></noscript>
    <script language="javascript" type="text/javascript"><!--
    if(11<js)d.write(\'--\'+\'>\');//--></script>
    <!--// Rating@Mail.ru counter-->';
$extLink3.='</div>';

$extLink3.='<!-- Start 1FreeCounter.com code -->
    <script language="JavaScript">
    var data = \'&r=\' + escape(document.referrer)
          + \'&n=\' + escape(navigator.userAgent)
          + \'&p=\' + escape(navigator.userAgent)
          + \'&g=\' + escape(document.location.href);
    if (navigator.userAgent.substring(0,1)>\'3\')
      data = data + \'&sd=\' + screen.colorDepth 
          + \'&sw=\' + escape(screen.width+\'x\'+screen.height);
    document.write(\'<a href="http://www.1freecounter.com/stats.php?i=9761" target=\"_blank\" >\');
    document.write(\'<img alt="Free Counter" border=0 hspace=0 \'+\'vspace=0 src="http://www.1freecounter.com/counter.php?i=9761\' + data + \'">\');
    document.write(\'</a>\');
    </script>
  <!-- End 1FreeCounter.com code --> ';
}

          
$baner_text="";
//в основном только в открытой зоне, т.е. там, где 3 колонки
if ($external_link) 
{$baner_text="
<!-- FreeWebHostingArea.com -->
<center>
<a href='http://www.freewebhostingarea.com' target='_blank'><img src='http://www.freewebhostingarea.com/468x60.gif' border='0' alt='Free Web Hosting'></a>
<br></center>
<!-- FreeWebHostingArea.com -->
";}

$logo='<table width="99%" border="0" cellpadding="0" cellspacing="0" align=center>
  <tr align="center" bgcolor="">';
  
if (!isset($_GET['wap'])) {
 	$logo=$logo.'<td height="90" width="3%" background="'.$web_root.'_themes/'.$theme_folder.'/images/head.gif" style="background-repeat:repeat-x;" align=left>
			<div style="position:absolute; top:0;z-index:0;"><a href="'.$left_logo_href.'" title="'.$left_logo_title.'">';
	
	if ($new_year) {$logo=$logo.'<img src="'.$web_root.'_themes/'.$theme_folder.'/images/left_logo_ny.gif" border=0>';}
	else {$logo=$logo.'<img src="'.$web_root.'_themes/'.$theme_folder.'/images/'.$left_logo_img.'" border=0>';}
	
	$logo=$logo.'</a></div></td>'; 
	}
    	
	$logo=$logo.'<td width="" background="'.$web_root.'_themes/'.$theme_folder.'/images/head.gif" style="background-repeat:repeat-x;padding-left:200px;">
    <font color="#FFFFFF" face="Arial" size=2><div>'.$portal_title.'</div>
		<b>'.$dateTimeOut.'</b></font>
      </td>';
      
if (!isset($_GET['wap'])) {
 	//пиктограмма в правом верх. углу
        
        $logo.='<td width="192" background="'.$web_root.'_themes/'.$theme_folder.'/images/head.gif" style="background-repeat:repeat-x;">'.
	echoif($right_logo_href!='','<a href="'.$right_logo_href.'" title="'.$right_logo_title.'">','').        
        echoif($right_logo_img!='','<img src="'.$web_root.'_themes/'.$theme_folder.'/images/'.$right_logo_img.'" border=0></a>','').
        echoif($right_logo_href!='','</a>','');
        '</td>';
        }
	
	$logo=$logo.'</tr>';
  if ($new_year) {$logo=$logo.'<tr><td height="50" background="'.$web_root.'images/NY_7_sm.jpg" colspan=5>&nbsp;</td></tr>';}
$logo=$logo.'</table>';

//для отрисовки закругленных таблиц
$tab_begin='<table width=160 cellspacing=0 cellpadding=0 class=round_table align=center valign="top" border=0>
   <tr height=18>
   <td width=10 background='.$web_root.'_themes/'.$theme_folder.'/images/1_03.gif></td>
   <td width=135 background='.$web_root.'_themes/'.$theme_folder.'/images/1_05.gif></td>
   <td width=15 background='.$web_root.'_themes/'.$theme_folder.'/images/1_07.gif></td></tr>';
$tab_end='   <tr height=18>
   <td width=10 background='.$web_root.'_themes/'.$theme_folder.'/images/1_09.gif></td>
   <td width=135 background='.$web_root.'_themes/'.$theme_folder.'/images/1_10.gif></td>
   <td width=15 background='.$web_root.'_themes/'.$theme_folder.'/images/1_11.gif></td></tr></table>';

//поиск по порталу
$search='<FORM NAME="searchF" id="searchF" METHOD="get" ACTION="'.$web_root.'p_search_detail.php">
<div align=left class=text>поиск
<span style="padding-left:20px;"></span>
'.echoIf($admin_email!='','<a class=light_blink href="mailto:'.$admin_email.'?subject=To Portal administrator" title="отправить письмо администратору портала">
    <img src="'.$web_root.'_themes/'.$theme_folder.'/images/email.gif" border=0 alt="письмо"></a>','').
'<span style="padding-left:20px;"></span>
<a class=light_blink href="'.$web_root.'p_map_view.php" title="перейти на карту портала">
    <img src="'.$web_root.'_themes/'.$theme_folder.'/images/navigator.gif" border=0 alt="карта"></a>
</div>
<INPUT TYPE="text" NAME="q" SIZE=20 VALUE="" MAXLENGTH=160 title="введите фразу для поиска">
<a href="#search" onclick="searchF.submit();" class=light_blink>
    <img  src="'.$web_root.'_themes/'.$theme_folder.'/images/search.gif" width=16 alt="поиск на портале"border=0></a><br>
<INPUT TYPE=SUBMIT VALUE="Найти" style="display:none;">
<INPUT TYPE="hidden" NAME="r1" VALUE="on">
<INPUT TYPE="hidden" NAME="r2" VALUE="on">
<INPUT TYPE="hidden" NAME="r3" VALUE="on">
<INPUT TYPE="hidden" NAME="r4" VALUE="on">
</FORM>';

//---------------------------------------------------------------------------------------
$leftColDispl='';	//стиль отражения лев.панели (глав.меню.. статистика)
$rightColDispl='';	//стиль отражения прав.панели (глав.меню.. статистика)

if (isset ($_COOKIE['leftMainCol'])) {$leftColDispl=$_COOKIE['leftMainCol'];$rightColDispl=$leftColDispl;}

$head='<html>
<head>
<title>'.del_HTMLTags($head_title).'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="author" content="'.$pg_author.'">
<meta name="keywords" content="'.$metaKeywords.'">
<meta name="DESCRIPTION" content="'.$pg_description.'">
<script language="javascript" src="'.$web_root.'scripts/function.js" type="text/javascript"> </script>';

// <abarmin date="06.05.2012">
// это все надо переписать.

// подключаем библиотеки и скрипты, если используются мои модели и
// оставляем как есть, если не используются
   $newJquery = array(
		"/asu/protocols_view.php"
    );
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
            if (!in_array($_SERVER["SCRIPT_NAME"], $newJquery)) {
                $head .= '<script language="javascript" src="'.$web_root.'scripts/jquery-1.3.2.min.js" type="text/javascript"> </script>';
            } else {
                $head .= '<script language="javascript" src="'.$web_root.'scripts/jquery-1.7.2.min.js" type="text/javascript"> </script>';
            }
        }
    } else {
        if (!in_array($_SERVER["SCRIPT_NAME"], $newJquery)) {
            $head .= '<script language="javascript" src="'.$web_root.'scripts/jquery-1.3.2.min.js" type="text/javascript"> </script>';
        } else {
            $head .= '<script language="javascript" src="'.$web_root.'scripts/jquery-1.7.2.min.js" type="text/javascript"> </script>';
        }
    }
// </abarmin>

$head .= '<script language="javascript">
window.defaultStatus="'.del_HTMLTags($portal_title).'";
//скрываем статус. строку
 $(document).ready(function(){
   hide_show("wait_layer");';

if ($redirect2mainSite)
$head.='var not_redirect=0;   
   if (typeof(getCook("not_redirect"))!="undefined") not_redirect=parseInt(getCook("not_redirect"));
   if (not_redirect!=1) {
        hide_show(\'redirect_layer\');
        startTimer();
        }';
        
$head.=' });
</script>
<LINK href="'.$web_root.'_themes/'.$theme_folder.'/styles.css" rel="stylesheet" type="text/css">
<link rel="icon" type="image/vnd.microsoft.icon" href="'.$web_root.'_themes/'.$theme_folder.'/favicon.ico">
<link rel="shortcut icon" href="'.$web_root.'_themes/'.$theme_folder.'/favicon.ico">

</head>
<body '.$bodyOnLoad.'>
<script language="JavaScript">main_path="'.$web_root.'";</script>
<script language="JavaScript" src="'.$web_root.'scripts/wait_window.js"></script>'.
echoIf($redirect2mainSite,'<script language="JavaScript" src="'.$web_root.'scripts/redirect_window.js"></script>','').$logo.'
<table border="0" width="99%" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF" height="100%" name=struct_site>
<tr><td height=20 colspan="3"></td></tr>
    <tr><td valign="top" width=*><!-- левый столбик начало -->
		<table border=0><tr><td width=200 style="display:'.$leftColDispl.'" id="leftMainCol">
	
<table width=160 height=120  border=0 align="center" valign="top" name=main_menu>
<tr class="main_menu"><td><a href="'.$curpage.'?wap" title="просмотреть страницу для КПК">
<p>&nbsp;</p><img src="'.$web_root.'_themes/'.$theme_folder.'/images/wap.gif" border=0></a></td></tr></table>
';


include '_modules/left_menu/index.php';	//подключение главного меню
$head.=$menu_str;



$head_wap=$head;
          

		
$head=$head.'&nbsp;'.$extLink1.'
<div align=center class=middle> Дружественные сайты:</div>'.$tab_begin.'
   <tr class=round_table><td colspan=3 align=center class=baner_img><a href=http://www.ugatu.ac.ru title="Официальный сайт УГАТУ"  target="_blank">
                           <img src='.$web_root.'images/design/baners/baner4.gif alt="Cайт УГАТУ" border=1></a></td></tr>
<tr class=round_table><td colspan=3 align=center class=baner_img><a href=http://www.businessstudio.ru title="Управление бизнесом, бизнес-моделирование, бизнес-процесс, описание бизнес-процессов, оптимизация бизнес-процессов&nbsp;&mdash;&nbsp;Business Studio"  target="_blank">
                           <img src='.$web_root.'images/design/baners/baner8.gif alt="Cайт Business Studio" border=0 style="background-color:White;"></a></td></tr>
<tr class=round_table><td colspan=3 align=center class=baner_img><a href=http://erp4students.ru title="Программа дистанционного обучения решениям SAP для студентов России и стран СНГ, организуемую университетом Дуйсбург-Эссена (Германия)   erp4students"  target="_blank">
                           <img src='.$web_root.'images/design/baners/baner9.gif alt="erp4students" border=0 style="background-color:White;"></a></td></tr>
   <tr class=round_table><td colspan=3 align=center class=baner_img><a href=http://www.ugatu.net title="Портал студгородка УГАТУ"  target="_blank">
                           <img src='.$web_root.'images/design/baners/baner5.gif alt="Cтудгородок УГАТУ" border=1></a></td></tr>
   <tr class=round_table><td colspan=3 align=center class=baner_img><a href=http://www.library.ugatu.ac.ru/index.html title="Библиотека,электронный каталог УГАТУ" target="_blank">
                           <img src='.$web_root.'images/design/baners/baner6.gif alt="Библиотека УГАТУ" border=1></a></td></tr>'.$tab_end;

$query='select 
(select count(*) from news) as new_cnt ,
(select count(*) from users where status="преподаватель") as lect_cnt,
(select count(*) from documents) as subj_cnt,
(select count(*) from files where nameFolder not like "gost%") as files_cnt,
(select count(*) from files where nameFolder like "gost%") as docs_cnt    
';
$res=mysql_query($query);
$a=mysql_fetch_array($res);

$head=$head.'<br>
<div align=center class=middle> Общая статистика:</div>
<ul class=text>
<li><a href="'.$web_root.'index.php">новостей: <b>'.$a['new_cnt'].'</b></a></li>
<li><a href="'.$web_root.'p_lecturers.php">преподавателей: <b>'.$a['lect_cnt'].'</b></a> </li>
<li><a href="'.$web_root.'p_library.php">предметов:  <b>'.$a['subj_cnt'].'</b></a></li>
<li><a href="'.$web_root.'p_library.php">учебных файлов:  <b>'.$a['files_cnt'].'</b></a></li>
<li><a href="'.$web_root.'p_gost_docs_view.php">документов:  <b>'.$a['docs_cnt'].'</b></a></li>
</ul>
';
						   
if ($count_files!=0 || $count_notice!=0 || $count_news!=0 || 1>0) {
	$head=$head.'<div class=middle>Статистика за неделю: </div><ul class=TEXT>';
		if ($count_files!=0 || 1>0) 
			{$head=$head.'<li><a href="'.$web_root.'p_library.php">новых файлов:<b>'.$count_files.'</b> </a> </li>';}
		if ($count_notice!=0 || 1>0) 
			{$head=$head.'<li><a href="'.$web_root.'p_notice.php">новых объявлений: <b>'.$count_notice.'</b></a></li>';}
		if ($count_news!=0 || 1>0) 
			{$head=$head.'<li><a href="'.$web_root.'index.php">новых новостей: <b> '.$count_news.'</b></a></li>';}
	$head=$head.'</ul>';
}
$head=$head.'</td>
		<td valign=top>
			<a href="#hidecol" onclick=\'javascript:hide_show("leftMainCol");hide_show("rightMainCol");saveState("leftMainCol","hide");\' title="скрыть\отразить панель">
				<img src="'.$web_root.'_themes/'.$theme_folder.'/images/hideshow.gif" border=0></a></td>
	</tr></table>
	<!--  левый столбик осн. конец -->  </td>
<td valign="top" width="100%" align=left>
	<table height=100% width=100% align=left border=0><tr><td valign=top align=left><a name="top"></a>
<SCRIPT language="javascript"></SCRIPT>
<NOSCRIPT>
<h3 align=center style="color:red;">Для корректной работы портала требуется включение JavaScript .
<br> Дальнейшая работа будет  ограничена либо невозможна. <br>Обратитесь к администратору портала .<p></h3> </NOSCRIPT>
	
	';

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
<div class=text style="padding-left:20px;">Разделы сайта ('.(count($left_menu)-2).') 
<select name=main_menu style="width:150;" onChange="javascript:window.location.href=this.options[this.selectedIndex].value;">';


$head_wap.='</select> <a href="?'.reset_param_name($_SERVER['QUERY_STRING'],'wap').'" title="вернуться в обычный режим просмотра страницы" class="not_wap"> Обычный режим </a></div>';
//------------------------------------------------------------------
$end1='
</td></tr><tr align=center valign=bottom><td>
'.$baner_text.'</td></tr></table>
</td><td width=185 valign="top" style="display:'.$rightColDispl.';" id="rightMainCol">'.$search;

//сообщество Вконтакте
$end1.='<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?31"></script>
<!-- VK Widget -->
<div id="vk_groups"></div>
<script type="text/javascript">
try {VK.Widgets.Group("vk_groups", {mode: 0, width: "200", height: "290"}, 24892947);}
catch (e) { document.write("<p class=warning>нет интернет-соединения</p>"); }
</script>';

if (!isset($_GET['wap'])) {

$end2=$extLink2.$str_holidays.'<!--div align=center class=middle> Образовательные сайты:</div>'.$tab_begin.'
   <tr><td colspan=3 align=center><a href=http://www.ucheba.ru title="Образовательный сайт">
                           <img src='.$web_root.'images/design/baners/baner1.gif alt="Учеба.ру"></a><br>&nbsp;</td></tr>
   <tr><td colspan=3 align=center><a href=http://www.5ballov.ru title="Студенческий портал">
                           <img src='.$web_root.'images/design/baners/baner2.gif alt="5Баллов.ру"></a><br>&nbsp;</td></tr>
   <tr><td colspan=3 align=center><a href=http://www.bankreferatov.ru title="Крупнейший каталог рефератов">
                           <img src='.$web_root.'images/design/baners/baner3.gif alt="БанкРефератов.ру"></a><br>&nbsp;</td></tr>'.$tab_end.'-->'.$extLink3.
'</tr>
</table>';
}
else {$end2='';}

?>