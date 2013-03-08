<?php
include ('authorisation.php');

if (isset($_POST['part_list']) ) { //фильтр по указанному периоду
 	setcookie('part_list',$_POST['part_list']); setcookie('year_list',$_POST['year_list']);
	header('Location:?');
	}

if (isset($_GET['day']) && (intval($_GET['day'])>=7 || intval($_GET['day'])<=0))
{
 header('Location:?go=1&day=1');
}

include ('master_page_short.php');
?>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
<style>
.table_td {font-size:12px; font-family:Arial, Helvetica, sans-serif;}
.names {font-family:Arial; font-size:10pt;}
</style>
</head>


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
var c=1; //число должно превышать число элементов в выборке "свеху"
var i=1;

function addline()
{
	

	var max=parseInt(document.getElementById('max').value)+1;
	if (max<2) {max=2;}
	
	c=max+i;  // увеличиваем счётчик строк
	i++;
	s=document.getElementById('table_resize').innerHTML; // получаем HTML-код таблицы
	s=s.replace(/[\r\n]/g,''); // вырезаем все символы перевода строк
	re=/(.*)(<tr id=.*>)(<\/table>)/gi; 
                // это регулярное выражение позволяет выделить последнюю строку таблицы
	s1=s.replace(re,'$2'); // получаем HTML-код последней строки таблицы
	s2=s1.replace(/\_\d/gi,'_'+(c)+''); // заменяем все цифры к квадратных скобках
                // на номер новой строки
	s2=s2.replace(/(rmline\()(\d+\))/gi,'$1'+(c)+')');
                // заменяем аргумент функции rmline на номер новой строки
	s=s.replace(re,'$1$2'+s2+'$3');
                // создаём HTML-код с добавленным кодом новой строки
	document.getElementById('table_resize').innerHTML=s;
	return false; // чтобы не происходил переход по ссылке
}
function rmline(q)
{
                if (q==10)return false;
                if (c==10) return false; else c--;
                // если раскомментировать предыдущую строчку, то последний (единственный) 
                // элемент удалить будет нельзя.
           
	s=document.getElementById('table_resize').innerHTML;
	s=s.replace(/[\r\n]/g,'');
	re=new RegExp('<tr id="?newline"? nomer="?_'+q+'.*?<\\/tr>','gi');
                // это регулярное выражение позволяет выделить строку таблицы с заданным номером
	s=s.replace(re,'');
                // заменяем её на пустое место
	
	document.getElementById('table_resize').innerHTML=s;
	document.getElementById('max').value=c;
	
	return false;
}
function test_labs_nums(number_id)
{//проверка нечетности пар для л\р (т.е. пары их начала !)
var val_number;
var kind_number;
var num_number;
try {kind_number=document.getElementById('kind_'+number_id).value;
     num_number=document.getElementById('number_'+number_id).value;}
catch (e) {kind_number=document.all['kind_'+number_id].value;
           num_number=document.all['number_'+number_id].value;}
if (kind_number=='1')	 {//т.е. л\р
	val_number=parseInt(num_number);
	
	if (Math.round(val_number/2)==(val_number/2)) {//т.е. число четное
		
		alert('Вы указали возможно номер окончания л/р, а не начала.\n Для л/р должны быть указаны номера пар их начала, по умолчанию они нечетные'); }
	
	
}
} 
function change_color(div_name,cnt_name,mark_color)	//меняем цвет с учетом числа блоков div для замены
{
// alert('Привет!');
var div_item;
var div_cnt;
	
	try {div_cnt=(document.all[cnt_name].value);}
	catch (e) {div_cnt=(document.getElementById(cnt_name).value);}

//alert('div_cnt='+div_cnt);
//alert('cnt_name='+cnt_name);

if (div_name!='' && div_cnt>0) 
	{//меняем цвет на красный
//	alert('Привет1!');
	for (i=0;i<div_cnt;i++) {
		try {div_item=document.all[div_name+'_'+i];}
		catch (e) {div_item=document.getElementById(div_name+'_'+i);}
	//	alert (div_item.name);
		div_item.style.color=mark_color;
							}
	}
else {alert('Нет элементов для выделения.');}
//alert('Привет2!');
}
</script>
<?php
function curWeek_In_TimeWeeks($str_nedeli,$curWeek) {
//создаем массив номеров недель
//

$str_tmp_arr=array();	//времен.массив
$str_arr=array();		//конечный.массив номеров недель

$str_nedeli=str_replace(' ','',$str_nedeli);	//удалили пробелы

$str_tmp_arr=split(',',$str_nedeli);	//получили разбивку по запятым

//echo 'str_tmp_arr<hr>';
//print_r($str_tmp_arr);

$k=0;$findId=0;
for ($i=0;$i<count($str_tmp_arr);$i++)
{	$findId=strpos($str_tmp_arr[$i],'-');
	//echo ' findId='.$findId.'<br>';
 	if ($findId>=1) //т.е. элемент включает тире (-)
	{$valMin=substr($str_tmp_arr[$i],0,$findId);
	 $valMax=substr($str_tmp_arr[$i],$findId+1);
	 //echo '<hr>valMin='.$valMin.', valMax='.$valMax;
	 for ($j=$valMin;$j<=$valMax;$j++) {$str_arr[$k]=$j;$k++;}
	}
	
	else {$str_arr[$k]=$str_tmp_arr[$i];$k++;}
} 
sort($str_arr);
$maxId=count($str_arr)-1;
//echo '$str_arr[$maxId]='.$maxId.'!!!';

//echo '<br>str_arr<hr>';
//print_r($str_arr);

    if ($curWeek<=$str_arr[$maxId]) {
        return true;
    } else {
        return false;
    }

}
?>
<body>
<?php
$color1="Magenta";
$color2="#C0C0C0";
$color3="#ccccff";
$color4="#000000";
$color5="#00FFFF";
$color6="#808080";
$color_links='<p><a href="#" onClick=javascript:change_color("old","old_cnt","#008080");>Выделить прошедшие предметы</a></p>
<p><a href="#" onClick=javascript:change_color("future","future_cnt","#2020bd");>Выделить непрошедшие предметы</a></p>
<a class="notinfo" href="?'.$_SERVER['QUERY_STRING'].'">Отменить выделение </a>';
//include ('authorisation.php');
//include 'sql_connect.php';
//session_start();
$weeksBeetween=floor($daysBeetween/7)+1;
$time=array();
$time[1]='  8.00- 9.35 ';
$time[2]='  9.45-11.20 ';
$time[3]=' 12.10-13.45 ';
$time[4]=' 13.55-15.30 ';
$time[5]=' 16.10-17.45 ';
$time[6]=' 17.55-19.30 ';
$time[7]=' Вечерники ';
$exist_days=array('1','Пн','Вт','Ср','Чт','Пт','Сб');
$lab_audit=array('все','1','6-215б','6-217','6-310','6-317','6-319');

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

