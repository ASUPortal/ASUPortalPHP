<?php
include ('authorisation.php');

$main_page='s_tasks.php';
$task_id=0;
$type="";

$days4stat=0;
$date_now=date('Y.m.d',mktime(0,0,0, date("m"),date("d")-$days4stat,  date("Y"))  );
$date_now_Unix_prev7=mktime(0,0,0, date("m"),date("d")-7,  date("Y"))  ;

if (isset($_GET['task_id'])) {$task_id=intval($_GET['task_id']);}
if (isset($_GET['type'])) {$type=$_GET['type'];}

$taskFiles=array();
$i=0;


function lastWeekEdit($fDate,$markColor,$bgColor)
{
global $date_now;
global $date_now_Unix_prev7;
if ($fDate=='') {return;}
if ($bgColor!='') {$bgColor_='background-color:'.$bgColor.';';}
if ($markColor!='') {$markColor_='color:'.$markColor.';';}
 
$fileDate=$date_now;
$fDate_QNIX=strtotime($fDate);//mktime(0,0,0, ,date("d")-7,  date("Y"))  ;

if ($date_now_Unix_prev7>$fDate_QNIX) {$data2color=DateTimeCustomConvert($fDate,'d','mysql2rus');}
else {$data2color='<font style="'.$bgColor_.$markColor_.'">'.DateTimeCustomConvert($fDate,'d','mysql2rus').'</font>';}

//echo ' fDate_QNIX='.$fDate_QNIX;
//echo ' date_now_Unix_prev7='.$date_now_Unix_prev7;

return $data2color;
} 

function GetFileAttr($str2find,$taskFiles)
{
$isQuery_Str_pos=strpos($str2find,'?');
//выделяем только имя файла, без q-String
if ($isQuery_Str_pos>0) {$str2find=substr($str2find,0,$isQuery_Str_pos);}

//global $taskFiles;
$key=0;
for ($i=0; $i<count($taskFiles);$i++) 
	{
	 if ($str2find==$taskFiles[$i]['fname']) {$key=$i;break;}
	//echo '<br> fname='.$taskFiles[$i]['fname'].'<br>';	
	}
return $i;
}

 // проверить на папку
if ($handle = opendir('.')) {//получаем список файлов в корневой папке, для отражения дат в таблице
    while (false !== ($file = readdir($handle))) { 
        if ($file != "." && $file != ".." && !is_dir($file)) { 
            //echo "$file\n"; 
			//$taskFiles[$i]['fname']=$file;
			$taskFiles[$i]=array();
			$taskFiles[$i]['fname']=$file;
			
			$fileAttr=array();
			$fileAttr=stat($file);
			
			$taskFiles[$i]['mtime']=date("Y-m-d",$fileAttr['mtime']);//дата изменений date("Y-m-d H:i:s",$fileAttr['mtime'])
			$taskFiles[$i]['ctime']=date("Y-m-d",$fileAttr['ctime']);//дата создания
			//$taskFiles[$i]['atime']=date("Y-m-d H:i:s",$fileAttr['atime']);//дата последнего доступа
			
			$i++;
        } 
    }
    closedir($handle); 
}else {echo 'error opening dir';}

if ($_GET['type']=='refresh' && 1<0)	
// обновить в БД даты по файлам в работе !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
{
if (!isset($_COOKIE["taskDateRefresh"]))
{
 	setcookie("taskDateRefresh","1");
 	//обновление БД
 	//include_once ('sql_connect_empty.php');
	//$_COOKIE["taskDateRefresh"]="1";
	//echo '<b>обновление дат в БД</b>';
	
	header('Location:'.$main_page);
 
}
//else {echo $_COOKIE["taskDateRefresh"];echo '<b>обновление дат в БД не произведено</b>';} 
 
//print_r($_COOKIE); 
} 
if (isset($_GET['type']) & $_GET['type']=='del' && isset($_GET['task_id']))
	{
	//echo '!!!!!!!del!!!!!!';
	$query="delete from tasks where id='".f_ri($_GET['task_id'])."'";
	$res=mysql_query($query);
	//exit();
	header('Location:'.$main_page);
		
	}

//print_r($_COOKIE); 
include ('master_page_short.php');

