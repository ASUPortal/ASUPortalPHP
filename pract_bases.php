<?php
include 'authorisation.php';
//работа с базами практики студентов

$page=1;
$q='';			//строка поиска
$stype='asc';		//тип сортировки столбца
$pageVals=20;	//число записей на странице по умолчанию
$query_string=$_SERVER['QUERY_STRING'];

$filt_str_display="";

$item_id=0;$town_id=0;

//----------------------------------------------------------
if (isset($_GET['action']) && $_GET['action']=='del' && intval($_GET['item_id'])>0)
	{
	//echo '!!!!!!!del!!!!!!';
	$query='delete from `pract_places` where id="'.intval($_GET['item_id']).'"';	
	//echo $query;
	$res=mysql_query($query);

	$query_string=reset_param_name($query_string,'action');
	$query_string=reset_param_name($query_string,'item_id');
	header('Location:'.$curpage.'?'.$query_string);
			
	}
//------------------------------------------------------------------------
include ('master_page_short.php');

if (isset($_GET['q'])) {$q=$_GET['q'];}
if (isset($_GET['item_id']) && intval($_GET['item_id'])>0) {$item_id=intval($_GET['item_id']);}
if (isset($_GET['town_id']) && intval($_GET['town_id'])>0) {$town_id=intval($_GET['town_id']);}
if (isset($_GET['stype']) && $_GET['stype']=='desc') {$stype=$_GET['stype'];}


if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}
if (isset($_GET['pageVals']) && $_GET['pageVals']<=99 && $_GET['pageVals']>=1) {$pageVals=$_GET['pageVals'];}


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
function pgVals()
{
 var pageVal=document.getElementById('pageVals');
 if (pageVal.value>0 && pageVal.value<100) {
 	window.location.href='?<?php echo reset_param_name($query_string,'pageVals');?>&pageVals='+pageVal.value;}
 else {alert('необходимо: '+pageVal.title);}
 } 
