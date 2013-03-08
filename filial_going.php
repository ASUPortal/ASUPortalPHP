<?php
include 'authorisation.php';


$query_string=$_SERVER['QUERY_STRING'];
$item_id=0;

$view_all_mode=false;	//обзор всех записей, а не тольео своих
if ($_SESSION['task_rights_id']==2 || $_SESSION['task_rights_id']==4) $view_all_mode=true;

$write_mode=false;	//возможность записи, а не только просмотра
if ($_SESSION['task_rights_id']==3 || $_SESSION['task_rights_id']==4) $write_mode=true;

if (isset($_GET['item_id'])) {$item_id=intval($_GET['item_id']);}
if (isset($_GET['kadri_id'])) {$kadri_id=$_GET['kadri_id'];}
//----------------------------------------------------------
if (isset($_GET['action']) && $_GET['action']=='del' && $item_id>0 && $write_mode)
	{
	//echo '!!!!!!!del!!!!!!';
	$query='delete from `filial_going`
		where id="'.$item_id.'" '.($view_all_mode?"":" and kadri_id=".$kadri_id).'';	
	//echo $query;
	$res=mysql_query($query);

	$query_string=reset_param_name($query_string,'action');
	$query_string=reset_param_name($query_string,'order_id');
	header('Location:'.$curpage.'?'.$query_string);
			
	}

if ($item_id>0 && !$view_all_mode) //проверяем принадлежность записи сотруднику
{
	$f_g_stats=getRowSqlVar();
	$in_kadri=intval(getScalarVal('select id from filial_going where kadri_id='.intval($_SESSION['kadri_id'])));
	if ($in_kadri<=0) $item_id=0;
	else $kadri_id=$_SESSION['kadri_id'];	
}
if ($item_id==0 && !$view_all_mode && (!isset($_GET['kadri_id']) || $_GET['kadri_id']!=$_SESSION['kadri_id'])) 
	{header('Location:?kadri_id='.$_SESSION['kadri_id'].'');}

//------------------------------------------------------------------------
include ('master_page_short.php');

$page=1;
$q='';			//строка поиска
$pgVals=20;	//число записей на странице по умолчанию
$query_string=$_SERVER['QUERY_STRING'];

$stype='desc';		//тип сортировки столбца
$sort=5;	//дата начала
$filt_str_display="";

if (isset($_GET['q'])) {$q=$_GET['q'];}
if (isset($_GET['item_id']) && intval($_GET['item_id'])>0) {$item_id=intval($_GET['item_id']);}

if (isset($_GET['kadri_id'])) {$kadri_id=$_GET['kadri_id'];$filt_str_display=$filt_str_display.' сотруднику;';}

if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}
if (isset($_GET['pageVals']) && $_GET['pageVals']<=99 && $_GET['pageVals']>=1) {$pgVals=$_GET['pageVals'];}
if (isset($_GET['stype']) && ($_GET['stype']=='desc' || $_GET['stype']=='asc')) {$stype=$_GET['stype'];}

