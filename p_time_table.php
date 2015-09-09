<?php
$pg_title='Расписание занятий';

include "sql_connect.php";

$gr_mode=0;	// признак выбора учебной группы, а не преподавателя
$idlect=0;	// идентификатор группы\преподавателя
$idlect2=0;	// для поворота

$query_string=$_SERVER['QUERY_STRING'];
if (isset($_GET['gr_mode']) && intval($_GET['gr_mode'])>0 ) {$gr_mode=intval($_GET['gr_mode']);}
if (isset($_GET['idlect']) && intval($_GET['idlect'])>0) {$idlect=intval($_GET['idlect']);}
if (isset($_GET['idlect2']) && intval($_GET['idlect2'])>0) {$idlect2=intval($_GET['idlect2']);}

$fiolect='';
if (!$hide_person_data_rule && ($idlect>0 || $idlect2>0) ) {
  if (isset($_GET['gr_mode']) && $_GET['gr_mode']==1) $fiolect=getScalarVal('select name from study_groups where id="'.$idlect.'"');
  else $fiolect=getScalarVal('select FIO from users where id="'.($idlect>0?$idlect:$idlect2).'"');
  if ($fiolect!='') $head_title=$fiolect.'. '.$head_title;
}


if (!isset($_GET['onget']))
 {
  $wap='';
  if (isset($_GET['wap'])) {$wap='wap&';}
  header("Location:?".$wap."onget=1&getallsub=1");
 }

include "header.php";

if (!isset($_GET['save']) && !isset($_GET['print'])) {
   if (!isset($_GET['wap'])) {	echo $head;}
   else { echo $head_wap;}
}
else echo $head1;



 ?>
<style>
td.time1 {font-size:12px; font-family:Arial, Helvetica, sans-serif;text-align:center;vertical-align:top;}
td {font-size:12px; font-family:Arial, Helvetica, sans-serif;}
.names {font-family:Arial; font-size:10pt;}
</style>

<?php
//расписание для просмотра - не для администрации
$firstLet=array ("А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф",
"Х","Ц","Ч","Ш","Щ","Э","Ю","Я");

$lectors4page=20;



//echo ' idlect='.$_GET['idlect'].'<hr>';
if ($hide_person_data_rule) $gr_mode=1;//die($hide_person_data_task);

function letters()
 {global $firstLet,$hide_person_data_rule;
  
  echo '<div class="middle_library" style="font-size:18pt;">';
  if (!$hide_person_data_rule) {	//скрывать без авторизации
  $letterId=-1;
  if (isset($_GET['getsub']) && intval($_GET['getsub'])>=0) {$letterId=intval($_GET['getsub']);}
  
  for ($i=0;$i<count($firstLet);$i++)
  	{ if ($firstLet[$i]=="A") {echo '<br><BR>';}
	  if ($letterId==$i) {echo '<font size=+2>'.$firstLet[$i].'</font>&nbsp;';}
	  else echo '<a href="?onget=1&getsub='.$i.'">'.$firstLet[$i].'</a>&nbsp;'; 	     }
  }
  echo '</div><p class=main><a href="?onget=1&getallsub=1">все</a> &nbsp;  <a href="p_time_table_all.php">общее расписание</a></p>';
  
 }



$time=array();
$time[1]='  8.00- 9.35 ';
$time[2]='  9.45-11.20 ';
$time[3]=' 12.10-13.45 ';
$time[4]=' 13.55-15.30 ';
$time[5]=' 16.10-17.45 ';
$time[6]=' 17.55-19.30 ';
$time[7]=' Вечерники ';
$exist_days=array('1','Пн','Вт','Ср','Чт','Пт','Сб');

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

