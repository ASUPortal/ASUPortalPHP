<?php												
include ('authorisation.php');
//&& 
//$lect_control

$lect_control=false;	//позволять преподавателям менять нагрузку в форме через копирование
$multy_ptypes=true;	//множественные "тип участия на каф." у сотрудника

$lect_id=0;  
//получаем норме пользователя по его kadri_id
if (array_key_exists("kadri_id", $_GET)) {
    $lect_id=getScalarVal('select id from users where kadri_id="'.$_GET['kadri_id'].'"');
}

if ($_SESSION['task_rights_id']<3 && !$lect_control) {
    $lect_control=false;
} else {
    $lect_control=true;
}

$kadri_id=0;
if (isset($_GET['kadri_id']) && intval($_GET['kadri_id'])>0 ) {
    $kadri_id=intval($_GET['kadri_id']);
}


$kind_type_defaults='&kind_type1=on&kind_type2=on&kind_type3=on&kind_type4=on&filial_flag=on';		//какую нагрузку показывать
$query_string=$_SERVER['QUERY_STRING'];
if (isset($_GET['year'])) {$year=intval($_GET['year']);}

//session_start();
//var_dump($_SESSION);

if ($kadri_id==0) {
    if ($_SESSION['task_rights_id']<=2 && $kadri_id!=$_SESSION['kadri_id'])  {
        header('Location:?kadri_id='.$_SESSION['kadri_id'].$kind_type_defaults);
    } else {
        header('Location:s_hours.php');
    }
}

if (array_key_exists("type", $_GET)) {
    if (isset($_GET['type']) & $_GET['type']=='del' & isset($_GET['hours_id'])){
        $query='delete from hours_kind where id="'.$_GET['hours_id'].'"';
        $res=mysql_query($query);
        header('Location:?kadri_id='.$kadri_id.'&year='.$_GET['year'].$kind_type_defaults);
    }
}

include ('master_page_short.php');
?>
<h4><?php echo $pg_title;?></h4>
<style>
tr.title {font-size:11px;vertical-align:center; font-family:Arial; background-color:<?php 
	if (!isset($_GET['save']) && !isset($_GET['print'])) {echo '#E6E6FF;';} else {echo '#FFFFFF;';}?> }
tr.main_ {font-size:11px;vertical-align:top; font-family:Arial; background-color:<?php 
	if (!isset($_GET['save']) && !isset($_GET['print'])) {echo '#DFEFFF;';} else {echo '#FFFFFF;';}?> }
</style>
<script language="JavaScript">
function del_confirm(kadri_id,id,year)
{
	 if (confirm('Удалить строку ?')) 
	 	{window.location.href='s_hours_view.php?kadri_id='+kadri_id+'&hours_id='+id+'&type=del&year='+year;} 
} 
function test_copy(part_num)
{
 	var msg='';
	if (part_num==1)//осенний семестр
	{
		if (document.h_copy_1.teach_name.value==0) {msg=' сотрудник;';}
	 	if (document.h_copy_1.year_list.value==0) {msg=msg+' год;';}
	 	if (document.h_copy_1.part_list.value==0) {msg=msg+' семестр;'}	 
	} 
	else if (part_num==2) //весенний семестр
	{
		if (document.h_copy_2.teach_name.value==0) {msg=' сотрудник;';}
	 	if (document.h_copy_2.year_list.value==0) {msg=msg+' год;';}
	 	if (document.h_copy_2.part_list.value==0) {msg=msg+' не указан семестр;'}	 
	} 

	//alert(document.h_copy_1.teach_name.value);
	if (msg!='')  alert('Не указаны: '+msg+' \n -продолжение возможно только после исправления всех указанных ошибок');
	else {//alert('Копирование нагрузки выполнено');
		if (part_num==1) {document.h_copy_1.submit();} 
		if (part_num==2) {document.h_copy_2.submit();}
	
	}
 // onChange="javascript:window.location.href=\'?tab=1&kadri_id=\'+this.options[this.selectedIndex].value;"
}
function mark_all(click_name,part_num)
{//
	
	var item_cnt=0;
	var chech_val='';
	var mark_val;
	
	var ignore_items_cnt=5;	//число элементов выбора справочников формы
	//alert(part_num);
	//try {mail_cnt=document.getElementById('mail_cnt').value;}
	//catch (e) {mail_cnt=document.mail_list_form.elements.length;}
	if (part_num==1)//осенний семестр

		{item_cnt=document.h_copy_1.elements.length-ignore_items_cnt;
		
		try {mark_val=document.getElementById("checkbox_del_all1").checked;}
		catch (e) {	mark_val=document.h_copy_1.checkbox_del_all1.checked;  }
			  
	 	for (i=0;i<item_cnt;i++) { document.h_copy_1.elements[i].checked=mark_val; }  
		}
	else if (part_num==2) {
	 	item_cnt=document.h_copy_2.elements.length-ignore_items_cnt;
		
		try {mark_val=document.getElementById("checkbox_del_all2").checked;}
		catch (e) {	mark_val=document.h_copy_2.checkbox_del_all2.checked;}
			  
	 	for (i=0;i<item_cnt;i++) { document.h_copy_2.elements[i].checked=mark_val; }  
		 }
	
	//alert(item_cnt);
	
	
	//alert(mark_val);
	//document.mail_list_form.checkbox_del_all1.checked=mark_val;
	//document.mail_list_form.checkbox_del_all2.checked=mark_val;
	//alert(mail_cnt);alert(mark_val);
}  
</script>
<?php
$part_num=1;
$hour_kind_name=array('лекции', 'практич.', 'лаборатор. занятий', 'расчет.-грф. работы', 'рецензир. контр. работ', 'консультация', 'зачеты', 
	'экзамены', 'учебная практика', 'производств. практика', 'курсовые проекты', 'консультац. диплом. проект', 'ГЭК', 'занятия с аспирантами', 
	'руководство аспирант.', 'посещение занятий');