?>
<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}
.err {color:red;font-family:Arial;}
</style>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
<script type="text/javascript" src="scripts/calendar_init.js"></script>
<script language="JavaScript">
var main_page="<?php echo $curpage;?>";
function check_form()
{
 	a = new Array(
	 	new Array('kadri_id','')
	);
requireFieldCheck(a,'item_form');
 
} 
function go2search(kadri_id,search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {
	  	href_addr='q='+search_query+'&<?php echo reset_param_name($query_string,'q'); ?>';
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
} 

</script>
<?php
//групп операции 	-----------------------------------------------

	echo '<h4 class="notinfo">'.$pg_title.' 
	<span class=text>*печать формы по ОКУД 0301024</span> </h4>	';


if (isset($_POST['kadri_id']))
{
	if (intval($_POST['kadri_id'])>0) 
  {
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'action'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

	 if ($_GET['action']=='add' && $write_mode) {	//добавление
		 $query="insert into `filial_going`(`kadri_id` , `filial_id` , `day_cnt` , `hours_cnt`,`day_start` , `day_end` , `filial_act_id` , `transport_type_id`, `comment`) 
		 values ('".intval($_POST["kadri_id"])."',
		 '".intval($_POST["filial_id"])."',
		 '".intval($_POST["day_cnt"])."',
		 '".intval($_POST["hours_cnt"])."',
		 '".f_ri(DateTimeCustomConvert($_POST["day_start"],'d','rus2mysql'))."','".f_ri(DateTimeCustomConvert($_POST["day_end"],'d','rus2mysql'))."',
		 '".intval($_POST["filial_act_id"])."',
		 '".intval($_POST["transport_id"])."',
		 '".f_ri($_POST["comment"])."')";		

		 if ($res=mysql_query($query) && !$err) {
		  	echo '<div class=success> Запись добавлена  успешно.'.$onEditRemain_text.'</div><br>';
		 				}
		 else {echo '<div class="warning">Запись не добавлена .<p>&nbsp;</div>';$err=true;}
	 }
	 else if ($_GET['action']=='edit') //обновление
	 {
	  	if ($item_id>0 && $write_mode) {
		 $query="update  `filial_going` set 
		 	`kadri_id`='".intval($_POST["kadri_id"])."',
			`filial_id`='".intval($_POST["filial_id"])."', 
			`day_cnt`='".intval($_POST["day_cnt"])."',
			`hours_cnt`='".intval($_POST["hours_cnt"])."',
			`day_start`='".f_ri(DateTimeCustomConvert($_POST["day_start"],'d','rus2mysql'))."',
			`day_end`='".f_ri(DateTimeCustomConvert($_POST["day_end"],'d','rus2mysql'))."', 
			`filial_act_id`='".intval($_POST["filial_act_id"])."', 
			`transport_type_id`='".intval($_POST["transport_id"])."', 
			`comment`='".f_ri($_POST["comment"])."'
		where id='".$item_id."'";		

		 if ($res=mysql_query($query) && !$err) {
		  	echo '<div class=success> Запись обновлена  успешно.'.$onEditRemain_text.'</div><br>';
		 				}
		 else {echo '<div class="warning">Запись не обновлена .<p>&nbsp;</div>';$err=true;}	
		}	    
	 }
  }
	 
	else {echo '<div class="warning">Часть обязательных данных не заполнено .<br>&nbsp;</div>';$err=true;}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'action'),'order_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
} 

if (isset($_GET['action']) && $_GET['action']=='edit')
{
	if ($item_id>0)
	{//echo '<h4>Правка темы</h4>';
	$query="SELECT `kadri_id` , `filial_id` , `day_cnt` , `hours_cnt`, `day_start` , `day_end` , `filial_act_id` , `transport_type_id` as `transport_id` , `comment` 
		FROM `filial_going` where id='".$item_id."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="warning">не выбрана Запись для правки</h4>';}	
}