if($_SESSION['auth']==1 )
 {
  if (!$view_all_mode || !$write_mode) {$_SESSION['lect_id']=$_SESSION['id'];$_SESSION['lect_fio']=$_SESSION['FIO']; 	}
  else {
	  if (isset($_GET['user_id']) && $_GET['user_id']!=0) //если выбран преподаватель
	  	{
		   	$_SESSION['lect_id']=$_GET['user_id'];
		   	$res=mysql_query('select fio from users where id="'.$_SESSION['lect_id'].'" limit 0,1');
		   	
		   	if (mysql_num_rows($res)>0) {
			    $a=mysql_fetch_array($res);$_SESSION['lect_fio']=$a['fio'];}
		}
	}
  if (!isset($_GET['go']))
   {//выбираем  преподавателя для расписания
       if (array_key_exists("lect_id", $_SESSION)) {
           $res0222=mysql_query ('select * from time where id="'.$_SESSION['lect_id'].'" and year="'.$def_settings['year_id'].'" and month="'.$def_settings['part_id'].'"');
       } else {
           $res0222=mysql_query ('select * from time where year="'.$def_settings['year_id'].'" and month="'.$def_settings['part_id'].'"');
       }
    $z02=mysql_fetch_array($res0222);
    $sess=$def_settings['part_name'];//'весенней сессии ';
    echo '<div class="main">Расписание</div>
    <div class="middle2">'.$sess.' &nbsp;'.$def_settings['year_name'].' года<form name=forTaskMenu id=forTaskMenu></form>';

echo '<a href=javascript:hide_filter("time_form") class=text>выбрать другой период</a>
	<div name=time_form id=time_form style="display:none;">';
	echo '<form name="time_select" id=time_select action="" method="post">';
	echo '<select name="year_list" id=year_list style="width:200;"> 
			<option value="0">...выберите год ...</option>';
			$query='select id,name from time_intervals';
			$res2=mysql_query($query);
			while ($a=mysql_fetch_array($res2)) 	{
			 	$select_val='';
				  	if (isset($def_settings)) { if ($def_settings['year_id']==$a['id']) {$select_val=' selected';}}	
				echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
				}
	echo'	</select> учебный год &nbsp;&nbsp;&nbsp; ';
	echo '<select name="part_list" id=part_list style="width:200;"> 
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
//РАСПИСАНИЕ ЛАБ.РАБ.
echo'<p><form name="labrab" id=labrab method="GET" action="lect_time_table_all.php?go&labrab=1">';
echo '<div class="middle_library" style="text-align:left;">';
echo'Лабораторные работы:&nbsp;';
echo '<input type=hidden name=go id=go><input type=hidden name=labrab id=labrab value=1>';
echo '<Select Name="select_labrab" id=select_labrab style="width:245;">';
echo'<Option Value=0>...все...</Option>';
     for($lab=2;$lab<sizeof($lab_audit);$lab++)
     {
     $selected='';
	 if (isset($_GET['select_labrab']) && $_GET['select_labrab']==$lab) {$selected=' selected';}
	 echo'<Option Value='.$lab.$selected.'>'.$lab_audit[$lab].'</Option>';
	 }
	 echo'</Select>&nbsp';
echo'<input type="submit" value="OK"></form>';
	echo '<p>&nbsp</p>';
	persons_select('user_id');
	echo '&nbsp<input type=button onclick=window.location.href="lect_time_table_all.php?go=1&insert=1" title="Добавление (правка) расписания" value="OK">';
	echo $end1;
    include "display_voting.php";
    echo $end2; include('footer.php'); 
    //mysql_close();
   }
  else
   {//преподаватель выбран
//-------------------------------------------------------------------------------------------
//ВЫВОД ИНДИВИД. РАСПИСАНИЯ
if (isset($_GET['lect_select']))		 
{
    if (!isset($_GET['save']) && !isset($_GET['print'])) {
	

echo '<div class=text style="text-align:right;">
		 	<a class=text href="?'.$_SERVER["QUERY_STRING"].'&save&attach=doc" title="Выгрузить">Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
			<a class=text href="?'.$_SERVER["QUERY_STRING"].'&print" title="Распечатать">Печать</a></div>'; }
echo '<div class="main"><a href="?" title="к выбору расписания">Расписание занятий</a>,<span class="names">'
.$def_settings['part_name'].' &nbsp;'.$def_settings['year_name'].'года , 
<font size=-1>'.$_SESSION['lect_fio'].'</font></span></div> ';

echo "\n\n<table name=time_table_all id=time_table_all border=1 cellspacing=0>\n";
//Вывод индивидуального расписания
echo '<tr bgcolor='.$color3.'><td>&nbsp;</td>';
$n=0;//для фиксации ошибок с лабами
$o=0;//для фиксации наличия лаб
$future_cnt=0;
$old_cnt=0;
for ($num=1;$num<=count($time);$num++)
{
		echo "<td align=left width=150 ><font size=2> &nbsp;<b>".$num."</b>&nbsp;".$time[$num]."</font> &nbsp;</td>";
}
echo'</tr>';
for ($day=1;$day<=6;$day++)
{                                                                  
echo '<tr><td bgcolor='.$color3.'><b>'.$exist_days[$day].'</b>&nbsp;</td>';

for ($num=1;$num<=count($time);$num++)
{
 $query_cell='select time.length,time.place,time_kind.name as kind,subjects.name_short as study_short,subjects.name as study,
 		study_groups.name as grup from time 
					left join subjects on subjects.id=time.study
					left join study_groups on study_groups.id=time.grup			
					left join time_kind	on  time_kind.id=time.kind 	
					where time.id="'.$_SESSION['lect_id'].'" and time.number="'.$num.'" and time.day="'.$day.'"
						 and year="'.$def_settings['year_id'].'" and month="'.$def_settings['part_id'].'"';
				echo '<td valign=top width="150">';
				$res=mysql_query($query_cell);
				$i=1;$num_rows=mysql_num_rows($res);
				while ($b=mysql_fetch_array($res))
				{if (isset($b['length']))	{$ned=$b['length']; $b['length']=	$b['length']." нед. ";}	//номера недель
				if (isset($b['place']))		{$b['place']=	', ауд.'.$b['place'].',';}	//кабинет
				if (isset($b['kind']))		{$b['kind']=	' ('.$b['kind'].')';}	//тип занятия (пр,лаб,л)
				if (isset($b['study_short'])) {$b['study_short']=	'<b>'.$b['study_short'].'</b>';}	//название предмета 
				
				//echo $div_name;
				if($prev_cell_lab!=''){$prev_cell_lab=$prev_cell_lab.'<hr>';}
				if(($num%2==0)&& $b['kind']==' (л/р)')
				{$n=1;//фиксация ошибок
				if (!isset($_GET['save']) && !isset($_GET['print']))
				{$clr1=$color1;}}
				else {$clr1=$color4;}
				
				if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{echo '<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$clr1.';">';
				 $future_cnt++;}
				else
				{echo '<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$clr1.';">'; $old_cnt++;}
				echo $prev_cell_lab;
				$prev_cell_lab='';
				
 if (!isset($_GET['save']) && !isset($_GET['print'])){
if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{echo '<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$clr1.';">';
				 $future_cnt++;}
				else
				{echo '<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$clr1.';">'; $old_cnt++;}  
  				
echo'<a href="lect_time_table_all.php?go&day='.$day.'&user_id='.$_SESSION['lect_id'].'&prep='.$_GET['lect_select'].'&nedeli='.$ned.'&num='.$num.'">
				<img src=images/toupdate.png alt=изменить border=0></a>';} 
				
				echo ''.$b['length'].$b['grup'].$b['place'].
			' <a href="#" title="'.$b['study'].'">'.$b['study_short'].'</a>'.$b['kind'].'';
							
				if(isset($b['kind']) && $b['kind']==' (л/р)')
					{$m=1;//проверка на наличие в ячейки лабы
					$o=1;//проверка на наличие лабы(для инфо снизу)
					
					 if ($prev_cell_lab_tmp!=''){ if ($i==1){$prev_cell_lab_tmp='';}
					  else {$prev_cell_lab_tmp=$prev_cell_lab_tmp.'<hr>';}}
					 if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{$prev_cell_lab_tmp=' '.$prev_cell_lab_tmp.'<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$color2.';">'.$b['length'].$b['grup'].$b['place'].$b['study_short'].$b['kind'];
				 $future_cnt++;}
				else
				{$prev_cell_lab_tmp=' '.$prev_cell_lab_tmp.'<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$color2.';">'.$b['length'].$b['grup'].$b['place'].$b['study_short'].$b['kind']; $old_cnt++;} 
					/*$prev_cell_lab_tmp=' '.$prev_cell_lab_tmp.$b['length'].$b['grup'].$b['place'].$b['study_short'].$b['kind'];*/
				    }
					 else
					 { if ($m!=1)
					 {$prev_cell_lab_tmp='';}}	
					 
					 if (($i<$num_rows)){echo '<hr>';}
				$i++; 
					
				 }
		$m=0;//обнуление	
		if($num_rows==0)
		{
		 if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{echo '<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$color2.';">';
				 $future_cnt++;}
				else
				{echo '<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$color2.';">'; $old_cnt++;}
				
		    if ($prev_cell_lab_tmp!='')
			{echo $prev_cell_lab_tmp.'</div></td>';}
			else
			{echo'&nbsp</div></td>';}
			$prev_cell_lab_tmp='';$prev_cell_lab='';
		}
		else
		{echo'</div></td>';}
		$prev_cell_lab=$prev_cell_lab_tmp;
		}
	echo "</tr>\n";
} 
echo "</table><br>\n\n\n";
//echo $old_cnt.'; '.$future_cnt;
echo '<input type="hidden" name="old_cnt" id="old_cnt" value="'.$old_cnt.'">';
echo '<input type="hidden" name="future_cnt" id="future_cnt" value="'.$future_cnt.'">';
//echo '<div name="old_cnt" id="old_cnt" style="display:none;">'.$old_cnt.'</div>';
//echo '<div name="future_cnt" id="future_cnt" style="display:none;">'.$future_cnt.'</div>';
 if (!isset($_GET['save']) && !isset($_GET['print']))
    {
echo '<div class="notinfo"><a class="notinfo" href="lect_time_table_all.php?go=1&insert=1" onClick="JavaScript:window.location.href="lect_time_table_all.php?go=1&insert=1;">Вернуться</a>';
//или <a class="notinfo" href="javascript:history.back()">
echo '&nbsp;&nbsp;<a href="?go&lect_select2&user_id='.$_GET['user_id'].'">Перевернуть </a>';
echo $color_links;
if ($o==1)
{echo '<p><font color='.$color2.'> Серым цветом</font> отмечена вторая половина лаб. работы.';}
if($n==1)
{echo '<p><font color='.$color1.'> Розовым цветом</font> выделены лаб. работы с неверно указанным временем! Измените их!';}}
exit();}

//Поворот
if (isset($_GET['lect_select2']))		 
{
    if (!isset($_GET['save']) && !isset($_GET['print']))
	 {
	echo '<div class=text style="text-align:right;">
		 	<a class=text href="?'.$_SERVER["QUERY_STRING"].'&save&attach=doc" title="Выгрузить">Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
			<a class=text href="?'.$_SERVER["QUERY_STRING"].'&print" title="Распечатать">Печать</a></div>'; }
echo '<div class="main"><a href="?" title="к выбору расписания">Расписание занятий</a>,<span class="names">'
.$def_settings['part_name'].' &nbsp;'.$def_settings['year_name'].'года , 
<font size=-1>'.$_SESSION['lect_fio'].'</font></span></div> ';

echo "\n\n<table name=time_table_all2 id=time_table_all2 border=1 cellspacing=0>\n";

//Вывод индивидуального расписания-поворот
echo '<tr bgcolor='.$color3.'><td>&nbsp;</td>';
$o=0;//для проверки на наличие лаб(инфо снизу)
$n=0;//для проверки на ошибки в времени лаб (инфо снизу)
$future_cnt=0;
$old_cnt=0;
for ($day=1;$day<=6;$day++)
{
		echo "<td align=left width=150 ><font size=2> &nbsp;<b>".$exist_days[$day]."</b></font></td>";
}
echo'</tr>';
$mas=array(6);
for ($num=1;$num<=count($time);$num++)
{
echo '<tr><td bgcolor='.$color3.' align=left width=125><font size=2><b>'.$num.'</b>&nbsp;'.$time[$num].'</font> &nbsp;</td>';
for ($day=1;$day<=6;$day++)
{
$query_cell2='select time.length,time.place,time_kind.name as kind,subjects.name_short as study_short,subjects.name as study,
 		study_groups.name as grup from time 
					left join subjects on subjects.id=time.study
					left join study_groups on study_groups.id=time.grup			
					left join time_kind	on  time_kind.id=time.kind 	
					where time.id="'.$_SESSION['lect_id'].'" and time.number="'.$num.'" and time.day="'.$day.'"
						 and year="'.$def_settings['year_id'].'" and month="'.$def_settings['part_id'].'"';
				echo '<td valign=top width="150">';
				$res2=mysql_query($query_cell2);
				$i2=1;$num_rows2=mysql_num_rows($res2);
				$j2=0;
		if($num_rows2==0) 
		{
		    if ($mas[$day]!='') 
			{
			 if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{echo '<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$color2.';">';
				 $future_cnt++;}
				else
				{echo '<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$color2.';">'; $old_cnt++;}
			 echo $mas[$day].'</div></td>';}
			else
			{echo'&nbsp';}
			$mas[$day]='';
			
		}
		
		while ($b2=mysql_fetch_array($res2))
				
			
				{if (isset($b2['length']))	{$ned=$b2['length']; $b2['length']=	$b2['length']." нед. ";}	//номера недель
				if (isset($b2['place']))		{$b2['place']=	', ауд.'.$b2['place'].',';}	//кабинет
				if (isset($b2['kind']))		{$b2['kind']=	' ('.$b2['kind'].')';}	//тип занятия (пр,лаб,л)
				if (isset($b2['study_short'])) {$b2['study_short']=	'<b>'.$b2['study_short'].'</b>';}	//название предмета 
				
				
				if(($num%2==0)&& $b2['kind']==' (л/р)')
					 {$n=1;//фиксация ошибок
				if (!isset($_GET['save']) && !isset($_GET['print']))
				{$clr1=$color1;}}
				else {$clr1=$color4;}
				if(($mas[$day]!='')&& ($j2==0))
		{
		 if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{echo '<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$clr1.';">';
				 $future_cnt++;}
				else
				{echo '<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$clr1.';">'; $old_cnt++;}
		 echo $mas[$day].'<hr>'; $mas[$day]='';}
		if (!isset($_GET['save']) && !isset($_GET['print'])){
		 if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{echo '<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$clr1.';">';
				 $future_cnt++;}
				else
				{echo '<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$clr1.';">'; $old_cnt++;}
			echo '<a href="lect_time_table_all.php?go&day='.$day.'&user_id='.$_SESSION['lect_id'].'&prep2='.$_GET['lect_select'].'&nedeli='.$ned.'&num='.$num.'"> <img src=images/toupdate.png alt=изменить border=0></a>';}
			
			echo ''.$b2['length'].$b2['grup'].$b2['place'].
			' <a href="#" title="'.$b2['study'].'">'.$b2['study_short'].'</a>'.$b2['kind'].'';
				if ($i2<$num_rows2) {echo '<hr>';}
				$i2++; 
				
				if(isset($b2['kind']) && $b2['kind']==' (л/р)')
					{
				 if($mas[$day]!='') {$mas[$day]=$mas[$day].'<hr>';}
				 
				 if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{$mas[$day]=' '.$mas[$day].'<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$color2.';">'.$b2['length'].$b2['grup'].$b2['place'].$b2['study_short'].$b2['kind'];
				 $future_cnt++;}
				else
				{$mas[$day]=' '.$mas[$day].'<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$color2.';">'.$b2['length'].$b2['grup'].$b2['place'].$b2['study_short'].$b2['kind']; $old_cnt++;}
				 
		    	/* $mas[$day]=' '.$mas[$day].$b2['length'].$b2['grup'].$b2['place'].$b2['study_short'].$b2['kind'].'</font>';*/
			    ++$j2;// фиксирует лабу
					}
				}
			echo '</div></td>';
	}
	echo "</tr>\n";
} 
echo "</table><br>\n\n\n";
echo '<input type="hidden" name="old_cnt" id="old_cnt" value="'.$old_cnt.'">';
echo '<input type="hidden" name="future_cnt" id="future_cnt" value="'.$future_cnt.'">';
 if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<div class="notinfo"><a class="notinfo" href="lect_time_table_all.php?go&lect_select&user_id='.$_SESSION['lect_id'].'" onClick="JavaScript:window.location.href="lect_time_table_all.php?go&lect_select&user_id='.$_SESSION['lect_id'].';">Вернуться</a>'; 
echo $color_links;
echo '<p><font color='.$color2.'> Серым цветом</font> отмечена вторая половина лаб. работы.';
if ($n==1) {echo '<p><font color='.$color1.'> Розовым цветом</font> выделены лаб. работы с неверно указанным временем! Измените их!';}}
exit();}
//----------------------------------------------------------------------------------------------       
//ВЫВОД ЛАБ.РАБ.
if (isset($_GET['select_labrab']))		 
{
    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '<div class=text style="text-align:right;">
		 	<a class=text href="?'.$_SERVER["QUERY_STRING"].'&save&attach=doc" title="Выгрузить">Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
			<a class=text href="?'.$_SERVER["QUERY_STRING"].'&print" title="Распечатать">Печать</a></div>'; }
$future_cnt=0;
$old_cnt=0;			
if($_GET['select_labrab']=='0')
{
$per1='2';
$per2='6';
if (!isset($_GET['save']) && !isset($_GET['print']))  {
	echo '<a class=notinfo href="lect_time_table_all.php?" onClick="JavaScript:window.location.href="lect_time_table_all.php?;"> Вернуться</a>';
echo '&nbsp;&nbsp;<a href="?go=&labrab=1&select_labrab2='.$_GET['select_labrab'].'">Перевернуть </a>'; }
}
else
{
$per1=$_GET['select_labrab'];
$per2=$_GET['select_labrab'];
}
for($lab=$per1;$lab<=$per2;$lab++)
{
 echo '<div class="main"><a href="?" title="к выбору расписания">Расписание лабораторных работ </a>, 
<span class="middle2">'.$def_settings['part_name'].' &nbsp;'.$def_settings['year_name'].'года, </span> <font size=-1> лаборатория №: '.$lab_audit[$lab].'</font></div>';
echo '<p><table name=time_table_all_laba id=time_table_all_laba border=1 cellspacing=0>
<tr bgcolor='.$color3.'><td></td>';
$numer=1;
while($numer<=count($time)) {
    echo "<td align=left width=90 ><font size=2> &nbsp;<b>".$numer."</b>&nbsp;".$time[$numer++];
    $num = $numer++;
    if (array_key_exists($num, $time)) {
        echo"<br>&nbsp;<b>".$num."</b>&nbsp;".$time[$num]."</font> </td>";
    }
}
echo'</tr>';
for ($day=1;$day<=6;$day++)
{
echo '<tr><td bgcolor='.$color3.'><b>'.$exist_days[$day].'</b></td>';
for ($num=1;$num<=count($time);$num++)
{
  $query_cell='select users.id as lect_id, users.FIO_SHORT as user, time.id as lect_id, time.length,time.place,time_kind.name as kind,subjects.name_short as study_short,subjects.name as study,study_groups.name as grup from time 
					left join subjects on subjects.id=time.study
					left join study_groups on study_groups.id=time.grup			
					left join time_kind	on  time_kind.id=time.kind 
					left join users	on  users.id=time.id  	
					where time.place="'.$lab_audit[$lab].'" and time.length<>"проект" 
					and time.number="'.$num.'" and time.day="'.$day.'"
					and year="'.$def_settings['year_id'].'"
					and month="'.$def_settings['part_id'].'"';
$nomer=$num;					
$num++;
$query_cell_lab='select users.id as lect_id, users.FIO_SHORT as user, time.id as lect_id, time.length,time.place,time_kind.name as kind,subjects.name_short as study_short,subjects.name as study,study_groups.name as grup from time 
					left join subjects on subjects.id=time.study
					left join study_groups on study_groups.id=time.grup			
					left join time_kind	on  time_kind.id=time.kind 
					left join users	on  users.id=time.id  	
					where time.place="'.$lab_audit[$lab].'" and time.length<>"проект" 
					and time.number="'.$num.'" and time.day="'.$day.'"
					and year="'.$def_settings['year_id'].'"
					and month="'.$def_settings['part_id'].'"';					
				echo '<td valign=top width="500"><font size=-1>';
				
				$res=mysql_query($query_cell);
				$i=1;$num_rows=mysql_num_rows($res);
				
				$res_lab=mysql_query($query_cell_lab);
				$i_lab=1;$num_rows_lab=mysql_num_rows($res_lab);
				
				while ($b=mysql_fetch_array($res))
		{if (isset($b['length']))	{$ned=$b['length']; $b['length']=	$b['length']." нед.";}	//номера недель
		if (isset($b['user']))		{$b['user']=', '.$b['user'];}	//тип занятия (пр,лаб,л)
		if (isset($b['study_short'])) {$b['study_short']=	'<b>'.$b['study_short'].'</b>';}	//название предмета 

if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{echo '<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$color4.';">';
				 $future_cnt++;}
				else
				{echo '<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$color4.';">'; $old_cnt++;}
				if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo'<a href="lect_time_table_all.php?go&day='.$day.'&user_id='.$b['lect_id'].'&laba='.$_GET['select_labrab'].'&nedeli='.$ned.'&num='.$nomer.'">
				<img src=images/toupdate.png alt=изменить border=0></a>'; }
				
				echo $b['length'].$b['grup'].', '.
				'<br>&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="#" title="'.$b['study'].'">'.$b['study_short'].'</a>'.$b['user'].'';
				if ($i<$num_rows) {echo '<hr>';}
				$i++; 
				if(isset($_GET['day'])) {$labs=$lab;}
				}
			if ($num_rows_lab!=0)
			{$nomer=$num;
			 if($num_rows!=0)
			{echo '<hr>';} 
			  $k=1;
			while ($b_lab=mysql_fetch_array($res_lab))
			{
			if (isset($b_lab['length']))	{$ned=$b_lab['length']; $b_lab['length']=	$b_lab['length']." нед.";}	//номера недель
		if (isset($b_lab['user']))		{$b_lab['user']=', '.$b_lab['user'];}	//тип занятия (пр,лаб,л)
		if (isset($b_lab['study_short'])) {$b_lab['study_short']=	'<b>'.$b_lab['study_short'].'</b>';}	//название предмета 

if (!isset($_GET['save']) && !isset($_GET['print'])) {$clr1=$color1;}
 else {$clr1=$color4;}

if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{echo '<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$clr1.';">';
				 $future_cnt++;}
				else
				{echo '<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$clr1.';">'; $old_cnt++;}
				
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo'<a href="lect_time_table_all.php?go&day='.$day.'&user_id='.$b_lab['lect_id'].'&laba='.$_GET['select_labrab'].'&nedeli='.$ned.'&num='.$nomer.'">
				<img src=images/toupdate.png alt=изменить border=0></a>'; }
				echo $b_lab['length'].$b_lab['grup'].', '.
				'<br>&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="#" title="'.$b_lab['study'].'">'.$b_lab['study_short'].'</a>'.$b_lab['user'];
				if ($i_lab<$num_rows_lab) {echo '<hr>';}
				$i_lab++; 	
			}}
			echo '&nbsp;</div></td>';
		}
		echo "</tr>\n";
} 
echo "</table><p><br>\n\n\n";
}
echo '<input type="hidden" name="old_cnt" id="old_cnt" value="'.$old_cnt.'">';
echo '<input type="hidden" name="future_cnt" id="future_cnt" value="'.$future_cnt.'">';
    if (!isset($_GET['save']) && !isset($_GET['print']))  {
	echo '<a class=notinfo href="lect_time_table_all.php?" onClick="JavaScript:window.location.href="lect_time_table_all.php?;"> Вернуться</a>';
echo '&nbsp;&nbsp;<a href="?go=&labrab=1&select_labrab2='.$_GET['select_labrab'].'">Перевернуть </a>'; 
if($_GET['select_labrab']=='0')
{echo '<p><a href="#top">Наверх</a><p>';}
echo '<p><a href="#" onClick=javascript:change_color("old","old_cnt","#008080");>Выделить прошедшие предметы</a></p>'; 
echo '<p><a href="#" onClick=javascript:change_color("future","future_cnt","#2020bd");>Выделить непрошедшие предметы</a></p>';
    if (isset($k)) {
        if ($k==1){	echo'<p><font color='.$color1.'> Розовым цветом</font> выделены лаб. работ, с неверно указанным временем! Измените их!<p>';}}
    }
exit();}

//ПОВОРОТ ЛАБ.РАБ.
if (isset($_GET['select_labrab2']))		 
{
    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '<div class=text style="text-align:right;">
		 	<a class=text href="?'.$_SERVER["QUERY_STRING"].'&save&attach=doc" title="Выгрузить">Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
			<a class=text href="?'.$_SERVER["QUERY_STRING"].'&print" title="Распечатать">Печать</a></div>'; }
$future_cnt=0;
$old_cnt=0;
if($_GET['select_labrab2']=='0')
{
$per1='2';
$per2='6';
if ((!isset($_GET['save'])) && (!isset($_GET['print'])))  {
	echo '<a class=notinfo href="lect_time_table_all.php?go=&labrab=1&select_labrab='.$_GET['select_labrab2'].'" onClick="JavaScript:window.location.href="lect_time_table_all.php?go=&labrab=1&select_labrab='.$_GET['select_labrab2'].';">Вернуться</a>'; }
}
else
{
$per1=$_GET['select_labrab2'];
$per2=$_GET['select_labrab2'];
}
for($lab=$per1;$lab<=$per2;$lab++)
{
 echo '<div class="main"><a href="?" title="к выбору расписания">Расписание лабораторных работ </a>, 
<span class="middle2">'.$def_settings['part_name'].' &nbsp;'.$def_settings['year_name'].'года, </span> <font size=-1> лаборатория №: '.$lab_audit[$lab].'</font></div>';
echo '<p><table name=time_table_all_laba_pov id=time_table_all_laba_pov border=1 cellspacing=0>
<tr bgcolor='.$color3.'><td>&nbsp;</td>';
for ($day=1;$day<=6;$day++)
{
echo '<td align=left> <font size=2> <b>'.$exist_days[$day].'</b></td>';
}
echo '</tr><tr>';
$numer=1;
for ($num=1;$num<=count($time);$num=$num+2)
{$nom_pov=$num;
echo "<td bgcolor=".$color3." align=left width=125><font size=2> <b>".$numer."</b>&nbsp;".$time[$numer++];
 echo"<br><b>".$numer."</b>&nbsp;".$time[$numer++]."</font> </td>";
for ($day=1;$day<=6;$day++)
{
  $query_cell_pov='select users.id as lect_id, users.FIO_SHORT as user, time.id as lect_id, time.length,time.place,time_kind.name as kind,subjects.name_short as study_short,subjects.name as study,study_groups.name as grup from time 
					left join subjects on subjects.id=time.study
					left join study_groups on study_groups.id=time.grup			
					left join time_kind	on  time_kind.id=time.kind 
					left join users	on  users.id=time.id  	
					where time.place="'.$lab_audit[$lab].'" and time.length<>"проект" 
					and time.number="'.$num.'" and time.day="'.$day.'"
					and year="'.$def_settings['year_id'].'"
					and month="'.$def_settings['part_id'].'"';
$nom_pov=$num;
$nomer=$num;					
$num++;
$query_cell_lab_pov='select users.id as lect_id, users.FIO_SHORT as user, time.id as lect_id, time.length,time.place,time_kind.name as kind,subjects.name_short as study_short,subjects.name as study,study_groups.name as grup from time 
					left join subjects on subjects.id=time.study
					left join study_groups on study_groups.id=time.grup			
					left join time_kind	on  time_kind.id=time.kind 
					left join users	on  users.id=time.id  	
					where time.place="'.$lab_audit[$lab].'" and time.length<>"проект" 
					and time.number="'.$num.'" and time.day="'.$day.'"
					and year="'.$def_settings['year_id'].'"
					and month="'.$def_settings['part_id'].'"';					
				echo '<td valign=top width=150><font size=-1>';
				$res_pov=mysql_query($query_cell_pov);
				$i=1;$num_rows_pov=mysql_num_rows($res_pov);
				$res_lab_pov=mysql_query($query_cell_lab_pov);
				$i_lab=1;$num_rows_lab_pov=mysql_num_rows($res_lab_pov);
				while ($b_pov=mysql_fetch_array($res_pov))
		{if (isset($b_pov['length']))	{$ned=$b_pov['length']; $b_pov['length']=	$b_pov['length']." нед.";}	//номера недель
		if (isset($b_pov['user']))		{$b_pov['user']=', '.$b_pov['user'];}	//тип занятия (пр,лаб,л)
		if (isset($b_pov['study_short'])) {$b_pov['study_short']=	'<b>'.$b_pov['study_short'].'</b>';}	//название предмета 
if (!isset($_GET['save']) && !isset($_GET['print'])) {
 if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{echo '<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$color4.';">';
				 $future_cnt++;}
				else
				{echo '<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$color4.';">'; $old_cnt++;}
echo'<a href="lect_time_table_all.php?go&day='.$day.'&user_id='.$b_pov['lect_id'].'&laba2='.$_GET['select_labrab2'].'&nedeli='.$ned.'&num='.$nomer.'">
				<img src=images/toupdate.png alt=изменить border=0></a>'; }
				
				echo $b_pov['length'].$b_pov['grup'].', '.
				'<a href="#" title="'.$b_pov['study'].'">'.$b_pov['study_short'].'</a>'.$b_pov['user'].'';
				if ($i<$num_rows_pov) {echo '<hr>';}
				$i++; 
				if(isset($_GET['day'])) {$labs=$lab;}
			    }
			if ($num_rows_lab_pov!=0)
			{$nomer=$num;
			 if($num_rows_pov!=0)
			{echo '<hr>';} 
			  $k=1;
			while ($b_lab_pov=mysql_fetch_array($res_lab_pov))
			{
			if (isset($b_lab_pov['length']))	{$ned=$b_lab_pov['length']; $b_lab_pov['length']=	$b_lab_pov['length']." нед.";}	//номера недель
		if (isset($b_lab_pov['user']))		{$b_lab_pov['user']=', '.$b_lab_pov['user'];}	//тип занятия (пр,лаб,л)
		if (isset($b_lab_pov['study_short'])) {$b_lab_pov['study_short']=	'<b>'.$b_lab_pov['study_short'].'</b>';}	//название предмета 
if (!isset($_GET['save']) && !isset($_GET['print'])) {$clr1=$color1;}
else{$clr1=$color4;}

if (curWeek_In_TimeWeeks($ned,$weeksBeetween))
				{echo '<div name="future_'.$future_cnt.'" id="future_'.$future_cnt.'" style="color:'.$clr1.';">';
				 $future_cnt++;}
				else
				{echo '<div name="old_'.$old_cnt.'" id="old_'.$old_cnt.'" style="color:'.$clr1.';">'; $old_cnt++;}
				if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo'<a href="lect_time_table_all.php?go&day='.$day.'&user_id='.$b_lab_pov['lect_id'].'&laba2='.$_GET['select_labrab2'].'&nedeli='.$ned.'&num='.$nomer.'">
				<img src=images/toupdate.png alt=изменить border=0></a>'; }
				echo $b_lab_pov['length'].$b_lab_pov['grup'].', '.
				'<a href="#" title="'.$b_lab_pov['study'].'">'.$b_lab_pov['study_short'].'</a>'.$b_lab_pov['user'];
				if ($i_lab<$num_rows_lab_pov) {echo '<hr>';}
				$i_lab++; 	
			}}
			echo '&nbsp;</div></td>';
		$num=$nom_pov;	
		}
		echo "</tr>\n";
} 
echo "</table><p><br>\n\n\n";
}
echo '<input type="hidden" name="old_cnt" id="old_cnt" value="'.$old_cnt.'">';
echo '<input type="hidden" name="future_cnt" id="future_cnt" value="'.$future_cnt.'">';
    if ((!isset($_GET['save'])) && (!isset($_GET['print'])))  {
	echo '<a class=notinfo href="lect_time_table_all.php?go=&labrab=1&select_labrab='.$_GET['select_labrab2'].'" onClick="JavaScript:window.location.href="lect_time_table_all.php?go=&labrab=1&select_labrab='.$_GET['select_labrab2'].';">Вернуться</a>';
if($_GET['select_labrab2']=='0')
{echo '<p><a href="#top">Наверх</a><p>';}
echo '<p><a href="#" onClick=javascript:change_color("old","old_cnt","#008080");>Выделить прошедшие предметы</a></p>'; 
echo '<p><a href="#" onClick=javascript:change_color("future","future_cnt","#2020bd");>Выделить непрошедшие предметы</a></p>';
if ($k==1){	echo'<p><font color='.$color1.'> Розовым цветом</font> выделены лаб. работ, с неверно указанным временем! Измените их!<p>';}}
exit();}
//-----------------------------------------------------------------------
//при правке лабы, вывод ФИО преподавателя
if(isset($_GET['lecter']))
{$res_lab=mysql_query("select FIO from users where id='".$_GET['lecter']."'");
$mas_lab=mysql_fetch_array($res_lab);
 $_SESSION['lect_fio']=$mas_lab['FIO'];}
 
        echo '<div class="main">Добавление (правка) расписания <div class="middle2">'.$def_settings['part_name'].' &nbsp;'.$def_settings['year_name'].'года </div> 	<p><font size=-1>'.$_SESSION['lect_fio'].'</font></div>';       
      if (isset($_POST['toinsert'])) {
	  $query="delete from time where day='".$_GET['day']."' and  year='".$def_settings['year_id']."' and month='".$def_settings['part_id']."' 
	  		and id='".$_SESSION['lect_id']."'";
	  mysql_query($query);
	  
	  while (list($val,$name)=each($_POST)) { 
	   		if (strstr($val,'number_') && $name!=0)
			   {$i=preg_replace("/[^0-9]/","",$val); //echo 'i='.$i.' val='.$val.'<hr>';
			    
				$query="insert into time set day='".$_GET['day']."', year='".$def_settings['year_id']."', month='".$def_settings['part_id']."', 
					id='".$_SESSION['lect_id']."', 
					number= '".$_POST['number_'.$i]."', length= '".$_POST['length_'.$i]."', kind= '".$_POST['kind_'.$i]."',
					study= '".$_POST['study_'.$i]."', grup= '".$_POST['group_'.$i]."', place= '".$_POST['place_'.$i]."' ";
				mysql_query($query);
				}
			   }
      echo '<p>данные обновлены.<p>'; }
		echo '<div class=text style="text-align:center;">Выделен день с наличием расписания<br>
		для печати расписания воспользуйтесь ссылкой "просмотр", для правки расписания выберите день недели
		</div><a href="?go&lect_select&user_id='.$_GET['user_id'].'"> Просмотр </a>';
	   echo '<table bordercolor='.$color3.' cellspacing=7 cellpadding=0 class="middle_lite" border=1 align=center style="text-align:center;"><tr>';
        
		for ($i=1;$i<=6;$i++)
         {
          $res00=mysql_query ('select count(if(id="'.$_SESSION['lect_id'].'" and day="'.$i.'" 
		  	and year="'.$def_settings['year_id'].'" and month="'.$def_settings['part_id'].'",1,NULL)) from time');
          $count_rasp=mysql_result($res00,0,0);
          $bgcolor='';
		  if ($count_rasp>=1) { $bgcolor=' bgcolor='.$color3;}
              echo'<td '.$bgcolor.' width=120><a href="lect_time_table_all.php?go=1&day='.$i.'&user_id='.$_GET['user_id'].'">&nbsp;'.$exist_days[$i].'&nbsp;</a></td>';
       }
        echo'</tr></table><p>'; 
      if (isset($_GET['day']))
      {
		echo '<div class="middle">';
       switch ($_GET['day'])
        {
         case 1: echo "Понедельник"; break;
         case 2: echo "Вторник"; break;
         case 3: echo "Среда"; break;
         case 4: echo "Четверг"; break;
         case 5: echo "Пятница"; break;
         case 6: echo "Суббота"; break;
         default: echo "Не знаю такой день!";
        }
       echo '</div><br>
       		<div class=text> Чтобы удалить строку достаточно выставить поле "№ зан." в " ...", т.е. очистить цифру в нем<p>
			для добавления новых строк нажмите на знак "+" ниже основной таблицы <p>
			для <b>л\р</b> указывайте всегда <b>№ начала</b>, при выводе расписания л\р <u>автоматически</u> продублируется 2 раза   </div>';
//----------------------------------------------------------------------------------------------	  
$p=0;	         
	   //if ($_SESSION['userType']=='преподаватель')  {$_GET['user_id']=$_SESSION['lect_id'];}
	   $_GET['user_id']=$_SESSION['lect_id'];
	   echo '<form action="" method="post">
	   <table border="0" cellspacing="2" cellpadding="0" align="center" class="middle_lite" bgcolor="#FFFFFF" width="100%">
       <tr height="20" class="middle"><td width=60>№ зан.</td><td width=60>Вид</td><td width=150>Период(нед.)</td>
	   	<td width=300>Предмет</td><td width=100>Группа(Курс)</td><td width=80>Ауд.</td> </tr>';
         $query='select * from time where id="'.$_GET['user_id'].'" and day="'.$_GET['day'].'" 
		 	and year="'.$def_settings['year_id'].'" and month="'.$def_settings['part_id'].'" order by number';
		 $res_=mysql_query($query);//echo "3";
		 $num_row=mysql_num_rows($res_); $row_id=0; 
         while ($z=mysql_fetch_array($res_))	//вывод по номерам пар (number)
         {
		   $row_id++;
		   if (($z['length']==$_GET['nedeli']) && ($z['number']==$_GET['num']))//правка из таблицы
		    {$coll=$color3; $znak='<font color="black">!</font>';$p=1;}//фиксация
		   else {$coll="white"; $znak='';}
		   echo '<tr align="center" bgcolor='.$coll.'>';
		   echo '<td>'.$znak.'<select name=number_'.$row_id.' style="width:50;" onchange="javascript:test_labs_nums('.$row_id.');">
		   		<option value=0> ... </option>';
			for ($i=1;$i<=7;$i++) {
				$select_val='';
				if ($i==$z['number']) {$select_val=' selected';}  
			 	echo '<option value='.$i.$select_val.'>'.$i.'</option>';
				 }
			echo '</select></td>';
			
            echo '<td>	<select name="kind_'.$row_id.'" style="width:55;" onchange="javascript:test_labs_nums('.$row_id.');"> 
				<option value="">...</option>';
	           $query="select * from time_kind";
			   $res=mysql_query($query);
				while ($a=mysql_fetch_array($res)) 	{
				 	$select_val='';
					if ($z['kind']==$a['id']) {$select_val=' selected';}  
				echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
					}
			echo '	</select></td>';
           echo '<td><input type="text" name="length_'.$row_id.'" id="length_'.$row_id.'" class="text2" size="15" maxlength="35" value="'.$z['length'].'" style="width:150;"></td>
           <td>	<select name="study_'.$row_id.'" id="study_'.$row_id.'" style="width:350;"> 
				<option value="">...</option>';
				$query='select id,name from subjects order by name';
				$res=mysql_query($query);
				while ($a=mysql_fetch_array($res)) 	{
				 	$select_val='';
					if ($z['study']==$a['id']) {$select_val=' selected';}  
				echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
					}
			echo '	</select></td>
           <td><select name="group_'.$row_id.'" id="group_'.$row_id.'" style="width:180;"> 
					<option value="">...</option>';
					$query='select id,name from study_groups order by name';
					$res=mysql_query($query);
					while ($a=mysql_fetch_array($res)) 
						{$select_val='';
						 if ($z['grup']==$a['id']) {$select_val=' selected';}  
				echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';			
						}
			echo '	</select></td>
			<td><input type="text" name="place_'.$row_id.'" id="place_'.$row_id.'" class="text2" size="15" maxlength="35" value="'.$z['place'].'"style="width:75;">
           </tr>';          
        }
		$row_id++;
?>
<tr><td colspan=6 align=left>
<p>&nbsp;</p><div id="table_resize" name="table_resize" style="text-align:left" >
   <table border="0" cellspacing="2" cellpadding="0" width="100%">
     <tr id="newline" nomer="_<?php echo $row_id; ?>" colspan=6>
       <td valign="top" align="center">
	   <a href="#" onclick="return addline();" style="text-decoration:none"><img src="images/design/pl.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
    <tr id="newline" nomer="_<?php echo $row_id; ?>">
      <td>
	  <select name="number_<?php echo $row_id; ?>" id="number_<?php echo $row_id; ?>" style="width:60;">
	  	<?php	//список преподавателей
		 echo '<option value="0"> ...</option>';
			for ($i=1;$i<=7;$i++) {
				$select_val='';
			 	echo "\n<option value=".$i.$select_val.">".$i."</option>";
				 }
		echo '</select></td>';
		echo '<td>	<select name="kind_'.$row_id.'" id="kind_'.$row_id.'" style="width:60;"> 
				<option value=""> ... </option>';
	           $query="select * from time_kind";
			   $res=mysql_query($query);
			   $aa=mysql_fetch_array($res02);
				while ($a=mysql_fetch_array($res)) 	{
				 	$select_val='';
					echo "\n<option value='".$a['id']."'".$select_val.">".$a['name']."</option>";
					}
			echo '	</select></td>';
           echo '<td><input type="text" name="length_'.$row_id.'" id="length_'.$row_id.'" class="text2" size="15" maxlength="35" value="'.$z['length'].'" style="width:150;"></td>
           <td>	<select name="study_'.$row_id.'" id="study_'.$row_id.'" style="width:350;"> 
				<option value="">...</option>';
				$query='select id,name from subjects order by name';
				$res=mysql_query($query);
				while ($a=mysql_fetch_array($res)) 	{
				 	$select_val='';
				echo "\n<option value='".$a['id']."'".$select_val.">".$a['name']."</option>";
					}
			echo '	</select></td>
           <td><select name="group_'.$row_id.'" id="group_'.$row_id.'" style="width:180;"> 
					<option value="">...</option>';
					$query='select id,name from study_groups order by name';
					$res=mysql_query($query);
					while ($a=mysql_fetch_array($res)) 
						{$select_val='';
				 echo "\n<option value='".$a['id']."'".$select_val.">".$a['name']."</option>";			
						}
			echo '	</select></td>
			<td><input type="text" name="place_'.$row_id.'" id="place_'.$row_id.'" class="text2" size="15" maxlength="35" value="'.$z['place'].'" style="width:80;"></td>';
	  		?>
      <td valign="top" align="center"><a href="#" onclick="return rmline(0);" style="text-decoration:none"><img src="images/design/mn.gif" border="0" WIDTH="17" HEIGHT="18"></a></td>
	  </tr>
  </table>
</div>
</td></tr>
<?php       echo'</table> <br>
		<input type=hidden name=max id=max value='.$num_row.'>
       <input type="hidden" name="go" id=go value="1">
       <input type="hidden" name="toinsert" id= toinsertvalue="1">
       <input type="hidden" name="day" id=day value="'.$_GET['day'].'">
       <input type="reset" value="Вернуть" class="button">&nbsp;&nbsp;
       <input type="submit" value="Изменить" class="button" onclick="javascript:test_labs_nums();">';
		
		if (isset($_GET['laba'])) {
?>
       &nbsp;&nbsp;&nbsp;<input type="button" class="button" onclick="Javascript:window.location.href='lect_time_table_all.php?go&select_labrab=<?php echo $_GET['laba'];?>';" value="К лабораториям">
<?php }

if (isset($_GET['laba2'])) {
?>
       &nbsp;&nbsp;&nbsp;<input type="button" class="button" onclick="Javascript:window.location.href='lect_time_table_all.php?go&select_labrab2=<?php echo $_GET['laba2'];?>';" value="К лабораториям">
<?php }
if (isset($_GET['prep'])) {
?>
       &nbsp;&nbsp;&nbsp;<input type="button" class="button" onclick="Javascript:window.location.href='lect_time_table_all.php?go&lect_select&user_id=<?php echo $_GET['prep'];?>';" value="К преподавателю">
<?php }

if (isset($_GET['prep2'])) {
?>
       &nbsp;&nbsp;&nbsp;<input type="button" class="button" onclick="Javascript:window.location.href='lect_time_table_all.php?go&lect_select2&user_id=<?php echo $_GET['prep'];?>';" value="К преподавателю">
<?php }
if ($p==1){echo '<p><font color='.$color3.'>Фиолетовым цветом</font> +! выделена строка, которую вы желаете изменить!';}
?>       
       </form>
<?php
      }
   }
 }
?>