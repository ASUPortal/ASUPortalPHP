<?php
include ('authorisation.php');

$item_id=0;
$type='';

if (isset($_GET['type'])) {$type=$_GET['type'];} 
if (isset($_GET['item_id'])) {$item_id=intval($_GET['item_id']);}


if ($type=='del' && $item_id>0)
	{
	//echo '!!!!!!!del!!!!!!';
	$query='delete from `protocol_trip_details` where id="'.$item_id.'"';	
	
	//echo ' query_string='.$query_string;
	$res=mysql_query($query);	//reset_param_name ($query_string,$param_name)
	//echo 'Location:'.$main_page.'?'.reset_param_name($query_string,'type');	
	$query_string=reset_param_name($query_string,'type');
	$query_string=reset_param_name($query_string,'item_id');
	header('Location:'.$main_page.'?'.$query_string);
	//page=2&pgVals=20&archiv&sort=4	
	}
$admin_role=false;

include ('master_page_short.php');

//-----------настройка формы ------------
$showRez=true;	//скрывать рецензента в "+", иначе показывать его ФИО-кратко


//----------------------


$main_page=$curpage;//'diploms_view.php';
$page=1;
$trip_id=0;		//отбор по протоколу
$q='';			//строка поиска
$pgVals=20;	//число тем на странице по умолчанию
$sort=0;
$filt_str_display='';	//отражение по чему идет отбор
$err=false;		//нет ошибок при изменении БД
$stype='asc';		//тип сортировки столбца
$type='';
$item_id=0;

$query_string=$_SERVER['QUERY_STRING'];

if (isset($_GET['sort'])) {$sort=intval($_GET['sort']);}
if (isset($_GET['q'])) {$q=$_GET['q'];$filt_str_display=$filt_str_display.'  поиску;';}
//if (isset($_GET['archiv'])) {$query_string=$query_string.'&archiv';}

if (isset($_GET['page']) && $_GET['page']>1) {$page=intval($_GET['page']);$filt_str_display=$filt_str_display.'  странице;';}
if (isset($_GET['pgVals']) && $_GET['pgVals']<=99 && $_GET['pgVals']>=1) {$pgVals=intval($_GET['pgVals']);$filt_str_display=$filt_str_display.' числу записей;';}
//if (isset($_GET['sort'])) {$sort=$_GET['sort'];}
//echo ' query_string='.$query_string;

if (isset($_GET['stype']) && $_GET['stype']=='desc') {$stype=$_GET['stype'];}
if (isset($_GET['type'])) {$type=$_GET['type'];} 

