<?php
//ответы на вопросы пользователям, модерация и пр.
$pg_title="Вопрос-ответ пользователей портала";

include ('authorisation.php');


$main_page=$curpage;
$page=1;
$user_id=0;		//отбор по пользователю
$item_id=0;
$q='';			//строка поиска
$pgVals=20;	//число записей на странице по умолчанию
$sort=4;
$filt_str_display='';	//отражение по чему идет отбор
$err=false;		//нет ошибок при изменении БД
$stype='asc';		//тип сортировки столбца

$showDel=false;		//не отражать удаленные вопросы

$query_string=$_SERVER['QUERY_STRING'];
//user_id sort  q  archiv  page  pgVals

if (isset($_GET['user_id'])) {$user_id=intval($_GET['user_id'],10);$filt_str_display.=' преподавателю;';}
if (isset($_GET['item_id'])) {$item_id=intval($_GET['item_id'],10);$filt_str_display.=' записи;';}

if (isset($_GET['sort'])) {$sort=intval($_GET['sort'],10);}

//if (isset($_GET['user_id'])) {$user_id=$_GET['user_id'];}
if (isset($_GET['q'])) {$q=$_GET['q'];$filt_str_display.='  поиску;';}
//if (isset($_GET['archiv'])) {$query_string=$query_string.'&archiv';}

if (isset($_GET['page']) && $_GET['page']>1) {$page=intval($_GET['page'],10);$filt_str_display.='  странице;';}
if (isset($_GET['pgVals']) && $_GET['pgVals']<=99 && $_GET['pgVals']>=1) {$pgVals=intval($_GET['pgVals'],10);$filt_str_display.=' числу записей;';}
//if (isset($_GET['sort'])) {$sort=$_GET['sort'];}
//echo ' query_string='.$query_string;

if (isset($_GET['stype']) && $_GET['stype']=='desc') {$stype=$_GET['stype'];}
if (isset($_GET['showDel']) && $_GET['showDel']=='true') {$showDel=true;}


if (isset($_GET['type']) && $_GET['type']=='del' && $item_id>0)
	{
	//echo '!!!!!!!del!!!!!!';
	$query="update question2users set status=5 where id=$item_id";	
	
	//echo ' query_string='.$query_string;
	$res=mysql_query($query);	//reset_param_name ($query_string,$param_name)
	//echo 'Location:'.$main_page.'?'.reset_param_name($query_string,'type');	
	$query_string=reset_param_name($query_string,'type');
	$query_string=reset_param_name($query_string,'item_id');
	header('Location:'.$main_page.'?'.$query_string);
	//page=2&pgVals=20&archiv&sort=4	
	}
$admin_role=false;
if ($_SESSION['task_rights_id']==4)	{$admin_role=true;} 

if (!$admin_role && (!isset($_GET['user_id']) || $_GET['user_id']!=$_SESSION['id']))
	{header('Location:?user_id='.$_SESSION['id'].'');}
	
include ('master_page_short.php');

//--------------------------------------------------------------------------------------------
?>

<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}

</style>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
<script type="text/javascript" src="scripts/calendar_init.js"></script>

