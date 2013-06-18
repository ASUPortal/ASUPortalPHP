<?php
include ('authorisation.php');

if (isset($_GET['type']) && $_GET['type']=='del' && intval($_GET['item_id'])>0 && $write_mode)
{
	 $query='delete from diplom_previews where id="'.intval($_GET['item_id']).'"';	
	 $res=mysql_query($query);	
	 header('Location:'.$curpage.'?'.reset_param_name_ARR($_SERVER['QUERY_STRING'],array('type','item_id')));
}

if (!$view_all_mode && (!isset($_GET['kadri_id']) || $_GET['kadri_id']!=$_SESSION['kadri_id'])) 
	{header('Location:?kadri_id='.intval($_SESSION['kadri_id']).'');}


include ('master_page_short.php');


//-----------настройка формы ------------
//----------------------


$main_page=$curpage;
$page=1;
$comm_id=0;	//отбор по комиссии предзащиты	
$kadri_id=0;	//отбор по сотруднику в составе комиссии
$q='';			//строка поиска
$pgVals=20;	//число тем на странице по умолчанию
$sort=4;
$filt_str_display='';	//отражение по чему идет отбор
$err=false;		//нет ошибок при изменении БД
$stype='asc';		//тип сортировки столбца


$query_string=$_SERVER['QUERY_STRING'];

//!if (isset($_GET['kadri_id']) && intval($_GET['kadri_id']>0) ) {$comm_id=intval($_GET['kadri_id']);$filt_str_display.=' преподавателю;'.del_filter_item('kadri_id');}

if (isset($_GET['q']) && trim($_GET['q'])!='') {$q=f_ri($_GET['q']);$filt_str_display=$filt_str_display.'  поиску;'.del_filter_item('q');}
if (isset($_GET['page']) && intval($_GET['page'])>1) {$page=intval($_GET['page']);$filt_str_display=$filt_str_display.'  странице;';}
if (isset($_GET['pgVals']) && intval($_GET['pgVals'])<=999 && intval($_GET['pgVals'])>=1) {$pgVals=intval($_GET['pgVals']);$filt_str_display=$filt_str_display.' числу записей;';}
if (isset($_GET['stype']) && ($_GET['stype']=='desc' || $_GET['stype']=='asc')) {$stype=$_GET['stype'];}

if (isset($_GET['item_id'])) 	{$item_id=intval($_GET['item_id']);}
if (isset($_GET['comm_id'])) 	{$comm_id=intval($_GET['comm_id']);$filt_str_display.=' комиссии;'.del_filter_item('comm_id');}
if (isset($_GET['kadri_id'])) 	{$kadri_id=intval($_GET['kadri_id']);}
if (isset($_GET['sort'])) 	{$sort=intval($_GET['sort']);}


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