$hour_kind_code=array('lects', 'practs', 'labor', 'rgr', 'recenz', 'consult', 'test', 'exams', 'study_pract', 'work_pract', 'kurs_proj', 
	'consult_dipl', 'gek', 'aspirants', 'aspir_manage', 'duty');



function curWeek_In_TimeWeeks($str_nedeli) {
//создаем массив номеров недель

$str_tmp_arr=array();	//времен.массив
$str_arr=array();		//конечный.массив номеров недель

$str_nedeli=str_replace(' ','',$str_nedeli);	//удалили пробелы
$str_tmp_arr=split(',',$str_nedeli);	//получили разбивку по запятым

$k=0;$findId=0;
for ($i=0;$i<count($str_tmp_arr);$i++)
{	$findId=strpos($str_tmp_arr[$i],'-');
 	if ($findId>=1) //т.е. элемент включает тире (-)
	{$valMin=substr($str_tmp_arr[$i],0,$findId);
	 $valMax=substr($str_tmp_arr[$i],$findId+1);
	 for ($j=$valMin;$j<=$valMax;$j++) {$str_arr[$k]=$j;$k++;}
	}
	else {$str_arr[$k]=$str_tmp_arr[$i];$k++;}
} 
return $str_arr;

}
function hour_cnt($lect_id,$year_id,$part_id,$kindType)
{//считаем число часов у преп-ля в указ-м году\семесте    без учета Курс. проектов
//$kindType тип занятий: л, пр, л/р

$query='SELECT length,tk.name as tk_name FROM `time` t left join time_kind tk on tk.id=t.kind
	where t.id="'.$lect_id.'" and t.month="'.$part_id.'" and t.year="'.$year_id.'" ';

if ($kindType!='' && $kindType!='все')
{
	switch ($kindType) {
	case 'л/р':
		$query.='and tk.name="л/р"';
		break;
	case 'л':
		$query.='and tk.name="л"';	
		break;
	case 'пр':
		$query.='and tk.name="пр"';
		break;	
}
}
//echo '<div> query='.$query.'</div>';

$res=mysql_query($query);
$sum=0;$item=0;
if (mysql_num_rows($res)>0) {
	while ($a=mysql_fetch_array($res)) {
	
	$curArray=curWeek_In_TimeWeeks($a['length']);	//формируем массив недель
	
	//считаем число часов (для л\р *4, лекций *2)
	switch ($a['tk_name']) {
	case 'л/р':
		$item=count($curArray)*4;
		break;
	case 'л':
		$item=count($curArray)*2;	
		break;
	case 'пр':
		$item=count($curArray)*2;
		break;
	
	default:
		$item=0;	 //не учитываем проект,  
	}
	$sum+=$item;
	}
}
return $sum;
}

