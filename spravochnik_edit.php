<?php
include ('authorisation.php');
include ('master_page_short.php');

?>
<script language="javascript">
var main_page='<?php echo $curpage;?>';	//for redirect & links
function check_form()
{

var a = new Array(
	 	new Array('name','')
	);
 
 requireFieldCheck(a,'item_form');
} 
function check_val(value_)
         {
		  if (value_=='') {alert('Введите непустое значение...'); }
		  
         }
function go2search(search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {href_addr='q='+search_query+'&'+search_path;	
	 	
		 //alert(href_addr);
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
}  

</script>
<?php
echo '<h4 class="notinfo">'.$pg_title.'</h4>	';

$main_page=$curpage;
$page=1;
$q='';			//строка поиска
$pgVals=20;	//число тем на странице по умолчанию
$sort=1;
$filt_str_display='';	//отражение по чему идет отбор
$err=false;		//нет ошибок при изменении БД
$stype='asc';		//тип сортировки столбца
$query_string=$_SERVER['QUERY_STRING'];

if (isset($_GET['sort'])) {$sort=$_GET['sort'];}
if (isset($_GET['q'])) {$q=$_GET['q'];$filt_str_display=$filt_str_display.'  поиску;';}
if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];$filt_str_display=$filt_str_display.'  странице;';}
if (isset($_GET['pgVals']) && $_GET['pgVals']<=99 && $_GET['pgVals']>=1)
 	{$pgVals=$_GET['pgVals'];$filt_str_display=$filt_str_display.' числу записей;';}
if (isset($_GET['stype']) && $_GET['stype']=='desc') {$stype=$_GET['stype'];}

$sprav_id=0;
if (isset ($_GET['sprav_id'])) {$sprav_id=intval($_GET['sprav_id'],0);}

$item_id=0;
if (isset ($_GET['item_id'])) {$item_id=intval($_GET['item_id'],0);}

