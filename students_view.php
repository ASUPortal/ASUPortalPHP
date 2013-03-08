<?php
include 'authorisation.php';

//phpinfo();
function add_stud_gr($stud_id,$new_gr_id)
{
$err=false;
if ($stud_id>0) {
	$query='select group_id, prev_group_ids from students where id="'.f_ri($stud_id).'" limit 0,1';	
	$res=mysql_query($query);
	$a=mysql_fetch_array($res);
	//print_r($a);echo '<hr>';
	//echo '<div>пред.гр='.$a['group_id'].'</div>';
	if (intval($a['group_id'])>0) { //если есть тек.группа у студента
		$end_sg='';
		if ($a['prev_group_ids']!='') {$end_sg=';';	}
		$query='update students set group_id="'.$new_gr_id.'", prev_group_ids="'.date("Y-m-d").'+'.$a['group_id'].$end_sg.
				$a['prev_group_ids'].'" where id="'.$stud_id.'"';	
	}
	else { $query='update students set group_id="'.$new_gr_id.'" where id="'.$stud_id.'"';}
	//echo '<div>'.$query.'</div>';
	if ($res=mysql_query($query) && mysql_affected_rows()==1) {return true;}
	else {return false;}
}	
} 
function del_student($stud_id)
{
$err=false;
	if ($stud_id>0) {
	 	$cur_st_cnt=0;	//число ссылок на студента в дипломах
		$query='select count(*)as cur_st_cnt from diploms where student_id="'.$stud_id.'"';	
	 	$res=mysql_query($query);
	 	$cur_st_cnt=mysql_result($res,'cur_st_cnt');
	 	//echo '<div>'.$query.'--'.$cur_st_cnt.'</div>';
		if ($cur_st_cnt==0){//ссылок нет, начинаем удаление
			$query='delete from students where id="'.$stud_id.'"';	
		 	$res=mysql_query($query);
			if (mysql_affected_rows()==0) {$err=true;}
		}
		else {$err=true;}
	 	
	} else {$err=true;}
return !$err;	
} 
//------------------------------------------------------------
//include ('sql_connect_empty.php');
$main_page='students_view.php';
$page=1;
$q='';		//отбор по группе студентов
$q='';			//строка поиска
$pageVals=20;	//число данных о студенте на странице по умолчанию
$query_string=$_SERVER['QUERY_STRING'];
$group_id=0;	//отбор по ID группы
$filt_str_display="";
$stype='asc';		//тип сортировки столбца
$sort=1;

if (isset($_GET['q'])) {$q=$_GET['q'];}
if (isset($_GET['group_id']) && $_GET['group_id']>0) {$group_id=$_GET['group_id'];$filt_str_display=$filt_str_display.' группе;';}

if (isset($_GET['kadri_id'])) {$kadri_id=$_GET['kadri_id'];$group_id=$_GET['group_id'];$filt_str_display=$filt_str_display.' дипл.руководителю;';}

if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}
if (isset($_GET['pageVals']) && $_GET['pageVals']<=99 && $_GET['pageVals']>=1) {$pageVals=$_GET['pageVals'];}
if (isset($_GET['stype']) && $_GET['stype']=='desc') {$stype=$_GET['stype'];}
if (isset($_GET['sort'])) {$sort=$_GET['sort'];}


//----------------------------------------------------------
if (isset($_GET['type'])) {
    if ($_GET['type']=='del') {
        //echo '!!!!!!!del!!!!!!';
        $query='delete from students where id="'.$_GET['item_id'].'"';
        //echo $query;
        $res=mysql_query($query);

        $query_string=reset_param_name($query_string,'type');
        $query_string=reset_param_name($query_string,'item_id');
        header('Location:'.$main_page.'?'.$query_string);
    }
}

if (array_key_exists("save", $_GET)) {
    if ($_GET['save']==1) {
        header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
        header('Content-Type: application/msword; charset=windows-1251; format=attachment;');
        header('Content-Disposition: attachment; filename=orders.doc');
        //table_print($result,'select',$tablename);return;
    }
}
if (isset($_GET['toarhiv']) && isset($_GET['item_id'])	)
{
	//помещение тек.группы студента в архив с предложением выбора тек.гр
	$query='select group_id, prev_group_ids from students where id="'.f_ri($_GET['item_id']).'" limit 0,1';	
	$res=mysql_query($query);
	$a=mysql_fetch_array($res);
	if (intval($a['group_id'])>0) {
		$end_sg='';
		if ($a['prev_group_ids']!='') {$end_sg=';';	}
		$query='update students set group_id="0", prev_group_ids="'.date("Y-m-d").'+'.$a['group_id'].$end_sg.
				$a['prev_group_ids'].'" where id="'.$_GET['item_id'].'"';	
		$res=mysql_query($query);
	 	
		
	}
	header('Location:'.$main_page.'?'.reset_param_name ($query_string,'toarhiv'));
 
} 