if (isset($_GET['trip_id'])) {$trip_id=intval($_GET['trip_id']);$filt_str_display=$filt_str_display.' протоколу;';}
if (isset($_GET['item_id'])) {$item_id=intval($_GET['item_id']);}

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
	{ window.location.href=main_page+"?trip_id="+document.getElementById('orders_type').value;}
 else {window.location.href=main_page;}
} */
function go2search(trip_id,search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {
	 href_addr='q='+search_query+'&trip_id='+trip_id;
		if (search_path=='archiv') {href_addr='archiv&'+href_addr+'';}
	 	
		 //alert(href_addr);
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
} 
function check_form()
{
var err=false;
var date_start=document.getElementById('date_start');
var date_end=document.getElementById('date_end');

if (date_check(date_start.value)) 
	{
	 err=true;
	 alert('Дата начала не существует. воспользуйтесь календарем;');
	}
else {
	 if (date_check(date_end.value)) 
	{
	 err=true;
	 alert('Дата окончания не существует. воспользуйтесь календарем;');
	}
	 else {	 
 	a = new Array(
	 	new Array('trip_id','протокол'),
	 	new Array('kadri_id','преподаватель')
	);
	requireFieldCheck(a,'order_form');
	 }
	}

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


if (isset($_POST['trip_id']) && intval($_POST['trip_id'])>0)
{
	$trip_id= intval($_POST['trip_id']);
        $kadri_id=intval($_POST['kadri_id']);
	
	if ($kadri_id>0) 
	{
		 
		 $house_type=intval($_POST['house_type']);
		 $trip_cost=intval($_POST['trip_cost']);
		 $dotation=intval($_POST['dotation']);
		 
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

	 //обновление темы
	 if ($type=='edit' && $item_id>0) {
		 //echo 'Правка темы.';
		 $query="update `protocol_trip_details` set trip_id='".$trip_id."',kadri_id='".$kadri_id."',
		 	trip_count='".f_ri($_POST["trip_count"])."',		 	
			date_start='".f_ri(DateTimeCustomConvert($_POST["date_start"],'d','rus2mysql'))."',
			date_end='".f_ri(DateTimeCustomConvert($_POST["date_end"],'d','rus2mysql'))."',
			trip_cost='".$trip_cost."',
			dotation='".$dotation."',
			house_type='".$house_type."',
			comment='".f_ri($_POST["comment"])."'
			  where id='".$item_id."'";

		 if ($res=mysql_query($query)) {

			//header("Location: ".$main_page);
		  	echo '<div class=success> Запись обновлена  успешно.'.$onEditRemain_text.'</div><br>';
		 	//echo "Location:".$main_page;
			 
		 				}
		 else {echo '<div class="warning">Запись не обновлена .<p>&nbsp;</div>';$err=true;}
		 //echo $query;
	 }
	 
	 //новая тема
	 if ($type=='add') {
		 
		 $query="insert into `protocol_trip_details`(`trip_id` , `kadri_id` , `trip_count` , `date_start` , `date_end` , `trip_cost` , `dotation` , `house_type` , `comment`) 
		 	values('".$trip_id."','".$kadri_id."','".f_ri($_POST["trip_count"])."',
			'".f_ri(DateTimeCustomConvert($_POST["date_start"],'d','rus2mysql'))."',
			'".f_ri(DateTimeCustomConvert($_POST["date_end"],'d','rus2mysql'))."',
			'".$trip_cost."','".$dotation."',
			 '".$house_type."','".f_ri($_POST["comment"])."')";
		 $res_news=true;
		 if ($res_news & $res=mysql_query($query)) {


		  	echo '<div class=success> Запись добавлена успешно.'.$onEditRemain_text.'</div><br>';
			}
		 else {echo '<div class="warning">Запись не добавлена. Возможно запись уже есть</div><br>';$err=true;}	
	 }
	 //echo $query;
	 }
	else {echo '<div class="warning">Часть обязательных данных не заполнено .<br>&nbsp;</div>';$err=true;}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
}

if ($type=='edit')
{
	if ($item_id>0)
	{//echo '<h4>Правка темы</h4>';
	$query="select * from `protocol_trip_details` where id='".$item_id."'";
	//echo $query;
	
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="warning">не выбрана Запись для правки</h4>';}	
}

//добавление тем
if ($type=='add' || $type=='edit')
{
?>
<div><a href="<?php echo 	$main_page.'?'.reset_param_name(reset_param_name($query_string,'item_id'),'type');?>">Просмотр списка записей </a></div>
<h4> <?php if(isset($_GET['type']) && $_GET['type']=='edit') {echo 'Правка';} else {echo 'Ввод новой';} ?>  записи </h4>
<div class="forms_under_border" style="width:99%;">
<form name="order_form" id="order_form" method="post" action="">
Протокол* <br>
<select name="trip_id" id="trip_id" style="width:300;"> 
		<?php
		$query='select pt.id, concat(p.num," от ",DATE_FORMAT(p.date_text,"%d.%m.%Y") ," (", 
			   (select count(*) from protocol_trip_details ptd where ptd.trip_id=pt.id),")") as name 
			   from protocol_trips pt left join protocols p on p.id=pt.protocol_id order by 2 desc';
		
		echo getFrom_ListItemValue($query,'id','name','trip_id');
		?>
</select> <a href="protocols_view.php"> создать </a>
<p>
Сотрудник* <br>
		  <select name="kadri_id" id="kadri_id" style="width:300;"> 
		<?php
		$query='SELECT kadri.id  as id,concat(kadri.fio," (",(select count(*) from protocol_trip_details ptd where ptd.kadri_id=kadri.id),")") as fio 
			FROM kadri ';
		//if (!$admin_role) {$query.=' where kadri.id="'.$trip_id.'"';}
		$query.=' order by 2';
		
		echo getFrom_ListItemValue($query,'id','fio','kadri_id');
		?>
</select> <a href="lect_anketa_view.php"> создать </a>
<p>
Количество путевок (число дней) * <br><input type=text maxlength=10 size=15 name=trip_count value="<?php echo getFormItemValue('trip_count'); ?>">
<p>
Заезд: 
<FIELDSET> начало
<input type=text maxlength=10 size=15 id=date_start name=date_start value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['date_start'],0,10),'d','mysql2rus'));}else if (isset($_POST['date_start'])) {echo $_POST['date_start'];}else {  
		 echo date("d.m.Y");  
	 } ?>"> 
	<button type="reset" id="f_trigger_date_start">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_start",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_start",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script> 