function print_hours($year,$part)
{
global $hour_kind_name,$hour_kind_code;
global $total_year;//для расчета общего итога
global $total_year_fil;
global $part_num;
global $kind_type_defaults;
global $lect_control;
global $lect_id;
global $multy_ptypes;
global $kadri_id;
$hours_kind_type_selected='';	//среди чего выборка по типу нагрузки

		$query='select id from hours_kind_type order by id';
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 
			{//$select_val='';
			 if (isset($_GET['kind_type'.$a['id']]) && $_GET['kind_type'.$a['id']]=='on') 
			 	{if ($hours_kind_type_selected=='') {$hours_kind_type_selected=$a['id'];}
				  else {$hours_kind_type_selected=$hours_kind_type_selected.','.$a['id'];} 
				} 
			 //echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';			
			}
$q_fields_list='';			
for ($i=0;$i<sizeof($hour_kind_code);$i++)
{
	$q_fields_list.=', '.$hour_kind_code[$i].', '.$hour_kind_code[$i].'_add';	
}

$query_all='SELECT hours_kind.id,hours_kind.stud_cnt,hours_kind.stud_cnt_add, subjects.name as subj_name, specialities.name as spec_name, levels.name as level_name, study_groups.name as group_name, study_groups.man_cnt as man_cnt,hours_kind.hours_kind_type,hours_kind.groups_cnt,hours_kind.comment,hours_kind.year_id,hours_kind.part_id'.
$q_fields_list.',
	time_intervals.name as year_name, time_parts.name as part_name,hours_kind_type.name as hours_kind_type_name,hours_kind.on_filial  
	FROM hours_kind left join subjects on hours_kind.subject_id=subjects.id 
					left join specialities on specialities.id=hours_kind.spec_id 
					left join levels on levels.id=hours_kind.level_id 
					left join study_groups on study_groups.id=hours_kind.group_id
					left join time_intervals on time_intervals.id=hours_kind.year_id
					left join time_parts on time_parts.id=hours_kind.part_id
					left join hours_kind_type on hours_kind_type.id=hours_kind.hours_kind_type
	 where kadri_id='.$kadri_id.' and time_intervals.name="'.$year.'" and time_parts.name="'.$part.'" 
	 			and hours_kind_type in('.$hours_kind_type_selected.') limit 0,100 ';
       //echo $query_all;
          if ( $res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) 
		  	{/*echo '<hr><font size=-1> Найдено записей: '.mysql_numrows($res_all).'</font><br>';*/          	}
          else {
		   	if (!isset($_GET['save']) && !isset($_GET['print'])) 
			   	{echo '<hr><font color="red" size=-1>Записей за указанный семестр нет</font>';}
			   }
//echo $query_all;
//для расчета нагрузки - список показателей видов часов

echo '<div align="center"> '.strtoupper($part).' СЕМЕСТР</div>';

$cols_disable=0;//число скрытых столцов
$not_print_cols=0; //число непечатаемых столбцов (в нумерации столбцов)

//echo ' kind_type_defaults='.$kind_type_defaults;
echo '<form name="h_copy_'.$part_num.'" method="POST" action="?kadri_id='.$kadri_id.'&year='.$_GET['year'].'&export='.$kind_type_defaults.'"><table name=tab1 border=1 cellpadding="0" cellspacing="0" width="">
	<tr align="center" class="title">';
	if (!isset($_GET['save']) && !isset($_GET['print']) && $_SESSION['task_rights_id']>=3) {
		
		echo '<td width="50"><input type=checkbox name="checkbox_del_all'.$part_num.'" title="для копирования нагрузки" onClick="javascript:mark_all(this.name,'.$part_num.');"> </td>
			<td width="50"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}
	if ($_SESSION['task_rights_id']<=2) {$cols_disable=2;}
	
	echo '<td width="20">№</td>';
	echo '<td width="230">НАИМЕНОВАНИЕ ДИСЦИПЛИН</td>';//250
	echo '<td width="40"><img src="images/hours_pics/facul.gif" border=0 title="Факультет"></td>';
	echo '<td width="40"><img src="images/hours_pics/special.gif" border=0 title="Cпециальность"></td>';
	echo '<td width="40"><img src="images/hours_pics/kurs.gif" border=0 title="Курс"></td>';
	echo '<td width="40"><img src="images/hours_pics/gr_number.gif" border=0 title="Число групп"></td>';
	echo '<td width="40"><img src="images/hours_pics/stud_number.gif" border=0 title="Количество студентов"></td>';
	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		//echo '<td width="60">группа</td>';
		echo '<td width="100">тип нагрузки</td>';
		echo '<td width="60">комментарий</td>';
		}
	else {$not_print_cols=4;}
	//вывод видов часов (лекция, практ, лабор...)
	for ($i=0;$i<sizeof($hour_kind_name);$i++) 
		{echo '<td width=35><img src="images/hours_pics/'.$hour_kind_code[$i].'.gif" alt="'.$hour_kind_name[$i].'"></td>';}
	
	echo '<td width=40><img src="images/hours_pics/sum.gif" alt="Всего часов"></td>';

	$add_col=0;	//добавочный номер для столбца
	if (isset($_GET['filial_flag']) && $_GET['filial_flag']=='on') //с учетом филиалов *1,5
		{echo '<td><img src="images/hours_pics/add_filials.gif" alt="надбавка 50% к всего"></td>';
		//$cols_disable--;$not_print_cols--;
		$add_col=1;
		}
	echo '</tr><tr align="center" class="main_">';

	// нумерация столбцов списочной таблицы
	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		for ($i=1;$i<sizeof($hour_kind_name)+13-$cols_disable+$add_col;$i++) { echo '<td>'.$i.'</td>';}		
	}
	else {
		for ($i=1;$i<sizeof($hour_kind_name)+13-$not_print_cols+$add_col;$i++) { echo '<td>'.$i.'</td>';}
	}
	echo '</tr>';