//------------------------------------------------------------------------

include ('master_page_short.php');

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
        time_intervals.date_end,
        time_intervals.id as year_id
    from
        time_intervals
    where
        time_intervals.id = ".CUtils::getCurrentYear()->getId();
if ($res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
    $def_settings=mysql_fetch_array($res_all);
}

$archiv_query=' and (sg.year_id="'.$def_settings['year_id'].'" or sg.year_id is null or sg.year_id=0)';
if (isset($_GET['archiv'])) {$archiv_query=' and (sg.year_id!="'.$def_settings['year_id'].'" and sg.year_id is not null and sg.year_id!=0)';}

?>
<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}
.err {color:red;font-family:Arial;}
</style>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">

<script language="JavaScript">
var main_page='students_view.php';	//for redirect & links
function del_confirm(id,num)
{
	 if (confirm('Удалить данные о студенте: '+num+' ?')) 
	 	{window.location.href=main_page+'?item_id='+id+'&type=del'+'<?php echo '&page='.$_GET['page'].'&q='.$_GET['q'];?>';} 
} 
function group_id(q)
{
 if (document.getElementById('group_id').value!=0)
	{ window.location.href=main_page+"?group_id="+document.getElementById('group_id').value+"&"+q;}
 else {window.location.href=main_page;}
} 
function go2search()
{
 	var search_query='';
 	try {search_query=document.getElementById('q').value;}
 	catch (e) {search_query=document.all['q'].value;}
 	
 	if (search_query!='') {window.location.href=main_page+'?&q='+search_query;}
 	else {alert('Введите строку поиска');}
} 
function day_now()
{
 //alert('сегодня');
 var today_date=new Date();
 var date=today_date.getDate();
 var month=today_date.getMonth()+1;
 var year=today_date.getFullYear();
 var date_string='';
 //alert(day);
 if (date<10) {date_string='0'+date;} else {date_string=date;}
 if (month<10) {date_string+='.'+'0'+month;} else {date_string+='.'+month;}
 date_string+='.'+year;
 document.getElementById('date_order').value=date_string;
 
} 
function pageVals(q)
{
 	var pageCnt= parseInt(document.getElementById('pageVals').value);
 	if (pageCnt>0 && pageCnt<100) {document.location.href=main_page+'?pageVals='+pageCnt+'&'+q;}
 	else {alert('Введите значение с 1 до 99.');}
 	
	 //if (pageCnt>0 && pageCnt<100) {alert('ok');}
	 
	//alert(pageCnt);
} 
function check_form()
{
var err=false;
var msg='Не заполнены поля информации о студенте: ';

if (document.order_form.student_name.value=='') {err=true;msg=msg+' ФИО;';}
if (document.order_form.group_id.value==0) {err=true;msg=msg+' группа;';}

//num_order
if (err==true) {alert(msg);} else {document.order_form.submit();}
 
} 
function mark_all(click_name)
{//
	
	var item_cnt=0;
	var chech_val='';
	var mark_val;
	
	var ignore_items_cnt=0;	//число элементов выбора справочников формы
	//alert(click_name);
	//try {mail_cnt=document.getElementById('mail_cnt').value;}
	//catch (e) {mail_cnt=document.mail_list_form.elements.length;}
		item_cnt=document.order_list.elements.length-ignore_items_cnt;
		//alert(item_cnt);
		try {mark_val=document.getElementById(click_name).checked;}
		catch (e) {	mark_val=document.order_list.checkbox_del_all_1.checked;  }
			  
	 	for (i=0;i<item_cnt;i++) { document.order_list.elements[i].checked=mark_val; }  
		
}  
function test_copy()
{
 	var msg='';
 	var act_value=document.order_list.act_type.value;
 	var act_title=document.order_list.act_type.options[document.order_list.act_type.selectedIndex].text;
 	
	if (document.order_list.group_id.value==0 && act_value!='del_selected') {msg=' группа;';}

	//alert(document.order_list.group_id.options[document.order_list.act_type.selectedIndex].text);
	//alert(act_title);
	if (msg!='')  //для 
	{alert('Не указаны: '+msg+' \n -продолжение возможно только после исправления всех указанных ошибок');}
	else {
	 if (confirm('Вы уверены в: "'+act_title+'"')) {document.order_list.submit();}
	 } 
 // onChange="javascript:window.location.href=\'?tab=1&kadri_id=\'+this.options[this.selectedIndex].value;"
}
function gr_oper(act_value)
{
  if (act_value=='') {document.order_list.group_id.style.display='none';document.order_list.act_btn.disabled=true;}
  else {document.order_list.act_btn.disabled=false;}	 
  
  if (act_value=='del_selected') 
  	{document.order_list.group_id.style.display='none';document.order_list.group_id.selectedIndex=0;}
  
  if (act_value=='gr_ch_selected') 
  	{document.order_list.group_id.style.display='';}
  else {document.order_list.group_id.style.display='none';}
  
 
} 
</script>
<?php
//групп операции 	-----------------------------------------------
//перемещение в нов.группу
if (isset($_POST['act_type']) && $_POST['act_type']=='gr_ch_selected'){//print_r($_POST); echo '';

	if (isset($_POST['group_id']) && intval($_POST['group_id'])>0) 
	{
	$group_id= intval($_POST['group_id']);
	$err=false;$stud_id=0;
	while (list($key, $value) = each ($_POST)) {
	 	if 	  (strstr($key,"checkbox_h_copy_") && $value='on') {
		
		$stud_id=substr($key,strpos($key,'copy_')+5);
		//echo $stud_id.'='.$value.'<br>';
		if (!add_stud_gr($stud_id,$group_id)) {$err=true;echo error_msg('ошибка в записе с ID студента:'.$stud_id);}
		}
	  }
	if (!$err) {echo success_msg('перемещение в группу с архивом текущей прошло успешно.');}
	}

}
//удаление нес.студентов
if (isset($_POST['act_type']) && $_POST['act_type']=='del_selected'){
	$err=false;$stud_id=0;
	while (list($key, $value) = each ($_POST)) {
	 	if 	  (strstr($key,"checkbox_h_copy_") && $value='on') {
		
		$stud_id=substr($key,strpos($key,'copy_')+5);
		//echo $stud_id.'='.$value.'<br>';
		if (!del_student($stud_id)) {$err=true;
		echo error_msg('ошибка при удалении записи ID студента:'.$stud_id.'. Сначала удалите ссылку на запись в дипломах.');}
		}
	  }
	if (!$err) {echo success_msg('удаление записей прошло успешно.');}
 
  
} 