function go2search(comm_id,search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {href_addr='q='+search_query+(comm_id>0?'&comm_id='+comm_id:'');
		if (search_path=='archiv') {href_addr='archiv&'+href_addr+'';}	 	
		 
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
} 
function check_form()	//проверить данные формы перед отправкой
{
var err=false;
var date_preview=document.getElementById('date_preview');

if (date_check(date_preview.value)) 
	{
	 err=true;
	 alert('Дата не существует. воспользуйтесь календарем;');
	}
else {
 	a = new Array(	 	
	 	new Array('student_id',''),
	 	new Array('comm_id','')
	);
	requireFieldCheck(a,'order_form');
	
	}

}
function start_gr_event(form_name)	//групповые операции
{
	 if (check_cnt(form_name)) document.forms[form_name].submit();
	 else alert('Не выбран ни один элемент для групповой операции !');	 
}
</script>

<div class=main style="text-align:left;" > <?php echo $pg_title ?></div>
<div class=text>связанные справочники:
	 <a href="students_view.php">список студентов</a>,
	 <a href="diploms_view.php">дипломные темы студентов</a>,
	 <a href="diploms_preview_comm.php">комиссии по предзащите</a>
</div>
<?php

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
        time_intervals.date_end,
		time_intervals.id as year_id
    from
        time_intervals
    where
        time_intervals.id = ".CUtils::getCurrentYear()->getId();
if ($res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
    $def_settings=mysql_fetch_array($res_all);
}


//добавление темы

if (isset($_POST['student_id']) && $write_mode)
{
	if ($_POST['comm_id']!=0) 
	{
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

	 //обновление темы
	 if (isset($_GET['type']) && $_GET['type']=='edit' && $item_id>0) {		 
		 $query="update diplom_previews set 
		  student_id='".intval($_POST["student_id"])."',
		  diplom_percent='".intval($_POST["diplom_percent"])."',
		  comm_id='".intval($_POST["comm_id"])."',
		  date_preview='".f_ri(DateTimeCustomConvert($_POST["date_preview"],'d','rus2mysql'))."',
		  another_view='".($_POST["another_view"]=='on'?1:0)."',  
		  comment='".f_ri($_POST["comment"])."' 
			  where id='".$item_id."'";

		 if ($res=mysql_query($query)) {
		  	echo '<div class=success> Запись обновлена  успешно.'.$onEditRemain_text.'</div><br>'; 	
						}
		 else {echo '<div class="warning">Запись не обновлена .<p>&nbsp;</div>';$err=true;}
		 //echo $query;
	 }
	 
	 //новая тема
	 if (isset($_GET['type']) & $_GET['type']=='add') {	
		 $query="insert into diplom_previews (student_id,diplom_percent,another_view,date_preview,comm_id,comment) 
		 	values(
			'".intval($_POST["student_id"])."',
			'".intval($_POST["diplom_percent"])."',
			'".($_POST["another_view"]=='on'?1:0)."',
			'".f_ri(DateTimeCustomConvert($_POST["date_preview"],'d','rus2mysql'))."', 
			'".intval($_POST["comm_id"])."',
			'".f_ri($_POST["comment"])."'
			 )";
		 
		 if ($res=mysql_query($query)) {
		  	echo '<div class=success> Запись добавлена успешно.'.$onEditRemain_text.'</div><br>';
			}
		 else {echo '<div class="warning">Запись не добавлена. Возможно пара значений:студент-комиссия там уже есть</div><br>';$err=true;}
	//echo $query;
	 }
	 
	 }
	else {echo '<div class="warning">Часть обязательных данных не заполнено .<br>&nbsp;</div>';$err=true;}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
} 

if (isset($_GET['type']) && $_GET['type']=='edit' && $write_mode)	//Правка темы
{
	if ($item_id>0)
	{
	$query="select * from diplom_previews where id='".$item_id."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="warning">не выбрана запись для правки</h4>';}	
}

//добавление записей
if (isset($_GET['type']) && ($_GET['type']=='add' || $_GET['type']=='edit') && $write_mode)
{
?>
<div><a href="<?php echo 	$main_page.'?'.reset_param_name(reset_param_name($query_string,'item_id'),'type');?>">просмотр записей </a></div>
<h4> <?php if(isset($_GET['type']) && $_GET['type']=='edit') {echo 'Правка';} else {echo 'Ввод';} ?> новой записи </h4>
<div class="forms_under_border" style="width:99%;">
<form name="order_form" id="order_form" method="post" action="">

Студент <a class=help title="фильтрация по учебному году, наличию дипломного проекта">?</a> <span class=warning>*</span> <br>
<select name="student_id" id="student_id" style="width:500;" title="cтудент">
		<?php
		$query='SELECT s.id, concat(s.fio," (",sg.name,")") as fio
			  FROM    study_groups sg RIGHT OUTER JOIN  students s  ON (sg.id = s.group_id) 
			  left join diploms d on d.student_id=s.id 
			  WHERE (sg.year_id = '.$def_settings['year_id'].') and d.id is NOT NULL
			  group by s.id, concat(s.fio," (",sg.name,")") 
			  order by 2';
		echo getFrom_ListItemValue($query,'id','fio','student_id');
		?>
</select>
<?php echo sprav_edit_link('students');?>
<p>
Выполнения работы на момент предзащиты <br>
<span class=text>готовность (0-100),%</span><input type=text maxlength=10 size=15 id=diplom_percent name=diplom_percent value="<?php echo getFormItemValue('diplom_percent'); ?>" title="в виде натурального числа">	 
<label><span class=text style="margin-left:40px;"> прослушать еще раз</span>
<input type=checkbox  id=another_view name=another_view <?php echo ( (getFormItemValue('another_view')=='1' ||  getFormItemValue('another_view')=='on')?'checked':'') ?>></label>
<p>
	 
Дата предзащиты	 <br>
<input type=text maxlength=10 size=15 id=date_preview name=date_preview value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['date_preview'],0,10),'d','mysql2rus'));}else if (isset($_POST['date_preview'])) {echo $_POST['date_preview'];}else {  	 
	 echo date("d.m.Y");
	 } ?>"> 
		  <button type="reset" id="f_trigger_date_preview">...</button>
		  <script type="text/javascript">
	      Calendar.setup({
		  inputField     :    "date_preview",      // id of the input field
		  ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
		  showsTime      :    false,            // will display a time selector
		  button         :    "f_trigger_date_preview",   // trigger for the calendar (button ID)
		  singleClick    :    true,           // double-click mode false
		  step           :    1                // show all years in drop-down boxes (instead of every other year as default)
	      });
		  </script>
