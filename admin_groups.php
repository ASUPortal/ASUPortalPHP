<?php
include ('authorisation.php');


//$main_page='admin_groups.php';
$item_id=0;
$type="";



//include ('sql_connect.php');

if (isset($_GET['type']) && $_GET['type']=='del' && isset($_GET['item_id']))
	{
	//echo '!!!!!!!del!!!!!!';
	$query="delete from user_groups where id='".f_ri($_GET['item_id'])."'";
	//echo $query; 
	$res=mysql_query($query);
	//exit();
	header('Location: '.$curpage);
		
	}

include ('master_page_short.php');
?>
<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}

</style>
<script language="JavaScript">
var main_page='admin_groups.php';	//for redirect & links

function del_confirm(id,title,tasks_cnt,users_cnt)
{
	 if (tasks_cnt>0 || users_cnt>0) {alert('Задачу "'+title+'" удалить невозможно. \nСначала перенесите к другой задаче привязанных пользователей и задачи ?');}
	 else {
		 if (confirm('Удалить строку: "'+title+'"?')) 
		 	{window.location.href=main_page+'?item_id='+id+'&type=del';} 
		}
} 
</script>

<?php

if (isset($_GET['item_id'])) {$item_id=$_GET['item_id'];}
//if (isset($_GET['type'])) {$user_id=$_GET['type'];}			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!

//добавление группы
echo '';
if (isset($_POST['item_name']))
{
$err=false;
	if ($_POST['item_name']!="" && $_POST['item_comment']!="") 
	{
		 if ($_POST['all_user_select']=='on') {$_POST['all_user_select']=1;}
		 else {$_POST['all_user_select']=0;}
		 if ($_POST['blocked']=='on') {$_POST['blocked']=1;}
		 else {$_POST['blocked']=0;}

	 //обновление группы
	 $onEditRemain_text='';
	 if ($onEditRemain==false) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'">по ссылке</a>';}
	 
	 if (isset($_GET['type']) & $_GET['type']=='edit' & isset($_GET['item_id']) & $item_id!='') {
		 //echo 'Правка группы.';
		 
		 $query="update user_groups set name='".$_POST['item_name']."', all_user_select='".$_POST['all_user_select']."', 
		 blocked='".$_POST['blocked']."', comment='".$_POST['item_comment']."',
		 color_mark='".$_POST['color_mark']."' where id='".$_GET["item_id"]."'";
		//echo $query;//.$_POST['hidden_new'];
		 if ($res=mysql_query($query)) {echo '<div class=success>Группа обновлена  успешно.'.$onEditRemain_text.'</div><p>&nbsp;';}
		 else {echo '<div class="warning">Группа не обновлена. Возможно такая уже есть.<p>&nbsp;</div>';$err=true;}
	 }
	 
	 //новое обновление
	 if (isset($_GET['type']) & $_GET['type']=='add') {
		 echo 'Добавление группы.';
		 $query="insert into user_groups(name,all_user_select,blocked,comment,color_mark) 
		 	values('".f_ri($_POST["item_name"])."','".f_ri($_POST["all_user_select"])."','".f_ri($_POST["blocked"])."',
			 '".f_ri($_POST["item_comment"])."','".f_ri($_POST["color_mark"])."')";
		 if ($res=mysql_query($query)) {echo '<div class=success>Группа добавлена успешно.'.$onEditRemain_text.'</div><p>&nbsp;';}
		 else {echo '<div class="warning">Группа не добавлена.Возможно такая уже есть<p>&nbsp;</div>';$err=true;}
		 //echo $query;
	 													}
	 }
	else {echo '<div class="warning">Часть обязательных данных не заполнена .<br>&nbsp;</div>';$err=true;}
	
if (!$err && !$onEditRemain) {echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'\"",2000);</script>';}
} 