$sess=$def_settings['part_name'];

  if (isset($_GET['getsub']) )
   {

    echo '<div class="main">Расписание';
if (isset($_SESSION['auth']) && $_SESSION['userType']=='преподаватель') {echo '<a href="lect_time_table.php" class=text title="'.$_SESSION['FIO'].'"> добавить  расписание</a>';}
echo '</div><div class="middle2">'.$sess.' &nbsp;'.$def_settings['year_name'].' года</div><br>';
    letters();
    $letter=$firstLet[$_GET['getsub']];
    $res0=mysql_query ('select * from users where status!="администратор" order by FIO');
    $b2=0;
    if (!$hide_person_data_rule)
    while($a=mysql_fetch_array($res0))
     {
      $b=substr($a['FIO'],0,1);
      if ($b==$letter)
       {
        $res0aaa=mysql_query ('select * from time where id="'.$a['id'].'" and  
	time.year="'.$def_settings['year_id'].'" and time.month="'.$def_settings['part_id'].'"');
		$p111c=mysql_num_rows($res0aaa);
        $b2++;
        echo '<p class="lecturers"><a href="?onget=1&idlect='.$a['id'].'"';
        if ($p111c>=1)
         {
          echo '>'.$a['FIO'].'(+)</a>';
         }
        else
         {
          echo ' style="color:#8D8D8D;">'.$a['FIO'].'(-)</a>';
         }
       }
     }
    if ($b2==0)
     {
      echo '<p class="lecturers">В данном разделе преподавателей нет.';
     }
    echo '<p class="pages" valign="bottom">(+) расписание есть (-) расписания нет';
    //mysql_close();
		if (!isset($_GET['wap'])) {
		  echo $end1;
		  //include "display_voting.php";
		  }
       define("CORRECT_FOOTER", true);
		echo $end2; include('footer.php'); 
   }
  if (isset($_GET['getallsub']))
   {
    if(!isset($_GET['number']))
     {
      $number=1; $start=0;
     }
    else
     {
      $number=$_GET['number'];$start=($number-1)*$lectors4page;
     }

    echo '<div class="main">Расписание <span class=warning>';    
	if ($gr_mode==1) { echo ' <a href="?'.reset_param_name($query_string,'gr_mode').'" class=success>по преподавателям</a> &nbsp; <b>по группе</b>';}
    else { echo ' <b>по преподавателям</b> <a href="?'.reset_param_name($query_string,'gr_mode').'&gr_mode=1" class=success>по группе</a>';}
    echo '</span>';
    
if (isset($_SESSION['auth']) && $_SESSION['userType']=='преподаватель') {echo '<a href="lect_time_table.php" class=text title="'.$_SESSION['FIO'].'"> добавить  расписание</a>';}
echo '</div><div class="middle2">'.$sess.' &nbsp;'.$def_settings['year_name'].' года</div><br>';
    letters();

    if ($gr_mode!=1)
		$res05=mysql_query ('select count(if(status!="администратор",1,NULL)) from users');
    else $res05=mysql_query ('select count(*) from study_groups where year_id='.$def_settings['year_id']);
    
	$count=mysql_result($res05,0,0);
    $pages=$count/$lectors4page;
    $pages_and=$pages;
    $pages_and=intval($pages_and);
    if ($pages_and==$pages)
     {
      $pages=$pages_and;
     }
    else
     {
      $pages=$pages_and+1;
     }
    if(($number>$pages) || ($number<1))
     {
      $number=$pages;$start=($number-1)*$lectors4page;
     }
    $finish=$start+$lectors4page;
    if($finish>$count)
     {
      $length=$count-$start;
     }
    else
     {
      $length=$lectors4page;
     }
    //общая выборка преподавателей без группировки по Букве фамилии
    
	if ($gr_mode==1) $query_list='select id,name as FIO from study_groups where year_id="'.$def_settings['year_id'].'" order by 2 asc limit '.$start.','.$length;
    else $query_list='select * from users where status!="администратор" order by FIO asc limit '.$start.','.$length;
    $res06=mysql_query ($query_list);
    //echo $query;
	echo '<div align=center>';
	//$href='?'.reset_param_name($query_string,'number').'&number=';
	echo getPagenumList($pages,$number,3,reset_param_name($query_string,'number').'&number','','');
	echo '</div>';   
	
    while($p=mysql_fetch_array($res06))
     {
      //print_r($p);
	  if ($gr_mode!=1) $query='select * from time where id="'.$p['id'].'" 
	  	and time.year="'.$def_settings['year_id'].'" and time.month="'.$def_settings['part_id'].'"';
	  else  $query='select * from time where grup="'.$p['id'].'" 
	  	and time.year="'.$def_settings['year_id'].'" and time.month="'.$def_settings['part_id'].'"';
	  	
	 $res06aaa=mysql_query($query);
      
	  
	  $p111=mysql_num_rows($res06aaa);
      echo '<p class="lecturers">
      <a href="?onget=1&idlect='.$p['id'].'';
      
	  if ($gr_mode==1) { echo '&gr_mode=1';}
      
      if ($p111>=1)
       {
        echo '">'.$p['FIO'].'(+)</a>';
       }
      else
       {
        echo '" style="color:#8D8D8D;">'.$p['FIO'].'(-)</a>';
       }
     }
    //mysql_close();
    echo '<p class="text" valign="bottom">(+) расписание есть (-) расписания нет';
    echo '<p align=center>';
    
	//$href='?gr_mode='.$gr_mode.'&onget=1&getallsub=1&number=';
	//$href='?'.reset_param_name($query_string,'number').'&number=';
	//printPGnums($pages,$number,$href);
	echo getPagenumList($pages,$number,3,reset_param_name($query_string,'number').'&number','','');
	echo '</p>';   
	
		if (!isset($_GET['wap'])) {
		  echo $end1;
		  //include "display_voting.php";
		  }
       define("CORRECT_FOOTER", true);
		echo $end2; include('footer.php'); 

	   }
 //-------------------------------------------------------------- 
  //ВЫВОД ИНДИВИД. РАСПИСАНИЯ
  if ($idlect>0)
   {
	if ($gr_mode==1) {$query="select name as FIO from study_groups where id='".$idlect."'";}
	else $query="select FIO from users where id='".$idlect."'";
	
	//echo ' $query='.$query;
    $res_1=mysql_query ($query) or die("<br>Invalid query: " . mysql_error());
    
    $result_1=(mysql_fetch_array($res_1));
    if (!isset($_GET['save']) && !isset($_GET['print'])) {

		echo '<div class=text style="text-align:right;">
				 	<a class=text href="?'.$_SERVER["QUERY_STRING"].'&save&attach=doc&gr_mode='.$gr_mode.'" title="Выгрузить">Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
					<a class=text href="?'.$_SERVER["QUERY_STRING"].'&print&gr_mode='.$gr_mode.'" title="Распечатать">Печать</a></div>';
					}


echo '<div class="main"><a href="?onget=1&getallsub=1" title="к выбору расписания">Расписание занятий</a>,<span class="names">'
.$def_settings['part_name'].' &nbsp;'.$def_settings['year_name'].'года , 
<font size=-1>';
if ($gr_mode!=1) {echo '<a href="_modules/_lecturers/index.php?action=view&id='.$idlect.'" title="о преподавателе...">'.$result_1['FIO'].'</a>';}
else { echo '<a href="_modules/_student_groups/public.php?action=view&id='.$idlect.'" title="о учебной группе...">'.$result_1['FIO'].'</a>';}
echo '</font></span></div> ';

echo "\n\n<table name=time_table_all border=1 cellspacing=0>\n";
?>
<tr class=light_color_max><td></td>
<?php
$l=0;//для проверки наличия лаб.
//ДУБЛИРОВАННАЯ
for ($num=1;$num<=count($time);$num++)
{
		echo "<td  align=left width=150><font size=2> &nbsp;<b>".$num."</b>&nbsp;".$time[$num]."</font> &nbsp;</td>";
}
echo'</tr>';
for ($day=1;$day<=6;$day++)
{
echo "<tr><td valign=top class=light_color_max><b>".$exist_days[$day]."</b>&nbsp;</td>";
$prev_cell_lab='';	//для дублирования л.р
$prev_cell_lab_tmp='';
for ($num=1;$num<=count($time);$num++)
{
 $query_cell='select time.length,time.place,time_kind.name as kind,subjects.name_short as study_short,subjects.name as study,
 		study_groups.name as grup,time.study as study_id,users.fio from time 
					left join subjects on subjects.id=time.study
					left join study_groups on study_groups.id=time.grup			
					left join time_kind	on  time_kind.id=time.kind
					left join users on users.id=time.id '; 	
  if ($gr_mode!=1) {$query_cell.='where time.id="'.$idlect.'" ';} 
  else {$query_cell.='where time.grup="'.$idlect.'" ';}
  
  $query_cell.='and time.number="'.$num.'" and time.day="'.$day.'" and time.year="'.$def_settings['year_id'].'" and time.month="'.$def_settings['part_id'].'"';
	//echo $query_cell;			
				echo '<td valign=top width="150">';
				$res=mysql_query($query_cell);
				$i=1;$num_rows=mysql_num_rows($res);
				while ($b=mysql_fetch_array($res))
				{
				//echo 'grup='.$b['grup'].', fio='.$b['fio'];
				  if (isset($b['length']))	{$b['length']=	$b['length']." нед. ";}	//номера недель
				if (isset($b['place']))		{$b['place']=	', ауд.'.$b['place'].', ';}	//кабинет
				if (isset($b['kind']))		{$b['kind']=	' ('.$b['kind'].')';}	//тип занятия (пр,лаб,л)
				if (isset($b['study_short'])) {$b['study_short']=	'<b>'.$b['study_short'].'</b>';}	//название предмета 
				
			    if($prev_cell_lab!=''){$prev_cell_lab.='<hr>';}
			    
				//-----ссылка на страницы предмета (скачка пособий, если есть)
				$query_subj='SELECT nameFolder  FROM `documents` WHERE `subj_id` = '.$b['study_id'].' AND `user_id` = '.$idlect.'';
				$res_subj=mysql_query($query_subj);
				$num_rows_subj=mysql_num_rows($res_subj);
				$link_style='';$link='#';
				if ($num_rows_subj>0) {	
				 $b_subj=mysql_fetch_array($res_subj);
				 $subj=$b_subj['nameFolder'];
				 $link='_modules/_library/index.php?action=publicView&id='.$b_subj['nameFolder'].'';$link_style='';
				 }
				else {
				 $link='#';$link_style='style="color:#666666;"';
				 }
				//-----------------
				
				echo ''.$prev_cell_lab.$b['length'];
				
				if ($gr_mode==1) {echo ($hide_person_data_rule?$hide_person_data_text:$b['fio']);}
				else {echo $b['grup'];}
				
				echo $b['place'].' <a '.$link_style.' href="'.$link.'" title="'.$b['study'].'">'.$b['study_short'].'</a>'.$b['kind'].'';
				
				
				if(isset($b['kind']) && $b['kind']==' (л/р)')
					{$m=1;//проверка на наличие в ячейки лабы
					 if ($prev_cell_lab_tmp!=''){ if ($i==1){$prev_cell_lab_tmp='';}
					  else {$prev_cell_lab_tmp=$prev_cell_lab_tmp.'<hr>';}}
					 $prev_cell_lab_tmp='<font color="silver">'.$prev_cell_lab_tmp.$b['length'];

						if ($gr_mode==1) {$prev_cell_lab_tmp.=($hide_person_data_rule?$hide_person_data_text:$b['fio']);}
						else {$prev_cell_lab_tmp.=$b['grup'];}
					 
					 //if (!strstr ( $prev_cell_lab_tmp, $b['place'].$b['study_short'].$b['kind']))
					 $prev_cell_lab_tmp.=$b['place'].$b['study_short'].$b['kind'].'</font>';
					}	
					else
					 {
					 $prev_cell_lab_tmp='';
					 }
					 
				if ($i<$num_rows) {echo '<hr>';}
				$i++; 
				 }
		$m=0;//обнуление	
		if($num_rows==0)
		{
		    if ($prev_cell_lab_tmp!='')
			{echo $prev_cell_lab_tmp.'</td>';
			}
			else
			{echo'&nbsp</td>';}
			$prev_cell_lab_tmp='';$prev_cell_lab='';
		}
		echo'</td>';
		$prev_cell_lab=$prev_cell_lab_tmp;
		}
	
	echo "</tr>\n";
}
       
echo "</table><br>\n\n\n";
     if (!isset($_GET['save']) && !isset($_GET['print']))
    {
echo '<div class="notinfo"><a class="notinfo" href="javascript:history.back()">Вернуться</a>';
if ($gr_mode!=1) {
echo '&nbsp;&nbsp;<a href="?onget=1&idlect2='.$idlect.'">Перевернуть </a>';}
echo '<p><b>Примечание:</b><br> 
	<font color="silver"><p>Серым цветом</font> отмечена вторая половина лаб. работы.<br>
	При наведении на предмет, можно узнать его полное наименование. <br>
	Если ссылка предмета <a href="#">посвечена </a>, можно перейти к пособиям по предмету, кликнув по ссылке.';}

}

//ПОВОРОТ
if ($idlect2>0)		 
{
 $res_2=mysql_query ("select FIO from users where id='".$idlect2."'");
    $result_2=(mysql_fetch_array($res_2));
    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '<div class=text style="text-align:right;">
				 	<a class=text href="?'.$_SERVER["QUERY_STRING"].'&save&attach=doc" title="Выгрузить">Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
					<a class=text href="?'.$_SERVER["QUERY_STRING"].'&print" title="Распечатать">Печать</a></div>';
					}


echo '<div class="main"><a href="?onget=1&getallsub=1" title="к выбору расписания">Расписание занятий</a>,<span class="names">'
.$def_settings['part_name'].' &nbsp;'.$def_settings['year_name'].'года , 
<font size=-1>'.$result_2['FIO'].'</font></span></div> ';

echo "\n\n<table name=time_table_all border=1 cellspacing=0>\n";
?>
<tr class=light_color_max><td></td>
<?php
for ($day=1;$day<=6;$day++)
{
		echo "<td align=left><font size=2> &nbsp;<b>".$exist_days[$day]."</b></font></td>";
}
echo'</tr>';
$mas=array(6);
for ($num=1;$num<=count($time);$num++)
{
echo "<tr><td class=light_color_max align=left width=120 ><font size=2><b>".$num."</b>&nbsp;".$time[$num]."</font> &nbsp;</td>";
for ($day=1;$day<=6;$day++) 
{
$query_cell2='select time.length,time.place,time_kind.name as kind,subjects.name_short as study_short,subjects.name as study, study_groups.name as grup from time 
	left join subjects on subjects.id=time.study
	left join study_groups on study_groups.id=time.grup			
	left join time_kind	on  time_kind.id=time.kind 	
	where time.id="'.$idlect2.'" and time.number="'.$num.'" and time.day="'.$day.'"
	and year="'.$def_settings['year_id'].'" and month="'.$def_settings['part_id'].'"';
				
			echo '<td valign=top width="150">';
				$res2=mysql_query($query_cell2);
				$i2=1;$num_rows2=mysql_num_rows($res2);
				$j=0;
		if($num_rows2==0) 
		{
		    if ($mas[$day]!='') 
			{echo $mas[$day].'</td>';}
			else
			{echo'&nbsp';}
			$mas[$day]='';
		}
		while ($b2=mysql_fetch_array($res2))
	{
if (isset($b2['length']))	{$b2['length']=	$b2['length']." нед. ";}	//номера недель
if (isset($b2['place']))		{$b2['place']=	', ауд.'.$b2['place'].',';}	//кабинет
if (isset($b2['kind']))		{$b2['kind']=	' ('.$b2['kind'].')';}	//тип занятия (пр,лаб,л)
if (isset($b2['study_short'])) {$b2['study_short']=	'<b>'.$b2['study_short'].'</b>';}	//название предмета 
		if(($mas[$day]!='')&& ($j==0))
		{echo $mas[$day].'<hr>'; $mas[$day]='';}
		echo ''.$b2['length'].$b2['grup'].$b2['place'].
		' <a href="#" title="'.$b2['study'].'">'.$b2['study_short'].'</a>'.$b2['kind'].'';
			if ($i2<$num_rows2) {echo '<hr>';}
				$i2++; 

				if(isset($b2['kind']) && $b2['kind']==' (л/р)')
					{
				 if($mas[$day]!='') {$mas[$day]=$mas[$day].'<hr>';}
		    	 $mas[$day]='<font color="silver">'.$mas[$day].$b2['length'].$b2['grup'].$b2['place'].$b2['study_short'].$b2['kind'].'</font>';
			    ++$j;
					}
					else
					{if($mas[$day]=='')
					 {$mas[$day]='';}}
				 }
			echo '</td>';
	}
	echo "</tr>\n";
} 
echo "</table><br>\n\n\n";
 if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<div class="notinfo"><a class="notinfo" href="javascript:history.back()">Вернуться</a>'; 
echo '<p>Примечание:<br> 
	<font color="silver"><p>Серым цветом</font> отмечена вторая половина лаб. работы.<br>
	При наведении на предмет, можно узнать его полное наименование. <br>
	Если ссылка предмета <a href="#">посвечена </a>, можно перейти к пособиям по предмету, кликнув по ссылке.';}
}
      
?>  