//------------------------------------------------------------------------------------------
?>
<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}
.err {color:red;font-family:Arial;}
</style>

<script language="JavaScript">
var main_page='s_tasks.php';	//for redirect & links

function del_confirm(id,title)
{

	 if (confirm('Удалить строку: "'+title+'"?')) 
	 	{window.location.href=main_page+'?task_id='+id+'&type=del';} 
} 
</script>


<?php
//------------------------------------------------------------------------------------------



//	-----------------------групповые операции начало------------------------------

if (isset($_GET['gr_act']) )	{
				
	 $err=false;
	 if (!mysql_query('update tasks set students_in_task=NULL, kadri_in_task=NULL'))
	   echo '<div class=warning>ошибка очистки меток задач</div>';
	 while (list($key, $value) = each ($_POST)) {
		 
		 $chKadriTask_cnt=strstr($key,"chKadriTask_");
		 $chStudTask_cnt=strstr($key,"chStudTask_");
		 
		 if 	  ($chKadriTask_cnt || $chStudTask_cnt) {
			   $act_item_id=intval(preg_replace("/\D/","",$key));		
			
		 if ($act_item_id>0) {
			$update_params=($chStudTask_cnt?' students_in_task=1 ':'').
				       ($chKadriTask_cnt?' kadri_in_task=1 ':'');			
			
			$query_gr_act='update tasks set '.$update_params.
			' where id='.$act_item_id.' limit 1';			
		   }
		   if (! ($res=mysql_query($query_gr_act)) ) {$err=true;echo '<div class=warning> ошибка группового обновления записи id='.$act_item_id.'</div>';}    
	  
		  }
	 }

	 if ($err==true)	{echo '<div class=warning> Произошли ошибки при выполнении массовой операции </div>';}
	 else {echo '<div class=success> Выполнение массовой операции успешно</div>';}
}
//	-----------------------групповые операции конец------------------------------


//добавление задачи
if (isset($_POST['title_new']))
{
	if ($_POST['title_new']!="" & $_POST['file_new']!="") 
	{
	$menu_name_id=intval($_POST['menu_name_id']);
	
		 if ($_POST['hidden_new']=='on') {$_POST['hidden_new']=1;}
		 else {$_POST['hidden_new']=0;}
	 //обновление задачи
	 if (isset($_GET['type']) && $_GET['type']=='edit' && $task_id>0) {
		 echo 'Правка задачи.';
		 
		 $query="update tasks set name='".$_POST['title_new']."', url='".$_POST['file_new']."', comment='".$_POST['comment_new']."', hidden='".$_POST['hidden_new']."', menu_name_id='".$menu_name_id."' where id='".$task_id."'";
		//echo $query;//.$_POST['hidden_new'];
		 if ($res=mysql_query($query)) {echo 'Задача обновлена  успешно.<p>&nbsp;';}
		 else {echo '<div class="err">Задача не обновлена. Возможно такая уже есть.<p>&nbsp;</div>';}
	 }
	 
	 //новое обновление
	 if (isset($_GET['type']) & $_GET['type']=='add') {
		 echo 'Добавление задачи.';
		 $query="insert into tasks(name,url,comment,hidden,menu_name_id) 
		 	values('".f_ri($_POST["title_new"])."','".f_ri($_POST["file_new"])."','".f_ri($_POST["comment_new"])."','".$_POST['hidden_new']."','".$menu_name_id."')";
		 if ($res=mysql_query($query)) {echo 'Задача добавлена успешно.<p>&nbsp;';}
		 else {echo '<div class="err">Задача не добавлена.Возможно ее адрес(url) уже есть в списке задач портала<p>&nbsp;</div>';}
		 //echo $query;
	 													}
	 }
	else {echo '<div class="err">Часть обязательных данных не заполнена .<br>&nbsp;</div>';}
	
} 

