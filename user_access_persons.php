<?php
include ('authorisation.php');
include ('master_page_short.php');

?>
<script language="JavaScript">
var main_page='<?php echo $curpage; ?>';

function makeGetReq(selectItem)
{
  if (selectItem!=null)
  {
   	if (selectItem.value==0) alert('Не выбрано значение: '+selectItem.title);
   	else
   	{
   	 document.location.href=main_page+'?'+selectItem.id+'='+selectItem.value;
   	 }
   }
}
function add_task()
{
 	a = new Array(
 	new Array('user_id',''),
	new Array('task_id',''),
	new Array('task_right_id','')
	);

	requireFieldCheck(a,'access_form');
} 
function add_pg()
{
 	a = new Array(
 	new Array('user_id',''),
	new Array('pg_id','')
	);

	requireFieldCheck(a,'access_form');
 
} 
function help_msg()
{
   alert('Настройка прав пользователей к задачам на портале.\n Только для администратора.'); 
} 
</script>

<?php

$group_id=0;
$user_id=0;	//номер добавляемого пользователя
$task_id=0;	//номер добавляемой задачи
$type_act="";	//действие (del,add)
$delType="";
$item_id=0;
$task_right_id=0;	//права на задачу

if (isset($_POST['task_right_id'])) {$task_right_id=intval($_POST['task_right_id']);}
if (isset($_GET['user_id'])) 	{$user_id=intval($_GET['user_id']);}
if (isset($_GET['item_id'])) 	{$item_id=intval($_GET['item_id']);}

if (isset($_POST['task_id'])) 	{$task_id=intval($_POST['task_id']);}
if (isset($_POST['pg_id'])) 	{$pg_id=intval($_POST['pg_id']);}

if (isset($_GET['type_act'])) {$type_act=$_GET['type_act'];}
if (isset($_GET['delType'])) {$delType=$_GET['delType'];}

$query="";
if ($user_id>0) {
	if ($type_act=="add") {	//добавление прав
		if ($task_id!=0 && $task_right_id>0) {
			$query='insert into task_in_user(user_id,task_id,task_rights_id) values("'.$user_id.'","'.$task_id.'","'.$task_right_id.'")';
				$msg='добавление задачи ';}
		if ($pg_id!=0) {
			$query='insert into pg_in_user(user_id,pg_id) values("'.$user_id.'","'.$pg_id.'")';
				$msg='добавление веб-страницы ';}
	}
	else 
		if ($type_act=="del" && $item_id>0 && $delType!="") {	//удаление прав
			if ($delType=='task') {$query='delete from task_in_user where user_id="'.$user_id.'" and id="'.$item_id.'"';
				$msg='удаление задачи '; }
		    else if ($delType=='pg') {$query='delete from pg_in_user where user_id="'.$user_id.'" and id="'.$item_id.'"';
				$msg='удаление веб-страницы '; }
	     
			}
if ($query!="") {
 	echo $msg; 
	//echo 'query='.$query;
	if (mysql_query($query)) {echo ' <font class=success> выполнено </font>';}
	else {echo ' <font class=warning> не выполнено </font> ';} 
	}

}

?>
<h4><?php echo $pg_title; ?></h4>
<div class="text">пользователь может входить одновременно в несколько групп <br>
если задачи перекрываются будут выведены только уникальные задачи  </div>

<form name="access_form" id="access_form" action="?user_id=<?php echo $user_id;?>&type_act=add" method="post">
<table name=saves_table cellpadding="0" cellspacing="10" class=forms_under_border width="99%" border=0>
<tr><td colspan="2"><input type=hidden name="type_kind" value="<?php echo $type_kind;?> "></td></tr>

	<tr><td > ФИО пользователя</td><td>	
		<select name="user_id" id="user_id" style="width:300;" title="ФИО пользователя" OnChange="javascript:makeGetReq(this);">
		<?php
			$listQuery="select 
				concat(u.fio,' (',(select count(*) from task_in_user tiu where tiu.user_id=u.id),')') as name,u.id 
					from users u order by u.fio";
			echo getFrom_ListItemValue($listQuery,'id','name','user_id');
		?>
		</select></td></tr>