<p>
	 
Комиссия по предзащите <span class=warning>*</span> <br><select name="comm_id" id="comm_id" style="width:500;" title="комиссия по предзащите"> 
		<?php
		$query="select dpc.id,concat(dpc.name,' (',k_secr.fio_short,')') AS name 
			   FROM diplom_preview_committees dpc 
			   LEFT OUTER JOIN kadri k_secr ON (k_secr.id = dpc.secretary_id) ";
		if (!$view_all_mode) {$query.=" where k_secr.id='".$kadri_id."' or dpc.id in (
		  select comm_id from diplom_preview_kadri dpk where dpk.kadri_id='".$kadri_id."'
		  )";}
		$query.=" order by 2";
		
		echo getFrom_ListItemValue($query,'id','name','comm_id');
		?>
</select><?php echo sprav_edit_link('diplom_preview_committees');?>
<p>

Комментарий<br><input type=text style="width:500;" name=comment value="<?php echo getFormItemValue('comment'); ?>"> <p>

<input type=button onclick=javascript:check_form(); value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>
</div> 
<?php
}

else 
	{
	if (!isset($_GET['archiv'])) {	
		
		$query_='select count(*) from `diplom_previews` where 1 and (date_preview<"'.$def_settings['date_start'].'") ';
		if ($comm_id>0) {$query_.=' and `diplom_previews`.`comm_id`="'.$comm_id.'"';}
		$archiv_cnt=intval(getScalarVal($query_),10);
		
		echo '<a href="?'.$query_string.'&archiv" title="записи прошлых учебных лет">архив: '.$archiv_cnt.'</a><br>';
	}
	else {
		$query_='select count(*) from `diplom_previews` where 1 and (date_preview>="'.$def_settings['date_start'].'" or date_preview is NULL) ';
		if ($comm_id>0) {$query_.=' and diplom_previews.`comm_id`="'.$comm_id.'"';}
		$cur_cnt=intval(getScalarVal($query_),10);
 		echo '<a href="?'.reset_param_name(reset_param_name($query_string,'archiv'),'page').'" title="записи прошлых учебных лет">записи текущего учебного года: '.$cur_cnt.'</a><br>';}



$archiv_query=' and (date_preview>="'.$def_settings['date_start'].'" or date_preview is NULL)';
if (isset($_GET['archiv'])) {$archiv_query=' and (date_preview<"'.$def_settings['date_start'].'" )';}

$search_query='';
if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
$search_query=' and (
				        convert(d.dipl_name USING utf8) like "%'.$q.'%" or 
					convert(k_rez.fio_short USING utf8) like "%'.$q.'%" or 
					convert(k_secr.fio_short USING utf8) like "%'.$q.'%" or '.
					($comm_id==0?'convert(dpc.name USING utf8) like "%'.$q.'%"
					 or convert(k_secr.fio_short USING utf8) like "%'.$q.'%" or ':' ').					
				       'convert(s.fio USING utf8) like "%'.$q.'%" or
				        convert(sg.name USING utf8) like "%'.$q.'%" or
					dp.date_preview like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
					convert(dp.comment USING utf8) like "%'.$q.'%"
		    )';}

	$table_headers=array(
		1=>array('студент','40'),
		array('тема','200'),
		array('выполнение работы,%','20'),
		array('прослушать еще раз','20'),
		array('рецензент','20'),
		array('дата предзащиты','20')
		);
		
	 if ($comm_id==0)	//если  фильтр по комиссии не указан, выводить комиссии
		array_push($table_headers, array('номер и состав комиссии','100'));

$def_sort=1;
if ($sort<1 && $sort>=cont($table_headers))  {$sort=$def_sort;}
 
//	-----------------------групповые операции начало------------------------------

if (isset($_GET['gr_act']) && isset($_POST['comm_id_gr']))	{
	 $comm_id_gr=intval($_POST['comm_id_gr']);
				
	 $err=false;

	 while (list($key, $value) = each ($_POST)) {
		 if 	  (strstr($key,"checkbox_tab_item_")) {
			   $act_item_id=intval(preg_replace("/\D/","",$key));			  
		   $query_gr_act='update diplom_previews set comm_id='.$comm_id_gr.' where id='.$act_item_id.' limit 1';
		    
		   if (! ($res=mysql_query($query_gr_act)) ) {$err=true;echo '<div class=warning> ошибка группового обновления записи id='.$act_item_id.'</div>';}
	  
		  }
	 }

	 if ($err==true)	{echo '<div class=warning> Произошли ошибки при выполнении массовой операции </div>';}
	 else {echo '<div class=success> Выполнение массовой операции успешно</div>';}
					}
//	-----------------------групповые операции конец------------------------------

//выборка для показа списочной таблицы записей

$query="SELECT concat(s.fio,' - ',sg.name) as stud_name,
       d.dipl_name,
       dp.diplom_percent,
       dp.another_view,
       concat(IFNULL(k_rez.fio_short,''),IF(IFNULL(d.recenz,'')!='',concat(' - ',substring(d.recenz,1,30),'...'),'')) AS rec_fio,
       dp.date_preview, ".      
       ($comm_id==0?"concat(dpc.name,' (',k_secr.fio_short,')') AS comm_name,":"").
       "dp.comment,
       dp.id,
       dpc.id as comm_id,
       d.id as dipl_id 
  FROM    diplom_previews dp 
          LEFT OUTER JOIN diplom_preview_committees dpc ON (dpc.id = dp.comm_id)
          LEFT OUTER JOIN students s ON (s.id = dp.student_id) 
	  LEFT OUTER JOIN study_groups sg ON (sg.id = s.group_id) 
          LEFT OUTER JOIN kadri k_secr ON (k_secr.id = dpc.secretary_id) 
	  LEFT OUTER JOIN diploms d ON (dp.student_id = d.student_id) 
	  LEFT OUTER JOIN kadri k_rez ON (k_rez.id = d.recenz_id)";		
		

if ($comm_id>0) 
	{$search_query.=' and dpc.id="'.$comm_id.'"';}

if ($kadri_id>0) 
	{$search_query.=' and k_secr.id="'.$kadri_id.'" or dpc.id in (
		  select comm_id from diplom_preview_kadri dpk where dpk.kadri_id="'.$kadri_id.'"
		  )';}


$query=$query." where 1 ".$archiv_query."".$search_query;
	
$res=mysql_query($query.' order by '.$sort.' '.$stype.' limit '.(($page-1)*$pgVals).','.$pgVals);
//echo $query;

if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode) {
	echo '<p class="notinfo"><a href="?type=add&'.$_SERVER["QUERY_STRING"].'"> Добавить</a><p>';
if ($filt_str_display!='') {echo '<div class=text><img src="images/filter.gif" alt="фильтр" border=0>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; сбросить фильтр<a class=button href="?'.reset_param_name_ARR($query_string,array('comm_id','page','q','notconfirm')).'" title="сбросить фильтр"><img src="images/del_multi_filter.gif" alt="сбросить фильтр" border=0></a></div>';}
}
	if (mysql_num_rows($res)==0) {
	 
	if (!isset($_GET['archiv'])) echo '<p class=warning style="font-size:12pt; text-align:center;">в текущем году записей не найдено, попробуйте поискать в  <a href="?'.reset_param_name($query_string,'archiv').'&archiv">архиве</a> ';
	else echo '<p class=warning style="font-size:12pt; text-align:center;">в архиве записей не найдено, попробуйте поискать в  
		<a href="?'.reset_param_name($query_string,'archiv').'">текущем году</a> ';
	if ($search_query!='') echo ', либо <a href="?">сбросить фильтр</a>';
	echo '</p>';
	 
	 }
	else {

if (!isset($_GET['save']) && !isset($_GET['print'])) {
//-------------------------------------  списочная таблица -----------------------------------------------------	
	echo '<table width=99% class="notinfo" border=0><tr>';	
	echo '<td align=left colspan=2 class=text>';
	
		
	
	$add_string=reset_param_name($query_string,'kadri_id');	//для фильтра по преп-лю
	$add_string=reset_param_name($add_string,'page');	//для перехода к первой странице сортировки по преп-лю
	
	
 	if ($view_all_mode) {
	  	?>
	Комиссия <select name="comm_id" id="comm_id" style="width:300;" onChange="javascript:window.location.href='?<?php echo reset_param_name($query_string,'comm_id');?>&comm_id='+this.options[this.selectedIndex].value;"> 
	<?php

		$query_list="select distinct dpc.id,concat(dpc.name,' (',k_secr.fio_short,') - ',count(*)) AS name 
			   FROM diplom_preview_committees dpc 
			   LEFT OUTER JOIN kadri k_secr ON (k_secr.id = dpc.secretary_id)
			   INNER JOIN diplom_previews dp ON (dp.comm_id = dpc.id) 
			   where 1 ";
		if (!$view_all_mode) {$query_list.=" and k_secr.id='".$kadri_id."' or dpc.id in (
		  select comm_id from diplom_preview_kadri dpk where dpk.kadri_id='".$kadri_id."'
		  )";}
		$query_list.=" group by dpc.id 
			  order by 2";
		
echo getFrom_ListItemValue($query_list,'id','name','comm_id');
		?>
</select>
	<?php
		  echo '&nbsp;&nbsp;<input type=button value="Все" onclick=javascript:window.location.href="'.$main_page.'";>&nbsp;&nbsp;';
		  }
 	else {echo 'Комиссия: <b>'.getScalarVal("select concat(dpc.name,' (',k_secr.fio_short,')') AS name
						from diplom_preview_committees dpc
						LEFT OUTER JOIN kadri k_secr ON (k_secr.id = dpc.secretary_id) 
						where dpc.id='".$comm_id."'").'</b>'; }
	
		echo showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'');
		?>
		<span style="white-space: nowrap;">
		<img src=images/report.png border=0 style="margin-left:20px;" title="отчетность">		
		<?php
		echo ' комиссии: ';
		echo showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'_modules/docs_tpl/diplom_preview_comm_rpt.php');
		echo ' рецензенты: ';
		echo showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'_modules/docs_tpl/diplom_preview_recenz_rpt.php');
		echo ' </span></td> 
	<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; 
		<input type=button value="Найти" title="поиск проводится в текущем разделе (архив или тек.уч.год) выбором соот.ссылки" OnClick=javascript:go2search(\''.$comm_id.'\',\'';
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
echo '<form name="order_list" id="order_list" method="POST" action="?'.reset_param_name($query_string,'gr_act').'&gr_act">
<table name=tab1 id=tab1 border=1 cellpadding="0" cellspacing="0" width="99%"><tr align="center" class="title" height="30">';
	if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode) {
		echo '
		<td width="10"><input type=checkbox name="checkbox_all" id="checkbox_all" title="групповые операции" onClick="javascript:mark_all_checkbox(this.id,\'order_list\');"> </td>
		<td width="50" class="notinfo"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}

$add_string=reset_param_name($query_string,'sort');
	
//---------------------------- шапка списочной таблицы -начало-----------------------------------------------------
	echo '<td width="50">№</td>';

	for ($i=1;$i<=count($table_headers);$i++)
	{
		echo '<td width="'.$table_headers[$i][1].'">'.print_col($i,$table_headers[$i][0]).'</td>';
	}
	if (!isset($_GET['save']) && !isset($_GET['print']) ) 
		{echo '<td width="100" class="notinfo">комментарий</td>';}
//----------------------------- шапка списочной таблицы -конец-----------------------------------------------------
	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};
	$i=0;
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		echo '<tr align="left" class="row_light" '.$bgcolor.' id="row'.$tmpval['id'].'" valign="top" >';
		if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode) {
		  echo '<td width="10"><input type=checkbox id="checkbox_tab_item_'.$tmpval['id'].'" name="checkbox_tab_item_'.$tmpval['id'].'" title="выбор элемента"> </td>
			<td align="center" > 
		  	<a href="javascript:del_confirm_act(\''.f_ro(substr($tmpval['stud_name'],0,50)).'...\',\'?item_id='.$tmpval['id'].'&type=del&'.reset_param_name_ARR($query_string,array('item_id','type')).'\');" title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="?item_id='.$tmpval['id'].'&type=edit&'.reset_param_name_ARR($query_string,array('item_id','type')).'" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		$i++;
		echo '<td>&nbsp;'.($i+($page-1)*$pgVals).'</td>';		
		echo '<td>&nbsp;'.color_mark($q,$tmpval['stud_name']).'</td>';
		echo '<td>&nbsp;<a href="diploms_view.php?item_id='.$tmpval['dipl_id'].'&type=edit&" title="о  дипломе">'.color_mark($q,$tmpval['dipl_name']).'</a></td>';
		echo '<td align=center>&nbsp;'.(intval($tmpval['diplom_percent'])>0?$tmpval['diplom_percent']:'').'</td>';		
		echo '<td align=center>&nbsp;'.(intval($tmpval['another_view'])>0?'<img src="images/accept.png" title="подтверждено">':'').'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['rec_fio']).'</td>';
		
	        $date_preview=$tmpval['date_preview'];		
		$date_preview=substr($date_preview,0,10);
		$date_preview=DateTimeCustomConvert($date_preview,'d','mysql2rus');
		echo '<td>&nbsp;'.color_mark($q,$date_preview).'</td>';
		
		if ($comm_id==0) echo '<td>&nbsp;<a href="diploms_preview_comm.php?item_id='.$tmpval['comm_id'].'&type=edit&" title="подробнее">'.color_mark($q,$tmpval['comm_name']).'</a></td>';

		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.color_mark($q,$tmpval['comment']).'</td>';}
	}
