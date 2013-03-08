<?php
include ('authorisation.php');
include ('master_page_short.php');

?>
<h4><?php echo $pg_title; ?></h4>
<script language="JavaScript">
var main_page='<?php echo $curpage; ?>';

function group_change(group_id)
{
	 if (parseInt(group_id)==0) {alert('Выберите задачу для добавления');}
	 else {document.location.href=main_page+'?group_id='+group_id;}

}
function add_task()
{
 	a = new Array(
 	new Array('group_id',''),
	new Array('task_id',''),
	new Array('task_right_id','')
	);

	requireFieldCheck(a,'access_form');
} 


function add_user(group_id,user_id)
{
 if (parseInt(user_id)==0) {alert('Выберите пользователя для добавления');}
 else {document.location.href=main_page+'?group_id='+group_id+'&user_id='+user_id+'&type_act=add#user';}
 
} 
function add_pg(group_id,pg_id)
{
 if (parseInt(pg_id)==0) {alert('Выберите веб-страницу для добавления');}
 else {document.location.href=main_page+'?group_id='+group_id+'&pg_id='+pg_id+'&type_act=add#pg';}
 
} 
function help_msg()
{
   alert('Настройка прав пользователей к задачам на портале.\n Только для администратора.');
 
} 
</script>

<?php
//include ('authorisation.php');
//include ('menu.htm');
//include ('sql_connect.php');

$group_id=0;
$pg_id=0;
$user_id=0;	//номер добавляемого пользователя
$task_id=0;	//номер добавляемой задачи
$type_act="";	//действие (del,add)
$task_right_id=0;	//права на задачу
$item_id=0;
$delType="";

if (isset($_GET['item_id'])) 	{$item_id=intval($_GET['item_id']);}

if (isset($_GET['group_id'])) {$group_id=intval($_GET['group_id']);}
if (isset($_GET['user_id'])) {$user_id=intval($_GET['user_id']);}
if (isset($_GET['pg_id'])) {$pg_id=intval($_GET['pg_id']);}
if (isset($_GET['type_act'])) {$type_act=$_GET['type_act'];}
if (isset($_GET['delType'])) {$delType=$_GET['delType'];}

if (isset($_POST['task_id'])) {$task_id=intval($_POST['task_id']);}
if (isset($_POST['task_right_id'])) {$task_right_id=intval($_POST['task_right_id']);}

$query="";
if ($group_id!=0) {
	if ($type_act=="add") {		
		if ($user_id>0) { 
			$query='insert into user_in_group(group_id,user_id) values("'.$group_id.'","'.$user_id.'")';
				//echo "<hr>".$query;
				$msg='добавление пользователя ';}
		if ($task_id>0) {
			$query='insert into task_in_group(user_group_id,task_id,task_rights_id) values("'.$group_id.'","'.$task_id.'","'.$task_right_id.'")';
				//echo "<hr>".$query;
				$msg='добавление задачи ';}
		if ($pg_id>0) {
			$query='insert into pg_in_group(group_id,pg_id) values("'.$group_id.'","'.$pg_id.'")';
				//echo "<hr>".$query;
				$msg='добавление веб-страницы ';}
		  //echo "<hr>".$query;
	}
	
	else 
	if ($type_act=="del" && $item_id>0 && $delType!="")
	 {
		if ($delType=='task') {$query='delete from task_in_group where user_group_id="'.$group_id.'" and task_id="'.$item_id.'"';
				$msg='удаление задачи '; }
		else if ($delType=='pg') {$query='delete from pg_in_group where group_id="'.$group_id.'" and pg_id="'.$item_id.'"';
				$msg='удаление веб-страницы '; }
		  else if ($delType=='user') {$query='delete from user_in_group where group_id="'.$group_id.'" and user_id="'.$item_id.'"';
			//echo "<hr>".$query;
			$msg='удаление пользователя '; }   
	     
	}
if ($query!="") {
 	echo $msg; 
	//echo 'query='.$query;
	if (mysql_query($query)) {echo ' <font class=success> выполнено </font>';}
	else {echo ' <font class=warning> не выполнено </font> ';} 
	}

}
?>
<div class="text">пользователь может входить одновременно в несколько групп <br>
если задачи перекрываются будут выведены только уникальные задачи  </div>

<form name="access_form" id=access_form action="?group_id=<?php echo $group_id;?>&type_act=add" method="post">
<table name=saves_table cellpadding="0" cellspacing="10" class=forms_under_border width="="99%" border=0>
<tr><td width=200 colspan="2"></td></tr>

	<tr><td width="200"> группа пользователей</td><td>	
		<select name="group_id" id=group_id style="width:300;" OnChange="javascript:group_change(this.value);"> 
		<option value="0">...выберите группу задач...</option>
		<?php
		$query='select id,name,comment from user_groups order by name';
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if ($group_id!=0) { if ($group_id==$a['id']) {$select_val=' selected';} } 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['comment'].'</option>';
			}
echo '	</select></td></tr>';