//	echo '<h3>$total='.$total.'</h3>';
	$total=0;
	$sumCol=array();	//итоги по столбцам
	for ($i=0;$i<sizeof($hour_kind_code);$i++) 
		{ $sumCol[$i]=0;}

	$j=0;$sumColFilial=0;
	while ($tmpval=mysql_fetch_array($res_all))	//вывод показателей
	{
		$sum=0;$j++;
		
		echo '<tr align="left" class="main_">';
		if (!isset($_GET['save']) && !isset($_GET['print']) && $_SESSION['task_rights_id']>=3) {
			
			echo '
				<td width="50"><input type=checkbox name="checkbox_h_copy_'.$tmpval['id'].'" title="для копирования нагрузки"> </td>
				<td align="center"> <a href=javascript:del_confirm('.$kadri_id.','.$tmpval['id'].','.$tmpval['year_id'].') title="Удалить">
				<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
				<a href="s_hours.php?kadri_id='.$kadri_id.'&hours_id='.$tmpval['id'].'&tab=2" title="Правка">
				<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		//else {}
		echo '<td width="">'.$j.'&nbsp;</td>';
		echo '<td width="230" align=left>'.$tmpval['subj_name'].'&nbsp</td>';
		echo '<td width="30">&nbsp; ИРТ</td>';
		echo '<td width="30" align=center>'.$tmpval['spec_name'].'&nbsp</td>';
		echo '<td width="30" align=right>'.$tmpval['level_name'].'&nbsp</td>';
		
			 if (trim($tmpval['man_cnt'])!='') {$tmpval['man_cnt']=' ( '.$tmpval['man_cnt'].'чел. )';}
			 else {$tmpval['man_cnt']='';}
		

		
		if ($tmpval['groups_cnt']==0) {$tmpval['groups_cnt']='';}
		echo '<td align=right>'.$tmpval['groups_cnt'].'&nbsp</td>';
		
		if ($tmpval['stud_cnt']==0) {$tmpval['stud_cnt']='';}
		echo '<td align=right>'.numLocal($tmpval['stud_cnt']+$tmpval['stud_cnt_add']).'&nbsp</td>';//число студентов
		
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
			//echo '<td>'.$tmpval['group_name'].$tmpval['man_cnt'].'&nbsp</td>';

			/*if ($tmpval['hours_kind_type']==0) {$tmpval['hours_kind_type']='основная';}
			else {$tmpval['hours_kind_type']='дополнительная';}*/
			echo '<td>'.$tmpval['hours_kind_type_name'].'&nbsp</td>';
		 
		 	echo '<td>'.$tmpval['comment'].'&nbsp</td>';}
		// вывод видов нагрузки (лекции, практики ...)
		$kind_code_join=0;	// нагрука бюджетная и коммерческая
		for ($i=0;$i<sizeof($hour_kind_code);$i++) 
			{
			$kind_code_join=$tmpval[$hour_kind_code[$i]]+$tmpval[$hour_kind_code[$i].'_add'];
			echo '<td align="center">'.numLocal(number_format($kind_code_join,1)).'&nbsp</td>';
			$sum+=$kind_code_join;
			$sumCol[$i]+=$kind_code_join;
			}
		
		echo '<td align="center">'.number_format($sum,1,',','').'&nbsp</td>';
		
		if (isset($_GET['filial_flag']) && $_GET['filial_flag']=='on')	//вывод с учетом выезда
			if ($tmpval['on_filial']==1) 
			{echo '<td align="center">'.number_format($sum*0.5,1,',','').'&nbsp</td>';$sumColFilial+=$sum*0.5;}
			else {echo '<td align="center">&nbsp</td>';}
		
		echo '</tr>';$total+=$sum;
	$part_id=$tmpval['part_id'];
	$year_id=$tmpval['year_id'];
	
	}