окончание 	 
<input type=text maxlength=10 size=15 id=date_end name=date_end value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['date_end'],0,10),'d','mysql2rus'));}else if (isset($_POST['date_end'])) {echo $_POST['date_end'];}else {  
	 echo date("d.m.Y"); 
	 } ?>"> 
	<button type="reset" id="f_trigger_date_end">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_end",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_end",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</FIELDSET>	<br>
Cтоимость путёвки 
<FIELDSET>	
полная <input type=text maxlength=10 size=15 name=trip_cost value="<?php echo getFormItemValue('trip_cost'); ?>"> 
дотация <input type=text maxlength=10 size=15 name=dotation value="<?php echo getFormItemValue('dotation'); ?>">
<p>
Тип строения <br>
<select name="house_type" id="house_type" style="width:300;"> 
		<?php
		$query='select id,concat(name," (",(select count(*) from protocol_trip_details ptd  where ptd.house_type=th.id),")") as name
		  from `trip_houses` th  order by name';
		echo getFrom_ListItemValue($query,'id','name','house_type');
		?>
</select> <a href="spravochnik.php?<?php echo showSpravLink(''); ?>">создать</a> &nbsp; 
</FIELDSET>
<br>
	Комментарий<br><input type=text size=100 name=comment value="<?php echo getFormItemValue('comment'); ?>"> <p>
	<input type=button onclick=javascript:check_form(); value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>
</div> 
<?php
}

else 
	{

	echo '<h4 class="notinfo"> '.$pg_title.' <a href="protocols_view.php';
		if ($view_all_mode!==true) {
            //фильтр студентов по дипл.руков-лю
            if (array_key_exists("trim_ip", $_SESSION)) {
                echo '?trip_id='.$_SESSION['trip_id'];
            }
        }
	echo '">список протоколов</a></h4>';

	if (!isset($_GET['archiv'])) {
	
		//$tmp_str='?'.$_SERVER["QUERY_STRING"];
		
		//if (strpos($tmp_str,'archiv')<=0) {$tmp_str=$tmp_str.'&archiv';}
		//where 
		$query_='select count(*) from `protocol_trip_details` ptd where 1 and (date_start<"'.$def_settings['date_start'].'" or date_start is NULL) ';
		if ($trip_id>0) {$query_.=' and `ptd`.`trip_id`="'.$trip_id.'"';}
		$archiv_cnt=intval(getScalarVal($query_),10);
		
		echo '<a href="?'.$query_string.'&archiv" title="записи прошлых учебных лет">архив: '.$archiv_cnt.'</a><br>';
	}
	else {
		$query_='select count(*) from `protocol_trip_details` ptd where 1 and date_start>"'.$def_settings['date_start'].'" ';
		if ($trip_id>0) {$query_.=' and `ptd`.`trip_id`="'.$trip_id.'"';}
		$cur_cnt=intval(getScalarVal($query_),10);
 		echo '<a href="?'.reset_param_name(reset_param_name($query_string,'archiv'),'page').'" title="записи прошлых учебных лет">записи текущего учебного года: '.$cur_cnt.'</a><br>';}



$archiv_query=' and date_start>"'.$def_settings['date_start'].'"';
if (isset($_GET['archiv'])) {$archiv_query=' and (date_start<"'.$def_settings['date_start'].'" or date_start is NULL)';}


	$table_headers=array(
		1=>array('Ф.И.О.','200'),
		2=>array('должность','10'),
		3=>array('кол-во путёвок','100'),
		4=>array('начало заезда','50'),
		5=>array('окончание заезда','50'),
		6=>array('полная стоим. путёвки','50'),
		7=>array('дотация','20'),
		8=>array('дом','20')
		);

$search_query='';
if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
$search_query=' and (k.fio_short like "%'.$q.'%" or 
					d.name like "%'.$q.'%" or 
					ptd.trip_count like "%'.$q.'%" or 
					ptd.dotation like "%'.$q.'%" or
					ptd.trip_cost like "%'.$q.'%" or
					th.name like "%'.$q.'%" or
					ptd.date_start like "%'.$q.'%" or ptd.date_start like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
					ptd.date_end like "%'.$q.'%" or ptd.date_end like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
					ptd.comment like "%'.$q.'%")';}