echo '<tr><td>задачи в группе:</td><td class=text>';
if ($group_id!=0) {
		$query="SELECT tasks.id,tasks.name,tasks.url, tr.color as tr_color,tr.name_short as tr_name_short,tr.name as tr_name 
		  FROM tasks
		  inner join task_in_group on tasks.id=task_in_group.task_id
		  left join task_rights tr on tr.id=task_in_group.task_rights_id
			WHERE task_in_group.user_group_id='".$group_id."'  order by tasks.name";
		$res=mysql_query($query);
		if (mysql_num_rows($res)==0) {echo 'активных задач нет';}
		else {
		while ($a=mysql_fetch_array($res)) 	{
			echo '<a onclick="javascript:del_confirm_act(\''.$a['name'].'\',\'?group_id='.$group_id.'&item_id='.$a['id'].'&type_act=del&&delType=task\');" href="#deltask">удалить</a> '.
			'<span title="'.$a['tr_name'].'" style="color:'.$a['tr_color'].';">'.$a['tr_name_short'].'</span> '.
			$a['name'].'<br>';
			}		
		}
	
}
echo'</td></tr>';
echo '<tr><td></td><td>	'; 
	 
?>
<select name="task_id" id="task_id" style="width:300;" title="задачи в группе">
		<?php
		//для преподавателя позиционируем на его ФИО
		$listQuery='select id,name from tasks where id not in (select distinct task_id from task_in_group where user_group_id="'.$group_id.'")  order by name';
		echo getFrom_ListItemValue($listQuery,'id','name','task_id');
		?>
	</select>&nbsp;
	 <select name="task_right_id" id="task_right_id" title="права в задаче">
		 <?php
			 $listQuery='select id,name from task_rights';
			 //по умолчанию доступ в задаче на все
			 if (!isset($_POST['task_right_id'])) $res_edit['task_right_id']=4;
			 
			 echo getFrom_ListItemValue($listQuery,'id','name','task_right_id');	
		 ?>
	 </select>	
	
	<input type="button" value="Добавить" onclick="javascript:add_task();">
	<hr>
<?php
echo '</td></tr>';

echo '<tr><td><a name=user></a>пользователи в группе:</td><td class=text>';
if ($group_id!=0) {
		$query="select users.fio,users.id from users inner join user_in_group on user_in_group.user_id=users.id 
			where user_in_group.group_id='".$group_id."' order by users.fio";
		//echo $query;
		$res=mysql_query($query);
		if (mysql_num_rows($res)==0) {echo 'активных пользователей нет';}
		else {
		while ($a=mysql_fetch_array($res)) 	{
			echo '<a href="?group_id='.$group_id.'&item_id='.$a['id'].'&type_act=del&delType=user#user">удалить</a> '.$a['fio'].'<br>';
			}
		}
	
}
echo'</td></tr>';
echo '<tr><td></td><td>';
?>
<select name="user_id" id="user_id" style="width:300;" title="пользователи в группе">
		<?php
		//для преподавателя позиционируем на его ФИО
		$listQuery='select id,fio from users where id not in (select user_id from user_in_group where group_id="'.$group_id.'") order by fio';
		echo getFrom_ListItemValue($listQuery,'id','fio','user_id');
		?>
	</select> &nbsp;	
	<input type="button" value="Добавить" onclick="javascript:add_user(document.access_form.group_id.value,document.access_form.user_id.value);">
	<hr> </td></tr>
<?php
//------------------------
echo '<tr><td><a name=pg></a>веб-страницы в группе:</td><td class=text>';
if ($group_id!=0) {
		$query="select pu.title,pu.id 
		from `pg_uploads` pu inner join pg_in_group pg on pg.pg_id=pu.id 
		where pg.group_id='".$group_id."' and (pu.`type_id`=1 or pu.`static`=1) order by pu.title";
		//echo $query;
		$res=mysql_query($query);
		if (mysql_num_rows($res)==0) {echo 'активных страниц нет';}
		else {
		while ($a=mysql_fetch_array($res)) 	{
			echo '<a href="?group_id='.$group_id.'&item_id='.$a['id'].'&type_act=del&delType=pg#pg">удалить</a> '.$a['title'].'<br>';
			}}
	
}
echo'</td></tr>';
echo '<tr><td></td><td>';
?>
<select name="pg_id" id="pg_id" style="width:300;" title="веб-страницы в группе">
		<?php
		//для преподавателя позиционируем на его ФИО
		$listQuery='select id,title from `pg_uploads` where (`type_id`=1 or `static`=1) and id not in (select pg_id from `pg_in_group` where group_id="'.$group_id.'") order by title';
		echo getFrom_ListItemValue($listQuery,'id','title','pg_id');
		?>
	</select> &nbsp;
	<input type="button" value="Добавить" onclick="javascript:add_pg(document.access_form.group_id.value,document.access_form.pg_id.value);">
	<hr> </td></tr>
<?php

//------------------------
echo '<tr valign="middle" height="44">
	<td colspan=2><input type="button" value="Справка" onclick="javascript:help_msg();"></td>  
</tr>
</table></form>';
?>
<p><a href="admin_users.php">К списку пользователей.</a><p>
<p><a href="s_tasks.php">К списку всех задач портала.</a><p>
<p><a href="p_administration.php">К списку задач администратора.</a><p>

<?php include('footer.php'); ?>