if ($item_id!=0 & isset($_GET['type']) & $_GET['type']=='edit')
{
	if (isset($item_id) && $item_id!="")
	{//echo '<h4>Правка группы</h4>';
	$query="select * from user_groups where id='".$item_id."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="warning">не выбрана группа для правки</h4>';}	
}

//добавление группы
if (isset($_GET['type']) & $_GET['type']=='add' || $_GET['type']=='edit')
{
?>
<p><a href="<?php echo $curpage;?>" title="Просмотр групп портала"> Просмотр групп портала </a><p>
<h4> <?php if($_GET['type']=='add') {echo 'Добавление';} else {echo 'Правка';} ?> группы портала </h4>
<form name="new_form" method="post" action="">
<div class="forms_under_border" style="width:99%;">
<table border=0 cellspacing=10 cellpadding=10>
	<tr><td>Наименование * </td><td><input type=text size=50 name=item_comment value='<?php if (isset($res_edit)) {echo f_ro($res_edit['comment']);}
	else if (isset($_POST['item_comment'])) {echo $_POST['item_comment'];} ?>'> </td></tr>
	<tr><td>Наименование анг* &nbsp; </td><td><input type=text size=50 name="item_name" value='<?php if (isset($res_edit)) {echo f_ro($res_edit['name']);}else if (isset($_POST['item_name'])) {echo $_POST['item_name'];} ?>'> </td></tr>
	<tr><td>Цвет <br> <small>(в формате "#xxxxxx", например "#CCCC00")</small>  &nbsp; </td><td><input type=text size=50 name="color_mark" value='<?php if (isset($res_edit)) {echo f_ro($res_edit['color_mark']);} else {echo '#CCCC00';} ?>'> </td></tr>
	<tr><td>Отражение всех <br>пользователей &nbsp; </td><td><input type=checkbox name=all_user_select <?php if (isset($res_edit) && $res_edit['all_user_select']==1) {echo 'checked';} ?> > </td></tr>
	<tr><td>Блокированная &nbsp; </td><td><input type=checkbox name=blocked <?php if (isset($res_edit) && $res_edit['blocked']==1) {echo 'checked';} ?> title="запрещает доступ"> </td></tr>
<tr><td colspan=2>	
	<input type=submit value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> </td></tr>
</table>	
</div>
</form>

<?php
}

else 
	{
	echo '<h4> Группы пользователей.</h4>
		 <div class=text>после создания группы пользователей, к ней можно привязать <a href="s_tasks.php">задачи портала</a> и <a href="admin_users.php">список пользователей </a>.<br> Распределение прав через задачу <a href="user_access.php"> права пользователей </a>.
		 </div>';
	echo '<p><a href="'.$curpage.'?type=add"> Добавить группу </a><p>';

  if (isset($_GET['sort'])) {$sort_id=$_GET['sort'];
   	if ($sort_id<1 || $sort_id>5) {$sort_id=1;}
   }
   else {$sort_id=1;}

$query="select user_groups.name,count(task_in_group.user_group_id) as task_cnt,
		user_groups.all_user_select,user_groups.blocked,user_groups.comment,user_groups.id,user_groups.color_mark  from user_groups
			left join task_in_group on task_in_group.user_group_id=user_groups.id
		group by user_groups.id
		ORDER by ".$sort_id." ";
	$res=mysql_query($query);
//echo $query;
echo '<form name=task_list>
	<table name=tab1 bnew=1 cellpadding="1" cellspacing="1">
		<tr align="center" class="title" height="30">';
	echo '<td width="50"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';
	echo '<td width="20">#</td>';
	echo '<td width="20">цвет</td>';
	echo '<td width="60"><a href="?sort=1">наименование</a></td>';
	echo '<td width="100"><a href="?sort=2">задач</a></td>';
	echo '<td width="100"><a href="#">пользователей</a></td>';	
	echo '<td width="200"><a href="?sort=3" title="отражение всех пользователей в списке выбора задачи">все пользователи в списке</a></td>';
	echo '<td width="100"><a href="?sort=4" title="отказ в доступе">блокированная</a></td>';	
	echo '<td width="200"><a href="?sort=5">примечание</a></td>';
	$i=1;

	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$query="select count(*)as users_cnt from user_in_group where group_id='".$tmpval['id']."'";
		$res_=mysql_query($query);$tmpval_=mysql_fetch_array($res_);
		
		
		echo '<tr align="left" class="main" bgcolor="#DFEFFF">';
		
		//echo '<tr align="left" class="main">';
		echo '<td align="center" height=40> <a href=javascript:del_confirm("'.f_ro($tmpval['id']).'","'.str_replace(' ','_',f_ro($tmpval['name'])).'","'.f_ro($tmpval['task_cnt']).'","'.f_ro($tmpval_['users_cnt']).'") title="Удалить"><img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="'.$curpage.'?item_id='.$tmpval['id'].'&type=edit" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';

		echo '<td>'.$i.'</td>';
		echo '<td><font size="+3" color="'.$tmpval['color_mark'].'">*</font></td>';
		echo '<td>'.$tmpval['comment'].'</td>';
		echo '<td style="text-align:center;">'.$tmpval['task_cnt'].'</td>';

		echo '<td style="text-align:center;">';
		
		
		echo $tmpval_['users_cnt'];
		echo '</td>';

		
		echo '<td style="text-align:center;">';
			if ($tmpval['all_user_select']==1) {echo '+';}
		echo'</td>';
		echo '<td style="text-align:center;">';
			if ($tmpval['blocked']==1) {echo '+';}
		echo'</td>';
		
		echo '<td>'.$tmpval['name'].'</td>';
		echo '</tr>';
		$i++;
	}
echo '</table></form>';
$query="select * from user_groups";
$res_=mysql_query($query);
/*echo '<div class="text">обозначения групп:';
while ($tmpval_=mysql_fetch_array($res_)) {	echo color_mark($tmpval_['id']).'-'.$tmpval_['comment'].', ' ;} 
echo '</div><p>';*/
//постраничный вывод списка обновлений (по 10 обновлений)
/*if (isset($_GET['filtr']) & $_GET['filtr']!=0) {$query='select id from develop_news where news_type="'.$_GET['filtr'].'"';}
else {$query='select id from develop_news';}
//echo $query;*/
//$res=mysql_query($query);
//--------------------------------------------------------
echo '<div align="left"> Всего групп: '.mysql_num_rows($res).'</div>'; 	
	}

	 show_footer();
?>



<?php include('footer.php'); ?>