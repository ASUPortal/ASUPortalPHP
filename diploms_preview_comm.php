<?php
include ('authorisation.php');

function addKadri2Comm($comm_id)
{
	//сформировать список сотрудников в комиссии-----------------------------------
	$kadri_cnt=intval($_POST['max_kadri_list']);				
	 $err=false;
	 mysql_query('delete from diplom_preview_kadri where comm_id='.$comm_id.' ');
	 while (list($key, $value) = each ($_POST)) {
		 //echo '!!!'.$key.'='.$value.'<br>';
		 if 	  (strstr($key,"kadri_comm") && intval($value)>0) {
		   $query_gr_act='insert into diplom_preview_kadri(kadri_id,comm_id) 
		   values ('.intval($value).','.intval($comm_id).') ';
		   //echo  $query_gr_act;
		   if (! ($res=mysql_query($query_gr_act)) ) {$err=true;echo '<div class=warning> ошибка добавления в комиссию сотрудника с id="'.intval($value).'"</div>';}
	  
		  }
	 }
	
	 if ($err==true)	{echo '<div class=warning> Произошли ошибки при добавлении в комиссию сотрудников </div>';}
	
}

if (isset($_GET['type']) && $_GET['type']=='del' && intval($_GET['item_id'])>0 && $write_mode)
{
	 $query='delete from diplom_preview_committees where id="'.intval($_GET['item_id']).'"';	
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
$comm_id=0;	//отбор по комиссии предзаписи	
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
//if (isset($_GET['comm_id'])) 	{$comm_id=intval($_GET['comm_id']);$filt_str_display.=' комиссии;'.del_filter_item('comm_id');}
//if (isset($_GET['kadri_id'])) 	{$kadri_id=intval($_GET['kadri_id']);}
if (isset($_GET['sort'])) 	{$sort=intval($_GET['sort']);}


//--------------------------------------------------------------------------------------------
?>

<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}

</style>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
<script type="text/javascript" src="scripts/calendar_init.js"></script>
<script type="text/javascript" src="scripts/rows_edit.js"></script>
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
	a = new Array(	 	
		new Array('secretary_id',''),
		new Array('name',''),
		new Array('date_act','')
	);
	requireFieldCheck(a,'order_form');
}

function start_gr_event(form_name)	//групповые операции
{
	 if (check_cnt(form_name)) document.forms[form_name].submit();
	 else alert('Не выбран ни один элемент для групповой операции !');	 
}

</script>

<div class=main style="text-align:left;" > <?php echo $pg_title ?></div>
<div class=text>связанные справочники:
	 <a href="lect_anketa_view.php">сотрудники</a>,
	 <a href="diploms_preview.php">предзащиты дипломных проектов-студенты</a>	 
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
        time_intervals.date_end
    from
        time_intervals
    where
        time_intervals.id = ".CUtils::getCurrentYear()->getId();
if ($res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
    $def_settings=mysql_fetch_array($res_all);
}


//добавление записи

if (isset($_POST['secretary_id']) && $write_mode)
{
	if ($_POST['name']!="") 
	{
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

	 //обновление темы
	 if (isset($_GET['type']) && $_GET['type']=='edit' && $item_id>0) {		 
		 $query="update diplom_preview_committees set 
		  secretary_id='".intval($_POST["secretary_id"])."',
		  name='".f_ri($_POST["name"])."',
		  date_act='".f_ri(DateTimeCustomConvert($_POST["date_act"],'d','rus2mysql'))."',
		  comment='".f_ri($_POST["comment"])."' 
			  where id='".$item_id."'";

		 if ($res=mysql_query($query)) {
			//сформировать список сотрудников в комиссии-----------------------------------
			addKadri2Comm($item_id);
			//-----------------------------------------------------------------------------
			echo '<div class=success> Запись обновлена  успешно.'.$onEditRemain_text.'</div><br>'; 	
						}
		 else {echo '<div class="warning">Запись не обновлена .<p>&nbsp;</div>';$err=true;}
		 //echo $query;
	 }
	 
	 //новая запись
	 if (isset($_GET['type']) & $_GET['type']=='add') {	
		 $query="insert into diplom_preview_committees (secretary_id,name,comment,date_act) 
		 	values(
			'".intval($_POST["secretary_id"])."',
			'".f_ri($_POST["name"])."',
			'".f_ri($_POST["comment"])."',
			'".f_ri(DateTimeCustomConvert($_POST["date_act"],'d','rus2mysql'))."'
			 )";
		 
		 if ($res=mysql_query($query)) {
			//сформировать список сотрудников в комиссии-----------------------------------
			$last_item_id=getScalarVal('select max(id) from diplom_preview_committees');
			if (intval($last_item_id)>0) addKadri2Comm($last_item_id);
			//-----------------------------------------------------------------------------
		  	echo '<div class=success> Запись добавлена успешно.'.$onEditRemain_text.'</div><br>';
			}
		 else {echo '<div class="warning">Запись не добавлена. Возможно пара значений:секретарь-комиссия там уже есть</div><br>';$err=true;}
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
	$query="select * from diplom_preview_committees where id='".$item_id."'";
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

Секретарь <span class=warning>*</span> <br><select name="secretary_id" id="secretary_id" style="width:500;" title="секретарь"> 
		<?php
		$query='SELECT kadri.id, concat(kadri.fio, " - ", kadri_role(kadri.id,",")) AS fio
			FROM    kadri
			ORDER BY 2 ASC';
		echo getFrom_ListItemValue($query,'id','fio','secretary_id');
		?>
</select><?php echo sprav_edit_link('kadri');?>  
<p>

Наименование комиссии <span class=warning>*</span><br>
<input type=name style="width:500;" name=name value="<?php echo getFormItemValue('name'); ?>" title="Наименование комиссии"> <p>

Дата создания комисии <span class=warning>*</span><br>
<input type=text maxlength=10 size=15 id=date_act name=date_act value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['date_act'],0,10),'d','mysql2rus'));}else if (isset($_POST['date_act'])) {echo $_POST['date_act'];}else { echo date("d.m.Y"); 
	 } ?>" title="Дата создания комисии"> 
		  <button type="reset" id="f_trigger_date_act">...</button>
		  <script type="text/javascript">
	      Calendar.setup({
		  inputField     :    "date_act",      // id of the input field
		  ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
		  showsTime      :    false,            // will display a time selector
		  button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
		  singleClick    :    true,           // double-click mode false
		  step           :    1                // show all years in drop-down boxes (instead of every other year as default)
	      });
		  </script>