echo '<tr class=text style="font-weight:bold;white-space:nowrap;">';//<td>&nbsp;</td><td>&nbsp;</td>';

if (!isset($_GET['save']) && !isset($_GET['print']) && $_SESSION['task_rights_id']>=3)
{echo '<td colspan=2>&nbsp;</td>';}

echo '<td  align="center" colspan=2>&nbsp; Итого часов: </td><td>&nbsp;</td>';
for ($i=0;$i<6-$not_print_cols/2;$i++) {echo '<td  align="center">&nbsp;</td>';}

$sumTimeTibleCheck=0;//для сверки с расписанием
for ($i=0;$i<sizeof($hour_kind_code);$i++) 
	{if ($sumCol[$i]>0) {
		//подсчет суммы (Лекции+ЛабРаб+Практики) для сверки с расписанием
		if ($i<3 && !isset($_GET['save']) && !isset($_GET['print'])) //считаем первые 3 столбца итогов
		{$sumTimeTibleCheck+=$sumCol[$i];
//-------------------------
$sum_l_h=0; 

//.$hour_kind_name[$i]

	switch ($hour_kind_name[$i]) {
	case 'лаборатор. занятий':	//
		$sum_l_h=hour_cnt($lect_id,$year_id,$part_id,'л/р');
		break;
	case 'лекции'://
		$sum_l_h=hour_cnt($lect_id,$year_id,$part_id,'л');
		break;
	case 'практич.':	//
		$sum_l_h=hour_cnt($lect_id,$year_id,$part_id,'пр');
		break;	
								}
//-------------------------	
		if ($sumCol[$i]!=$sum_l_h) {
		   echo '<td  align="center">'.number_format($sumCol[$i],1,',','').'<span class=warning><sup>'.$sum_l_h.'</sup></span></td>';}
		else {echo '<td  align="center">'.number_format($sumCol[$i],1,',','').'</td>';}
		}
		else {echo '<td  align="center">'.number_format($sumCol[$i],1,',','').'</td>';}		
		
	} 
		
	 else {echo '<td  align="center">&nbsp;</td>';}
	}
echo '<td  align="center">'.number_format($total,1,',','').'</td>';

//учет при выборе "с учетом Филиалов"
if (isset($_GET['filial_flag']) && $_GET['filial_flag']=='on')	 
	{$total_year_fil+=$total+$sumColFilial;
	if ($sumColFilial>0)
		{echo '<td  align="center">'.$sumColFilial.'&nbsp;</td>';}
		else {echo '<td  align="center">&nbsp;</td>';}
	}

echo '</tr>';
$total_year+=$total;

echo '</table>';	//окончание формы часов

	if (!isset($_GET['save']) && !isset($_GET['print']) && $lect_control) {
?>

<select name=type_copy style="width:300;">
<option value=0> копировать с перемещением (удаляем у одного- добавляем другому) </option>
<option value=1> только копирование (сохраняем у одного и добавляем другому)</option>
</select> 

<select id="teach_name" name="teach_name" style="width:200;"><?php 
		if ($_SESSION['task_rights_id']==4) {
		 $listQuery='select k.id,k.fio
		from kadri k left join kadri_in_ptypes kpt on k.id = kpt.kadri_id left join person_types pt on pt.id=kpt.person_type_id 
			where pt.name_short like "%ППС%" 
			order by k.fio ';}
		 else {$listQuery='select k.id,k.fio from kadri k
		 left join kadri_in_ptypes kpt on k.id = kpt.kadri_id left join person_types pt on pt.id=kpt.person_type_id  
			where pt.name_short like "%ППС%" and k.id="'.$kadri_id.'" 
			order by k.fio ';}
		echo getFrom_ListItemValue($listQuery,'id','fio','kadri_id'); 
		?></select>
		
<select name="year_list" style="width:100;"> 
		<option value="0">год</option>
		<?php
		$query='select id,name from time_intervals order by name desc';
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if (isset($tmpval)) { if ($tmpval['year_id']==$a['id']) {$select_val=' selected';} } 
			 else {	
			  	if (isset($_POST['year_list'])) { if ($_POST['year_list']==$a['id']) {$select_val=' selected';}}
			  	else
				  if (isset($def_settings)) { if ($def_settings['year_id']==$a['id']) {$select_val=' selected';}}	
			  		
				  } 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
			} 
		?>
</select>
<select name="part_list" style="width:100;"> 
		<option value="0">семестр</option>
		<?php
		$query='select id,name from time_parts order by name desc ';
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if (isset($tmpval)) { if ($tmpval['part_id']==$a['id']) {$select_val=' selected';} } 
			 else {	
			  	if (isset($_POST['part_list'])) { if ($_POST['part_list']==$a['id']) {$select_val=' selected';}}
			  	else
				  if (isset($def_settings)) { if ($def_settings['part_id']==$a['id']) {$select_val=' selected';}}
					  	
				  } 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
			} ?>
</select>
<input type=button value="Ok" onClick="javascript:test_copy(<?php echo $part_num;$part_num++; ?>);">
</form>
<?php

$sum_l_h=0; 
$sum_l_h=hour_cnt($lect_id,$year_id,$part_id,'');
//echo 'lect_id='.$lect_id.', year='.$tmpval['year_id'].', part='.$tmpval['part_id'].', sum_l_h='.$sum_l_h;
if ($sum_l_h!=$sumTimeTibleCheck) 
{echo '<div class=warning> за <u>'.$part.'</u> семестр  ошибка сверки с расписанием. в расписании='.$sum_l_h.', в нагрузке='.$sumTimeTibleCheck.'</div>';}
else { echo '<div class=success>за <u>'.$part.'</u> семестр  сверено с расписанием</div>';}

} 
//echo '<div align="left"> Итого часов: '.$total.'</div>'; 
} 