if ($task_id>0 & isset($_GET['type']) & $_GET['type']=='edit')
{
	if ($task_id>0)
	{echo '<h4>Правка задачи</h4>';
	$query="select * from tasks where id='".$task_id."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="err">не выбрана задача для правки</h4>';}	
}

//добавление задачи
if (isset($_GET['type']) & $_GET['type']=='add' || $_GET['type']=='edit')
{
?>
<p><a href="s_tasks.php" title="Просмотр задач портала"> Просмотр задач портала </a><p>
<h4> <?php if($task_id==0) {echo 'Добавление';} else {echo 'Правка';} ?> задачи портала </h4>
<form name="new_form" method="post" action="">
	Название* &nbsp; <input type=text size=50 name=title_new value='<?php if (isset($res_edit)) {echo f_ro($res_edit['name']);} ?>'> <p>
	Имя файла* &nbsp; <input type=text size=50 name=file_new value='<?php if (isset($res_edit)) {echo f_ro($res_edit['url']);} ?>'> <p>
	Скрывать &nbsp; <input type=checkbox name=hidden_new <?php if (isset($res_edit) && $res_edit['hidden']==1) {echo 'checked';} ?> > &nbsp; &nbsp;
	Группа меню &nbsp; 
	
		<select name="menu_name_id" style="width:200;">
		<option value="">прочие</option>
		<?php
			//для преподавателя только просмотр своих публикаций
		 $query='select id,name from task_menu_names order by name';
		
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if (isset($res_edit) && $res_edit['menu_name_id']==$a['id']) {$select_val=' selected';} 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
			}
		?>
</select>
	
	<p>	
	Комментарий<br><input type=text size=100 name=comment_new value='<?php if (isset($res_edit)) {echo f_ro($res_edit['comment']);} ?>'> <p>
	
	<input type=submit value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>

<?php
}

