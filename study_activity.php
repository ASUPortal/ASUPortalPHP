<?php
include ('authorisation.php');

//-----------настройка формы ------------
$showRez=true;	//скрывать рецензента в "+", иначе показывать его ФИО-кратко


//----------------------


$main_page=$curpage;
$page=1;
$kadri_id='';		//отбор по преподавателю
$q='';			//строка поиска
$pgVals=20;	//число тем на странице по умолчанию
$sort=1;
$filt_str_display='';	//отражение по чему идет отбор
$err=false;		//нет ошибок при изменении БД
$stype='desc';		//тип сортировки столбца
$gr_mode=0;


$query_string=$_SERVER['QUERY_STRING'];
//kadri_id sort  q  archiv  page  pgVals

if (isset($_GET['kadri_id'])) {$kadri_id=$_GET['kadri_id'];$filt_str_display=$filt_str_display.' преподавателю;';}
if (isset($_GET['sort'])) {$sort=$_GET['sort'];}

//if (isset($_GET['kadri_id'])) {$kadri_id=$_GET['kadri_id'];}
if (isset($_GET['q'])) {$q=$_GET['q'];$filt_str_display=$filt_str_display.'  поиску;';}
//if (isset($_GET['archiv'])) {$query_string=$query_string.'&archiv';}

if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];$filt_str_display=$filt_str_display.'  странице;';}
if (isset($_GET['pgVals']) && $_GET['pgVals']<=999 && $_GET['pgVals']>=1) {$pgVals=$_GET['pgVals'];$filt_str_display=$filt_str_display.' числу записей;';}
//if (isset($_GET['sort'])) {$sort=$_GET['sort'];}
//echo ' query_string='.$query_string;

if (isset($_GET['stype']) && $_GET['stype']=='asc') {$stype=$_GET['stype'];}
if (isset($_GET['gr_mode'])) {$gr_mode=intval($_GET['gr_mode']);}



if (isset($_GET['type']) && $_GET['type']=='del' && isset($_GET['item_id']))
	{
	//echo '!!!!!!!del!!!!!!';
	$query='delete from `study_activity` where id="'.$_GET['item_id'].'"';	
	
	//echo ' query_string='.$query_string;
	$res=mysql_query($query);	//reset_param_name ($query_string,$param_name)
	//echo 'Location:'.$main_page.'?'.reset_param_name($query_string,'type');	
	$query_string=reset_param_name($query_string,'type');
	$query_string=reset_param_name($query_string,'item_id');
	header('Location:'.$main_page.'?'.$query_string);
	//page=2&pgVals=20&archiv&sort=4	
	}
$admin_role=false;
if ($write_mode===true)
{$admin_role=true;} 

if (!$admin_role && (!isset($_GET['kadri_id']) || $_GET['kadri_id']!=$_SESSION['kadri_id'])) 
	{header('Location:?kadri_id='.$_SESSION['kadri_id'].'');}
	
//if (isset($_GET['type']) && $_GET['type']=='add')
//$bodyOnLoad=' onload="javascript:loadCookie(\'item_form\',\'study_activity.php\');"';

include ('master_page_short.php');

?>