//-----------------------------------------------------------

//добавление данных о студенте
//echo '<br><br>';
if (isset($_POST['student_name']) & isset($_GET['type']))
{
	if ($_POST['student_name']!='' & $_POST['group_id']!=0) 
	{
	 
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) 
		 {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?';
			 if ($_GET['type']=='add') 
			 	{$query_string='group_id='.$_POST['group_id'].'&'.reset_param_name($query_string,'group_id');}
			 //else {}
			 $onEditRemain_text=$onEditRemain_text.$query_string;
		 
		 $onEditRemain_text=$onEditRemain_text.'">по ссылке</a>';}
		 
		 	 
	 //обновление студента
	 if ($_GET['type']=='edit' & isset($_GET['item_id']) & $_GET['item_id']!='') {
		 echo 'Правка данных о студенте.';
		 $query="update students set fio='".f_ri($_POST["student_name"])."',group_id='".f_ri($_POST["group_id"])."',
		 	bud_contract='".f_ri($_POST["bud_contract"])."',telephone='".f_ri($_POST["telephone"])."',
			comment='".f_ri($_POST["comment_order"])."',stud_num='".f_ri($_POST["stud_num"])."' where id='".f_ri($_GET["item_id"])."'";

		 if ($res=mysql_query($query)) {

		  	echo '<div class=success> Запись обновлена  успешно.'.$onEditRemain_text.'</div><br>';}
		 else {echo '<div class="err">Данные студента не обновлены .<p>&nbsp;</div>';$err=true;}
		 //echo $query;
	 }
	 
	 //новый студент
	 if ($_GET['type']=='add') {
		 echo 'Добавление данных о студенте.';

		 $query="insert into students(fio,group_id,comment,bud_contract,telephone,stud_num) 
		 	values('".f_ri($_POST["student_name"])."','".f_ri($_POST["group_id"])."','".f_ri($_POST["comment_order"])."',
			 '".f_ri($_POST["bud_contract"])."','".f_ri($_POST["telephone"])."','".f_ri($_POST["stud_num"])."')";
		 $res_news=true;
		 
		 if ($res=mysql_query($query)) {		
		 echo '<div class=success> Запись "'.$_POST['student_name'].'" добавлена успешно.'.$onEditRemain_text.'</div>';}
		 else {echo '<div class="err">Данные о студенте не добавлены. Возможно такое ФИО в паре с группой там уже есть<p>&nbsp;</div>';$err=true;}
	 
	 
	 }
	 }
	else {echo '<div class="err">Часть обязательных данных не заполнено .<br>&nbsp;</div>';}
	if (!$err && !$onEditRemain) {echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}
} 