<p>

<?php
$row_id=0;	//текущий сотрудник в комиссии
$row_cnt=1;	//число сотрудников в комиссии

if (isset($res_edit) && intval($res_edit['id'])>0)
{
$query_k_list='SELECT dpk.kadri_id 
	FROM diplom_preview_kadri dpk
	where dpk.comm_id='.$res_edit['id'];
$res_k_list=mysql_query($query_k_list);
$row_cnt=mysql_numrows($res_k_list);

//print_r($a_k_list);
//echo $query_k_list;
}
if ($row_cnt==0) $row_cnt=1;	//возможность добавления при пустом списке сотрудников в комиссии
?>
Состав комиссии, человек <input type=text disabled name="max_kadri_list" id="max_kadri_list" value="<?php echo $row_cnt;?>" size=5 style="text-align:right;">: 
<div id="kadri_list" name="kadri_list" >
   <table border="0" cellspacing="2" cellpadding="0" >
     <tr id="newline" nomer="_<?php echo $row_id; ?>" colspan=2>
       <td valign="top" align="left">
	   <a href="#" onclick="return addline('kadri_list');" style="text-decoration:none"><img src="images/design/pl.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
	<?php for ($i=0;$i<$row_cnt;$i++) {
		$a_k_list=mysql_fetch_assoc($res_k_list);
		$_POST['kadri_comm'.$row_id]=$a_k_list['kadri_id'];
	?>
    <tr id="newline" nomer="_<?php echo $row_id; ?>">
      <td>	
        <select name="kadri_comm_<?php echo $row_id; ?>" id="kadri_comm_<?php echo $row_id; ?>" style="width:300;" title="секретарь"> 
		<?php
		$query='SELECT kadri.id, concat(kadri.fio, " - ", kadri_role(kadri.id,",")) AS fio
			FROM    kadri  
			ORDER BY 2';
		echo getFrom_ListItemValue($query,'id','fio','kadri_comm'.$row_id);
		?>
	</select>
      </td>
	<td valign="top" align="center"><a href="#" onclick="return rmline(<?php echo $row_id; ?>,'kadri_list');" style="text-decoration:none"><img src="images/design/mn.gif" border="0" WIDTH="17" HEIGHT="18"></a>
	</td>
	  </tr>
	<?php $row_id++; }?>  
  </table>
</div> <!--a href="#getdiv" onclick="javascript:alert(document.getElementById('kadri_list').innerHTML);">getDiv</a-->
<p>
	
Комментарий<br>
<input type=text style="width:500;" name=comment value="<?php echo getFormItemValue('comment'); ?>"> <p>

<input type=button onclick=javascript:check_form(); value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>
</div> 
<?php
}