echo '</table>';
?>
<script language="javascript">
	 markTableRowsInit('tab1');	//сделать выделение строки в IE
</script>
	 <?php
	 if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode) {
	 ?>
	 <div class=text>
		укажите комиссию и прикрепляемых к ней студентов
	        <select name="comm_id_gr" id="comm_id_gr" style="width:300;" title="комиссия по предзащите при массовых операциях"> 
		<?php
		$query="select dpc.id,concat(dpc.name,' (',k_secr.fio_short,') - ',IFNULL(dp.cnt,0)) AS name 
			   FROM diplom_preview_committees dpc 
			   LEFT OUTER JOIN kadri k_secr ON (k_secr.id = dpc.secretary_id)
			   LEFT JOIN 
				(
				select comm_id,count(*) as cnt from diplom_previews group by  comm_id
				)dp ON (dp.comm_id = dpc.id) 
			   where 1
			   ";
		if (!$view_all_mode) {$query.=" where k_secr.id='".$kadri_id."' or dpc.id in (
		  select comm_id from diplom_preview_kadri dpk where dpk.kadri_id='".$kadri_id."'
		  )";}
		$query.="  group by dpc.id 
			   order by 2";
		
		echo getFrom_ListItemValue($query,'id','name','comm_id_gr');
		?>
		  </select>		  
		  
		  <input type=button value="Ok" onClick="javascript:start_gr_event('order_list');" style="width:40;">
	 </div>	 
	 <?php
	 }

