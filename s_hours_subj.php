<?php
include ('authorisation.php');
?>
<html>
<head>
<title>Приказы по преподавателям</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; text-align:center;}
tr.main {font-size:11px; font-family:Arial; background-color:#DFEFFF; font-weight:normal;text-align:left;}
.err{color:red;font-family:Arial;font-weight:bold;}
</style>

<body>

<?php
//---------------------для работы Кук--------------------------
//include "sql_connect_empty.php";
/*$sql_host='localhost';
$sql_base='asu';

$sql_login='asu_users';
$sql_passw='dfglkJ873sd';

    if(!mysql_connect($sql_host,$sql_login,$sql_passw))
     {
      echo 'Не могу соединиться с сервером Базы Данных';     exit();     }
    if(!mysql_select_db($sql_base))
     {      echo 'Не могу выбрать базу';      exit();     }
mysql_query("SET NAMES cp1251");*/
//---------------------для работы Кук-------------------------------------

//запоминаем номера предметов в Куку для вывода в таб. и хранения в базе
if (isset($_POST['subject_list'])& $_POST['subject_list']!=0 )// 
{	$subject=$_POST['subject_list'];
	//echo 'subject_list='.$subject;
	if (isset($_COOKIE['subject_list']) & $subject!='')
		{$subject_list=$_COOKIE['subject_list'].';'.$subject;}
	else {$subject_list=$subject;}

$subj_arr=explode(';',$subject_list);
$subj_arr=array_unique($subj_arr);
$subject_list=implode(';',$subj_arr);
	setcookie('subject_list',$subject_list);$subject='';
}


//запоминаем названия предметов в куку
if (isset($_POST['subj_name'])& $_POST['subj_name']!='' )// 
{	$subject=$_POST['subj_name'];
	//echo 'subj_name='.$subject;
	if (isset($_COOKIE['subj_name']) & $subject!='')
		{$subj_name=$_COOKIE['subj_name'].';'.$subject;}
	else {$subj_name=$subject;}

$subj_arr=explode(';',$subj_name);
$subj_arr=array_unique($subj_arr);
$subj_name=implode(';',$subj_arr);
	setcookie('subj_name',$subj_name);$subject='';

	header('Location:s_hours_subj.php?kadri_id='.$_GET['kadri_id']);
} 

//чистка списка предметов
if (isset($_GET['clear'])& $_GET['clear']==1 )// 
{
	setcookie('subject_list','');setcookie('subj_name','');
	$query='update hours_year set subjects="" where kadri_id="'.$_GET['kadri_id'].'"';
	//$res=mysql_query($query);
	header('Location:s_hours_subj.php?kadri_id='.$_GET['kadri_id']);
}


//при первом открытии страницы 
if (!isset($_POST['subject_list']) & $_COOKIE['kadri_id']!=$_GET['kadri_id'])
{
	$query='select subjects from hours_year where kadri_id="'.$_GET['kadri_id'].'" limit 0,1';
	//echo $query;
	$res=mysql_query($query);
	$a=mysql_fetch_array($res);
	setcookie('subject_list',$a['subjects']);
	
	$subj_arr_val=explode(';',$a['subjects']);
	$subj_name='';
	while (list ($key, $val) = each ($subj_arr_val)) 
	{
		$query='select id,name from subjects where id="'.$val.'" limit 0,1';
		$res=mysql_query($query);
		$a=mysql_fetch_array($res);
		if ($subj_name=='') {$subj_name=$a['name'];} else {$subj_name=$a['name'].';'.$subj_name;}
	}
	setcookie('subj_name',$subj_name);
	
	setcookie('kadri_id',$_GET['kadri_id']);
	header('Location:s_hours_subj.php?kadri_id='.$_GET['kadri_id']);
	//echo 'a_subjects="'.$a['subjects'].'"!!!';
} 




//echo '!_COOKIE_list='.$_COOKIE['subject_list'].'!';
//echo '!_COOKIE_name='.$_COOKIE['subj_name'].'!';
//-------------------------------------------------------------


//include ('menu.htm');
//include ('sql_connect.php');

if (!isset($_GET['kadri_id']) or $_GET['kadri_id']=="")
{echo $_GET['kadri_id']."Не найден преподаватель. <a href='javascript:history.back()'>Вернуться...</a>";exit;}

$fio="";            //

$fio_res=mysql_query("select fio from kadri where id=".$_GET['kadri_id']." limit 0,1");
//echo "select fio from kadri where id=".$_GET['kadri_id'];
$a=mysql_fetch_array($fio_res);
$fio=$a['fio'];

echo '<h4>Список закрепленных дисциплин в почасовке</h4>';
echo 'преподаватель:'.$fio.'<p>';

if (isset($_GET['save']) & $_GET['save']==1)
{
	$query='update hours_year set subjects="'.$_COOKIE['subject_list'].'" where kadri_id="'.$_GET['kadri_id'].'"';
	//echo $query;
	$res=mysql_query($query);
	//echo 'mysql_affected_rows='.mysql_affected_rows();
	if ($res & mysql_affected_rows()>0) {echo '<h4>Данные сохранены</h4>';}
	else {echo '<div class="err"> данные не сохранены! </div>';}
	
} 

//---------------------------------------------------------------------------
echo '	<form name="subj_form" method="post" action="s_hours_subj.php?kadri_id='.$_GET['kadri_id'].'"><p>&nbsp;</p> 
		Дисциплина для добавления.
		<input type=hidden name="subj_name" size=100><br>
		<select name="subject_list" style="width:500;" onchange="document.subj_form.subj_name.value=this.options[this.value].text;"> 
		<option value="0">...выберите дисциплину ...</option>';
		$query='select id,name from subjects';
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if (isset($tmpval)) { if ($tmpval['subject_id']==$a['id']) {$select_val=' selected';} } 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
			}
echo '	</select>&nbsp;&nbsp;&nbsp;';
echo '<input type=submit value=Добавить></form>';

$subj_arr_val=explode(';',$_COOKIE['subject_list']);
$subj_arr_name=explode(';',$_COOKIE['subj_name']);

echo '<p>&nbsp;</p> <table name=tab1 border=1 cellpadding="0" cellspacing="0" width="500"><tr align="center" class="title" height=30>';
echo '<td width="50">№ п\п</td>';
echo '<td width="400">дисциплина</td>';
echo '</tr><tr>';

while (list ($key, $val) = each ($subj_arr_name)) {
		list ($key1, $val1) = each ($subj_arr_val);//
		echo '<tr align="left" class="main" height=20>';
		echo '<td>&nbsp;'.($key+1).'</td>';
		echo '<td>&nbsp;'.$val.'</td>';

}
	echo '</tr></table><br>';
	echo '<input type=button value=Сохранить onclick=document.location.href="s_hours_subj.php?kadri_id='.$_GET['kadri_id'].'&save=1">&nbsp;&nbsp;&nbsp;';
	echo '<input type=button value=Очистить onclick=document.location.href="s_hours_subj.php?kadri_id='.$_GET['kadri_id'].'&clear=1"><p>&nbsp;</p> ';
?>

<p><a href="s_hours.php?kadri_id=<?php echo $_GET['kadri_id']; ?>&tab=1">Вернуться...</a><p>

<?php include('footer.php'); ?>