<style>
tr.title {font-size:13px; font-family:Arial; background-color:#e6e6ff; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}

</style>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
<script type="text/javascript" src="scripts/calendar_init.js"></script>
<script language="javascript" src="scripts/save_form.js"></script>
<script src="_ajax_templ/cascadeSelect/js/cascadeSelect.js" type="text/javascript"></script>

<script language="JavaScript">
var main_page='<?php echo $main_page;?>';	//for redirect & links

$(document).ready(function(){  

  <?php if (isset($_GET['type']) && $_GET['type']=='add') { ?>
  loadCookie('item_form','study_activity.php');
  
  //помечаем оценки студентов в группе, если оценки выбраны
  var docElemCnt=item_form.elements.length;  
  //alert(docElemCnt);
  for (var i=0;i<docElemCnt;i++)
  {
    var docElem=item_form.elements[i];
    
    if (docElem!=null && String(docElem.type).indexOf('select')>=0 && String(docElem.id).indexOf('study_mark')>=0)
      {
	mark_value(docElem.value,docElem.id);
      }
  }
  <?php } ?>
  $('#st_group_id').change(function(){  	
	  //главный список, фильтруемый список, тип_запроса, разворачивать_при_пустом_главном
	  //alert('test');
	  //var select_id=0;
	  //var list1Value = $('#st_group_id').val();
	  //alert('list1Value='+list1Value);
	  //alert('select_id='+select_id);
	  <?php
	$student_id=0;
	
	if (isset($_POST['student_id'])) $student_id=intval($_POST['student_id']);
	else if (isset($_GET['student_id'])) $student_id=intval($_GET['student_id']);
	else if (isset($_GET['type']) && $_GET['type']=='edit' && isset($_GET['item_id']))
	{//пытаемся получить ID-студента по номеру записи в успеваемости
	$item_id=intval($_GET['item_id']);
	
	$query="select student_id from study_activity where id='".$item_id."'";
	$student_id=intval(getScalarVal($query));	 
	 }
	
	echo 'var select_id=\''.echoIf($student_id>0,$student_id,'0').'\';';
	
	  ?>
	  //if (select_id<=0) select_id=list1Value;
	  //alert(select_id);
	  
	  adjustList2('st_group_id','student_id','StGroup2Students','allowMainIsNull',select_id);
	  //alert('test');
  }).change();

});
function gr_mode_click(q_str)
{
  var group_id=0;
  if (document.cookie.indexOf('group_id')>0) group_id=2;
  
  group_id=parseInt(eval('document.cookie.replace(/.*\'group_id\',\'([^\']*)\'.*/,"$1")'));
  if (isNaN(group_id)) group_id=0;
  
  window.location.href="?"+q_str+"&group_id="+group_id;
}
function clearMarks()   //вычищаем пометки Оценок при сбросе
{
var elCnt=document.item_form.elements.length;
for (var i=0;i<elCnt;i++){
    var formEl=document.item_form.elements[i];    
    if (formEl!=null && formEl.id.indexOf('study_mark')!=-1)
        formEl.style.backgroundColor="white";
    }
}
function mark_value(value2Check,selectId)   //выделяем оценку, если выбрана
{
 if (selectId=='') return;

 var markEl=document.getElementById(selectId);
 if (markEl==null) return;

    if (parseInt(value2Check)>0) markEl.style.backgroundColor="yellow";
    else  markEl.style.backgroundColor="white";

}
function go2search(kadri_id,search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {href_addr='q='+search_query+'&kadri_id='+kadri_id;
		if (search_path=='archiv') {href_addr='archiv&'+href_addr+'';}
	 	
		 //alert(href_addr);
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
}  
function pgVals(query_str)
{
 	var pageCnt= parseInt(document.getElementById('pgVals').value);
 	if (pageCnt>0 && pageCnt<1000) {document.location.href=main_page+'?'+query_str+'&pgVals='+pageCnt;}
 	else {alert('Введите значение с 1 до 999.');}
 	
	 //if (pageCnt>0 && pageCnt<100) {alert('ok');}
	 
	//alert(pageCnt);
} 
function check_form()
{
var err=false;
var date_act=document.getElementById('date_act');

if (date_check(date_act.value)) 
	{
	 err=true;
	 alert('Дата не существует. воспользуйтесь календарем;');
	}
else {
 	a = new Array(
	 	new Array('kadri_id','преподаватель'),
	 	new Array('student_id','студент'),
	 	new Array('subject_id','дисциплина')
	);
	if (document.getElementById('saveForm').checked==true)
		saveCookie('item_form','study_activity.php');
	requireFieldCheck(a,'item_form');	
	}
} 
function check_form_group()
{
 

var stud_mark_cnt=document.item_form.elements.length;
var err=true;
for (var i=0;i<stud_mark_cnt;i++)
{
 	var mark_elem=document.item_form.elements[i];	
	if (mark_elem.id.indexOf('study_mark')!=-1 && mark_elem.value!=0) {err=false; break;}
}

if (err) {alert('Не введено ни одной оценки у студентов.');}
else {
	var date_act=document.getElementById('date_act');	
	if (date_check(date_act.value)) 
		{
		 err=true;
		 alert('Дата не существует. воспользуйтесь календарем;');
		}
	else {	 	
		 a = new Array(
		 	new Array('group_id','студентческая группа'),
			new Array('kadri_id','преподаватель'),	 	
		 	new Array('subject_id','дисциплина'),
		 	new Array('study_act_id','вид контроля')
		);
	if (document.getElementById('saveForm').checked==true)
		saveCookie('item_form','study_activity.php');
		requireFieldCheck(a,'item_form');		
		}
	}
} 
</script>

<?php
//phpinfo();
//session_start();

//------------------------------------------------------------
//include ('sql_connect_empty.php');

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


//добавление записи
//echo '<br><br>';
if (isset($_POST['kadri_id']))
{
	if ($gr_mode==1) {	//работа при вводе студентов группой, массовый ввод сведений по успеваемости
		if ($_POST['group_id']!=0 && $_POST['kadri_id']!=0 && $_POST['subject_id']!=0 && $_POST['date_act']!="" && $_POST['study_act_id']!=0) 
		{		
		//-----------------------------
		$cnt_mas=0;	//число успешных массовых операций
		while (list($key, $value) = each ($_POST)) {
		 	if 	  (strstr($key,"study_mark")) {
				$mark_id=substr($key,strlen('study_mark'));	//выдираем ID из названий чекбоксов
				//echo '<div>$mark_id='.$mark_id.'</div>';				
				if (intval($_POST[$key])>0) {
				$query_mas="insert into study_activity(date_act,study_act_id,student_id,study_mark,subject_id,kadri_id,comment,study_act_comment) 
		 			values('".f_ri(DateTimeCustomConvert($_POST["date_act"],'d','rus2mysql'))."',
					 		'".intval($_POST["study_act_id"])."',
							'".intval($mark_id)."',
							'".f_ri($_POST[$key])."',
			 				'".intval($_POST["subject_id"])."',
							'".intval($_POST["kadri_id"])."',
							'".f_ri($_POST["comment"])."',
							'".f_ri($_POST["study_act_comment"])."'
							)";
					//echo $query_mas.'<br>';	
					if (!mysql_query($query_mas)) {$err=true;echo '<div class=warning> ошибки массовой операции</div>';}
					else {$cnt_mas++;}					
					}
			}
			   }
		if ($err==true)	{echo '<div class=warning> Произошли ошибки </div>';}
		else {echo '<div class=success> Массовая операция успешно завершена для <font size=+1>'.$cnt_mas.'</font> элементов</div>';}		
		}
		else {echo '<div class="warning">Часть обязательных данных, при вводе группой, не заполнено .<br>&nbsp;</div>';$err=true;}
		
	}
	else {//индивидуальный ввод сведений по успеваемости
	if ($_POST['kadri_id']!=0 && $_POST['student_id']!=0 && $_POST['subject_id']!='0' && $_POST['date_act']!="") 
	{
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

	 //обновление темы
	 if (isset($_GET['type']) & $_GET['type']=='edit' & isset($_GET['item_id']) & $_GET['item_id']!='') {
		 //echo 'Правка темы.';
		 $query="update study_activity set 
		 	kadri_id='".f_ri($_POST["kadri_id"])."',
		 	study_act_id='".f_ri($_POST["study_act_id"])."',
		 	student_id='".f_ri($_POST["student_id"])."',
			date_act='".f_ri(DateTimeCustomConvert($_POST["date_act"],'d','rus2mysql'))."',
			study_mark='".f_ri($_POST["study_mark"])."',
			subject_id='".f_ri($_POST["subject_id"])."', 
			comment='".f_ri($_POST["comment"])."',
			study_act_comment='".f_ri($_POST["study_act_comment"])."'
			  where id='".f_ri($_GET["item_id"])."'";
		//date_act 	study_act_id 	student_id 	study_mark 	subject_id 	kadri_id 	comment
		 if ($res=mysql_query($query)) {

			//header("Location: ".$main_page);
		  	echo '<div class=success> Запись обновлена  успешно.'.$onEditRemain_text.'</div><br>';
		 	//echo "Location:".$main_page;
			 
		 				}
		 else {echo '<div class="warning">Запись не обновлена .<p>&nbsp;</div>';$err=true;}
		 //echo $query;
	 }
	 
	 //новая тема
	 if (isset($_GET['type']) & $_GET['type']=='add') {
		 //echo 'Добавление записи.';
		 $query="insert into study_activity(date_act,study_act_id,student_id,study_mark,subject_id,kadri_id,comment,study_act_comment) 
		 	values('".f_ri(DateTimeCustomConvert($_POST["date_act"],'d','rus2mysql'))."','".f_ri($_POST["study_act_id"])."','".f_ri($_POST["student_id"])."','".f_ri($_POST["study_mark"])."',
			 '".f_ri($_POST["subject_id"])."','".f_ri($_POST["kadri_id"])."','".f_ri($_POST["comment"])."','".f_ri($_POST["study_act_comment"])."')";
		 $res_news=true;
		 if ($res_news & $res=mysql_query($query)) {


		  	echo '<div class=success> Запись от "'.f_ri(DateTimeCustomConvert($_POST["date_act"],'d','rus2mysql')).'" добавлена успешно.'.$onEditRemain_text.'</div><br>';
			}
		 else {echo '<div class="warning">Запись не добавлена. Возможно наличие дубликата</div><br>';$err=true;}
		 
			 
	
	//echo $query;
	 }
	 
	 }
	else {echo '<div class="warning">Часть обязательных данных не заполнено .<br>&nbsp;</div>';$err=true;}
	}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.reset_param_name_ARR($query_string,array('gr_mode','group_id')).'\"",2000);</script>';}	
} 

if (isset($_GET['type']) && $_GET['type']=='edit')
{
	if (isset($_GET['item_id']) & $_GET['item_id']!="")
	{//echo '<h4>Правка темы</h4>';
	$query="select * from study_activity where id='".$_GET['item_id']."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="warning">не выбрана Запись для правки</h4>';}	
}

//-----------------начало добавление успеваемости студентов ---------------------------------------
if (isset($_GET['type']) && ($_GET['type']=='add' || $_GET['type']=='edit') )
{
?>
<div><a href="<?php echo 	$main_page.'?'.reset_param_name(reset_param_name($query_string,'item_id'),'type');?>">Просмотр записей </a></div>
<?php
if (isset($_GET['gr_mode']) && $_GET['gr_mode']==1)
{ //---------начало добавление групповой успеваемости студентов
?>
<h4> <?php if(isset($_GET['type']) && $_GET['type']=='edit') {echo 'Правка';} else {echo 'Ввод новой';} ?> записи (только в текущем уч.году)</h4>
<div class="forms_under_border" style="width:99%;">
<form name="item_form" id="item_form" method="post" action="">
<span class=warning style="font-size:12pt;">Учебная группа </span>* после выбора отразиться список студентов
	<br>  <select name="group_id" id="group_id" style="width:500;" title="студенты тек.учебного года" onChange="javascript:window.location.href='?<?php echo reset_param_name($query_string,'group_id');?>&'+this.name+'='+this.options[this.selectedIndex].value;"> 
		<?php
		$group_id=0;
		if (isset($_GET['group_id'])) {$group_id=intval($_GET['group_id']);}
		
		$query="SELECT sg.id,concat(sg.name,' (',count(*),')') as name 
		FROM study_groups sg inner join students s on s.group_id=sg.id
		where sg.year_id = ".$def_settings['year_id']." group by  sg.id,sg.name
		order by sg.name";		
		echo getFrom_ListItemValue($query,'id','name','group_id');
		?>
</select> <?php echo sprav_edit_link('study_groups');?>
<p>
Преподаватель* <br> <select name="kadri_id" id="kadri_id" style="width:500;" title="сотрудники кафедры с участием ППС"> 
		<?php
		$query='SELECT kadri.id, concat(kadri.fio, " (", (SELECT count(*)
                                   FROM `study_activity` sa
                                  WHERE sa.kadri_id = kadri.id), ")"
       )   AS fio
  FROM    kadri
 WHERE (kadri_role(kadri.id,",")  LIKE "%ППС%")';
		if (!$admin_role) {$query.=' and kadri.id="'.$kadri_id.'"';}
		$query.=' order by 2';
		
		echo getFrom_ListItemValue($query,'id','fio','kadri_id');
		?>
</select> <a href="lect_anketa_view.php">редактировать список</a>
<p>
	Дисциплина* <br> 
<select name="subject_id" id="subject_id" style="width:500;"> 
		<?php
		$query='select id,name from subjects order by name';
		echo getFrom_ListItemValue($query,'id','name','subject_id');
		?>
</select> <?php echo sprav_edit_link('subjects');?>
	<p>
Вид контроля* <br> <select name="study_act_id" id="study_act_id" style="width:300;"> 
		<?php
		$query='select id,name from study_act order by name';
		echo getFrom_ListItemValue($query,'id','name','study_act_id');
		?>		
</select> <?php echo sprav_edit_link('study_act');?> </span>
номер занятия 
		<input type=text id=study_act_comment name=study_act_comment style="width:100;" value="<?php echo getFormItemValue('study_act_comment'); ?>">
<p>
	Дата записи * <br>
	<input type=text maxlength=10 size=15 id=date_act name=date_act value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['date_act'],0,10),'d','mysql2rus'));}else if (isset($_POST['date_act'])) {echo $_POST['date_act'];}else {  
	 //дата защиты с учетом 1 июня тек.учеб.года
	 echo date("d").'.'.date("m").'.'.date("Y"); 
	 } ?>"> 
	<button type="reset" id="f_trigger_date_act">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script> <p>
	Комментарий<br><input type=text size=100 name=comment value="<?php echo getFormItemValue('comment'); ?>"> <p>