<script language="JavaScript">
var main_page='<?php echo $main_page;?>';	//for redirect & links
function del_confirm(id,num)
{
	 if (confirm('Удалить запись: '+num+' ?')) 
	 	{window.location.href=main_page+'?item_id='+id+'&type=del&'+'<?php echo $_SERVER["QUERY_STRING"];?>';} 
} 
/*function filter()
{
 if (document.getElementById('orders_type').value!=0)
	{ window.location.href=main_page+"?user_id="+document.getElementById('orders_type').value;}
 else {window.location.href=main_page;}
} */
function go2search(user_id,search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {href_addr='q='+search_query+'&user_id='+user_id;
		if (search_path=='archiv') {href_addr='archiv&'+href_addr+'';}
	 	
		 //alert(href_addr);
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
} 
function day_now()
{
 //alert('сегодня');
 var today_date=new Date();
 var date=today_date.getDate();
 var month=today_date.getMonth()+1;
 var year=today_date.getFullYear();
 var date_string='';
 //alert(day);
 if (date<10) {date_string='0'+date;} else {date_string=date;}
 if (month<10) {date_string+='.'+'0'+month;} else {date_string+='.'+month;}
 date_string+='.'+year;
 document.getElementById('date_order').value=date_string;
 
} 
function pgVals(query_str)
{
 	var pageCnt= parseInt(document.getElementById('pgVals').value);
 	if (pageCnt>0 && pageCnt<100) {document.location.href=main_page+'?'+query_str+'&pgVals='+pageCnt;}
 	else {alert('Введите значение с 1 до 99.');}
 	
	 //if (pageCnt>0 && pageCnt<100) {alert('ok');}
	 
	//alert(pageCnt);
} 
function check_form()
{

 	a = new Array(
	 	new Array('question_text','')
	);
	requireFieldCheck(a,'form1');

} 
</script>

<?php
//phpinfo();
//session_start();

//------------------------------------------------------------
//include ('sql_connect_empty.php');

/**
 * $query_all='SELECT time_intervals.name as year_name, time_intervals.date_start,time_intervals.date_end
 *	FROM settings inner join time_intervals on time_intervals.id=settings.year_id
 *	where 1 limit 0,1';
 * if ( $res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {$def_settings=mysql_fetch_array($res_all);}
 *
 * Переписано для использования новой системы глобальных настроек
 */
$query_all = "
    select
        time_intervals.name as year_name,
        time_intervals.date_start,
        time_intervals.date_end
    from
        time_intervals
    where
        time_intervals.id = ".CUtils::getCurrentYear()->getId();
if ($res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
    $def_settings=mysql_fetch_array($res_all);
}


//добавление записи
//echo '<br><br>';
if (isset($_POST['question_text']))
{
  $question_text=f_ri(trim($_POST['question_text']));
  $user_id=intVal($_POST['user_id'],10);
  $contact_info=f_ri(trim($_POST['contact_info']).' ip '.$_SERVER["REMOTE_ADDR"]);
  $status=intval($_POST['status'],10);
  $answer_text=f_ri(trim($_POST['answer_text']));
  

	if ($question_text!="") 
	{
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

if (isset($_GET['type'])) {
    $query!='';
    //добавление записей только персональных
    if ($_SESSION['task_rights_id']!=4) $user_id=$_SESSION['id'];

    if ($_GET['type']=='edit' & isset($_GET['item_id']) & $_GET['item_id']!='') {
		 //echo 'Правка записи';
		 $item_id=intval($_GET['item_id'],10);
		 $query="update question2users set
			   user_id=$user_id,
			   status=$status,
			   question_text='$question_text',
			   contact_info='$contact_info',
			   answer_text='$answer_text'
			   ".echoIf($answer_text!='',",datetime_answ=now()","")."
			   where id='$item_id'";
	 }
	 
	 //новая запись
	 if ($_GET['type']=='add') {
  $query="insert into question2users(user_id,question_text,contact_info,status,answer_text".echoIf($answer_text!='',",datetime_answ","").") values(
	 $user_id,
	 '$question_text',
	 '$contact_info',
	 $status,
	 '$answer_text'
	 ".echoIf($answer_text!='',",now()","")."
	 )";		 
     }
         if ($query!='') {
         $res=mysql_query($query);
		 if (mysql_affected_rows()) {
             echo '<div class=success> Запись "'.$question_text.'" '.
                echoIf($_GET['type']=='add', 'добавлена', 'обновлена'). 'успешно.'.$onEditRemain_text.'</div><br>';
			}
		 else {echo '<div class="warning">Запись не '.
             echoIf($_GET['type']=='add', 'добавлена', 'обновлена').
               '. Возможно пара значений:студент-преподаватель там уже есть</div><br>';
           $err=true;}
         }
 }
	 }
	else {echo '<div class="warning">Часть обязательных данных не заполнено .<br>&nbsp;</div>';$err=true;}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';

    }
} 