//include ('sql_connect.php');


//------------------------------------------------------------------------------------

if (isset($_GET['export'])){
    //print_r($_POST); echo '';
    $max_id=0;
    $query='select max(id) as max_id from hours_kind';
    $res=mysql_query($query);
    $a=mysql_fetch_array($res);
    $max_id=$a['max_id'];

    $err=false;
    while (list($key, $value) = each ($_POST)) {
 	    if (strstr($key,"checkbox_h_copy_")) {
            $mail_id=substr($key,strpos($key,'copy_')+5);	//выбираем ID из названий чекбоксов

            // выборка необходимых полей для экспорта нагрузки
            $query='SELECT `subject_id` , `spec_id` , `level_id` , `group_id` , `hours_kind_type` , `groups_cnt` , `stud_cnt`, `stud_cnt_add` ';
            for ($i = 0; $i < sizeof($hour_kind_code); $i++) {
                $query.=', '.$hour_kind_code[$i].', '.$hour_kind_code[$i].'_add';
            }
            $query.=' FROM `hours_kind` WHERE `id` ='.$mail_id.' limit 0,1';
            if (!($res=mysql_query($query)) ) {
                $err=true;
            };
            $a=mysql_fetch_array($res,MYSQL_ASSOC);

            //echo '<p>'.$query;
            $query='SELECT kadri.fio_short, time_intervals.name as year_name , time_parts.name as part_name, `hours_kind`.`comment`,`hours_kind`.`id`
            FROM `hours_kind`
            left join kadri on kadri.id=hours_kind.kadri_id
            left join time_intervals on time_intervals.id=hours_kind.year_id
            left join time_parts on time_parts.id=hours_kind.part_id
              WHERE hours_kind.id ='.$mail_id.' limit 0,1';
            //$res=mysql_query($query);
            if (! ($res=mysql_query($query)) ) {
                $err=true;
            };

            $a_new_val=mysql_fetch_array($res,MYSQL_ASSOC);
            //print_r($a_new_val);

            // формирование списка полей для вставки
            $str_fields='';$str_values='';
            while (list($key_, $value_) = each ($a)) {
                $str_fields .= ', `'.$key_.'`';
                $str_values .= ', "'.$value_.'"';
            }
            //print_r($a);
            //echo ' <b>str_fields</b>='.$str_fields.'<br> <b>str_values</b>='.$str_values.'<br>';

            $max_id++;
            $comment='';
            $comment=$a_new_val['comment'].' копия от '.$a_new_val['fio_short'].', '.$a_new_val['year_name'].','.$a_new_val['part_name'].';';
            $query='insert into hours_kind (id,kadri_id,year_id,part_id,comment'.$str_fields.')
                values("'.$max_id.'", "'.$_POST['teach_name'].'","'.$_POST['year_list'].'", "'.$_POST['part_list'].'", "'.$comment.'" '.$str_values.')';
            //echo $query;
            //mysql_query($query);
            if (!mysql_query($query)) {
                $err=true;
                echo '<div class=warning> ошибки добавления при экспорте, вожможно производится копирование только с одним сотрудником</div>';
            };

            if ($_POST['type_copy']==0 && !$err) // копировать с перемещением, т.е. у источника удаляем
            {	$query='delete from hours_kind where id='.$a_new_val['id'].'';
                //mysql_query($query);
                if (!mysql_query($query)) {
                    $err=true;
                    echo '<div class=warning> ошибки удаления при экспорте.</div>';
                };
            }
        }
    }

    if ($err==true)	{
        echo '<div class=warning> Произошли ошибки при копировании нагрузки </div>';
    } else {
        echo '<div class=success> Копировании нагрузки успешно</div>';
    }
}