<?php
if ($group_id>0)	//вывод студентов выбранной группы
{
	$q_stud='select id,fio from students where group_id='.$group_id.' order by fio'; 
	$res_stud=mysql_query($q_stud);
	if (mysql_num_rows($res_stud)>0) {
		echo '<table border=1 cellspacing=0 cellpadding=5><tr align=center class=title><td>№</td><td>ФИО студента</td><td>Оценка</td></tr>';
		$i=1;
		while ($a_stud=mysql_fetch_assoc($res_stud))		
			{echo '<tr><td>'.$i.'</td><td>'.$a_stud['fio'].'</td><td>';
			$query='select id,name_short as name from study_marks order by name_short desc';
			?>
			<select name="study_mark<?php echo $a_stud['id'];?>" id="study_mark<?php echo $a_stud['id'];?>" 
                style="width:100;" onchange="javascript:mark_value(this.value,this.id);">
				<?php
				$query='select id,name_short as name from study_marks order by name_short desc';
				echo getFrom_ListItemValue($query,'id','name','study_mark');
				?>	
			</select>			
			<?php
			//echo '<select></select>';
			echo '</td></tr>';
			$i++;
			}
		echo '</table></br>';
	}
}
?>
	<input type=button onclick="javascript:check_form_group();" value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить onclick="javascript:clearMarks();"> &nbsp;  &nbsp;  &nbsp; &nbsp;  &nbsp;  &nbsp;
	<input type="button" value="Как ранее" onclick="javascript:loadCookie('item_form','study_activity.php');" title="будут автоматически подставлены данные предыдущей введенной записи в рамках одного сеанса" /> &nbsp; 
	<!--input type="button" value="deleteCookie" onclick="javascript:deleteCookie('item_form','study_activity.php');" /> &nbsp; 
	<input type="button" value="viewCookie" onclick="javascript:viewCookie();" /> &nbsp; <br/-->
	<label><input type=checkbox checked id="saveForm" name="saveForm" title="при вводе новой записи будут автоматически подставлены данные предыдущей"> запоминать последние данные ввода </label>