if (isset($_GET['type'])) {
    if ($_GET['type']=='edit') {
        if (isset($_GET['item_id']) & $_GET['item_id']!="") {
            echo '<h4>Правка данных о студенте</h4>';
            $query="select * from students where id='".$_GET['item_id']."'";
            $res=mysql_query($query);
            $res_edit=mysql_fetch_array($res);
        } else {
            echo '<h4 class="err">не выбраны данные о студенте для правки</h4>';
        }
    }
}

//добавление данных о студенте
if (isset($_GET['type']) & $_GET['type']=='add' || $_GET['type']=='edit') {
?>
<p><a href="<?php echo $main_page;?>">К списку студентов </a><p>
<h4> Ввод данных о студенте </h4>

<?php
	// <abarmin date="26.11.2012">
	// печать по шаблону
	CHtml::printOnTemplate("formset_students");
?>

<form name="order_form" method="post" action="">
Группа
<?php
$archiv=false;
if (isset($_GET['showArcGr']))
	{if ($_GET['showArcGr']==1) $archiv=true;}
else 	{	
	if (isset($_GET['archiv'])) $archiv=true;
	else if (isset($res_edit['group_id']))
	{
		$sql_gr_date_end='SELECT ti.date_end
			FROM time_intervals ti RIGHT OUTER JOIN study_groups sg
				ON (ti.id = sg.year_id)
		       WHERE sg.id = '.intval($res_edit['group_id']);
		$gr_date_end=getScalarVal($sql_gr_date_end);
		
		if ($gr_date_end!='' && $gr_date_end<intval($def_settings['date_start']) ) $archiv=true;
	}
}
if ($archiv) {echo '<span class=warning>архивная</span>';}?>*
<select name="group_id" id="group_id" style="width:300;" title="<?php echo echoIf($archiv,'архивные группы','группы текущего уч.года'); ?>"> 
		<?php
		$query="SELECT sg.id,
       concat(sg.name,' (',(select count(*) from students s where sg.id = s.group_id),')') as name
		  FROM       study_groups sg
		 WHERE ";
		 if ($archiv) {
		 $query.=' sg.year_id!="'.$def_settings['year_id'].'" and sg.year_id>0'; }
		 else {
		  	$query.=' sg.year_id="'.$def_settings['year_id'].'" or sg.year_id=0 or sg.year_id is null';}
		$query.=' order by name';	
echo getFrom_ListItemValue($query,'id','name','group_id');

		?>
</select>
<?php
	
	if ( (isset($_GET['showArcGr']) && $_GET['showArcGr']==1) || $archiv)
		echo '<a href="?'.reset_param_name($query_string,'showArcGr').'&showArcGr=0'.'" title="перейти к группам текущего уч.года">текущие</a>';
	else
		echo '<a href="?'.reset_param_name($query_string,'showArcGr').'&showArcGr=1'.'" title="перейти к группам архивного уч.года">архивные</a>';
?> &nbsp; 
<a href="spravochnik.php?<?php echo showSpravLink('учебная группа'); ?>">Новая</a> &nbsp; 
<a href="?<?php echo $query_string;?>&toarhiv" title="добавить группу в историю смены групп">В архив</a> <p>
<?php if ($res_edit['prev_group_ids']!="")  { ?>
история смены групп:<br>
<?php
$old_gr_arr=explode(";",$res_edit['prev_group_ids']);	//дата-окончания группы  + группа
$cur_item_arr=array();
echo '<table class=text border=0 cellpadding=5>';
for ($i=0;$i<count($old_gr_arr);$i++)
{
 	$cur_item_arr=explode("+",$old_gr_arr[$i]);
		$query='select id,name from study_groups where id="'.intval($cur_item_arr[1]).'" limit 0,1';
		$res=mysql_query($query);
		echo '<tr>	<td>'.DateTimeCustomConvert($cur_item_arr[0],'d','mysql2rus').'</td>
					<td><b><a href="studygr_view.php?item_id='.mysql_result($res,0,'id').'&action=edit">'.mysql_result($res,0,'name').'</a></b></td></tr>';
 	
	//print_r($cur_item_arr);
 	//echo '<br>';
} 
echo '</table>';

 } else { ?>
<div>история смены групп <b>пуста</b> </div>
<?php }?>
<p>

	ФИО*<br><input type=text size=100 name=student_name value="<?php if (isset($res_edit)) {echo $res_edit['fio'];} ?>"> <p>
	№ зач.книжки*<br><input type=text maxlength=20 size=25 name=stud_num value="<?php if (isset($res_edit)) {echo $res_edit['stud_num'];} ?>"> <p>
	форма обучения<br><select name="bud_contract" id="bud_contract" style="width:300;"> 
		<?php if (!isset($res_edit) || $res_edit['bud_contract']==0) {echo $selected=' selected';} else {$selected='';} ?>
		<option value="0" <?php echo $selected;?>>...выберите форму...</option>
		<?php if (isset($res_edit) && $res_edit['bud_contract']==1) {echo $selected=' selected';} else {$selected='';} ?>
			<option value="1" <?php echo $selected;?>> бюджет </option>		
		<?php if (isset($res_edit) && $res_edit['bud_contract']==2) {echo $selected=' selected';} else {$selected='';}  ?>
		<option value="2" <?php echo $selected;?>> контракт </option>				
	</select> <p>
	телефон<br><input type=text size=100 name=telephone value="<?php if (isset($res_edit)) {echo $res_edit['telephone'];} ?>"> <p>
	комментарий<br><input type=text size=100 name=comment_order value="<?php if (isset($res_edit)) {echo $res_edit['comment'];} ?>"> <p>
	<input type=button onclick=javascript:check_form(); value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>

<?php
}