function check_form()
{
 	a = new Array(
	 	new Array('name','')
	);
requireFieldCheck(a,'item_form');
 
} 
function go2search(kadri_id,search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {
	  	href_addr='q='+search_query+'&<?php echo reset_param_name(reset_param_name($query_string,'q'),'page'); ?>';
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
} 
function test_copy()	//для массовых операций
{
 	a = new Array(
	 	new Array('town_id_copy','')
	);
	 	
	requireFieldCheck_mass_operation(a,'order_list');
}
</script>
<?php
	
	echo '<h4 class="notinfo">'.$pg_title.'</h4>	';
	
//массовые операции 	----------------------------------------------------------------------------
$err=false;
$town_id_copy=intval($_POST['town_id_copy'],10);
$query_mas='';
$cnt_mas=0;
//echo 'year_id_copy='.$year_id_copy.'  speciality_id_copy='.$speciality_id_copy;
if ($town_id_copy>0) {
	
	while (list($key, $value) = each ($_POST)) {
	 	if 	  (strstr($key,"checkbox_h_copy_")) {
			$mail_id=substr($key,strpos($key,'copy_')+5);	//выдираем ID из названий чекбоксов
			//echo '<div>$mail_id='.$mail_id.'</div>';
		$query_mas="update  `pract_places` set `town_id`='".$town_id_copy."' where id='".$mail_id."' limit 1";
		//echo $query_mas;

		if (!mysql_query($query_mas)) {$err=true;echo '<div class=warning> ошибки массовой операции</div>';}
		else {$cnt_mas++;}	
			
		}	   }
	
	if ($err==true)	{echo '<div class=warning> Произошли ошибки </div>';}
	else {echo '<div class=success> Массовая операция успешно завершена для <font size=+1>'.$cnt_mas.'</font> элементов</div>';}
}
//----------------------------------------------------------------------------------------------

if (isset($_POST['name']))
{
	if ($_POST['name']!="") 
  {
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'action'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

	 if ($_GET['action']=='add') {	//добавление
		 $query="insert into `pract_places`(`name`,town_id,comment) 
		 values ('".f_ri($_POST["name"])."',
		 '".f_ri($_POST["town_id"])."',
		 '".f_ri($_POST["comment"])."')";		

		 if ($res=mysql_query($query) && !$err) {
		  	echo '<div class=success> Запись добавлена  успешно.'.$onEditRemain_text.'</div><br>';
		 				}
		 else {echo '<div class="warning">Запись не добавлена .<p>&nbsp;</div>';$err=true;}
	 }
	 else if ($_GET['action']=='edit') //обновление
	 {
	  	if ($item_id>0) {
		 //SELECT id,name,comment,man_cnt,speciality_id,head_student_id,year_id FROM `study_groups ` where id='20'
		 $query="update  `pract_places` set 
		 	`name`='".f_ri($_POST["name"])."',
			`town_id`='".f_ri($_POST["town_id"])."', 
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
 	$query_string=reset_param_name(reset_param_name($query_string,'action'),'item_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
} 

if (isset($_GET['action']) && $_GET['action']=='edit')
{
	
	if ($item_id>0)
	{//echo '<h4>Правка темы</h4>';
	$query="SELECT * FROM `pract_places` where id='".$item_id."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	//print_r($res_edit);echo $query;
	}
	else {echo '<h4 class="warning">не выбрана Запись для правки</h4>';}	
}

//добавление тем
if (isset($_GET['action']) && ($_GET['action']=='add' || $_GET['action']=='edit'))
{
?>
<div><a href="<?php echo 	$curpage.'?'.reset_param_name(reset_param_name($query_string,'item_id'),'action');?>">
К списку записей </a></div>
<h4> <?php if ($_GET['action']=='edit') echo 'Правка существующей '; else echo 'Ввод новой ';?> записи </h4>

<div class="forms_under_border" style="width:99%;">
<form name="item_form" method="post" action="" id="item_form">

Наименование <font color=red>*</font> <br>
<input type=text maxlength=90 size=100 name="name" id="name" value='<?php if (isset($res_edit)) {echo $res_edit['name'];} ?>' title="Наименование"> 
<p>
Город</b>:  <br>
<select name="town_id" id="town_id" style="width:300;">
		<?php
		//для преподавателя позиционируем на его ФИО
		$listQuery='select id,name from towns order by name';
		echo getFrom_ListItemValue($listQuery,'id','name','speciality_id');
		?>
		</select> <a href="spravochnik.php?sprav_id=24&spr_type=0#id_24">города</a>
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
	echo '<p class="notinfo"><a href="?action=add&'.$_SERVER["QUERY_STRING"].'"> Добавить</a> <br/>
	<b>Справочники:</b> <a href="spravochnik.php?sprav_id=24&spr_type=0#id_24">города</a> &nbsp;
	<p>';
	echo '<table width=99% class="notinfo"><tr>';
	echo '<td align=left width=350>
	Город практики &nbsp;  
	<select name="town_id" id="town_id" style="width:200;" onChange=javascript:window.location.href="?"+this.id+"="+this.options[this.selectedIndex].value+"&'.reset_param_name(reset_param_name($query_string,'town_id'),'page').'">'; 

	$query='SELECT tw.id,concat(tw.name, " (",count(*),")") as caption   
			FROM towns tw 
			right join pract_places pp on tw.id=pp.town_id
			where tw.id is not null
			group by tw.id,tw.name 
			order by 2 ASC limit 0,1000';
	echo getFrom_ListItemValue($query,'id','caption','town_id');
	
	echo '</select> </td><td> ';
	echo '<input type=button value="Все" onclick=javascript:window.location.href="'.$curpage.'";>&nbsp;&nbsp;
	<input type=button value="Печать" onclick=javascript:window.location.href="?print=1&'.$_SERVER["QUERY_STRING"].'";>&nbsp;&nbsp;
	<input type=button value="в Word" onclick=javascript:window.location.href="?save&attach=doc&'.$_SERVER["QUERY_STRING"].'"></td> 
	<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; <input type=button value="Найти" 
	OnClick=javascript:go2search();></td>
	</tr></table>
	<p>';}


if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b> <a href="?'.reset_param_name($query_string,'q').'">сбросить поиск</a></div><br>';
$search_query='and (LOWER(pp.name) like "%'.strtolower($q).'%" or 
					LOWER(tw.name) like "%'.strtolower($q).'%" or 
					LOWER(pp.comment) like "%'.strtolower($q).'%")';}
$sort=1;
if (isset($_GET['sort']) && $_GET['sort']>1 && $_GET['sort']<=7) {$sort=$_GET['sort'];}

if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?"> сбросить фильтр </a></div>';}

//-----------------------------------------------начало списочной таблицы  

$query="SELECT pp.name,tw.name as tw_name,(select count(*) from diploms dp where dp.pract_place_id=pp.id) as dp_cnt,pp.comment,pp.id,pp.town_id 
		FROM `pract_places` pp
		left join towns tw on tw.id=pp.town_id";
if ($town_id>0) {$search_query.=' and pp.town_id='.$town_id;}

$query=$query." where 1 ".$search_query." order by ".$sort." ".$stype." ";

$res=mysql_query($query.'limit '.(($page-1)*$pageVals).','.$pageVals);
//echo $query.'limit '.(($page-1)*$pageVals).','.$pageVals;

echo '<form name=order_list id=order_list action="" method="post"><table name=tab1 border=1 cellpadding="0" cellspacing="0" width="99%">
<tr align="center" class="title" height="30">';

//-------------------------------------  списочная таблица -----------------------------------------------------	

	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '		
		<td class="notinfo">
		<input type=checkbox name="checkbox_del_all" id="checkbox_del_all" title="выделить\снять выделение со всех элементов" onClick="javascript:mark_all_checkbox(this.name,\'order_list\',0);">
		<img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}
	echo '<td width="30">№</td>';
	echo '<td width="100">'.print_col(1,'Наименование').'</td>';
	
	echo '<td width="100">'.print_col(2,'Город').'</td>';
	echo '<td width="50">'.print_col(3,'Дипломов').'</td>';
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
		  <td align="center"> <input type=checkbox name="checkbox_h_copy_'.$tmpval['id'].'" title="" id="checkbox_h_copy_'.$tmpval['id'].'"> &nbsp;
		  <a href="#del" onclick=javascript:del_confirm_act(\''.str_replace(" ","&nbsp;",f_ro($tmpval['name'])).'\',\'?item_id='.$tmpval['id'].'&action=del\') title="удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="?item_id='.$tmpval['id'].'&action=edit" title="править">
			<img src="images/toupdate.png" alt="Правка" border="0"></a>
			</td>';}
		$i++;
		echo '<td>&nbsp;'.($i+($page-1)*$pageVals).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['name']).'</a></td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['tw_name']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['dp_cnt']).'</td>';
					
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.$tmpval['comment'].'</td>';}
		echo "</tr>\n";
	}
echo '</table>';
if (!isset($_GET['save']) && !isset($_GET['print'])) {
?>назначить выбранным записям город: &nbsp;  
	<select name="town_id_copy" id="town_id_copy" style="width:300;">
		<?php
		//для преподавателя позиционируем на его ФИО
		$listQuery='select id,name from towns order by name';
		echo getFrom_ListItemValue($listQuery,'id','name','speciality_id');
		?>
	</select> 
	<input type=button value="Ok" onClick="javascript:test_copy();">	
<?php } ?>
</form>

<?php
//постраничный вывод списка данных о (по 10)

//оптимизация для подсчета числа страниц с учетом всех условий фильтрации
//$query=$query." ".$search_query." ";
$res=mysql_query($query);

if (floor(mysql_num_rows($res)/$pageVals)==mysql_num_rows($res)/$pageVals) {$pages_cnt=floor(mysql_num_rows($res)/$pageVals);}
 else {$pages_cnt=floor(mysql_num_rows($res)/$pageVals)+1;}

echo '<div align="left"> страницы ';

$add_string=reset_param_name($query_string,'page');

for ($i=1;$i<=$pages_cnt;$i++) {if ($i!=$page) {echo '<a href="?page='.$i.'&'.$add_string.'"> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}}
//--------------------------------------------------------
echo '<br>макс.число строк на странице:  <input type=text value="'.$pageVals.'" name="pageVals" id="pageVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgVals();" value=Ok>
	<p> Всего строк: '.mysql_num_rows($res).'</div>'; 	
?>
<div class=text>
	<b>Примечание</b> <br>

</div>	
<?php	
}
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>