</form>
</div>  
 <?php
 //---------конец добавление групповой успеваемости студентов
}
else { //-----------------добавление индивидуальной успеваемости студентов---------------------------------------
?>
<h4> <?php if(isset($_GET['type']) && $_GET['type']=='edit') {echo 'Правка';} else {echo 'Ввод новой';} ?> записи (только в текущем уч.году)</h4>
<div class="forms_under_border" style="width:99%;">
<form name="item_form" id="item_form" method="post" action="">

	Дата записи * <br>
	<input type=text maxlength=10 size=15 id=date_act name=date_act value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['date_act'],0,10),'d','mysql2rus'));}else if (isset($_POST['date_act'])) {echo $_POST['date_act'];}else {  
	 //дата защиты с учетом 1 июня тек.учеб.года
	 echo date("d").'.'.date("m").'.'.date("Y"); 
	 } ?>"> 
	<button type="reset" id="f_trigger_date_act">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script> <p>
	Дисциплина* <br> 
<select name="subject_id" id="subject_id" style="width:500;"> 
		<?php
		$query='select id,name from subjects order by name';
		echo getFrom_ListItemValue($query,'id','name','subject_id');
		?>
</select> <?php echo sprav_edit_link('subjects');?>
	<p>
Преподаватель* <br> <select name="kadri_id" id="kadri_id" style="width:500;" title="сотрудники кафедры с участием ППС"> 
		<?php
		$query='SELECT kadri.id, concat(kadri.fio, " (", (SELECT count(*)
                                   FROM `study_activity` sa
                                  WHERE sa.kadri_id = kadri.id), ")"
       )   AS fio
  FROM    kadri
 WHERE (kadri_role(kadri.id,",") LIKE "%ППС%")';
		if (!$admin_role) {$query.=' and kadri.id="'.$kadri_id.'"';}
		$query.=' order by 2';
		
		echo getFrom_ListItemValue($query,'id','fio','kadri_id');
		?>