if ($sprav_id<=0) {echo '<div class=warning>Справочник не выбран. <a href="spravochnik.php">Выбрать справочник</a></div>';}
else
{
$sprav_name='';

$query_spr='SELECT sprav_links.id AS sprav_id,
       sprav_links.sprav_name,
       sprav_links.comment AS sprav_com,
       sprav_links.sprav_main_id AS cat_id,
       sprav_main.name AS cat_name
  FROM    sprav_main sprav_main
       RIGHT OUTER JOIN
          sprav_links sprav_links
       ON (sprav_main.id = sprav_links.sprav_main_id)
 WHERE (sprav_links.id = '.$sprav_id.')';

$sprav_data=getRowSqlVar($query_spr);
$sprav_data=$sprav_data[0];
$sprav_name=$sprav_data['sprav_name'];
$sprav_cat_id=$sprav_data['cat_id'];

if ($sprav_name=='') {echo '<div class=warning>Справочник не найден. <a href="spravochnik.php">Выбрать справочник</a></div>';}
else {

	$query="select * from `".$sprav_name."` limit 0,1";
	$res=mysql_query($query);	
	$is_short=false;
	$columns = mysql_num_fields($res);	
	for ($i = 0; $i < $columns; $i++) {
	    if  (mysql_field_name($res, $i)=='name_short') {$is_short=true;break;}
	} 
//------------удаление начало
if (isset($_GET['type']) && $_GET['type']=='del' && isset($_GET['item_id']))
	{
	//echo '!!!!!!!del!!!!!!';
	$query='delete from `'.$sprav_name.'` where id="'.$_GET['item_id'].'"';	
	
	//echo ' query_string='.$query_string;
	$res=mysql_query($query);	//reset_param_name ($query_string,$param_name)
	//echo 'Location:'.$main_page.'?'.reset_param_name($query_string,'type');	
	$query_string=reset_param_name($query_string,'type');
	$query_string=reset_param_name($query_string,'item_id');
	header('Location:'.$main_page.'?'.$query_string);
	//page=2&pgVals=20&archiv&sort=4	
	}
//------------удаление конец

if (isset($_POST['name']))
{
	if ($_POST['name']!='') 
	{
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

	 //обновление темы
	 if (isset($_GET['type']) & $_GET['type']=='edit' & isset($_GET['item_id']) & $_GET['item_id']!='') {
		 //echo 'Правка темы.';
		 $query="update `$sprav_name` set 
		 	name='".f_ri($_POST["name"])."',".
		 	echoIf($is_short,"`name_short`='".f_ri($_POST["name_short"])."',","").
			"comment='".f_ri($_POST["comment"])."'
			  where id='".f_ri($_GET["item_id"])."'";
		 if ($res=mysql_query($query)) {	echo '<div class=success> Запись обновлена  успешно.'.$onEditRemain_text.'</div><br>';	}
		 else {echo '<div class="warning">Запись не обновлена .<p>&nbsp;</div>';$err=true;}
		 //echo $query;
	 }
	 //новая тема
	 if (isset($_GET['type']) & $_GET['type']=='add') {
		 //echo 'Добавление записи.';
		 $query="insert into `$sprav_name`(`name`,".echoIf($is_short,'`name_short`,','')."`comment`) 
		 	values('".f_ri($_POST["name"])."', ".echoIf($is_short,"'".f_ri($_POST["name"])."',",'')."'".f_ri($_POST["comment"])."')";
		 $res_news=true;
		 if ($res_news & $res=mysql_query($query)) {
		  	echo '<div class=success> Запись от "'.f_ri(DateTimeCustomConvert($_POST["date_act"],'d','rus2mysql')).'" добавлена успешно.'.$onEditRemain_text.'</div><br>';
			}
		 else {echo '<div class="warning">Запись не добавлена. Возможно наличие дубликата</div><br>';$err=true;}	
	//echo $query;
	 }	 
	 }
	else {echo '<div class="warning">Часть обязательных данных не заполнено .<br>&nbsp;</div>';$err=true;}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
} 

if (isset($_GET['type']) && $_GET['type']=='edit')
{
	if ($item_id>0)
	{//echo '<h4>Правка темы</h4>';
	$query="select * from `$sprav_name`  where id='$item_id'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="warning">не выбрана Запись для правки</h4>';}	
}

//добавление тем ---------------------------------------
if (isset($_GET['type']) && ($_GET['type']=='add' || $_GET['type']=='edit') )
{
//---------------------------начало формы ввоза\правки элемента
?>
<div><a href="<?php echo 	$main_page.'?'.reset_param_name(reset_param_name($query_string,'item_id'),'type');?>">Просмотр записей </a></div>
<h4> <?php if(isset($_GET['type']) && $_GET['type']=='edit') {echo 'Правка';} else {echo 'Ввод новой';} ?> записи в справочнике <font size=+2><?php echo $sprav_data['sprav_com']; ?></font> </h4>
<div class="forms_under_border" style="width:99%;">
<form name="item_form" id="item_form" method="post" action="">
	Имя полное * <br><input type=text title="Имя полное" size=100 name=name id=name value="<?php echo getFormItemValue('name'); ?>"> <p>
	<?php if ($is_short) {?>Имя краткое<br><input type=text size=100 name=name_short id=name_short value="<?php echo getFormItemValue('name_short'); ?>"> <p><?php } ?>	
	Комментарий<br><input type=text size=100 name=comment id=comment value="<?php echo getFormItemValue('comment'); ?>"> <p>
	<input type=button onclick="javascript:check_form();" value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>
</div> 
<?php
//---------------------------конец формы ввоза\правки элемента
}  
else {
echo 'Просмотр справочника: ';

if (!isset($_GET['save']) && !isset($_GET['print'])) {
  echo '<select id=sprav_id name=sprav_id onChange=javascript:window.location.href="?'.reset_param_name($query_string,'sprav_id').'&"+this.name+"="+this.options[this.selectedIndex].value;>';
  $list_q='select id,comment from sprav_links order by comment';
  echo getFrom_ListItemValue($list_q,'id','comment','sprav_id');
  
  echo '</select> &nbsp; ';
} else { echo '<font size=+2>'.$sprav_data['sprav_com'].'</font> &nbsp; ';}  
  echo 'категория: <b>'.echoIf($sprav_data['cat_name']!='',$sprav_data['cat_name'],'прочие') .'</b>  &nbsp; 
  <a href="spravochnik.php?spr_type='.$sprav_cat_id.'#id_'.$sprav_id.'"> к категории справочников </a><br>';
  //$query='select * from '.$sprav_name.' where id='.$elem_id;

//выборка для показа списочной таблицы записей------------------------
	if ($is_short) {$query="select name,name_short,comment,id from `".$sprav_name."`";}
	else {$query="select name,comment,id from `".$sprav_name."`";}

$search_query='';
$search_query=' and (`name` like "%'.$q.'%" or 
					`comment` like "%'.$q.'%")';
	
	$query=$query." where 1 ".$search_query." order by ".$sort." ".$stype." ";
	
	//echo $query;	
	$res=mysql_query($query.'limit '.(($page-1)*$pgVals).','.$pgVals);
	
$table_headers=array(1=>array('имя полное','200'));

if ($is_short)	{array_push($table_headers, array('имя краткое','40'));}

//print_r($table_headers);

	$sort=1;
	if (isset($_GET['sort']) && $_GET['sort']>1 && $_GET['sort']<=count($table_headers)) 
		{	 $sort=$_GET['sort']; }
    
if (!isset($_GET['save']) && !isset($_GET['print'])) {
	echo '<div align=right><a class=text href="?'.$_SERVER['QUERY_STRING'].'&save&attach=doc" title="Выгрузить в MS Word" target="_blank">в MS Word</a>&nbsp;&nbsp;&nbsp;
		<a class=text href="?'.$_SERVER['QUERY_STRING'].'&print" title="Распечатать" target="_blank">Печать</a>&nbsp;&nbsp;&nbsp;
		<input type=text name="q" id="q" width=50 value=""> &nbsp; 
		<input type=button value="Найти" title="введите часть имени для поиска" OnClick=javascript:go2search(\''.reset_param_name($query_string,'q').'\') > </div>';    

	echo '<p class="notinfo"><a href="?type=add&'.$_SERVER["QUERY_STRING"].'"> Добавить</a><p>';
if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div>';
if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?'.reset_param_name(reset_param_name ($query_string,'kadri_id'),'q').'"> сбросить фильтр </a></div>';}
}
}
echo '<form name=order_list>
<table name=tab1 border=1 cellpadding="0" cellspacing="0" width="99%"><tr align="center" class="title" height="30">';
	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '<td width="50" class="notinfo"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}

//echo $query;

$add_string=reset_param_name($query_string,'sort');
	
//------------------------------------------- шапка списочной таблицы -начало--------------------------------
	echo '<td width="50">№</td>';

	for ($i=1;$i<=count($table_headers);$i++)
	{
		echo '<td width="'.$table_headers[$i][1].'">'.echoIf(!isset($_GET['save']) && !isset($_GET['print']),print_col($i,$table_headers[$i][0]),$table_headers[$i][0]).'</td>';
	}
	if (!isset($_GET['save']) && !isset($_GET['print'])) 
		{echo '<td class="notinfo">комментарий</td>';}
//------------------------------------------- шапка списочной таблицы -конец----------------------------------
	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};
	$i=1;
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		echo '<tr align="left" class="text" '.$bgcolor.' valign="top">';
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		  echo '<td align="center"> 
		  	<a href="javascript:del_confirm_act(\''.str_replace(" ","_",f_ro($tmpval['name'])).'\',\'?item_id='.f_ro($tmpval['id']).'&type=del&'.
			  $query_string.'\');" title="Удалить">		  	
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="?item_id='.$tmpval['id'].'&type=edit&'.$query_string.'" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';			
			}		
		echo '<td>&nbsp;'.($i+($page-1)*$pgVals).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['name']).'</td>';
		
		if ($is_short) 	{echo '<td>&nbsp;'.color_mark($q,$tmpval['name_short']).'</td>'; }

		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.color_mark($q,$tmpval['comment']).'</td>';}
	$i++;
	}