//добавление тем
if (isset($_GET['action']) && ($_GET['action']=='add' || $_GET['action']=='edit'))
{
?>
<div><a href="<?php echo 	$curpage.'?'.reset_param_name(reset_param_name($query_string,'order_id'),'action');?>">
К списку записей </a></div>
<h4> <?php if ($_GET['action']=='edit') echo 'Правка существующей '; else echo 'Ввод новой ';?> записи 
<?php if ($_GET['action']=='edit') echo '&nbsp; &nbsp; &nbsp; 
<a target="_blank" href="filial_form.php?item_id='.$item_id.'&print"><img src="images/print.gif" border=0>печать формы по ОКУД 0301024</a>
<a target="_blank" href="filial_form.php?save&attach=doc&item_id='.$item_id.'"><img src="images/design/file_types/word_file.gif" border=0>передача в Word формы</a>'; ?>
</h4>

<div class="forms_under_border" style="width:99%;">
<form name="item_form" method="post" action="" id="item_form">

ФИО командируемого <font color=red>*</font> <b>(с кафедры)</b>:  <br>
<select name="kadri_id" id="kadri_id" style="width:400;" title="ФИО командируемого">
		<?php
		//для преподавателя позиционируем на его ФИО
		if (intval($_SESSION['kadri_id'])>0) {$res_edit['kadri_id']=intval($_SESSION['kadri_id']);}
		
		$listQuery='select k.id,concat(k.fio," (",kadri_role(k.id,","),")") as caption 
			from kadri k '.($view_all_mode?"":" where k.id=".$kadri_id).'
			order by k.fio';
		echo getFrom_ListItemValue($listQuery,'id','caption','kadri_id');
		?>
		</select>
<p>
Место командировки:  <br><select name="filial_id" id="filial_id" style="width:400;">
		<?php
		$listQuery='select id,name from filials order by name';
		echo getFrom_ListItemValue($listQuery,'id','name','filial_id');
		?>
		</select> <a href="spravochnik.php?sprav_id=23&spr_type=0#id_23">Справочник: филиалы</a>
<p>
Сроки командировки: <br/>
суток <input type=text maxlength=4 size=4 name="day_cnt" id="day_cnt" value="<?php if (isset($res_edit) && intval($res_edit['day_cnt'])>0) { echo intval($res_edit['day_cnt']);} ?>"> &nbsp;
часов <input type=text maxlength=4 size=4 name="hours_cnt" id="hours_cnt" value="<?php if (isset($res_edit) && intval($res_edit['hours_cnt'])>0) { echo intval($res_edit['hours_cnt']);} ?>"> &nbsp;
дата начала	<input type=text maxlength=10 size=15 name=day_start id=day_start value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['day_start'],0,10),'d','mysql2rus'));}else if (isset($_POST['day_start'])) {echo $_POST['day_start'];}else {echo date("d").'.'.date("m").'.'.date("Y");} ?>"> 
	<button type="reset" id="f_trigger_day_start">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "day_start",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_day_start",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script> &nbsp; 
дата окончания
<input type=text maxlength=10 size=15 name=day_end id="day_end" value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['day_end'],0,10),'d','mysql2rus'));}else if (isset($_POST['day_end'])) {echo $_POST['day_end'];}else {echo date("d").'.'.date("m").'.'.date("Y");} ?>"> 
	<button type="reset" id="f_trigger_day_end">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "day_end",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_day_end",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
<p>
Цель командировки:  <br><select name="filial_act_id" id="filial_act_id" style="width:400;">
		<?php
		$listQuery='select id,name from filial_actions order by name';
		echo getFrom_ListItemValue($listQuery,'id','name','filial_act_id');
		?>
		</select> &nbsp; <a href="spravochnik.php?sprav_id=21&spr_type=0#id_21">Справочник: действия в командировке</a> 
<p>
Разрешен проезд:  <br><select name="transport_id" id="transport_id" style="width:400;">
		<?php
		$listQuery='select id,name from transport order by name';
		echo getFrom_ListItemValue($listQuery,'id','name','transport_id');
		?>
		</select> &nbsp; <a href="spravochnik.php?sprav_id=22&spr_type=0#id_22">Справочник: тип проезда транспортом</a> 

<p>
	Доп.информация<br><input type=text size=100 name=comment id=comment value="<?php echo getFormItemValue('comment'); ?>"> <p>
	<input type=button onclick=javascript:check_form(); value="<?php if (isset($res_edit)) {echo 'Обновить';} else {echo 'Добавить';}?>" &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>