</select> <a href="lect_anketa_view.php">редактировать список</a>
<p>
<FIELDSET>
Учебная группа <select id="st_group_id" name="st_group_id" style="width:300;" title="только тек.учебного года">
	<?php
	$listQuery="SELECT sg.id,concat(sg.name,' (',count(*),')') as name 
		FROM study_groups sg inner join students s on s.group_id=sg.id
		where sg.year_id = ".$def_settings['year_id']." group by  sg.id,sg.name
		order by sg.name";

	echo getFrom_ListItemValue($listQuery,'id','name','st_group_id');
	?>
  </select> <?php echo sprav_edit_link('study_groups');?> 
<br><br>
Студент* 
	<span id="ac_loading" class="cascadeSelect_loading" style="display:none";> </span> 
	<span class=success style="padding-left:20px;">(автофильтрация при выборе группы)</span> <br>  	
	<select name="student_id" id="student_id" style="width:500;" title="только тек.учебного года"> 
		<?php
		/*
		$query='SELECT s.id, concat(s.fio," (",sg.name,")") as fio
			  FROM    study_groups sg RIGHT OUTER JOIN  students s  ON (sg.id = s.group_id)
			  WHERE (sg.year_id = '.$def_settings['year_id'].') order by 2';
		echo getFrom_ListItemValue($query,'id','fio','student_id');
		*/
		?>
</select> <a href="students_view.php">редактировать список</a>  
</FIELDSET>
<p>
Вид контроля <br> <select name="study_act_id" id="study_act_id" style="width:300;"> 
		<?php
		$query='select id,name from study_act order by name';
		echo getFrom_ListItemValue($query,'id','name','study_act_id');
		?>
</select> <?php echo sprav_edit_link('study_act');?>  
номер занятия
		<input type=text id=study_act_comment name=study_act_comment style="width:100;" value="<?php echo getFormItemValue('study_act_comment'); ?>">
<p>
	Оценка <br>
<select name="study_mark" id="study_mark" style="width:300;"> 
	<?php
	$query='select id,name_short as name from study_marks order by name_short desc';
	echo getFrom_ListItemValue($query,'id','name','study_mark');
	?>	
</select> <?php echo sprav_edit_link('study_marks');?> <p> 
	Комментарий<br><input type=text size=100 name=comment value="<?php echo getFormItemValue('comment'); ?>"> <p>
	<input type=button onclick="javascript:check_form();" value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> &nbsp;  &nbsp;  &nbsp;  &nbsp; 
	<input type="button" value="Как ранее" onclick="javascript:loadCookie('item_form','study_activity.php');" title="будут автоматически подставлены данные предыдущей введенной записи в рамках одного сеанса" /> &nbsp; 
	<!--input type="button" value="deleteCookie" onclick="javascript:deleteCookie('item_form','study_activity.php');" /> &nbsp; 
	<input type="button" value="viewCookie" onclick="javascript:viewCookie();" /> &nbsp; <br/-->
	<label><input type=checkbox checked id="saveForm" name="saveForm" title="при вводе новой записи будут автоматически подставлены данные предыдущей"> запоминать последние данные ввода </label>