else 
	{		//------------------------ списочная форма -----------------------------
	echo '<h4 class="notinfo"> Данные о студентах. 
		<a href="studygr_view.php">учебные группы</a> 
		<a href="diploms_view.php">темы дипломов</a> 
		</h4>';

	if (!isset($_GET['archiv'])) {
	
		//$tmp_str='?'.$_SERVER["QUERY_STRING"];
		
		//if (strpos($tmp_str,'archiv')<=0) {$tmp_str=$tmp_str.'&archiv';}
		//where 
		$query_='select count(*) from students s 
	           LEFT OUTER JOIN
	              study_groups sg
	           ON sg.id = s.group_id
			   where sg.year_id!="'.$def_settings['year_id'].'" and sg.year_id>0';
		$archiv_cnt=intval(getScalarVal($query_),10);
		
		echo '<a href="?'.$query_string.'&archiv">студенты прошлых учебных лет: '.$archiv_cnt.'</a><br>';
	}
	else {
		$query_='select count(*) from students s 
	           LEFT OUTER JOIN
	              study_groups sg
	           ON sg.id = s.group_id
			   where sg.year_id="'.$def_settings['year_id'].'" or sg.year_id is null or sg.year_id=0';
		$cur_cnt=intval(getScalarVal($query_),10);
 		echo '<a href="?'.reset_param_name(reset_param_name($query_string,'archiv'),'page').'">студенты текущего года: '.$cur_cnt.'</a><br>';}

if (!isset($_GET['save']) && !isset($_GET['print'])) {
	echo '<p class="notinfo"><a href="?'.reset_param_name($query_string,'type').'&type=add"> Добавить</a><p>';
	echo '<table width=99% class="notinfo"><tr>';
	echo '<td align=left width=150>
	Группа &nbsp; </td><td> <select name="group_id" id="group_id" style="width:200;" onChange="javascript:group_id(\''.reset_param_name($query_string,'group_id').'\')">'; 

	$query="select sg.id,concat(sg.name,concat(' (',count(*),')')) as name 
	from study_groups sg inner join students on students.group_id=sg.id ".
	" WHERE 1 ".$archiv_query.
	" group by sg.id order by sg.name";
echo getFrom_ListItemValue($query,'id','name','group_id');

	echo '</select> &nbsp;&nbsp; ';
	echo '<input type=button value="Отобрать" onclick="javascript:group_id();">&nbsp;&nbsp;';
	echo '<input type=button value="Все" onclick=javascript:window.location.href="'.$main_page.'";>&nbsp;&nbsp;
	<input type=button value="Печать" onclick=javascript:window.location.href="?print=1&'.$_SERVER["QUERY_STRING"].'";>&nbsp;&nbsp;
	<input type=button value="в Word" onclick=javascript:window.location.href="?save&attach=doc&'.$_SERVER["QUERY_STRING"].'"></td> 
	<td align=right><input type=text name="q" id="search_query" width=50 value=""> &nbsp; <input type=button value="Найти" 
	OnClick=javascript:go2search();></td>
	</tr></table>
	<p>';}


if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b> <a href="?'.reset_param_name($query_string,'q').'">сбросить поиск</a></div><br>';
$search_query=' and (s.fio like "%'.$q.'%" or 
					s.comment like "%'.$q.'%" or 
					sg.name like "%'.$q.'%")';}


if ($kadri_id>0) {$search_query=' and s.id in (select distinct student_id from diploms where kadri_id="'.$kadri_id.'")';}	//поиск по дипл.руководителю

if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?"> сбросить фильтр </a></div>';}

$table_headers=array(
	1=>array('ФИО','200'),
	2=>array('№ зач.книжки','100'),
	3=>array('уч.группа','100'),
	4=>array('форма обуч.','100'),
	5=>array('телефон','10'),
	6=>array('дипломы','50')
	);

if (isset($_GET['sort']) && $_GET['sort']>1 && $_GET['sort']<=count($table_headers)) {$sort=intval($_GET['sort']);}

$query_fields='SELECT s.fio,
       s.stud_num,
       sg.name AS group_name,
       s.bud_contract,
       s.telephone,
       (select count(*) from diploms d where d.student_id=s.id) as d_cnt,
       s.comment,
       s.group_id,
       s.id,
	   sg.head_student_id';
 $query_from=' 
 		FROM    (   students s 
           LEFT OUTER JOIN
              study_groups sg
           ON (sg.id = s.group_id)) ';
 

if ($group_id!=0) 
	{$search_query.=' and s.group_id="'.$group_id.'" ';}

$query_where=' WHERE 1 '.$archiv_query.$search_query;
$query_list=$query_fields.$query_from.$query_where;
$query_list.=' order by '.$sort.' '.$stype.' ';

$res=mysql_query($query_list.' limit '.(($page-1)*$pageVals).','.$pageVals);
//echo $query_list.'limit '.(($page-1)*$pageVals).','.$pageVals;


$add_string='';
if (isset($_GET['group_id']) && $_GET['group_id']!='')  {$add_string=$add_string.'&group_id='.$_GET['group_id'];};
if (isset($_GET['q']) && $_GET['q']!='')  {$add_string=$add_string.'&q='.$_GET['q'];};
if (isset($_GET['print']))  {$add_string=$add_string.'&print='.$_GET['print'];};
if (isset($_GET['page']))  {$add_string=$add_string.'&page='.$_GET['page'];};
if (isset($_GET['pageVals']))  {$add_string=$add_string.'&pageVals='.$_GET['pageVals'];};


if (mysql_num_rows($res)==0) {
	if (!isset($_GET['archiv'])) echo '<p class=warning style="font-size:12pt; text-align:center;">в текущем году записей не найдено, попробуйте поискать в  <a href="?'.reset_param_name($query_string,'archiv').'&archiv">архиве</a> ';
	else echo '<p class=warning style="font-size:12pt; text-align:center;">в архве записей не найдено, попробуйте поискать в  
		<a href="?'.reset_param_name($query_string,'archiv').'">текущем году</a> ';
	if ($search_query!='') echo ', либо <a href="?">сбросить фильтр</a>';
	echo '</p>';
}
else {	//рисуем списочную таблицу

echo '<form name=order_list action="" method="post"><table name=tab1 border=1 cellpadding="0" cellspacing="0" width="99%">
<tr align="center" class="title" height="30">';
 
	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '
		<td width="30"><input type=checkbox name="checkbox_del_all_1" title="выделить все" onClick="javascript:mark_all(this.name);"> </td>
		<td width="40" class="notinfo"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}
	echo '<td width="30">№</td>';
	for ($i=1;$i<=count($table_headers);$i++)
	{
		echo '<td width="'.$table_headers[$i][1].'">'.print_col($i,$table_headers[$i][0]).'</td>';
	}
	if (!isset($_GET['save']) && !isset($_GET['print'])) 
		{echo '<td class="notinfo">комментарий</td>';}
	echo "</tr>\n";
	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};

	$i=0;
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		echo '<tr align="left" class="main" '.$bgcolor.' valign="top">';
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		  echo '
		  <td width="30" align=center><input type=checkbox name="checkbox_h_copy_'.$tmpval['id'].'" title="груп.операции"></td>
		  <td align="center"> 
		  <a href="javascript:del_confirm_act(\''.str_replace(" ","_",f_ro($tmpval['fio'])).'\',
		  	\''.$curpage.'?item_id='.f_ro($tmpval['id']).'&type=del&'.$query_string.'\');" title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="?item_id='.$tmpval['id'].'&type=edit&'.$query_string.'" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		$i++;
		echo '<td>&nbsp;'.($i+($page-1)*$pageVals).'</td>';
		
		if ($tmpval['head_student_id']==$tmpval['id'])	//тек.студент староста в группе
		{$style='font-weight:bold;';} 
		echo '<td style="'.$style.'">&nbsp;'.color_mark($q,$tmpval['fio']).'</td>';
		$style='';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['stud_num']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['group_name']).'</td>';
		
		$bud_contract='';
		if ($tmpval['bud_contract']==1) {$bud_contract='бюджет';} 
		if ($tmpval['bud_contract']==2)	{$bud_contract='контракт';}
			
		echo '<td>&nbsp;'.color_mark($q,$bud_contract).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['telephone']).'</td>';
		
		$tmp_str='';
		if ($tmpval['d_cnt']>0) {$tmp_str='<a href="diploms_view.php?&q='.$tmpval['fio'].'" title="">'.$tmpval['d_cnt'].'</a>';}
		echo '<td align=center>&nbsp;'.$tmp_str.'</td>';			
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.$tmpval['comment'].'</td>';}
		echo "</tr>\n";
	}
