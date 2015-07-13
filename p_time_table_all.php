<?php
if (isset($_POST['part_list']) ) { //фильтр по указанному периоду
	setcookie('part_list',$_POST['part_list']); setcookie('year_list',$_POST['year_list']);
	header('Location:p_time_table_all.php');
	}

if (isset($_GET['save']) && $_GET['save']==1)
{
      header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0');
      header('Pragma: no-cache');
      header('Content-Type: application/x-msexcel; charset=windows-1251; format=attachment;');
      header('Content-Disposition: attachment; filename=raspisanie.xls');
}

include ('sql_connect.php');

$head_title='Расписание занятий общее.'.$comp_title;
$files_path='';

include "header.php";

echo $head1;

if ($hide_person_data_rule) die($hide_person_data_task);

?>
<script language=JavaScript>

function hide_filter(name_form)
{
if (name_form=='filter_form') {
	if (document.getElementById('filter_form').style.display=='')
		{document.getElementById('filter_form').style.display='none';}
	else {document.getElementById('filter_form').style.display='';}	 
	}
if (name_form=='time_form') {
	if (document.getElementById('time_form').style.display=='')
		{document.getElementById('time_form').style.display='none';}
	else {document.getElementById('time_form').style.display='';}	 
	}
}
function mark_all(cnt,mark_val)
{
	try {mark_val=document.lect_select.elements['mark_all_'].checked;}
	catch (e) {mark_val=true;}
	
	 for (i=1;i<=cnt;i++) {  
	  		try {document.lect_select.elements['checkbox'+i].checked=mark_val;}
	  		catch (e) {document.getElementById['checkbox'+i].checked=mark_val;}
	  }  
} 
</script>
<?php

$time=array();
$time[1]='  8.00- 9.35 ';
$time[2]='  9.45-11.20 ';
$time[3]=' 12.10-13.45 ';
$time[4]=' 13.55-15.30 ';
$time[5]=' 16.10-17.45 ';
$time[6]=' 17.55-19.30 ';
$time[7]=' Вечерники ';

    $exist_days=array('Понедельник','Вторник','Среда','Четверг','Пятница','Суббота');


if (isset($_COOKIE['part_list'])) //используем фильтр
{
	$def_settings['part_id']=$_COOKIE['part_list'];$def_settings['year_id']=$_COOKIE['year_list'];
	$query_all='select id,name from  time_intervals where id="'.$def_settings['year_id'].'"';
	$res_all=mysql_query($query_all);$a=mysql_fetch_array($res_all);$def_settings['year_name']=$a['name'];
	
	$query_all='select id,name from  time_parts where id="'.$def_settings['part_id'].'"';
	$res_all=mysql_query($query_all);$a=mysql_fetch_array($res_all);$def_settings['part_name']=$a['name'];
}
else 
{
	//выбираем найстройки по умолчанию (год, семестр)
    /**
     * Переписано для использования новой системы глобальных настроек
     */
    $def_settings["year_name"] = CUtils::getCurrentYear()->name;
    $def_settings["date_start"] = CUtils::getCurrentYear()->date_start;
    $def_settings["date_end"] = CUtils::getCurrentYear()->date_end;
    $def_settings["year_id"] = CUtils::getCurrentYear()->getId();
    $def_settings["part_id"] = CUtils::getCurrentYearPart()->getId();
    $def_settings["part_name"] = CUtils::getCurrentYearPart()->name;
}
$query='select distinct time.id as time_id,users.FIO,users.id as user_id from time inner join users on users.id=time.id 
	where year="'.$def_settings['year_id'].'" and month="'.$def_settings['part_id'].'" order by FIO';
$res=mysql_query($query);
$res1=mysql_query($query);
$cols=mysql_num_rows($res);
		$new_cols=0;
		for ($numLects=1;$numLects<=$cols;$numLects++)	
			{
			$tmp_var1='checkbox'.$numLects;
			if (isset($_POST[$tmp_var1]) && $_POST[$tmp_var1]=='on') {$new_cols++;		}
			}