//-----------------------------------------------------------------------------------------

if (!isset($kadri_id) or $kadri_id=="")
{echo $kadri_id."Не найден преподаватель. <a href='s_hours.php'>Вернуться к выбору преподавателя...</a>";exit;}

$fio="";            //

$fio_res=mysql_query("select kadri.fio,kadri.fio_short,dolgnost.name as dolgnost_name from kadri left join dolgnost on kadri.dolgnost=dolgnost.id where kadri.id=".$kadri_id." limit 0,1");
//echo "select fio from kadri where id=".$kadri_id;
$a=mysql_fetch_array($fio_res);
$fio=$a['fio'];
$fio_short=$a['fio_short'];
$dolgnost=$a['dolgnost_name'];
if (trim($dolgnost)=='') {$dolgnost='должность не указана ';}
//echo 'преподаватель: <b>'.$fio.'</b>';


//выбираем найстройки по умолчанию (год, семестр)
/*
$query_all='SELECT settings.year_id,time_intervals.name as year_name FROM settings 
	inner join time_intervals on time_intervals.id=settings.year_id where 1 limit 0,1';
*/
$query_all = "
    select
        time_intervals.name as year_name,
        time_intervals.date_start,
        time_intervals.date_end,
        time_intervals.id as year_id
    from
        time_intervals
    where
        time_intervals.id = ".CUtils::getCurrentYear()->getId();
//echo $query_all;exit();

//---------------------------

if (isset($_GET['year']) && ($_GET['year']>0 && $_GET['year']<10)) {//$def_settings['year_name']=$_GET['year'];
//echo '!!!!';
$query_all='SELECT time_intervals.id as year_id,time_intervals.name as year_name FROM time_intervals where id="'.$_GET['year'].'" limit 0,1';
//echo '$query_all='.$query_all;
}
//echo '1111111';

//---------------------------