echo '</table>';
?> Групповые операции
<select name=act_type id=act_type onchange="javascript:gr_oper(this.value);">
	<option value="">Выберите действие </option>
	<option value="del_selected">Удалить выбранных </option>
	<option value="gr_ch_selected">Сменить группу выбранным </option>
</select>
<select name="group_id" id=group_id style="width:200; display:none;">
	<?php
	$query_gr='SELECT id,name FROM `study_groups`';
	echo getFrom_ListItemValue($query_gr,'id','name','group_id');
	?>
</select> 
<input type=button name="act_btn" id="act_btn" value="Выполнить" onClick="javascript:test_copy();" disabled title="выберите действие" alt="выберите действие">
<input name=export value="" type=hidden> 
</form>

<?php
//echo 'select count(*)'.$query_from.$query_where;

$rec_cnt=getScalarVal('select count(*)'.$query_from.$query_where);


//$pages_cnt=floor(mysql_num_rows($res)/$pageVals)+1;
if (floor($rec_cnt/$pageVals)==$rec_cnt/$pageVals) {$pages_cnt=floor($rec_cnt/$pageVals);}
 else {$pages_cnt=floor($rec_cnt/$pageVals)+1;}

echo '<div align="left"> страницы ';

$add_string=reset_param_name($query_string,'page');

//for ($i=1;$i<=$pages_cnt;$i++) {if ($i!=$page) {echo '<a href="?page='.$i.'&'.$add_string.'"> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}}
echo getPagenumList($pages_cnt,$page,3,'page',$add_string,$link_tmp);
//--------------------------------------------------------
echo '<br>макс.число строк на странице:  <input type=text value="'.$pageVals.'" name="pageVals" id="pageVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pageVals(\''.reset_param_name($query_string,'pageVals').'\');" value=Ok>
	<p> Всего строк: '.$rec_cnt.'</div>'; 	
?>
<div class=text>
<b>Примечание</b></br>
<ul>
	<li><b>жирным</b> шрифтом выделен староста группы</li>
	<li>студент относится к прошлому учебному году в соответствии со своей учебной группой</li>
</ul>
</div>
<?php	
	}
	}
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>