</form>
</div> 
<?php
}
}
//------------------------конец--добавление успеваемости студентов ---------------------------------------
else 
	{

	echo '<h4 class="notinfo"> Журнал успеваемости. <a href="students_view.php';
		if ($view_all_mode!==true) {echo '?kadri_id='.$_SESSION['kadri_id'];}	//фильтр студентов по дипл.руков-лю
	echo '">список студентов</a></h4>';

	if (!isset($_GET['archiv'])) {
	
		//$tmp_str='?'.$_SERVER["QUERY_STRING"];
		
		//if (strpos($tmp_str,'archiv')<=0) {$tmp_str=$tmp_str.'&archiv';}
		//where 
		$query_='select count(*) from `study_activity` sa where 1 and (sa.date_act<"'.$def_settings['date_start'].'" or sa.date_act is NULL) ';
		if ($kadri_id>0) {$query_.=' and `sa`.`kadri_id`="'.$kadri_id.'"';}
		$archiv_cnt=intval(getScalarVal($query_),10);
		
		echo '<a href="?'.$query_string.'&archiv" title="успеваемость прошлых учебных лет">архив успеваемости: '.$archiv_cnt.'</a><br>';
	}
	else {
		$query_='select count(*) from `study_activity` `sa` where 1 and `sa`.date_act>"'.$def_settings['date_start'].'" ';
		if ($kadri_id>0) {$query_.=' and `sa`.`kadri_id`="'.$kadri_id.'"';}
		$cur_cnt=intval(getScalarVal($query_),10);
 		echo '<a href="?'.reset_param_name(reset_param_name($query_string,'archiv'),'page').'" title="успеваемость прошлых учебных лет">успеваемость текущего учебного года: '.$cur_cnt.'</a><br>';}



$archiv_query=' and `sa`.date_act>"'.$def_settings['date_start'].'"';
if (isset($_GET['archiv'])) {$archiv_query=' and (`sa`.date_act<"'.$def_settings['date_start'].'" or `sa`.date_act is NULL)';}

$search_query='';
if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
$search_query=' and (subjects.name_short like "%'.$q.'%" or 
					sg.name like "%'.$q.'%" or 
					kadri.fio_short like "%'.$q.'%" or 
					students.fio like "%'.$q.'%" or 
					kadri.fio like "%'.$q.'%" or 
					study_act.name_short like "%'.$q.'%" or 					
					sm.name_short like "%'.$q.'%" or 
					sa.date_act like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
					sa.study_act_comment like "%'.$q.'%" or 
					sa.comment like "%'.$q.'%")';}
//

//выборка для показа списочной таблицы записей------------------------

//заголовки таблицы
$table_headers=array(1=>array('дата','40'));

array_push($table_headers, array('дисциплина','100'));

//не показываем столбец преподаватель, когда отбор по нему
if ($kadri_id==0) array_push($table_headers, array('преподаватель','100'));

array_push($table_headers, array('студент','200'));
array_push($table_headers, array('вид контроля','50'));
array_push($table_headers, array('#','20'));
array_push($table_headers, array('оценка','50'));
//    ,
//	2=>,
//	3=>,
//	4=>,
//	5=>,
//	6=>
//	);


$query='SELECT sa.date_act,
       subjects.name_short AS subj_name,';

if ($kadri_id==0) $query.='kadri.fio_short AS kadri_fio,';
       
$query.='students.fio AS stud_fio,
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
       students.group_id,
       sg.name AS gr_name,
       users.id AS user_id,
       subjects.name AS subj_n_full,
       sm.color as mark_color,
(SELECT COUNT(f.id_file) 
					  FROM    (documents d 
					           INNER JOIN
					              files f
					           ON (d.nameFolder = f.nameFolder))					       
					where d.subj_id=subjects.id) as f_cnt 					       
  FROM    (   (   (   (   (   (   study_activity sa
                               LEFT OUTER JOIN
                                  subjects subjects
                               ON (subjects.id = sa.subject_id))
                           LEFT OUTER JOIN
                              kadri kadri
                           ON (kadri.id = sa.kadri_id))
                       LEFT OUTER JOIN
                          users users
                       ON (kadri.id = users.kadri_id))
                   LEFT OUTER JOIN
                      study_marks sm
                   ON (sm.id = sa.study_mark))
               LEFT OUTER JOIN
                  students students
               ON (students.id = sa.student_id))
           LEFT OUTER JOIN
              study_groups sg
           ON (sg.id = students.group_id))
       LEFT OUTER JOIN
          study_act study_act
       ON (study_act.id = sa.study_act_id)';