else 
	{
	echo '<h4> Задачи портала:доступ ограничен.</h4>
		 <div class=text>первоначальное добавление задач портала, 
		 в дальнейшем эти задачи могут быть <a href="user_access.php"><u>добавлены </u></a>  в группы пользователей, <br> 
		 после чего	они отразятся в списке задач пользователя при вхождении его в указанную группу задач.<br>
		 Связанные задачи можно пометить "скрывать", оставив только главную из них.
		 </div>';
	echo '<p>
	<a href="'.$main_page.'?type=add"> Добавить задачу </a>	
	 <p>';
	//echo '<p><a href="'.$main_page.'?type=refresh"> Обновить даты </a><p>';

  if (isset($_GET['sort'])) {$sort_id=$_GET['sort'];
   	if ($sort_id<1 || $sort_id>4) {$sort_id=1;}
   }
   else {$sort_id=1;}

$query="select name,url,hidden,kadri_in_task, students_in_task, comment,id from tasks ORDER by ".$sort_id.",1";
	$res=mysql_query($query);
//echo $query;
echo '<form name=task_list id=task_list method="POST" action="?'.reset_param_name($query_string,'gr_act').'&gr_act"><table name=tab1 bnew=1 cellpadding="1" cellspacing="1" width=""><tr align="center" class="title" height="30">';
	echo '<td width="50"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';
	echo '<td width="20">группы</td>';
	echo '<td width="20">#</td>';
	echo '<td width="300"><a href="?sort=1">наименование</a></td>';
	echo '<td width="200"><a href="?sort=2">ссылка</a></td>';
	echo '<td width="20"><a href="?sort=3">скрывать</a></td>';
	echo '<td width="60"><a href="#sort=7">создана</a></td>';
	echo '<td width="60"><a href="#sort=8">изменена</a></td>';
	echo '<td width="60"><a href="?sort=4">сотруд.</a></td>';
	echo '<td width="60"><a href="?sort=5">студ.</a></td>';
	echo '<td width="200"><a href="?sort=6" title="Сортировать по порядку их добавления от старой к новой">комментарий</a></td>';
	$i=1;$ctime='';$mtime='';
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		
		
		if ($tmpval['news_type']=='Планы')  {echo '<tr align="left" class="main" bgcolor="#FFFFCC">';} 
		else if ($tmpval['news_type']=='Модификация БД')  {echo '<tr align="left" class="main" bgcolor="#FFCC33">';} 				//
			 else {echo '<tr align="left" class="main" bgcolor="#DFEFFF">';}
		
		//echo '<tr align="left" class="main">';
		echo '<td align="center" height=40> <a href=javascript:del_confirm("'.f_ro($tmpval['id']).'","'.str_replace(' ','_',f_ro($tmpval['name'])).'") title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="'.$main_page.'?task_id='.$tmpval['id'].'&type=edit" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';

		echo '<td>';	//список групп, в кот. входит задача

		$query="SELECT task_in_group.user_group_id,user_groups.color_mark,user_groups.comment as gr_comment  
			from task_in_group left join user_groups on user_groups.id=task_in_group.user_group_id where task_in_group.task_id='".$tmpval['id']."'";
		//echo $query;
		$res_=mysql_query($query);
		//$tmpval_=mysql_fetch_array($res_);
		//echo mysql_num_rows($res_);
		while ($tmpval_=mysql_fetch_array($res_))	{echo '<a href="#" title="'.$tmpval_['gr_comment'].'"><font size="+3" color="'.$tmpval_['color_mark'].'">*</font></a>';}
			//.color_mark($tmpval_['user_group_id'],'*','+3'); }
		
		
		echo'</td>';	
		echo '<td>'.$i.'</td>';
		echo '<td>'.$tmpval['name'].'</td>';
		echo '<td><a href="'.$tmpval['url'].'" title="'.$tmpval['comment'].'">'.$tmpval['url'].'</a></td>';
		echo '<td>';
			if ($tmpval['hidden']==1) {echo '+';}
		echo'</td>';
		$str2find=$tmpval['url'];
		$ctime=$taskFiles[GetFileAttr($str2find,$taskFiles)]['ctime'];
		$mtime=$taskFiles[GetFileAttr($str2find,$taskFiles)]['mtime'];
		
		echo '<td> '.lastWeekEdit($ctime,'','#00ff00').'</td>';
		echo '<td> '.lastWeekEdit($mtime,'','#00ff00').'</td>';
		
		$kadriInTask_cnt=0;
		$studentInTask_cnt=0;
		
		
		   // считаем число упоминаний ссылок на таблицы Студенты, Сотрудники
		   $kadriInTask_cnt=getObjectInTask($tmpval['url'],'kadri');
		   $studentInTask_cnt=getObjectInTask($tmpval['url'],'student');
		  echo '<td><input type=checkbox name="chKadriTask_'.$tmpval['id'].'" id="chKadriTask_'.$tmpval['id'].'" '.($tmpval['kadri_in_task']>0?'checked':'').' '.
		  ($tmpval['kadri_in_task']!=$kadriInTask_cnt?' style="background-color:red;"':'').
		  ($tmpval['kadri_in_task']>0 && $tmpval['kadri_in_task']==$kadriInTask_cnt?' style="background-color:#00ff00;"':'').
		  '></td>';
		  echo '<td><input type=checkbox name="chStudTask_'.$tmpval['id'].'" id="chStudTask_'.$tmpval['id'].'" '.($tmpval['students_in_task']>0?'checked':'').' '.
		  ($tmpval['students_in_task']!=$studentInTask_cnt?' style="background-color:red;"':'').
		  ($tmpval['students_in_task']>0 && $tmpval['students_in_task']==$studentInTask_cnt?' style="background-color:#00ff00;"':'').
		  '></td>';
		

		//------------------------------------------		
		echo '<td>'.$tmpval['comment'].'</td>';
		echo '</tr>';
		$i++;
	}
echo '</table>
<input type=submit value="Сохранить" title="Сохранить признаки включения персональных данных">

</form>';
?>

<div class="text"><b>Примечание:</b> <br>
обозначения групп:
<?php
$query="select * from user_groups order by id ";
$res_=mysql_query($query);

while ($tmpval_=mysql_fetch_array($res_)) {	echo '<font color="'.$tmpval_['color_mark'].'"><font size="+3">*</font>-<font>'.$tmpval_['comment'].'</font></font>, ' ;} 
?><br> 
<span style="background-color:#00ff00"> цветом </span> отмечаны задачи, измененные за последние 7 дней
</div>

<p>
<?php
//постраничный вывод списка обновлений (по 10 обновлений)
/*if (isset($_GET['filtr']) & $_GET['filtr']!=0) {$query='select id from develop_news where news_type="'.$_GET['filtr'].'"';}
else {$query='select id from develop_news';}
//echo $query;*/
//$res=mysql_query($query);
//--------------------------------------------------------
echo '<div align="left"> Всего задач: '.mysql_num_rows($res).'</div>'; 	
	}

?>

<p><a href="p_administration.php">К списку задач.</a><p>

<?php include('footer.php'); ?>