<?php
	$pg_title="Журнал успеваемости студентов";
	include "sql_connect.php";
	include "header.php";
	if (!isset($_GET["wap"])) {	echo $head;}
	else { echo $head_wap;}

?>
<script language="JavaScript">
var main_page='<?php echo $main_page;?>';	//for redirect & links
function pageVals(query_str)
{
 	var pageCnt= parseInt(document.getElementById('pageVals').value);
 	if (pageCnt>0 && pageCnt<100) {document.location.href=main_page+'?'+query_str+'&pageVals='+pageCnt;}
 	else {alert('Введите значение с 1 до 99.');}
 	
	 //if (pageCnt>0 && pageCnt<100) {alert('ok');}
	 
	//alert(pageCnt);
} 
</script>
<?php

/**
 * $query_all='SELECT time_intervals.name as year_name, time_intervals.date_start,time_intervals.date_end
 *	FROM settings inner join time_intervals on time_intervals.id=settings.year_id
 *	where 1 limit 0,1';
 * if ( $res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {$def_settings=mysql_fetch_array($res_all);}
 *
 * Переписано для использования новой системы глобальных настроек
 */
$query_all = "
    select
        time_intervals.name as year_name,
        time_intervals.date_start,
        time_intervals.date_end
    from
        time_intervals
    where
        time_intervals.id = ".CUtils::getCurrentYear()->getId();
if ($res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
    $def_settings=mysql_fetch_array($res_all);
} else {
 	$def_settings['year_id']='1';$def_settings['year_name']='2006-2007';
}

echo '<h4 class=main>'.$pg_title.' за текущий ('.$def_settings['year_name'].') учебный год ';
if ($hide_person_data_rule) die($hide_person_data_task);
//-----------------------отражаем ссылку добавления сведений успеваемости----------
if (isset($_SESSION) && $_SESSION['auth']==1) {
		$pg2control='study_activity.php';
		$query="SELECT count(*)as num_rows ,tasks.url,tasks.name as pg_name
            FROM task_in_group inner join tasks on tasks.id=task_in_group.task_id
			WHERE task_in_group.user_group_id in (
			  SELECT group_id
				FROM user_in_group
				WHERE user_id ='".$_SESSION['id']."') and tasks.url like '".$pg2control."%' GROUP BY 2 limit 0,1";
		//echo '<div>'.$query.'</div>';
		//echo 'getScalarVal='.getScalarVal($query);
		$pgAdmin=false;
        if (getScalarVal($query)==1) {
            $pgAdmin=true;
            echo '<br><a class=success href="'.$pg2control.'">Добавить\изменить данные в журнал</a>';};
}
//--------------------------------------------------------------------------------
echo '</h4>';
echo '<div>Количество записей в журнале за текущий год: '.
    getScalarVal('SELECT count(*) as cnt FROM `study_activity` WHERE `date_act`>="'.$def_settings['date_start'].'"').', в архиве: '.
    getScalarVal('SELECT count(*) as cnt FROM `study_activity` WHERE `date_act`<"'.$def_settings['date_start'].'"');
$q='';
$student_id=0;
$pageVals=20;	//число тем на странице по умолчанию
$page=1;
$query_string=$_SERVER['QUERY_STRING'];
$archiv='';

$allowArchiv=true;	//разрешать поиск в архиве

if (isset($_GET['q'])) {$q=f_ri($_GET['q']);}  
if (isset($_GET['student_id'])) {$student_id=intval($_GET['student_id']);}  
if (isset($_GET['pageVals']) && $_GET['pageVals']<=99 && $_GET['pageVals']>=1)
    {$pageVals=$_GET['pageVals'];$filt_str_display=$filt_str_display.' числу записей;';}
if (isset($_GET['page']) && $_GET['page']>1)
    {$page=$_GET['page'];$filt_str_display=$filt_str_display.'  странице;';}

if (isset($_GET['archiv']) && $allowArchiv)
    {$archiv=$_GET['archiv'];$filt_str_display=$filt_str_display.'  архиву;';}

	?>
<div align=center style="padding:20px; width:70%;">
Введите Фамилию студента для поиска, &nbsp;  &nbsp;  &nbsp; 
<span style='font-size:10pt; color:#999999;'>например, <a href="?q=петров">петров</a> </span><br><br>
<form id="stud_search" name="stud_search" action="" method="get">
<input type=text name="q" id="q" style="font-size:14pt;" value='<?php echo $q;?>'> &nbsp;
<?php if (isset($_GET['wap'])) { ?> <input type=hidden name="wap" id="wap"> <?php } ?>
<input type=submit value=найти style="font-size:14pt;"> <br>
<label><input type=checkbox id=archiv name=archiv <?php if ($archiv=='on') echo ' checked'; if (!$allowArchiv) echo ' disabled title="архив отключен администратором системы"'; ?>  > искать в архиве </label>
</form>
</div>

<?php