if ($sort<=0 || $sort>=count($table_headers)) {$sort=1; }

//выборка для показа списочной таблицы записей

$query='SELECT k.fio_short AS fio,
       d.name AS dolgnost,
       ptd.trip_count,
       ptd.date_start,
       ptd.date_end,
       ptd.trip_cost,
       ptd.dotation,
       th.name AS house_type,
       ptd.comment,
       k.id as fio_id,
       ptd.id 
  FROM    (   (   kadri k
               RIGHT OUTER JOIN
                  protocol_trip_details ptd
               ON (k.id = ptd.kadri_id))
           LEFT OUTER JOIN
              trip_houses th
           ON (th.id = ptd.house_type))
       LEFT OUTER JOIN
          dolgnost d
       ON (d.id = k.dolgnost)';

 //'WHERE ptd.trip_id = 1';

if ($trip_id>0) {$search_query.=' and ptd.trip_id="'.$trip_id.'"';}

$query=$query." where 1 ".$archiv_query."".$search_query." order by ".$sort." ".$stype." ";
	
$res=mysql_query($query.'limit '.(($page-1)*$pgVals).','.$pgVals);
//echo $query;

if (!isset($_GET['save']) && !isset($_GET['print'])) {
	echo '<p class="notinfo"><a href="?type=add&'.$_SERVER["QUERY_STRING"].'"> Добавить</a><p>';
if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?'.reset_param_name_ARR($query_string,array('trip_id','page','q')).'"> сбросить фильтр </a></div>';}
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
	
		
	
	$add_string=reset_param_name($query_string,'trip_id');	//для фильтра по преп-лю
	$add_string=reset_param_name($add_string,'page');	//для перехода к первой странице сортировки по преп-лю
	
	//echo ' admin_role='.$admin_role;
 	  	?>
	Протокол № <select name="trip_id" id="trip_id" style="width:200;" onChange="javascript:window.location.href='?<?php echo reset_param_name($query_string,'trip_id');?>&trip_id='+this.options[this.selectedIndex].value;"> 
	<?php
//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		$query_='select pt.id, concat(p.num," от ",DATE_FORMAT(p.date_text,"%d.%m.%Y") ," (", 
			   (select count(*) from protocol_trip_details ptd where ptd.trip_id=pt.id),")") as name 
			   from protocol_trips pt left join protocols p on p.id=pt.protocol_id order by 2 desc';
echo getFrom_ListItemValue($query_,'id','name','trip_id');
		?>