//выбор преподавателей для формирования расписания
if (!isset($_GET['save']))
{
echo '<LINK href="css/styles.css" rel="stylesheet" type="text/css"><a name="top"></a>
	<a href="p_time_table.php?onget=1&getallsub=1"> Вернуться </a><p> 
	<a href="?save=1" title="При выгрузке фильтр по преподавателям не учитывается"> Выгрузить в Excel </a><br>
	<a href=javascript:hide_filter("filter_form"); title="фильтр по указанным преподавателям">Выбрать преподавателей</a><br>';
	echo '<div id="filter_form" name="filter_form" style="display:none">';
	echo '<form name="lect_select" action="" method="post">Вывести по выбранным преподавателям';
	echo '<table border=1 cellspacing=0><tr><td width=10></td>';
	$i=0;
	while ($c=mysql_fetch_array($res1))
		{$i++;
		 echo '<td width=150><input type=checkbox name=checkbox'.$i.' checked>'.$c['FIO'].
		 				'<input name="lect_id'.$i.'" type=hidden value="'.$i.'"></td>';
		}
	echo '</tr></table>';
	echo '<input type="checkbox" name="filter" checked>только выделенные<p>';
	echo '<input type="checkbox" name="mark_all_" checked onClick="javascript:mark_all('.$i.',this.value);">все<p>';
	echo '<br><input type="submit" value="OK">&nbsp;&nbsp;<input type="reset" value="Вернуть">';
	echo '</form>';
	echo '</div>';
}
$filter='';

if (isset($_POST['filter'])) {$filter=$_POST['filter'];} //выборка по отмеченным
	
//вывод отмеченный преподавателей на экран
echo '<div align="left" style="font-size:14pt; font-weight:bold;">Расписание занятий  <font size=-1> на <u>'.$def_settings['part_name'].'</u> семестр &nbsp;<u>'.$def_settings['year_name'].'</u> учебного года</font></div>';
if (!isset($_GET['save']))
{
	echo '<a href=javascript:hide_filter("time_form") class=text>выбрать другой период</a> &nbsp;&nbsp; <a href=#time class=text>к времени занятий</a>
		<div name=time_form id=time_form style="display:none;">';
		echo '<form name time_select action="" method="post">';
		echo '<select name="year_list" style="width:200;"> 
				<option value="0">...выберите год ...</option>';
				$query='select id,name from time_intervals';
				$res2=mysql_query($query);
				while ($a=mysql_fetch_array($res2)) 	{
				 	$select_val='';
					  	if (isset($def_settings)) { if ($def_settings['year_id']==$a['id']) {$select_val=' selected';}}	
					echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
					}
		echo'	</select> учебный год<p> ';
		echo '<select name="part_list" style="width:200;"> 
				<option value="0">...выберите семестр ...</option>';
				$query='select id,name from time_parts';
				$res2=mysql_query($query);
				while ($a=mysql_fetch_array($res2)) 	{
				 	$select_val='';
					  	if (isset($def_settings)) { if ($def_settings['part_id']==$a['id']) {$select_val=' selected';}}	
					echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
					}
		echo '	</select> учебный семестр';	
	echo '<br><input type="submit" value="OK">&nbsp;&nbsp;<input type="reset" value="Вернуть">';
	echo '</form>';
	echo '</div>';
}
if ($cols<=0) {echo '<br>Преподавателей с расписанием за этот период не найдено.';exit();}
echo '<br>Всего преподавателей с расписанием: '.$cols.'<p>';