$sort=1;
if (isset($_GET['sort']) && $_GET['sort']>1 && $_GET['sort']<=count($table_headers)) {
	 $sort=$_GET['sort'];
 }

if ($kadri_id>0)
	{$query=$query.' where sa.kadri_id="'.$kadri_id.'"'.$search_query.' '.$archiv_query.' order by '.$sort.' '.$stype.',4,5,6 ';}
else {$query=$query." where 1 ".$archiv_query."".$search_query." order by ".$sort." ".$stype.",4,5,6 ";}
	
$res=mysql_query($query.'limit '.(($page-1)*$pgVals).','.$pgVals);
//echo $query.'limit '.(($page-1)*$pgVals).','.$pgVals;

if (!isset($_GET['save']) && !isset($_GET['print'])) {
	echo '<p class="notinfo">
		<a href="?type=add&'.reset_param_name_ARR($query_string,array('gr_mode','group_id')).'" title="ввод сведение по отдельному студенту"> Добавить по студенту</a> &nbsp; 
		<a href="#" onclick=javascript:gr_mode_click("type=add&'.reset_param_name_ARR($query_string,array('gr_mode','group_id')).'&gr_mode=1")
		    title="возможность сразу проставить оценки  студентам одной учебной группы"> Добавить по группе (массово)</a>
	<p>';
if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?'.reset_param_name(reset_param_name ($query_string,'kadri_id'),'q').'"> сбросить фильтр </a></div>';}
}
	if (mysql_num_rows($res)==0) {echo '<p class=warning style="width:80%;text-align:center;">В текущем учебном году данных по успеваемости не обнаружено. ';
	if ($archiv_cnt>0) echo ' Возможно дата в журнале успеваемости раньше <u>'.DateTimeCustomConvert(substr($def_settings['date_start'],0,10),'d','mysql2rus').'</u>. Вы можете ознакомиться с архивом из <u>'.$archiv_cnt.'</u> записей</p>';
	}
	else {

if (!isset($_GET['save']) && !isset($_GET['print'])) {
//-------------------------------------  списочная таблица -----------------------------------------------------	
	echo '<table width=99% class="notinfo" border=0><tr>';	
	echo '<td align=left colspan=2>';
	
		
	
	$add_string=reset_param_name($query_string,'kadri_id');	//для фильтра по преп-лю
	$add_string=reset_param_name($add_string,'page');	//для перехода к первой странице сортировки по преп-лю
	
	//echo ' admin_role='.$admin_role;
 	if ($admin_role) {
	  	?>
	Преподаватель <select name="kadri_id" id="kadri_id" style="width:300;" onChange="javascript:window.location.href='?<?php echo reset_param_name_ARR($query_string,array('kadri_id','page')) ;?>&kadri_id='+this.options[this.selectedIndex].value;"> 
	<?php
//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		$query_='SELECT kadri.id, concat(kadri.fio, " (",count(*),") ", " - ", kadri_role(kadri.id,",")) AS fio
  FROM    (   kadri kadri
           INNER JOIN
              study_activity sa
           ON (kadri.id = sa.kadri_id))       
		LEFT OUTER JOIN
              subjects subjects
           ON (subjects.id = sa.subject_id)       
	     LEFT OUTER JOIN
                      study_marks sm
                   ON (sm.id = sa.study_mark)
               LEFT OUTER JOIN
                  students students
               ON (students.id = sa.student_id)
           LEFT OUTER JOIN
              study_groups sg
           ON (sg.id = students.group_id)
       LEFT OUTER JOIN
          study_act study_act
       ON (study_act.id = sa.study_act_id)       
WHERE kadri.id is not null '.$archiv_query.' '.$search_query.' 
GROUP by kadri.id
ORDER BY kadri.fio ASC';
echo getFrom_ListItemValue($query_,'id','fio','kadri_id');
		?>
</select>
	<?php
		  echo '&nbsp;&nbsp;<input type=button value="Все" onclick=javascript:window.location.href="'.$main_page.'";>&nbsp;&nbsp;';
		  }
 	else {echo 'Преподаватель: <b>'.getScalarVal('select fio from kadri where id="'.$kadri_id.'"').'</b>'; }
	
		echo showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'');
		echo ' </td>
		
	<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; 
		<input type=button value="Найти" title="поиск проводится в текущем разделе (архив или тек.уч.год) выбором соот.ссылки" OnClick=javascript:go2search(\''.$kadri_id.'\',\'';
			if (isset($_GET['archiv'])) {echo 'archiv';}
		echo '\');>
		</td>
	</tr></table>';}

//if (isset($_GET['archiv'])) {$filt_str='по архиву';}

$itemCnt=getScalarVal('select count(*) from ('.$query.')t');

if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {$pages_cnt=floor($itemCnt/$pgVals);}
 else {$pages_cnt=($itemCnt/$pgVals)+1;}

//echo '<div align="left"> страницы ';


$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

//for ($i=1;$i<=$pages_cnt;$i++) {if ($i!=$page) {echo '<a href="?'.$add_string.'&page='.$i.'"> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}}
//$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';

echo '<form name=order_list>
<table name=tab1 border=1 cellpadding="0" cellspacing="0" width="99%"><tr align="center" class="title" height="30">';
	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '<td width="50" class="notinfo"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}

//echo $query;

$add_string=reset_param_name($query_string,'sort');
	
//------------------------------------------- шапка списочной таблицы -начало-----------------------------------------------------
	echo '<td width="50">№</td>';

	for ($i=1;$i<=count($table_headers);$i++)
	{
		echo '<td width="'.$table_headers[$i][1].'">'.print_col($i,$table_headers[$i][0]).'</td>';
	}
	if (!isset($_GET['save']) && !isset($_GET['print'])) 
		{echo '<td width="100" class="notinfo">комментарий</td>';}
//------------------------------------------- шапка списочной таблицы -конец-----------------------------------------------------
	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};
	$i=0; $stud_prev='';
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		//если сортировка по студенту, выделить цветом чередование студентов
		if ($sort==3 && $kadri_id>0) {
			$stud_cur=$tmpval['stud_fio'].' ('.$tmpval['gr_name'].')';
			if ($stud_prev!=$stud_cur) {
				$stud_prev=$stud_cur;
				echo '<tr height=2 bgcolor=#3366FF><td colspan=8></td></tr>';
				}
			}
		echo '<tr align="left" class="main" '.$bgcolor.' valign="top">';
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		  echo '<td align="center"> 
		  	<a href="javascript:del_confirm_act(\''.str_replace(" ","_",f_ro(DateTimeCustomConvert($tmpval['date_act'],'d','mysql2rus').', '.$tmpval['stud_fio'])).'\',\'?item_id='.f_ro($tmpval['id']).'&type=del&'.$_SERVER["QUERY_STRING"].'\');" title="Удалить">		  	
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="?item_id='.$tmpval['id'].'&type=edit&'.$query_string.'" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		$i++;
		echo '<td>&nbsp;'.($i+($page-1)*$pgVals).'</td>';
		$date_act=$tmpval['date_act'];
		//$date_act=date("d.m.Y H:i:s",strtotime($tmpval['date_act']));
		$date_act=substr($date_act,0,10);
		$date_act=DateTimeCustomConvert($date_act,'d','mysql2rus');
		echo '<td>&nbsp;'.color_mark($q,$date_act).'</td>';
		
		echo '<td>&nbsp;';
		if ($tmpval['subj_name']=='') {$tmpval['subj_name']=$tmpval['subj_n_full'];}
//------------------------------
		if ($tmpval['f_cnt']>0)
		{
		 echo ' <a href="p_library.php?onget=1&getsubj='.$tmpval['subject_id'].'" title="'.$tmpval['subj_n_full'].', пособия по предмету">'.color_mark($q,$tmpval['subj_name']).' ('.$tmpval['f_cnt'].')</a> ';
		 }
		else {echo color_mark($q,$tmpval['subj_name']);}
//------------------------------		
		echo '</td>';
		if ($kadri_id==0) echo '<td>&nbsp;<a href="_modules/_lecturers/index.php?action=view&id='.$tmpval['user_id'].'" title="о преподавателе">'.color_mark($q,$tmpval[2]).'</td>';//
		
		echo '<td>&nbsp;<a href="students_view.php?item_id='.$tmpval['student_id'].'&type=edit" title="о студенте">'.color_mark($q,$tmpval['stud_fio']).' ('.color_mark($q,$tmpval['gr_name']).')</a></td>';

		echo '<td>&nbsp;'.color_mark($q,$tmpval['act_name']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['study_act_comment']).'</td>';
		echo '<td>&nbsp;<span style="color:'.$tmpval['mark_color'].';">'.color_mark($q,$tmpval['mark_name']).'</span></td>';

		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.color_mark($q,$tmpval['comment']).'</td>';}
	}
echo '</table></form>';
	}
//-------------------------------------списочная таблица -конец----------------------------------------------------

//постраничный вывод списка тем (по 10 тем)
$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';
//--------------------------------------------------------

$add_string=reset_param_name($add_string,'pgVals');// preg_replace("/(&pgVals=)(\d+)/x","",$add_string);		//убрать число страниц через RegExp
echo '<br>макс.число записей на странице:  <input type=text value="'.$pgVals.'" name="pgVals" id="pgVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgVals(\''.$add_string.'\');" value=Ok>
	<p> Всего записей: '.$itemCnt.'</div>'; 	
	}
?>
<div class="text">
<b>Примечание</b><br>
-при фильтрации списка по преподавателю и включении сортировки по студенту, добавляется <u>интеллектуальное</u> форматирование в виде выделения групп по каждому студенту для оценки его успеваемости, дополнительно рекомендуется включать фильтр по учебной группе студентов через поиск.
</div>
<?php
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>