if (isset($_GET['type']) && $_GET['type']=='edit')
{
	if (isset($_GET['item_id']) & $_GET['item_id']!="")
	{//echo '<h4>Правка записи</h4>';
	$query="select * from question2users where id='".$_GET['item_id']."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="warning">не выбрана Запись для правки</h4>';}	
}

//добавление записей
if (isset($_GET['type']) && ($_GET['type']=='add' || $_GET['type']=='edit') )
{
	 $elementActive='';
	 //if ($_SESSION['task_rights_id']!=4 && $_GET['type']=='edit') //если не доступно чтение\правка ВСЕХ записей
	 //{$elementActive='disabled';}
	 
?>
<div><a href="<?php echo 	$main_page.'?'.reset_param_name(reset_param_name($query_string,'item_id'),'type');?>">Просмотр записей </a></div>
<h4> <?php if(isset($_GET['type']) && $_GET['type']=='edit') {echo 'Правка';} else {echo 'Ввод';} ?> новой записи  </h4>
<div class="forms_under_border" style="width:99%;">

<form name=form1 id=form1 class=light_color_max action="" method=post>
  <table class="text" border="0" cellpadding=5>
  <tbody>
  <tr align="left" class=light_color_min>
    <td width="150">Преподаватель<br>
				  (кому адресуется вопрос)</td>
                  <td><select id=user_id name=user_id <?php if($_SESSION['task_rights_id']!=4) echo 'disabled';?> title="Преподаватель" >
      <?php
      $listQuery='select id,concat(fio," (",status,")") as fio from users order by fio';
      echo getFrom_ListItemValue($listQuery,'id','fio','user_id');      
      ?>
      
    </select></td>
  </tr>
  <tr align="left" class=light_color_min>
    <td width="150">Статус вопроса</td>
    <td><select id=status name=status <?php echo $elementActive;?> title="Преподаватель">
      <?php
      $listQuery='select id,name from question_status order by name';
      echo getFrom_ListItemValue($listQuery,'id','name','status');      
      ?>
      
    </select></td>
  </tr>  <tr height="40" align="left" >
    <td >Текст вопроса<span class=warning>*</span></td>
    <td><textarea rows=4 cols=40 id=question_text <?php echo $elementActive;?> name=question_text title="Текст вопроса"><?php echo getFormItemValue('question_text'); ?></textarea></td>
  </tr>
  <tr align="left">
    <td >Контактная информация задавшего вопрос<br>
					  (по умолчанию публиковаться не будет)</td>
    <td><textarea rows=4 cols=40 id=contact_info name=contact_info <?php echo $elementActive;?> title="Контактная информация"><?php echo getFormItemValue('contact_info'); ?></textarea></td>
  </tr>
  <tr height="40" align="left" >
    <td >Текст ответа</td>
    <td><textarea rows=4 cols=40 id=answer_text name=answer_text title="Текст ответа"><?php echo getFormItemValue('answer_text'); ?></textarea></td>
  </tr>
  <tr align="left">
    <td ><input type=button value="Сохранить" onclick=javascript:check_form();></td>
    <td><input type=reset value="Очистить"></td>
  </tr>
  </tbody>
  </table>
</form>
</div> 
<?php
}

else 
	{
?>
	<p class="main"><?php echo $pg_title;?></p>
<?php

	if (!isset($_GET['archiv'])) {

		$query_='select count(*) from `question2users` where 1 and (datetime_quest<"'.$def_settings['date_start'].'" or datetime_quest is NULL) ';
		if ($user_id>0) {$query_.=' and `question2users`.`user_id`="'.$user_id.'"';}
		$archiv_cnt=intval(getScalarVal($query_),10);
		
		echo '<a href="?'.$query_string.'&archiv" title="записи прошлых учебных лет">архив записей: '.$archiv_cnt.'</a><br>';
	}
	else {
		$query_='select count(*) from `question2users` where 1 and datetime_quest>"'.$def_settings['date_start'].'" ';
		if ($user_id>0) {$query_.=' and `question2users`.`user_id`="'.$user_id.'"';}
		$cur_cnt=intval(getScalarVal($query_),10);
 		echo '<a href="?'.reset_param_name(reset_param_name($query_string,'archiv'),'page').'" title="записи прошлых учебных лет">записи текущего учебного года: '.$cur_cnt.'</a><br>';}



$archiv_query=' and datetime_quest>"'.$def_settings['date_start'].'"';
if (isset($_GET['archiv'])) {$archiv_query=' and (datetime_quest<"'.$def_settings['date_start'].'" or datetime_quest is NULL)';}

$search_query='';
if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
$search_query=' and (q2u.question_text like "%'.$q.'%" or 
					q2u.contact_info like "%'.$q.'%" or
                    qs.name like "%'.$q.'%" or 
					q2u.answer_text like "%'.$q.'%" or 
					q2u.datetime_quest like "%'.$q.'%" or q2u.datetime_quest  like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
					q2u.datetime_answ like "%'.$q.'%" or q2u.datetime_answ  like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%")';}

$sort=1;
if (isset($_GET['sort']) && $_GET['sort']>1 && $_GET['sort']<=9) {
	 if ($_GET['sort']==6) {$sort='date_act';}
	 else {$sort=$_GET['sort'];}
 }

//выборка для показа списочной таблицы записей

$query='SELECT q2u.datetime_quest,
	 q2u.question_text,
	 q2u.contact_info,
	 q2u.datetime_answ,
	 q2u.answer_text,
	 qs.name AS qs_name,
       u.FIO_short,
       q2u.user_id,
       q2u.id
  FROM    ( question2users q2u   
           LEFT JOIN question_status qs
           ON (qs.id = q2u.status))
       LEFT JOIN
          users u
       ON (u.id = q2u.user_id)';

if ($user_id>0) {$search_query.=' and u.id="'.$user_id.'" ';}

if (!$showDel) {$search_query.=' and (qs.name is NULL or qs.name not like "удален") ';}


$query=$query." where 1 ".$archiv_query."".$search_query." order by ".$sort." ".$stype." ";
	
$res=mysql_query($query.'limit '.(($page-1)*$pgVals).','.$pgVals);
//echo $query;

if (!isset($_GET['save']) && !isset($_GET['print'])) {
	echo '<p class="notinfo"><a href="?type=add&'.$_SERVER["QUERY_STRING"].'"> Добавить</a><p>';
if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?'.reset_param_name_ARR($query_string,array('user_id','page','q')).'"> сбросить фильтр </a></div>';}
}
	if (mysql_num_rows($res)==0) {
	if (!isset($_GET['archiv'])) echo '<p class=warning style="font-size:12pt; text-align:center;">в текущем году записей не найдено, попробуйте поискать в  <a href="?'.reset_param_name($query_string,'archiv').'&archiv">архиве</a> ';
	else echo '<p class=warning style="font-size:12pt; text-align:center;">в архве записей не найдено, попробуйте поискать в  
		<a href="?'.reset_param_name($query_string,'archiv').'">текущем году</a> ';
	if ($search_query!='') echo ', либо <a href="?">сбросить фильтр</a>';
	echo '</p>';
	 
	 }
	else {

if (!isset($_GET['save']) && !isset($_GET['print'])) {
//-------------------------------------  списочная таблица -----------------------------------------------------	
	echo '<table width=99% class="notinfo" border=0><tr>';	
	echo '<td align=left colspan=2>';
	
		
	
	$add_string=reset_param_name($query_string,'user_id');	//для фильтра по преп-лю
	$add_string=reset_param_name($add_string,'page');	//для перехода к первой странице сортировки по преп-лю
	
	//echo ' admin_role='.$admin_role;
 	if ($admin_role) {
	  	?>
	Пользователь <select name="user_id" id="user_id" style="width:300px;" onChange="javascript:window.location.href='?<?php echo reset_param_name($query_string,'user_id');?>&user_id='+this.options[this.selectedIndex].value;">
	<?php
//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		$query_='SELECT u.id, concat(u.fio, " (",count(*),") ", " - ", ifnull(u.status, "-")) AS fio
  FROM    (   users u
           INNER JOIN
              question2users q2u
           ON (u.id = q2u.user_id))       
WHERE u.id is not null '.$archiv_query.'  
GROUP by u.id
ORDER BY u.fio ASC';
echo getFrom_ListItemValue($query_,'id','fio','user_id');
		?>
</select>
	<?php
		
		//persons_select($add_string.'&page=1&user_id');
		  echo '&nbsp;&nbsp;<input type=button value="Все" onclick=javascript:window.location.href="'.$main_page.'";>&nbsp;&nbsp;';
		  }
 	else {echo 'Пользователь: <b>'.getScalarVal('select fio from users where id="'.$user_id.'"').'</b>'; }
		  
		echo showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'');
		echo '<br><label class=text><input type=checkbox id=showDel name=showDel '.echoIf($showDel,'checked','').' 
		  onclick="javascript:window.location.href=\'?'.reset_param_name($_SERVER['QUERY_STRING'],'showDel').'&showDel=\'+this.checked;">
		  отражать удаленные записи</label>';
		echo ' </td> 
	<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; 
		<input type=button value="Найти" title="поиск проводится в текущем разделе (архив или тек.уч.год) выбором соот.ссылки" OnClick=javascript:go2search(\''.$user_id.'\',\'';
			if (isset($_GET['archiv'])) {echo 'archiv';}
		echo '\');>
		<div class=text style="text-align:right"> кроме полей: ин.яз.,оц. <br>
		Поиск по дате в формате дд.мм.гггг или гггг-мм-дд</div></td>
	</tr></table>';}

//-----------------------------------------------------
$itemCnt=getScalarVal('select count(*) from ('.$query.')t');

if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {$pages_cnt=floor($itemCnt/$pgVals);}
else {$pages_cnt=($itemCnt/$pgVals)+1;}

$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';
//-----------------------------------------------------
echo '<form name=order_list>
<table name=tab1 border=1 cellpadding="0" cellspacing="0" width="99%"><tr align="center" class="title" height="30">';
	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '<td width="50" class="notinfo"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}

//echo $query;

$add_string=reset_param_name($query_string,'sort');
	
//------------------------------------------- шапка списочной таблицы -начало-----------------------------------------------------
	echo '<td width="50">№</td>';

	$table_headers=array(
		1=>array('дата вопроса','40'),
		2=>array('вопрос','100'),
		3=>array('контакты','50'),
		4=>array('дата ответа','40'),
		5=>array('ответ','100'),		
		6=>array('статус','20')
		);
	
	if ($admin_role && $user_id==0) array_push($table_headers, array('адресат','130'));

	
	for ($i=1;$i<=count($table_headers);$i++)
	{
		echo '<td width="'.$table_headers[$i][1].'">'.print_col($i,$table_headers[$i][0]).'</td>';
	}
	//if (!isset($_GET['save']) && !isset($_GET['print'])) {echo '<td width="100" class="notinfo">комментарий</td>';}
//------------------------------------------- шапка списочной таблицы -конец-----------------------------------------------------
	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};
	$i=0;
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		echo '<tr align="left" class="main" '.$bgcolor.' valign="top" style="'.echoIf($tmpval['qs_name']=='удален','color:Grey;','').echoIf($tmpval['qs_name']=='опубликован','color:#007700;','').'">';
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		  echo '<td align="center"> 
		  	<a href="javascript:del_confirm(\''.f_ro($tmpval['id']).'\',\''.str_replace(" ","_",f_ro($tmpval['question_text'])).'\');" title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="?item_id='.$tmpval['id'].'&type=edit&'.$query_string.'" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		$i++;
		echo '<td>&nbsp;'.($i+($page-1)*$pgVals).'</td>';
		echo '<td>&nbsp;'.color_mark($q,DateTimeCustomConvert($tmpval['datetime_quest'],'dt','mysql2rus')).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['question_text']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['contact_info']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,DateTimeCustomConvert($tmpval['datetime_answ'],'dt','mysql2rus')).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['answer_text']).'</td>';        
        echo '<td>&nbsp;'.color_mark($q,$tmpval['qs_name']).'</td>';

        if ($admin_role && $user_id==0)
            echo '<td>&nbsp;<a href="p_lecturers.php?onget=1&idlect='.$tmpval['user_id'].'" title="о преподавателе">'.color_mark($q,$tmpval['FIO_short']).'</td>';//
		
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
            if (array_key_exists("comment", $tmpval)) {
                echo '<td class="notinfo">&nbsp;'.color_mark($q,$tmpval['comment']).'</td>';
            }
        }
	}
echo '</table></form>';
	}
//-------------------------------------списочная таблица -конец----------------------------------------------------

//постраничный вывод списка записей (по 10 записей)
echo '<div align="center"> страницы ';
$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo getPagenumList($pages_cnt,$page,6,'page',$add_string,'');
echo '</div>';
//--------------------------------------------------------

$add_string=reset_param_name($add_string,'pgVals');// preg_replace("/(&pgVals=)(\d+)/x","",$add_string);		//убрать число страниц через RegExp
echo '<br>макс.число записей на странице:  <input type=text value="'.$pgVals.'" name="pgVals" id="pgVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgVals(\''.$add_string.'\');" value=Ok>
	<p> Всего записей: '.mysql_num_rows($res).'</div>'; 	
	}

if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>