</div> 
<?php
}
else
{
if (!isset($_GET['save']) && !isset($_GET['print'])) {
	if ($write_mode) {	// если разрешена запись
	echo '<p class="notinfo"><a href="?action=add&'.$_SERVER["QUERY_STRING"].'"> Добавить</a> &nbsp;
	<a href="filial_form.php" title="более наглядно"> Добавить через печатную форму</a> <br/>
	<b>Справочники:</b> <a href="spravochnik.php?sprav_id=23&spr_type=0#id_23">филиалы</a> &nbsp;
	<a href="spravochnik.php?sprav_id=22&spr_type=0#id_22">тип проезда транспортом</a> &nbsp;
	<a href="spravochnik.php?sprav_id=21&spr_type=0#id_21">действия в командировке</a> &nbsp;
	<p>'; }
	echo '<table width=99% class="notinfo"><tr>';
	echo '<td align=left width=150>
	Сотрудник* &nbsp; </td><td> 
	<select name="kadri_id" id="kadri_id" style="width:300;" onChange=javascript:window.location.href="?kadri_id="+this.options[this.selectedIndex].value+"&'.reset_param_name($query_string,'kadri_id').'">'; 

	$query='select kadri.id, concat(kadri.fio, " (",count(*),")") as caption  
from kadri right join filial_going fg on kadri.id = fg.kadri_id '.($view_all_mode?"":" where kadri.id=".$kadri_id).'
group by kadri.id,kadri.fio 
order by 2 ASC limit 0,1000';
	echo getFrom_ListItemValue($query,'id','caption','kadri_id');
	
	echo '</select> &nbsp;&nbsp; ';
	if ($view_all_mode) echo '<input type=button value="Все" onclick=javascript:window.location.href="'.$curpage.'";>&nbsp;&nbsp;';
	echo '<input type=button value="Печать" onclick=javascript:window.location.href="?print=1&'.$_SERVER["QUERY_STRING"].'";>&nbsp;&nbsp;
	<input type=button value="в Word" onclick=javascript:window.location.href="?save&attach=doc&'.$_SERVER["QUERY_STRING"].'"></td> 
	<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; <input type=button value="Найти" 
	OnClick=javascript:go2search();></td>
	</tr></table>
	<p>';}


if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b> <a href="?'.reset_param_name($query_string,'q').'">сбросить поиск</a></div><br>';
$search_query='and (kadri.`fio` like "%'.$q.'%" or 
					kadri.`fio_short` like "%'.$q.'%" or 
					filial_going.day_start like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
					filial_going.day_end like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
					filial_going.comment like "%'.$q.'%" or
					filial_actions.name like "%'.$q.'%" or
					transport.name like "%'.$q.'%" or
					filials.name like "%'.$q.'%")';}
//$sort=1;
if (isset($_GET['sort']) && $_GET['sort']>0 && $_GET['sort']<=7) {$sort=$_GET['sort'];}

if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?"> сбросить фильтр </a></div>';}

//-----------------------------------------------начало списочной таблицы  
$query="SELECT 
       kadri.fio_short,
       filials.name as fil_name,
       filial_going.day_cnt,
       filial_going.hours_cnt,
       filial_going.day_start,
       filial_going.day_end,
       filial_actions.name as fil_act_name,
       transport.name as trans_name,
       filial_going.comment,
	   kadri.fio,
       kadri.id as kadri_id,      
       filial_going.id
  FROM    (   (   (   filials filials
                   RIGHT JOIN
                      filial_going filial_going
                   ON (filials.id = filial_going.filial_id))
               LEFT JOIN
                  transport transport
               ON (transport.id = filial_going.transport_type_id))
           LEFT JOIN
              filial_actions filial_actions
           ON (filial_actions.id = filial_going.filial_act_id))
       LEFT JOIN
          kadri kadri
       ON (kadri.id = filial_going.kadri_id)";
if ($kadri_id>0) {$search_query.=' and kadri.id='.$kadri_id;}

$query=$query." where 1 ".$search_query." order by ".$sort." ".$stype." ";

$res=mysql_query($query.'limit '.(($page-1)*$pgVals).','.$pgVals);
//echo $query.'limit '.(($page-1)*$pgVals).','.$pgVals;

echo '<form name=order_list id=order_list action="" method="post"><table name=tab1 border=1 cellpadding="0" cellspacing="0" width="99%">
<tr align="center" class="title" height="30">';

$add_string='';
if (isset($_GET['q']) && $_GET['q']!='')  {$add_string=$add_string.'&q='.$_GET['q'];};
if (isset($_GET['print']))  {$add_string=$add_string.'&print='.$_GET['print'];};
if (isset($_GET['page']))  {$add_string=$add_string.'&page='.$_GET['page'];};
if (isset($_GET['pageVals']))  {$add_string=$add_string.'&pageVals='.$_GET['pageVals'];};

	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '		
		<td class="notinfo">';
		if ($write_mode) echo '<img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка">
		&nbsp;&nbsp;';
		echo '<img src="images/print.gif" title="Печать формы">
		&nbsp;&nbsp;<img src="images/design/file_types/word_file.gif" title="Передача формы в Word"></td>';}
	echo '<td width="30">№</td>';
	echo '<td width="100">'.print_col(1,'ФИО').'</td>';
	echo '<td width="100">'.print_col(2,'место').'</td>';
	echo '<td width="50"> '.print_col(3,'суток').'</td>';
	echo '<td width="50"> '.print_col(4,'часов').'</td>';
	echo '<td width="100">'.print_col(5,'дата начала').'</td>';		
	echo '<td width="100">'.print_col(6,'дата окончания').'</td>';	
	echo '<td width="100">'.print_col(7,'цель').'</td>';
	echo '<td width="100">'.print_col(8,'вид проезда').'</td>';
	if (!isset($_GET['save']) && !isset($_GET['print'])) 
		{echo '<td class="notinfo">комментарий</td>';}
	echo "</tr>\n";
	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};

	$i=0;
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		echo '<tr align="left" class="main" '.$bgcolor.' valign="top">';
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		  echo '		  
		  <td align="center"> ';
		  if ($write_mode) echo '<a href="#del" onclick=javascript:del_confirm_act("'.str_replace(" ","&nbsp;",f_ro($tmpval['fio_short'])).'","?item_id='.$tmpval['id'].'&action=del") title="удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="?item_id='.$tmpval['id'].'&action=edit" title="править">
			<img src="images/toupdate.png" alt="Правка" border="0"></a>&nbsp;&nbsp;&nbsp;';
			echo '<a href="filial_form.php?item_id='.$tmpval['id'].'&print" title="печать" target="_blank">
			<img src="images/print.gif" alt="Печать" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="filial_form.php?save&attach=doc&item_id='.$tmpval['id'].'" title="передать в Word" target="_blank">
			<img src="images/design/file_types/word_file.gif" alt="Печать" border="0"></a>&nbsp;&nbsp;&nbsp;</td>';}
		$i++;
		echo '<td>&nbsp;'.($i+($page-1)*$pgVals).'</td>';
		echo '<td>&nbsp;<a href="lect_anketa.php?kadri_id='.$tmpval['kadri_id'].'&action=update" title="перейти в анкету преподавателя">'.color_mark($q,$tmpval['fio_short']).'</a></td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['fil_name']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,(intval($tmpval['day_cnt'])>0?intval($tmpval['day_cnt']):'')).'</td>';
		echo '<td>&nbsp;'.color_mark($q,(intval($tmpval['hours_cnt'])>0?intval($tmpval['hours_cnt']):'')).'</td>';
		echo '<td>&nbsp;'.color_mark($q,DateTimeCustomConvert($tmpval['day_start'],'d','mysql2rus')).'</td>';
		echo '<td>&nbsp;'.color_mark($q,DateTimeCustomConvert($tmpval['day_end'],'d','mysql2rus')).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['fil_act_name']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['trans_name']).'</td>';
					
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.$tmpval['comment'].'</td>';}
		echo "</tr>\n";
	}
echo '</table>';
?> 
</form>

<?php
//постраничный вывод списка данных о (по 10)

//оптимизация для подсчета числа страниц с учетом всех условий фильтрации
$query=$query."  ";
$res=mysql_query($query);

if (floor(mysql_num_rows($res)/$pgVals)==mysql_num_rows($res)/$pgVals) {$pages_cnt=floor(mysql_num_rows($res)/$pgVals);}
 else {$pages_cnt=floor(mysql_num_rows($res)/$pgVals)+1;}


$add_string=reset_param_name($query_string,'page');
echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';

//--------------------------------------------------------
echo '<br>макс.число строк на странице:  <input type=text value="'.$pgVals.'" name="pageVals" id="pageVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pageVals(\''.reset_param_name($query_string,'pageVals').'\');" value=Ok>
	<p> Всего строк: '.mysql_num_rows($res).'</div>'; 	
?>
<div class=text>
	<b>Примечание</b> <br>
	Печать формы по ОКУД 0301024 доступна в режиме правки записи о командировке (предварительно запись необходимо создать). 
</div>	
<?php	
}
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>