if ($new_cols==0) {$new_cols=$cols;}
$tab_width='width="'.($new_cols*150+100).'"';
$cols_i=floor($new_cols/6);
$prev_cell_lab_array=array();
$prev_cell_lab_tmp='';
echo "\n\n<table name=time_table_all border=1 cellspacing=0 class=middle_lite1 ".$tab_width.">\n<tr class=middle>\n<td>\\</td>";
for ($day=1;$day<=6;$day++)
{
			$numLects=1;
 			while ($a=mysql_fetch_array($res))
 			{	
				$tmp_var1='checkbox'.$numLects;
				//формирование шапки с названием преподавателей
				if (isset($_POST[$tmp_var1]) && $_POST[$tmp_var1]=='on' || $filter!='on') 
					{echo '<td width=150><b>';
					if (!isset($_GET['save'])) {
					 	echo '<a href="_modules/_lecturers/index.php?action=view&id='.$a['user_id'].'" title="о преподавателе"> '.$a['FIO'].'</a>';}
					else {echo $a['FIO'];}
					echo '</b></td>';
					$lects[$numLects]=$a['time_id'];
					}
				$numLects++;
			}
			echo "</tr>\n"; 
			echo "\n<tr class=light_color_max>\n";
			for ($i=1;$i<=$cols_i;$i++){echo "<td colspan=6 ><b>".$exist_days[$day-1]."</b></td>";}
			echo "<td colspan=".($cols+1-$cols_i*6)." ><b>".$exist_days[$day-1]."</b></td></tr>\n";
	 for ($num=1;$num<=count($time);$num++)
 		{	
		echo "\n<tr class=news>\n<td  align=left width=120> &nbsp;<b> ".$num."</b> &nbsp;<font size=1>".$time[$num]."</font> </td>";
		for ($numLects=1;$numLects<=$cols;$numLects++)	
			{
			$tmp_var1='checkbox'.$numLects;
			if (isset($_POST[$tmp_var1]) && $_POST[$tmp_var1]=='on' || $filter!='on') 
				{
				$query_cell='select time.length,time.place,time_kind.name as kind,subjects.name as study ,study_groups.name as grup,
				time.study as study_id from time 
					left join subjects on subjects.id=time.study
					left join study_groups on study_groups.id=time.grup			
					left join time_kind	on  time_kind.id=time.kind 	
					where time.id="'.$lects[$numLects].'" and time.number="'.$num.'" and time.day="'.$day.'"
						 and year="'.$def_settings['year_id'].'" and month="'.$def_settings['part_id'].'"';
				$res=mysql_query($query_cell);
				$i=1;$num_rows=mysql_num_rows($res);

				if (isset($prev_cell_lab_array[$numLects]) && $prev_cell_lab_array[$numLects]!='' && $num_rows>=1) 
					{$prev_cell_lab_array[$numLects]=$prev_cell_lab_array[$numLects].'<hr>';}
				echo '<td valign=top width="150">&nbsp;';
				if (isset($prev_cell_lab_array[$numLects])) {echo $prev_cell_lab_array[$numLects];}	//с дублированием л/р
					
				while ($b=mysql_fetch_array($res) )
				{//-------------------------------------------------------------------------------
				if (!isset($_GET['save'])) {
					//-----ссылка на страницы предмета (скачка пособий, если есть)
					$query_subj='SELECT documents.nameFolder,count(files.id) as cnt_files 
					FROM `documents` left join files on files.nameFolder=documents.nameFolder 
					WHERE documents.subj_id = '.$b['study_id'].' AND documents.user_id = '.$lects[$numLects].' group by documents.nameFolder';
					
					//echo $query_subj;
					$res_subj=mysql_query($query_subj);
					$num_rows_subj=mysql_num_rows($res_subj);
					//$link_style='';$link='#';
					if ($num_rows_subj>0) {	
					 $b_subj=mysql_fetch_array($res_subj);
					 $subj=$b_subj['nameFolder'];
					 $b['study']='<a href="p_library.php?onget=1&getdir='.$b_subj['nameFolder'].'" title="пособий по предмету: '.$b_subj['cnt_files'].'">'.$b['study'].'</a>';//$link_style='';
					 }
					else {
					 //$link='#';
					 //$link_style='style="color:#666666;"';
					 }
					//-----------------					
					
					if (isset($b['length']))	{$b['length']=	$b['length']." нед.<br>";}	//номера недель
					if (isset($b['place']))		{$b['place']=	', ауд.'.$b['place'].'';}	//кабинет
					if (isset($b['kind']))		{$b['kind']=	' ('.$b['kind'].')';}	//тип занятия (пр,лаб,л)
					if (isset($b['study']))		{$b['study']=	',<br>'.$b['study'];}	//название предмета 
						echo $b['length'].$b['grup'].$b['place'].$b['study'].$b['kind'].'';
						if ($i<$num_rows) {echo '<hr>';}
						$i++;	
					if(isset($b['kind']) && $b['kind']==' (л/р)')
						{
						 if ($prev_cell_lab_tmp!='') {$prev_cell_lab_tmp=$prev_cell_lab_tmp.'<hr>';}
					 $prev_cell_lab_tmp=$prev_cell_lab_tmp.$b['length'].$b['grup'].$b['place'].$b['study'].$b['kind'].'';
						
						 }
					}
				else {// при выгрузке в Excel для минимизации ячеек
					if (isset($b['length']))	{$b['length']=	$b['length']." нед.,";}	//номера недель
					if (isset($b['place']))		{$b['place']=	', ауд.'.$b['place'].'';}	//кабинет
					if (isset($b['kind']))		{$b['kind']=	' ('.$b['kind'].')';}	//тип занятия (пр,лаб,л)
					if (isset($b['study']))		{$b['study']=	', '.$b['study'];}	//название предмета 
						echo $b['length'].$b['grup'].$b['place'].$b['study'].$b['kind'].'';
						if ($i<$num_rows) {echo '<br>';}
						$i++;	
					if(isset($b['kind']) && $b['kind']==' (л/р)')
						{
						 if ($prev_cell_lab_tmp!='') {$prev_cell_lab_tmp=$prev_cell_lab_tmp.'<br>';}
					 $prev_cell_lab_tmp=$prev_cell_lab_tmp.$b['length'].$b['grup'].$b['place'].$b['study'].$b['kind'].'';
						
						 }
					}
				}//-------------------------------------------------------------------------------
					$prev_cell_lab_array[$numLects]=$prev_cell_lab_tmp;
					$prev_cell_lab_tmp='';//храним л.р. по препод-м для дублирования
				echo '</td>';
				}
			}
			echo "</tr>\n";	//можно закомментировать !!!!!!!!!!!!!!!
		}
} 
echo "</table><br>\n\n\n";
echo '<a name="time"></a><div clss=text> Расписание звонков по времени </div>
	  <table class="news" border=1 style="text-align:center" cellspacing=0>
	  	<tr class=news style="font-weight:bold;text-align:center"><td>№ </td> <td width=200>время</td></tr>
		<tr><td>1 </td> <td>8.00-9.35</td></tr>
		<tr><td>2 </td> <td>9.45-11.20</td></tr>
		<tr><td colspan=2> <b>перерыв 40 мин</b> </td></tr>
		<tr><td>3 </td> <td>12.10-13.45</td></tr>
		<tr><td>4 </td> <td>13.55-15.30</td></tr>
		<tr><td colspan=2><b>перерыв 40 мин.</b></td></tr>
		<tr><td>5 </td> <td>16.10-17.45</td></tr>
		<tr><td>6 </td> <td>17.55-19.30</td></tr>
		<tr><td colspan=2>в середине занятия перерыв 5 мин.</td></tr>  
	  </table><p>';
if (!isset($_GET['save']))
{
echo '	<br> <a href="#top"> наверх </a>
		<br> <a href="p_time_table.php?onget=1&getallsub=1"> Вернуться </a>
		<br> <a href="?save=1" title="При выгрузке фильтр по преподавателям не учитывается"> Выгрузить в Excel </a><br> ';
}  
?>