<tr><td>задачи у пользователя:</td><td class=text>
<?php
if ($user_id>0) {
		//----------------  вывод задач с учетом груп пользователя
		$query_from_gr_list='SELECT tasks.name, user_groups.comment, user_groups.color_mark
					  FROM    (   (   tasks tasks
					               INNER JOIN
					                  task_in_group task_in_group
					               ON (tasks.id = task_in_group.task_id))
					           INNER JOIN
					              user_groups user_groups
					           ON (user_groups.id = task_in_group.user_group_id))
					       INNER JOIN
					          user_in_group user_in_group
					       ON (user_groups.id = user_in_group.group_id)
					 WHERE (user_in_group.user_id = '.$user_id.')
					ORDER BY tasks.name ASC';
		$res=mysql_query($query_from_gr_list);
		if (mysql_num_rows($res)==0) {echo '<div class=warning>активных групповых задач пользователя нет</div>';}
		else {
		echo '<div><a href="javascript:hide_show(\'gr_task_list\');" title="показать\скрыть список" class=success>
			найдено групповых задач '.mysql_num_rows($res).'</a></div>'.
			'<div id=gr_task_list class=text style="display:none; padding-left:40px;">';
		while ($a=mysql_fetch_array($res)) 	{
			echo $a['name'].' (<span style="color:'.$a['color_mark'].';">'.$a['comment'].'</span>)<br>';
			}
		echo '</div>';		
		}	
		//-----------------
		
		$query_person="SELECT t.name, tiu.id, tr.color as tr_color,tr.name_short as tr_name_short,tr.name as tr_name 
			FROM tasks t 
				inner join task_in_user tiu on t.id=tiu.task_id 
				left join task_rights tr on tr.id=tiu.task_rights_id
			WHERE tiu.user_id='".$user_id."'  order by t.name";
		//echo $query;
		$res=mysql_query($query_person);
		if (mysql_num_rows($res)==0) {echo '<div class=warning>активных персональных задач нет</div>';}
		else {
		while ($a=mysql_fetch_array($res)) 	{
			echo '<a onclick="javascript:del_confirm_act(\''.$a['name'].'\',\'?user_id='.$user_id.'&item_id='.$a['id'].'&type_act=del&delType=task\');" href="#deltask">удалить</a> '.
			'<span title="'.$a['tr_name'].'" style="color:'.$a['tr_color'].';">'.$a['tr_name_short'].'</span> '.
			$a['name'].'<br>';
			}		
		}	
}
?>
</td></tr>
<tr><td></td><td>
<select name="task_id" id="task_id" style="width:300;" title="задачи у пользователя">
		<?php
		//вывод персональных задач и отметка в них встречающихся групповых
		$listQuery='select t.id,concat(t.name,(
			select if((select count(*) from user_groups ug
			 INNER JOIN task_in_group tig ON ug.id = tig.user_group_id
			 INNER JOIN user_in_group uig ON ug.id = uig.group_id
		 where tig.task_id=t.id and uig.user_id="'.$user_id.'" 
			)>0,"(+)","") )) as name 
		from tasks t
		where t.id not in 
			(select distinct task_id from task_in_user where user_id="'.$user_id.'")  order by name';
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
</td></tr>
<tr><td><a name=pg></a>веб-страницы персональные:</td><td class=text>
<?php
if ($user_id!=0) {
		$query="select pu.title,pg.id 
		from `pg_uploads` pu inner join pg_in_user pg on pg.pg_id=pu.id 
		where pg.user_id='".$user_id."' and (pu.`type_id`=1 or pu.`static`=1) order by pu.title";
		//echo $query;
		$res=mysql_query($query);
		if (mysql_num_rows($res)==0) {echo 'активных страниц нет';}
		else {
		while ($a=mysql_fetch_array($res)) 	{
			echo '<a onclick="javascript:del_confirm_act(\''.$a['title'].'\',\'?user_id='.$user_id.'&item_id='.$a['id'].'&type_act=del&delType=pg\');" href="#delpg">удалить</a> '.$a['title'].'<br>';
			}}	
}
?>
</td></tr>
<tr><td></td><td>
<select name="pg_id" id="pg_id" style="width:300;" title="веб-страницы у пользователя">
		<?php
		//для преподавателя позиционируем на его ФИО
		$listQuery='select id,title from `pg_uploads` 
			where (`type_id`=1 or `static`=1) and id not in 
				(select pg_id from `pg_in_user` where user_id="'.$user_id.'") 
			order by title';
		echo getFrom_ListItemValue($listQuery,'id','title','pg_id');
		?>
	</select>
 &nbsp;
	<input type="button" value="Добавить" onclick="javascript:add_pg();">
	<hr> </td></tr>
<tr valign="middle" height="44">
	<td colspan=2><input type="button" value="Справка" onclick="javascript:help_msg();"></td>  
</tr>
</table></form>
<div class=text>
<b>Примечение:</b><br>
<ul>
<li>слева от наименования задачи в списке "задачи у пользователя:" отражается тип доступа пользователя (одной задаче-только один тип доступа)<br>
к веб-страницам деление по типам доступа не применяется </li>
<li>список групповых задач можно изменить только через <a href="user_access.php">"Права пользователей"</a></li>
<li>при добавлении персональной задачи пользователю дополнительно указывается признак (+) наличия задачи в групповом списке </li>
<li>по умолчанию персональной задаче присваивается статус максимальных прав доступа, <b>будьте внимательны</b></li>
<li>права по персональным и групповым задачам при наложении выбирают максимальный из указанных уровней доступа в разрезе "тип доступа"</li>
</ul>
</div>
<p><a href="admin_users.php">К списку пользователей.</a><p>
<p><a href="s_tasks.php">К списку всех задач портала.</a><p>
<p><a href="p_administration.php">К списку задач администратора.</a><p>

<?php include('footer.php'); ?>