if ($q!='') {
 
echo '<div>Результаты поиска по: <span style="font-weight:bold;font-size:14pt;">'.$q.'</span></div>';

$query='SELECT s.fio, s.id, sg.name AS gr_name, count(*) as rec_cnt
  FROM    study_groups sg
       INNER JOIN students s ON (sg.id = s.group_id)
       INNER JOIN study_activity sa ON (s.id = sa.student_id)
 WHERE '.echoIf($archiv=='on','sa.date_act<"'.$def_settings['date_start'].'"','(sa.date_act>="'.$def_settings['date_start'].'" or sa.date_act is null)').' 
 and s.fio like "%'.strtolower($q).'%"
 group by s.fio, s.id, sg.name
 order by s.fio limit 0,50';
$res=mysql_query($query);
$rec_cnt=mysql_num_rows($res);
if ($rec_cnt>0) {
	echo '<div>найдено записей: <b>'.$rec_cnt.'</b>'.echoIf($archiv=='on',' <span class=warning>(поиск в архиве)</span>','').'</div>'; 
	$i=1;
	while ($a=mysql_fetch_array($res))
	{
	 	echo $i.'. <a href="?student_id='.$a['id'].echoIf($archiv=='on','&archiv=on','').'">'.color_mark($q,$a['fio']).
            ' ('.$a['gr_name'].')</a> записей: '.$a['rec_cnt'].' <p>';
	 	$i++;
	 }
}
else {echo '<div>ничего не найдено</div>';}
}
//--------------------------------------------------
if ($student_id>0)
{
	$stud_name=getScalarVal('select concat(s.fio," (",sg.name,")") as fio from students s LEFT OUTER JOIN
              study_groups sg
           ON (sg.id = s.group_id) where s.id='.$student_id);
    if ($stud_name=='') echo '<div class=warning>Указанный студент не найден';
    else {
	echo '<div>Результаты поиска по студенту: <span style="font-weight:bold;font-size:14pt;">'.
	$stud_name.'</span></div>';	

	//echo $query;
	if (!isset($_GET['old_style'])) {
		echo '<span><a href="?old_style=1&'.reset_param_name($query_string,'old_style').'">старый отчет</a></span>';
        require '_rep_gen.php';	//генератор отчета
		$inGrFilendNum=array(1,2,3);
	$query='SELECT DATE_FORMAT(sa.date_act, \'%d.%m.%Y\') as "дата",
       subjects.name_short as "дисциплина",
       kadri.fio_short as "преподаватель",
       study_act.name_short as "вид контроля", sa.study_act_comment as "номер контроля",
       sm.name_short as "оценка"';
   //показываем пользователям, кому разрешены правки в Успеваемости показывать Примечание в таблице
   if ($pgAdmin) $query.=', sa.comment as "примечание"';
  $query.='FROM       study_activity sa
                               LEFT OUTER JOIN
                                  subjects subjects
                               ON (subjects.id = sa.subject_id)                           
						LEFT OUTER JOIN
                              kadri kadri
                           ON (kadri.id = sa.kadri_id)
                   LEFT OUTER JOIN
                      study_marks sm
                   ON (sm.id = sa.study_mark)               
       LEFT OUTER JOIN
          study_act study_act
       ON (study_act.id = sa.study_act_id)  
	   where sa.student_id='.$student_id.' and '.echoIf($archiv=='on','sa.date_act<"'.$def_settings['date_start'].'"','(sa.date_act>="'.$def_settings['date_start'].'" or sa.date_act is null)').'';
	   
	   	//echo $query;
		//$sort=1;
		//$stype='desc';		//тип сортировки столбца

		report_build($inGrFilendNum,$query);	//основная функция построения отчета	   
		
	}
	else {	//вывод запроса в старом стиле
		$sort=1;
		$stype='desc';
		if (isset($_GET['sort'])) {$sort=intval($_GET['sort']);}
		if (isset($_GET['stype']) && ($_GET['stype']=='desc' || $_GET['stype']=='asc')) {$stype=$_GET['stype'];}
	
	echo '<span><a href="?'.reset_param_name($query_string,'old_style').'">новый отчет</a></span>';
	$query='SELECT sa.date_act,
       subjects.name_short AS subj_name,
       kadri.fio_short AS kadri_fio,
       study_act.name_short AS act_name,
       sa.study_act_comment,
       sm.name_short AS mark_name,
       sa.comment,
       sa.id,
       sa.study_act_id,
       sa.student_id,
       sa.kadri_id,
       sa.study_mark,
       sa.subject_id,
       users.id AS user_id,
       subjects.name AS subj_n_full,
       sm.color as mark_color,
(SELECT COUNT(f.id_file) 
					  FROM    (documents d 
					           INNER JOIN
					              files f
					           ON (d.nameFolder = f.nameFolder))					       
					where d.subj_id=subjects.id) as f_cnt 					       
  FROM       study_activity sa
                               LEFT OUTER JOIN
                                  subjects subjects
                               ON (subjects.id = sa.subject_id)                           
						LEFT OUTER JOIN
                              kadri kadri
                           ON (kadri.id = sa.kadri_id)
                       LEFT OUTER JOIN
                          users users
                       ON (kadri.id = users.kadri_id)
                   LEFT OUTER JOIN
                      study_marks sm
                   ON (sm.id = sa.study_mark)               
       LEFT OUTER JOIN
          study_act study_act
       ON (study_act.id = sa.study_act_id)  
	   where sa.student_id='.$student_id.' and '.echoIf($archiv=='on','sa.date_act<"'.$def_settings['date_start'].'"','(sa.date_act>="'.$def_settings['date_start'].'" or sa.date_act is null)').' ';	
	$query.=" order by ".$sort." ".$stype." ";	
	$res=mysql_query($query.'limit '.(($page-1)*$pageVals).','.$pageVals);

	$rec_cnt=mysql_num_rows($res);
	
	if ($rec_cnt>0) {		
		$i=1;
		$table_headers=array(
			1=>array('дата','40'),
			2=>array('дисциплина','100'),
			3=>array('преподаватель','100'),
			4=>array('вид контроля',''),
			5=>array('номер контроля',''),
			6=>array('оценка','50')
			);
		
echo '<table name=tab1 border=1 cellpadding="10" cellspacing="0" width="99%">
<tr align="center" class="title">';
//------------------------------------------- шапка списочной таблицы -начало-----------------------------------------------------
	echo '<td width="50">№</td>';

	for ($i=1;$i<=count($table_headers);$i++)
	{
		echo '<td width="'.$table_headers[$i][1].'">'.print_col($i,$table_headers[$i][0]).'</td>';
	}
//------------------------------------------- шапка списочной таблицы -конец-----------------------------------------------------
	$bgcolor='';	
	$i=1;

		while ($tmpval=mysql_fetch_array($res))	{		
		echo '<tr align="left" class="main" style="font-size:10pt;" '.$bgcolor.' valign="top">';		
		echo '<td>&nbsp;'.($i+($page-1)*$pageVals).'</td>';
		$date_act=$tmpval['date_act'];
		//$date_act=date("d.m.Y H:i:s",strtotime($tmpval['date_act']));
		$date_act=substr($date_act,0,10);
		$date_act=DateTimeCustomConvert($date_act,'d','mysql2rus');
		echo '<td>&nbsp;'.$date_act.'</td>';

		echo '<td>&nbsp;';
		if ($tmpval['f_cnt']>0)
		{
		 echo ' <a href="p_library.php?onget=1&getsubj='.$tmpval['subject_id'].'" title="'.$tmpval['subj_n_full'].
            ', пособия по предмету">'.$tmpval['subj_name'].' ('.$tmpval['f_cnt'].')</a> ';
		 }
		else {echo $tmpval['subj_name'];}
		echo '</td>';
		
		echo '<td>&nbsp;<a href="p_lecturers.php?onget=1&idlect='.$tmpval['user_id'].'" title="о преподавателе">'.$tmpval[2].'</td>';//		

		echo '<td>&nbsp;'.$tmpval['act_name'].'</td>';
		echo '<td>&nbsp;'.$tmpval['study_act_comment'].'</td>';
		echo '<td>&nbsp;<span style="color:'.$tmpval['mark_color'].';">'.$tmpval['mark_name'].'</span></td>';

		 	//echo $i.'. <a href="?student_id='.$a['id'].'">'.color_mark($q,$a['fio']).' ('.$a['gr_name'].')</a><p>';
		 	$i++;
		 }
echo '</table>';
//---
$itemCnt=getScalarVal('select count(*) from ('.$query.')t');
//echo 'select count(*) from ('.$query.')t';
if (floor($itemCnt/$pageVals)==$itemCnt/$pageVals) {$pages_cnt=floor($itemCnt/$pageVals);echo '222222';}
 else {$pages_cnt=floor($itemCnt/$pageVals)+1;}
echo '<div align="left"> страницы ';


$add_string=reset_param_name($query_string,'page');//"&pageVals=".$pageVals;

for ($i=1;$i<=$pages_cnt;$i++)
    {if ($i!=$page)
        {echo '<a href="?'.$add_string.'&page='.$i.'"> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}

    }
//--------------------------------------------------------

$add_string=reset_param_name($add_string,'pageVals');// preg_replace("/(&pageVals=)(\d+)/x","",$add_string);
////убрать число страниц через RegExp
echo '<br>макс.число записей на странице:
    <input type=text value="'.$pageVals.'" name="pageVals" id="pageVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pageVals(\''.$add_string.'\');" value=Ok>
	<p> Всего записей: '.mysql_num_rows($res).'</div>'; 
		
	}
	else {echo '<div>ничего не найдено</div>';}
	}
} 
} 
//------------------------------------------------------
	if (!isset($_GET["wap"])) {
	  echo $end1;
	  include "display_voting.php";
	  }
	echo $end2; include('footer.php'); 
	?>