if ( $res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {$def_settings=mysql_fetch_array($res_all);}
$year_name = "";
if ($year>0) $year_name=getScalarVal('SELECT ti.name FROM time_intervals ti WHERE (ti.id = '.$year.')');

if (isset($def_settings)) {
	//echo '<span align="center">   <u>год: '.$def_settings['year_name'].'</u></span>';
//------------------------------------------------------------------------------------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo "<div style='text-align:right;'>
			 	<a class=text href='?".$_SERVER["QUERY_STRING"]."&save&attach=doc' title='Выгрузить' target='_blank'>Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
				<a class=text href='?".$_SERVER["QUERY_STRING"]."&print' title='Распечатать' target='_blank'>Печать</a></div>";}


echo '
<table width="" border="0">
  <tr valign="top" align="left">
    <td width="300">&nbsp;</td>
    <td align="center"><b>ПЛАН</b></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr valign="top" class="text">
    <td>УФИМСКИЙ ГОСУДАРСТВЕННЫЙ <br>
		АВИАЦИОННЫЙ ТЕХНИЧЕСКИЙ <br>
		УНИВЕРСИТЕТ
</td>
    <td>годовой нагрузки <b><u>'.$dolgnost.' </u></b>
	<div align=right><sup>должность преподавателя</sup></div>
	 <div align=right>';
	 	if (!isset($_GET['save']) && !isset($_GET['print']) && $_SESSION['task_rights_id']>=3) {
		
	 	//$query_string=reset_param_name($query_string,'kadri_id');
	 	
		 ?><select id="kadri_id" name="kadri_id" 
		 onChange="javascript:window.location.href='?kadri_id='+this.options[this.selectedIndex].value+'&<?php echo  reset_param_name_ARR($query_string,array('kadri_id','export'));?>';" title="Список сорудников с ролью ППС"><?php 
		$listQuery="select k.id,k.fio_short as name 
		 	from kadri k	
			       left join kadri_in_ptypes kpt on k.id = kpt.kadri_id left join person_types pt on pt.id=kpt.person_type_id 
			where pt.name_short like '%ППС%' 
			order by k.fio_short";
		//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		echo getFrom_ListItemValue($listQuery,'id','name','kadri_id'); 
		?></select>	<?php	 	 
	 	}
		else { echo '<b><u>'.$fio_short.'</u></b>';}
	 echo '</div>
	 <div align=right><sup>Ф.И.О. преподавателя</sup></div> </td>
    <td><p> кафедры <b><u>АСУ</u></b><br>
	 <sup>&nbsp;&nbsp;&nbsp;&nbsp; наименование кафедры </sup><br>
	 на ';
	 if (!isset($_GET['save']) && !isset($_GET['print'])) {
	 	//$query_string=reset_param_name($query_string,'year');
	 	//echo $query_string;
		 ?><select id="year" name="year" 
		 onChange="javascript:window.location.href='?year='+this.options[this.selectedIndex].value+'&<?php echo reset_param_name($query_string,'year');?>';"><?php 
	$listQuery="select id,name from time_intervals order by name desc";
	
	//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
	if (!isset($_GET['year'])) {$_GET['year']=$def_settings['year_id'];}

	echo getFrom_ListItemValue($listQuery,'id','name','year'); 
	?></select>	<?php }
	 else {
	 if ($year_name!='') echo '<b><u>'.$year_name.'</u></b>';
	 else echo '<b><u>'.$def_settings['year_name'].'</u></b>'; }
	 
	 
	 echo 'учебный год<br>
      </p>    </td>
  </tr>
</table>';

if (!isset($_GET['save']) && !isset($_GET['print'])) {
		
		$select_val='';
		
		$query='select id,name from hours_kind_type order by id';
		$res=mysql_query($query);
		?>
		<form name=kind_type_form action="" method="get">
        <?php if ($_SESSION['task_rights_id']>=3) { ?><a href="s_hours.php?tab=2&kadri_id=<?php echo $kadri_id;?>" class=button>
            <img src="images/new_elem.gif" alt="Добавить" border="0">добавить</a> <?php } ?>	&nbsp;
		<?php

		while ($a=mysql_fetch_array($res)) 
			{$select_val='';
			 if (isset($_GET['kind_type'.$a['id']]) && $_GET['kind_type'.$a['id']]=='on') {$select_val=' checked'; } 
			 echo '<label><input type=checkbox name="kind_type'.$a['id'].'"'.$select_val.'>'.$a['name'].'</label> &nbsp;';
			}
		?>
		<label><input type=checkbox name="filial_flag" <?php if (isset($_GET['filial_flag']) && $_GET['filial_flag']=='on') {echo ' checked';} ?>> 
		<font color=red><b>с учетом выезда</b></font></label>
		<?php
		echo '	
		<input type=hidden name=kadri_id value="'.$kadri_id.'">
		<input type=hidden name=year value="'.$_GET['year'].'">
		<input type=submit value=ok>
				</form>';
}

//-------------------------------------------------------------------------------------------------------------
	//найстройки по умолчанию из сохраненной таблицы
	
	if ($year_name != '') {
		print_hours($year_name,'осенний');
		echo '<br>';
		print_hours($year_name,'весенний');
	} else {
		print_hours($def_settings['year_name'],'осенний');
		echo '<br>';
		print_hours($def_settings['year_name'],'весенний');
	}
	
	echo '<b>Всего за год: <u>'.numLocal(number_format($total_year,1,',',''));
		//с учетом выезда
		if (isset($_GET['filial_flag']) && $_GET['filial_flag']=='on') {echo '<small> ( с учетом филиалов: '.numLocal(number_format($total_year_fil,1,',','')).')</small> ';}	
	echo '</u></b>';
	}
else 
	//нет настроек по умолчанию
	echo '<h4 class=warning>Необходимо указать настройки по умолчанию</h4>';
?>
<br><br>
<table cellspacing="0" cellpadding="0" border="0" class=text width=100%><tr>
  	  <td></td>
	  <td>&quot;___&quot;___________201__г.</td>
      <td width="20">&nbsp;</td>
	  <td>Зав.&nbsp;кафедрой_____________Куликов&nbsp;Г.Г.</td>
      <td width="20">&nbsp;</td>
      <td>Декан&nbsp;факультета_____________Юсупова&nbsp;Н.И.</td>
      <td width="20">&nbsp;</td>
      <td style="WORD-WRAP:normal;WHITE-SPACE:nowrap;">Преподаватель____________<?php echo $fio_short;?></td>
</tr></table>

<?php if (!isset($_GET['save']) && !isset($_GET['print'])) 
{
?>
<div class=text> <p><b>Примечание:</b><br>
сверка с расписанием производится только по сумме Лекции+ЛабРаботы+Практики, не включаются Курс.Проекты и пр.
</div>
<?php } ?>

<?php include('footer.php'); ?>