else 
	{
	if (!isset($_GET['archiv'])) {	
		
		$query_='select count(*) from `diplom_preview_committees` where 1 and (date_act<"'.$def_settings['date_start'].'") ';
		if ($comm_id>0) {$query_.=' and `diplom_preview_committees`.`comm_id`="'.$comm_id.'"';}
		$archiv_cnt=intval(getScalarVal($query_),10);
		
		echo '<a href="?'.$query_string.'&archiv" title="записи прошлых учебных лет">архив: '.$archiv_cnt.'</a><br>';
	}
	else {
		$query_='select count(*) from `diplom_preview_committees` where 1 and (date_act>="'.$def_settings['date_start'].'" or date_act is NULL) ';
		if ($comm_id>0) {$query_.=' and diplom_preview_committees.`comm_id`="'.$comm_id.'"';}
		$cur_cnt=intval(getScalarVal($query_),10);
 		echo '<a href="?'.reset_param_name(reset_param_name($query_string,'archiv'),'page').'" title="записи прошлых учебных лет">записи текущего учебного года: '.$cur_cnt.'</a><br>';}



$archiv_query=' and (date_act>="'.$def_settings['date_start'].'" or date_act is NULL)';
if (isset($_GET['archiv'])) {$archiv_query=' and (date_act<"'.$def_settings['date_start'].'" )';}

$search_query='';
if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
$search_query=' and (
				        convert(dpc.name USING utf8) like "%'.$q.'%" or 
					convert(k_secr.fio_short USING utf8) like "%'.$q.'%" or 
					convert(kadri_fio_list(dpc.id,\';\') USING utf8) like "%'.$q.'%" or 
				        convert(dpc.comment USING utf8) like "%'.$q.'%" or
					dpc.date_act like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" 
		    )';}

	$table_headers=array(
		1=>array('cекретарь','80'),
		array('cостав комиссии','200'),
		array('АСОИ','20'),
		array('ПИЭ','20'),
		array('дата создания','20'),
		);
		
$def_sort=1;
if ($sort<1 && $sort>=cont($table_headers))  {$sort=$def_sort;}
 
//	-----------------------групповые операции начало------------------------------
//	-----------------------групповые операции конец------------------------------

//выборка для показа списочной таблицы записей

$query="SELECT k_secr.fio_short as secr_fio,
	concat(dpc.name,' (',kadri_fio_list(dpc.id,';'),')') AS name,       
       asoi_stat.cnt as asoi_stat_cnt,
       pie_stat.cnt as pie_stat_cnt,
       dpc.date_act,
       dpc.comment,       
       dpc.id       
  FROM    diplom_preview_committees dpc 	  
          LEFT OUTER JOIN kadri k_secr ON (k_secr.id = dpc.secretary_id)
	  left OUTER join (
		SELECT count(*) cnt,dp.comm_id 
		  FROM    (   (   study_groups sg 
		  INNER JOIN students s ON (sg.id = s.group_id))
		  INNER JOIN diplom_previews dp ON (s.id = dp.student_id))
		  INNER JOIN specialities spec ON (spec.id = sg.speciality_id)
		where spec.name like '%ПИЭ%'       
		group by dp.comm_id		
	  ) pie_stat on pie_stat.comm_id=dpc.id
	  left OUTER join (
		SELECT count(*) cnt,dp.comm_id 
		  FROM    (   (   study_groups sg 
		  INNER JOIN students s ON (sg.id = s.group_id))
		  INNER JOIN diplom_previews dp ON (s.id = dp.student_id))
		  INNER JOIN specialities spec ON (spec.id = sg.speciality_id)
		where spec.name like '%АСОИ%'       
		group by dp.comm_id		
	  ) asoi_stat on asoi_stat.comm_id=dpc.id
	  ";		

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
	echo '<td align=left colspan=2>';		
	
	
		echo showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'');
		echo ' </td> 
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
		  echo '<td align="center" > 
		  	<a href="javascript:del_confirm_act(\''.f_ro(substr($tmpval['secr_fio'],0,50)).'...\',\'?item_id='.$tmpval['id'].'&type=del&'.reset_param_name_ARR($query_string,array('item_id','type')).'\');" title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="?item_id='.$tmpval['id'].'&type=edit&'.reset_param_name_ARR($query_string,array('item_id','type')).'" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		$i++;
		echo '<td>&nbsp;'.($i+($page-1)*$pgVals).'</td>';		
		echo '<td>&nbsp;'.color_mark($q,$tmpval['secr_fio']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['name']).'</td>';
		echo '<td>&nbsp;<a href="diploms_preview.php?q=асои&comm_id='.$tmpval['id'].'" title="подробнее">'.$tmpval['asoi_stat_cnt'].'</a></td>';
		echo '<td>&nbsp;<a href="diploms_preview.php?q=пиэ&comm_id='.$tmpval['id'].'" title="подробнее">'.$tmpval['pie_stat_cnt'].'</a></td>';		
		
		$date_act=$tmpval['date_act'];		
		$date_act=substr($date_act,0,10);
		$date_act=DateTimeCustomConvert($date_act,'d','mysql2rus');
		echo '<td>&nbsp;'.color_mark($q,$date_act).'</td>';
		
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.color_mark($q,$tmpval['comment']).'</td>';}
	}
echo '</table>';
?>
<script language="javascript">
	 markTableRowsInit('tab1');	//сделать выделение строки в IE
</script>
	 
<?php
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
		<li>выбрав ссылку с указанием числа в графах "АСОИ", "ПИЭ" Вы перейдете на страницу "привязки" студентов с необходимым фильтром по комиссии и части имени группы</li>
		<li>подсчет в графах "АСОИ", "ПИЭ" ведется с учетом специальностей групп "привязанных" студентов</li>
		<li>при поиске Вы также можете указывать краткое ФИО сотрудника в составе комиссии</li>		
	</ul>
</div>
<?php
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>