//-------------------------------------------------------
echo '<div align="center"> страницы ';
$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo getPagenumList($pages_cnt,$page,6,'page',$add_string,'');
echo '</div>';
//--------------------------------------------------------

$add_string=reset_param_name($add_string,'pgVals'); 
echo '<br>макс.число записей на странице:  <input type=text value="'.$pgVals.'" name="pgVals" id="pgVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgValsCh(\''.$add_string.'\');" value=Ok>
	<p> Всего записей: '.$itemCnt.'</div>'; 	
	}
//-------------------------------------списочная таблица -конец----------------------------------------------------

//постраничный вывод списка тем (по 10 тем)
	
	}
?>
<div class=text>
	<div><b>Примечание:</b></div>
	<ul>
		<li>наименование дипломного проекта и его рецензент формируются автоматически
			на основе сведений о последнем дипломном проекте текущего студента</li>
		<li>в названии комиссии указывается ее секретарь и число "привязанных" студентов</li>
		<li>при просмотре "своих записей" в задаче отражаются только комиссии,
			в составе которых присутствует текущий пользователей</li>
		<li>при выборе ссылки в списочной таблице с наименованием комиссии
			проиходит переход к правке указанной комиссии при наличии прав</li>
		<li>при выборе ссылки в списочной таблице с наименованием дипломного проекта
			происходит переход к правке указанного дипломного проекта</li>
	</ul>
</div>
<?php

if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>