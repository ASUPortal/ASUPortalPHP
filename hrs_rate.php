<?php
include ('authorisation.php');
//include ('sql_connect_empty.php');

$main_page='hrs_rate.php';
$page=1;
if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}

if (isset($_GET['type']) & $_GET['type']=='del' && isset($_GET['item_id']))
	{
	$query="delete from `hours_rate` where id='".f_ri($_GET['item_id'])."'";
	$res=mysql_query($query);
	//exit();
	header('Location:'.$main_page.'?page='.$page.'');
		
	}
include ('master_page_short.php');

?>
<script language="JavaScript">
function test_submit()
{
 	a = new Array(
	 	new Array('dolgnost_id',''),
		new Array('rate','')
	);
requireFieldCheck(a,'new_form');
} 

</script>

<h4> Cправочник ставок в часах по нагрузке </h4>
<?php


//добавление обновления
if (isset($_POST['rate']))
{
	if ($_POST['rate']!="" && $_POST['dolgnost_id']!=0) 
	{
	 //обновление обновления
	 if (isset($_GET['type']) & $_GET['type']=='edit' & isset($_GET['new_id']) & intval($_GET['new_id'])>0) {
		 echo 'Правка обновления.';//SELECT `id`,`dolgnost_id`,`rate`,`comment` FROM `hours_rate` WHERE 1
		 $query="update `hours_rate` set `dolgnost_id`='".$_POST["dolgnost_id"]."',
		 	`rate`='".intval($_POST["rate"])."',comment='".f_ri($_POST["comment"])."'
			 where id='".$_GET["new_id"]."'";

		 if ($res=mysql_query($query)) {echo '<div class="success">Элемент обновлен  успешно.</div>';}
		 else {echo '<div class="warning">Элемент не обновлен .<p>&nbsp;</div>';}
	 }

	 //новое обновление
	 if (isset($_GET['type']) & $_GET['type']=='add') {
		 echo 'Добавление обновления.';
		 $query="insert into `hours_rate`(`dolgnost_id`,`rate`,comment) 
		 	values('".$_POST["dolgnost_id"]."','".intval($_POST["rate"])."','".f_ri($_POST["comment"])."')";
		 if ($res=mysql_query($query)) {echo 'Элемент со ставкой "'.$_POST['rate'].'" добавлен успешно.<p>&nbsp;';}
		 else {echo '<div class="err">Элемент не добавлен.<p>&nbsp;</div>';}
		 //echo $query;
	 }
	 }
	else {echo '<div class="err">Часть обязательных данных не заполнено .<br>&nbsp;</div>';}
	
} 

if (isset($_GET['type']) & $_GET['type']=='edit')
{
echo '<h4>Правка обновления</h4>';
	if (isset($_GET['new_id']) & intval($_GET['new_id'])>0)
	{
	$query="SELECT `id`,`dolgnost_id`,`rate`,`comment` FROM `hours_rate` WHERE id='".$_GET['new_id']."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="err">не выбрано обновление для правки</h4>';}	
}
else {
 if (isset($_GET['type']) & $_GET['type']=='add')
 {echo '<h4> Ввод нового элемента</h4>';}
	}
//добавление обновлениев
if (isset($_GET['type']) & $_GET['type']=='add' || $_GET['type']=='edit')
{
?>
<p><a href="?"> Вернуться к списку </a><p>

<form name="new_form" id="new_form" method="post" action="">
	Должность* &nbsp; <select name="dolgnost_id" id="dolgnost_id" title="должность" style="width:300;"> 

<?php
$listQuery='select id,name from dolgnost';

if ( $_GET['type']=='add') {
	//скрываем должности по которым уже проставлены ставки
	$listQuery.=' where id not in (select `dolgnost_id` from `hours_rate`)';
}

echo getFrom_ListItemValue($listQuery.' order by name','id','name','dolgnost_id');

?>
</select>
<?php if ($_GET['type']=='add') {echo '<span class=text> скрыты уже задействованные в ставках должности</span>';}?>
<p>
	Размер нагрузки, часы* &nbsp; <input type=text size=10 name=rate id=rate title="Размер нагрузки" value='<?php if (isset($res_edit)) {echo f_ro($res_edit['rate']);} ?>'> <p>
	примечание<br><input type=text size=100 name=comment value='<?php if (isset($res_edit)) {echo f_ro($res_edit['comment']);} ?>'> <p>
	<input type=button value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>" onClick=test_submit();> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>

<?php
}
else //списочная форма страницы
	{
	echo '<div class=text>справочник используется при расчете <a href=s_hours.php>показателей нагрузки ППС</a> и носит плановый характер </div> ';
	echo '<p><a href="'.$main_page.'?type=add"> Добавить элемент </a><p>';

$page=1;$pageVals=10;$search_query="";
if (isset($_GET['page'])) {$page=$_GET['page'];}


 $query="select hr.id,hr.rate,hr.comment,d.name as dolg_name 
from `hours_rate` hr left join dolgnost d on d.id=hr.dolgnost_id ORDER by d.name asc ";
	$res=mysql_query($query.'limit '.(($page-1)*$pageVals).','.$pageVals);
//echo $query;

echo '<table name=tab1 border=1 cellpadding="10" cellspacing="0"><tr align="center" class="title" height="30">';
	echo '<td width="50"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';
	echo '<td width="50">№ п\п</td>';
	echo '<td width="70">должность</td>';
	echo '<td width="100">размер нагрузки, часы</td>';
	echo '<td>примечание</td>';
	$i=0;
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;$i++;
		
		if ($tmpval['item_type']=='Планы')  {echo '<tr align="left" class="main" bgcolor="#FFFFCC">';} 
		else if ($tmpval['item_type']=='Модификация БД')  {echo '<tr align="left" class="main" bgcolor="#FFCC33">';} 				//
			 else {echo '<tr align="left" class="text2" bgcolor="#DFEFFF">';}
		
		//echo '<tr align="left" class="main">';
		if ($_SESSION['task_rights_id']!=4) { //del_confirm_act(del_title,loc_href)
		echo '<td align="center"> <a href=javascript:del_confirm_act("'.f_ro(str_replace(' ','_',$tmpval['dolg_name'])).'","?type=del&item_id='.$tmpval['id'].'") title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="'.$main_page.'?new_id='.$tmpval['id'].'&type=edit" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		echo '<td>&nbsp;'.$i.'</td>';

		echo '<td>&nbsp;'.$tmpval['dolg_name'].'</td>';
		echo '<td>&nbsp;'.$tmpval['rate'].'</td>';
		echo '<td>&nbsp;'.$tmpval['comment'].'</td>';
		echo '</tr>';
	}
echo '</table>';

//постраничный вывод списка обновлений (по 10 обновлений)
/*if (isset($_GET['filtr']) & $_GET['filtr']!=0) {$query='select id from develop_item where item_type="'.$_GET['filtr'].'"';}
else {$query='select id from develop_item';}
//echo $query;*/
$res=mysql_query($query);
$pages_cnt=floor(mysql_num_rows($res)/$pageVals)+1;
echo 'страницы ';
for ($i=1;$i<=$pages_cnt;$i++) {if ($i!=$page) {echo '<a href=?page='.$i.'&filtr='.$_GET['filtr'].'> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}}
//--------------------------------------------------------
echo '<div align="left"> Всего элементов: '.mysql_num_rows($res).'</div>'; 	
	}
//конец списочной страницы
show_footer();
?>

<?php include('footer.php'); ?>