echo '</table></form>';  

//-----------------список страниц

//постраничный вывод списка тем (по 10 тем)
$res=mysql_query($query);
if (floor(mysql_num_rows($res)/$pgVals)==mysql_num_rows($res)/$pgVals) {$pages_cnt=floor(mysql_num_rows($res)/$pgVals);}
 else {$pages_cnt=floor(mysql_num_rows($res)/$pgVals)+1;}
echo '<div align="left"> страницы ';


$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

for ($i=1;$i<=$pages_cnt;$i++) {if ($i!=$page) {echo '<a href="?'.$add_string.'&page='.$i.'"> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}}
//--------------------------------------------------------

$add_string=reset_param_name($add_string,'pgVals');// preg_replace("/(&pgVals=)(\d+)/x","",$add_string);		//убрать число страниц через RegExp
echo '<br>макс.число записей на странице:  <input type=text value="'.$pgVals.'" name="pgVals" id="pgVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgVals(\''.$add_string.'\');" value=Ok>
	<p> Всего записей: '.mysql_num_rows($res).'</div>'; 	
}
//-----------------конец списка страниц

echo '<a href="spravochnik.php?spr_type='.$sprav_cat_id.'#id_'.$sprav_id.'">Вернуться </a>';
//Header('Location:spravochnik.php?');
}
}

if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';}
?>
<?php include('footer.php'); ?>