</select>
	<?php
		
		//persons_select($add_string.'&page=1&trip_id');
		  echo '&nbsp;&nbsp;<input type=button value="Все" onclick=javascript:window.location.href="'.$main_page.'";>&nbsp;&nbsp;';
		  //echo showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING']);
		echo ' <b>список</b>: '.showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'');
		
		if ($trip_id>0) {	//showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING']);
		  echo ' <b>выписка</b>: '.showPrintSaveOpt('print&doc','trip_id='.$trip_id,'protocol_trip_print.php');
		}
		echo '</td> 
	<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; 
		<input type=button value="Найти" title="поиск проводится в текущем разделе (архив или тек.уч.год) выбором соот.ссылки" OnClick=javascript:go2search(\''.$trip_id.'\',\'';
			if (isset($_GET['archiv'])) {echo 'archiv';}
		echo '\');>
		<div class=text style="text-align:right"> кроме полей: ин.яз.,оц. <br>
		Поиск по дате в формате дд.мм.гггг или гггг-мм-дд</div></td>
	</tr></table>';}

//if (isset($_GET['archiv'])) {$filt_str='по архиву';}

$itemCnt=getScalarVal('select count(*) from ('.$query.')t');
if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {
    $pages_cnt=floor($itemCnt/$pgVals);
} else {
    $pages_cnt=floor($itemCnt/$pgVals)+1;
}

//for ($i=1;$i<=$pages_cnt;$i++) {if ($i!=$page) {echo '<a href="?'.$add_string.'&page='.$i.'"> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}}
echo '<div align="center"> страницы ';
$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo getPagenumList($pages_cnt,$page,6,'page',$add_string,'');
echo '</div>';

echo '<form name=order_list>
<table name=tab1 border=1 cellpadding="0" cellspacing="0" width="99%"><tr align="center" class="title" height="30">';
	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '<td width="50" class="notinfo"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}

//echo $query;

$add_string=reset_param_name($query_string,'sort');
	
//------------------------------------------- шапка списочной таблицы -начало-----------------------------------------------------
	echo '<td width="50">№</td>';
	for ($i=1;$i<=count($table_headers);$i++)
	{
		echo '<td width="'.$table_headers[$i][1].'">'.print_col($i,$table_headers[$i][0]).'</td>';
	}
	if (!isset($_GET['save']) && !isset($_GET['print'])) 
		{echo '<td width="100" class="notinfo">комментарий</td>';}
//------------------------------------------- шапка списочной таблицы -конец-----------------------------------------------------
	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};
	$i=0;
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		echo '<tr align="left" class="main" '.$bgcolor.' valign="top">';
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		  echo '<td align="center"> 
		  	<a href="javascript:del_confirm(\''.f_ro($tmpval['id']).'\',\''.str_replace(" ","_",f_ro($tmpval['fio'])).'\');" title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="?item_id='.$tmpval['id'].'&type=edit&'.$query_string.'" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		$i++;
		echo '<td>&nbsp;'.($i+($page-1)*$pgVals).'</td>';
		echo '<td>&nbsp;<a href="lect_anketa.php?kadri_id='.$tmpval['fio_id'].'&action=update" title="в анкету преподавателя">'.color_mark($q,$tmpval['fio']).'</a></td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['dolgnost']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['trip_count']).'</td>';
		
		$date_start=DateTimeCustomConvert(substr($tmpval['date_start'],0,10),'d','mysql2rus');
		echo '<td>&nbsp;'.color_mark($q,$date_start).'</td>';

		$date_end=DateTimeCustomConvert(substr($tmpval['date_end'],0,10),'d','mysql2rus');
		echo '<td>&nbsp;'.color_mark($q,$date_end).'</td>';
		
		echo '<td>&nbsp;'.color_mark($q,$tmpval['trip_cost']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['dotation']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['house_type']).'</td>';

		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.color_mark($q,$tmpval['comment']).'</td>';}
	}
echo '</table></form>';
	}
//-------------------------------------списочная таблица -конец----------------------------------------------------

//постраничный вывод списка тем (по 10 тем)
echo '<div align="center"> страницы ';
$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

if (!isset($pages_cnt)) {
    $pages_cnt = 0;
}
echo getPagenumList($pages_cnt,$page,6,'page',$add_string,'');
echo '</div>';

//--------------------------------------------------------

$add_string=reset_param_name($add_string,'pgVals');// preg_replace("/(&pgVals=)(\d+)/x","",$add_string);		//убрать число страниц через RegExp
echo '<br>макс.число записей на странице:  <input type=text value="'.$pgVals.'" name="pgVals" id="pgVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgValsCh(\''.$add_string.'\');" value=Ok>
	<p> Всего записей: '.mysql_num_rows($res).'</div>'; 	
	}

if (!isset($_GET['save']) && !isset($_GET['print'])) 
{
?>
<div class=text>
<b>Примечание: </b><br>
<ul>
<li>печать и выгрузка в MS Word выписки установленной формы доступна при выборе в "Протокол №" необходимого протокола</li>
<li>для добавления новой выписки необходимо перейти в необходимой протокол в <a href="protocols_view.php">списке</a> и в разделе "пункты повестки" выбрать "сформировать выписку по путевкам"</li>
<li>для удаления всех привязанных к выписке путевок сотрудников, выберите в протоколе в разделе "пункты повестки" пункт "удалить выписку по путевкам". Дополнительно укажется число привязанных к выписке путевок сотрудников.</li>
<li></li>	 
</ul>
</div>
<?php
	 echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>