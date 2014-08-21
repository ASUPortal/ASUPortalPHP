<?php
include ('authorisation.php');

/**
 * Удаление. Убрать из списка ссылку на этот метод, удалить его потом
 */
if (isset($_GET['type']) && $_GET['type']=='del' && intval($_GET['item_id'])>0 && $write_mode) {
	 $query='delete from diploms where id="'.intval($_GET['item_id']).'"';	
	 $res=mysql_query($query);	
	 header('Location:'.$curpage.'?'.reset_param_name_ARR($_SERVER['QUERY_STRING'],array('type','item_id')));
}


if (!$view_all_mode && (!isset($_GET['kadri_id']) || $_GET['kadri_id']!=$_SESSION['kadri_id'])) {
    header('Location:?kadri_id='.intval($_SESSION['kadri_id']).'');
}


include ('master_page_short.php');

//-----------настройка формы ------------
$showRez=true;	//скрывать рецензента в "+", иначе показывать его ФИО-кратко


//----------------------


$main_page=$curpage;
$page=1;
$kadri_id='';		//отбор по руководителю диплома
$q='';			//строка поиска
$pgVals=20;	//число тем на странице по умолчанию
$sort=4;
$filt_str_display='';	//отражение по чему идет отбор
$err=false;		//нет ошибок при изменении БД
$stype='asc';		//тип сортировки столбца
$notconfirm=false;	//не применять фильтр по не утвержденным темам
$archiv=false;		//признак архива записей


$query_string=$_SERVER['QUERY_STRING'];

if (isset($_GET['kadri_id']) && intval($_GET['kadri_id']>0) ) {$kadri_id=intval($_GET['kadri_id']);$filt_str_display.=' преподавателю;'.del_filter_item('kadri_id');}
if (isset($_GET['sort'])) {$sort=intval($_GET['sort']);}

if (isset($_GET['q']) && trim($_GET['q'])!='') {$q=f_ri($_GET['q']);$filt_str_display=$filt_str_display.'  поиску;'.del_filter_item('q');}

if (isset($_GET['page']) && intval($_GET['page'])>1) {$page=intval($_GET['page']);$filt_str_display=$filt_str_display.'  странице;';}
if (isset($_GET['pgVals']) && intval($_GET['pgVals'])<=999 && intval($_GET['pgVals'])>=1) {$pgVals=intval($_GET['pgVals']);$filt_str_display=$filt_str_display.' числу записей;';}

if (isset($_GET['item_id'])) {$item_id=intval($_GET['item_id']);}
if (isset($_GET['archiv'])) {$archiv=true;}

if (isset($_GET['stype']) && ($_GET['stype']=='desc' || $_GET['stype']=='asc')) {$stype=$_GET['stype'];}
if (isset($_GET['notconfirm']) && $_GET['notconfirm']=='true') {$notconfirm=true; $filt_str_display.=' не утвержденным;'.del_filter_item('notconfirm');}

//--------------------------------------------------------------------------------------------
?>

<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}

</style>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
<script type="text/javascript" src="scripts/calendar_init.js"></script>

<script language="JavaScript">
var main_page='<?php echo $main_page;?>';	//for redirect & links
function start_gr_event(form_name)	//групповые операции
{
	 if (check_cnt(form_name)) document.forms[form_name].submit();
	 else alert('Не выбран ни один элемент для групповой операции !');	 
}

function go2search(kadri_id,search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {href_addr='q='+search_query+'&kadri_id='+kadri_id;
		if (search_path=='archiv') {href_addr='archiv&'+href_addr+'';}	 	
		 
	 	window.location.href=main_page+'?'+href_addr;
	 }
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

function check_form()	//проверить данные формы перед отправкой
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
	 	new Array('dipl_name','тема диплома'),
	 	new Array('pract_place_id','место практики')
	);
	requireFieldCheck(a,'order_form');
	
	}

} 
</script>

<script type="text/javascript" src="scripts/jquery.autocomplete.js"></script>
<LINK href="css/autocomplete.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
	//массив полей автозаполнения: имя поля (#id), тип запроса к БД для выборки
	var fieldsArr=new Array(
		new Array("#recenz","recenz"),
		new Array("#pract_place","pract_place")
	);
</script>
<script type="text/javascript" src="scripts/autocomplete_custom.js"></script>

<h4 > <?php echo $pg_title ?> <a href="<?php echo WEB_ROOT; ?>_modules/_students/">список студентов</a></h4>
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
        time_intervals.date_end,
		time_intervals.id as year_id
    from
        time_intervals
    where
        time_intervals.id = ".CUtils::getCurrentYear()->getId();
if ($res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
    $def_settings=mysql_fetch_array($res_all);
} else {
 	$def_settings['year_id']='1';$def_settings['year_name']='2006-2007';
}


//добавление темы

if (isset($_POST['kadri_id']) && $write_mode) {
	if (($_POST['student_id']!=0 || $_POST['student_fio']!='') & $_POST['dipl_name']!="" && $_POST['pract_place_id']!="0") 
	{
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

	 //обновление темы
	 if (isset($_GET['type']) && $_GET['type']=='edit' && $item_id>0) {		 
		 $query="update diploms set
		 kadri_id='".intval($_POST["kadri_id"])."',
		 student_id='".intval($_POST["student_id"])."',
		 	pract_place_id='".intval($_POST["pract_place_id"])."',
		 	dipl_name='".f_ri($_POST["dipl_name"])."',
			date_act='".f_ri(DateTimeCustomConvert($_POST["date_act"],'d','rus2mysql'))."',			
			foreign_lang='".intval($_POST["foreign_lang"])."',
			study_mark='".intval($_POST["study_mark"])."',
			gak_num='".f_ri($_POST["gak_num"])."',
			recenz_id='".intval($_POST["recenz_id"])."',
			recenz='".f_ri($_POST["recenz"])."',
			comment='".f_ri($_POST["comment"])."',
			protocol_2aspir_id='".intval($_POST["protocol_2aspir_id"])."', 
			diplom_confirm=".intval($_POST["diplom_confirm"])." 
			  where id='".$item_id."'";

		 if ($res=mysql_query($query)) {
		  	echo '<div class=success> Запись обновлена  успешно.'.$onEditRemain_text.'</div><br>'; 	
						}
		 else {echo '<div class="warning">Запись не обновлена .<p>&nbsp;</div>';$err=true;}
		 //echo $query;
	 }
	 
	 //новая тема
	 if (isset($_GET['type']) & $_GET['type']=='add') {		 
		 $err=false;
		 if ($_POST['student_fio']!='') {//добавление студента в список студентов		  	
		  	$query="select max(id) as max_id from students";
		  	$res=mysql_query($query);$a=mysql_fetch_array($res);$id=$a['max_id']+1;
		  	
		  	$query="insert into students(id,fio) values('".$id."','".$_POST['student_fio']."')";
		  	//echo $query;
		  	if (mysql_query($query)) {echo ' Студент добавлен в список ';$_POST["student_id"]=$id;}
		  	else {echo '<div class="warning"> Ошибка добавления студента в список. Возможно такие ФИО там уже есть. Запись не добавлена.</div>';$err=true;}
			
		  }
		 if ($err==false) {
		 $query="insert into diploms(dipl_name,student_id,kadri_id,pract_place_id,comment,date_act,foreign_lang,recenz,study_mark,gak_num,recenz_id,protocol_2aspir_id,diplom_confirm) 
		 	values('".f_ri($_POST["dipl_name"])."',
			'".intval($_POST["student_id"])."',
			'".intval($_POST["kadri_id"])."',
			'".intval($_POST["pract_place_id"])."',
			 '".f_ri($_POST["comment"])."',
			 '".f_ri(DateTimeCustomConvert($_POST["date_act"],'d','rus2mysql'))."','".f_ri($_POST["foreign_lang"])."',
			 '".f_ri($_POST["recenz"])."',
			 '".f_ri($_POST["study_mark"])."',
			 '".f_ri($_POST["gak_num"])."',
			 '".intval($_POST["recenz_id"])."',
			 '".intval($_POST["protocol_2aspir_id"])."',
			 '".intval($_POST["diplom_confirm"])."' 
			 )";
		 $res_news=true;
		 if ($res_news & $res=mysql_query($query)) {


		  	echo '<div class=success> Запись "'.$_POST['dipl_name'].'" добавлена успешно.'.$onEditRemain_text.'</div><br>';
			}
		 else {echo '<div class="warning">Запись не добавлена. Возможно пара значений:студент-преподаватель там уже есть</div><br>';$err=true;}
		 
			 }	
	//echo $query;
	 }
	 
	 }
	else {echo '<div class="warning">Часть обязательных данных не заполнено .<br>&nbsp;</div>';$err=true;}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
} 

if (isset($_GET['type']) && $_GET['type']=='edit' && $write_mode)	//Правка темы
{
	if ($item_id>0)
	{
	$query="select * from diploms where id='".$item_id."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="warning">не выбрана Запись для правки</h4>';}	
}

//добавление тем
if (isset($_GET['type']) && ($_GET['type']=='add' || $_GET['type']=='edit') && $write_mode)
{
?>
<div><a href="<?php echo 	$main_page.'?'.reset_param_name(reset_param_name($query_string,'item_id'),'type');?>">Просмотр тем </a></div>
<h4> <?php if(isset($_GET['type']) && $_GET['type']=='edit') {echo 'Правка';} else {echo 'Ввод';} ?> новой темы диплома </h4>
<div class="forms_under_border" style="width:99%;">
<form name="order_form" id="order_form" method="post" action="">

Преподаватель<span class=warning>*</span> <select name="kadri_id" id="kadri_id" style="width:300;"> 
		<?php
        foreach (CStaffManager::getAllPersons()->getItems() as $person) {
            echo '<option value="'.$person->getId().'">'.$person->getName().'</option>';
        }
        /*
		$query='SELECT kadri.id  as id,concat(kadri.fio," (",(select count(*) from `diploms` where diploms.kadri_id=kadri.id),")") as fio 
			FROM kadri ';
		if (!$view_all_mode) {$query.=' where kadri.id="'.$kadri_id.'"';}
		$query.=' order by 2';
		
		echo getFrom_ListItemValue($query,'id','fio','kadri_id');
        */
		?>
</select> <?php echo sprav_edit_link('kadri');?>
<p>
Студент <span class=warning>*</span> <a class=help title="привязка к текущему учебному году">Г</a><span id="student_id_loading" class="cascadeSelect_loading" style="display:none";> </span> 
	 <span id="student_id_" name="student_id_"><select name="student_id" id="student_id" style="width:300;"> 
	 <?php
	 $query='SELECT s.id, concat(s.fio," (",sg.name,")") as fio
		   FROM    study_groups sg RIGHT OUTER JOIN  students s  ON (sg.id = s.group_id) ';
	 if ($archiv) $query.='WHERE (sg.year_id < '.$def_settings['year_id'].' or sg.year_id is NULL) ';  
	 else $query.='WHERE (sg.year_id = '.$def_settings['year_id'].') ';
	 $query.='order by fio';
	 
	 echo getFrom_ListItemValue($query,'id','fio','student_id');
	 ?>
</select> <?php echo sprav_edit_link('students');?> &nbsp; 
<label class=warning title="отразить архивные записи"><input type=checkbox  onclick="javascript:uploadList('student_id','stud_list','<?php echo intval($res_edit['student_id']); ?>',String(!this.checked));" <?php echo ($archiv?' checked':'') ?> >архив</label> 

</span>  
<p>
	Место практики <span class=warning>*</span> <br> 
<select name="pract_place_id" id="pract_place_id" style="width:450;"> 
		<?php
		$query='select id,concat(name," (",replace((select count(*) from `diploms` where diploms.pract_place_id=pract_places.id),"0","-"),")") as name from pract_places order by name';
		echo getFrom_ListItemValue($query,'id','name','pract_place_id');
		?>
</select> <?php echo sprav_edit_link('pract_places');?> &nbsp;

<input type="text" name="pract_place_filter" id="pract_place_filter" value="" size="20" maxlength="40" onKeyPress="javascript:do_filter_if_enter_press(event);" /> 
<script language="JavaScript">
	 var pract_place_filter=document.getElementById('pract_place_filter');
	 var pract_place_filter_cancel_btn=document.getElementById('pract_place_filter_cancel_btn');
function pract_place_filter_do()	// применить фильтр
{
	if (pract_place_filter!=null)  {	
		  uploadList('pract_place_id','pract_list',null,pract_place_filter.value,true);
		  hide_show('pract_place_filter_cancel_btn','show');
	}	
}
function pract_place_filter_cancel()	// применить фильтр
{
	if (pract_place_filter!=null)  {
		  pract_place_filter.value="";
		  uploadList('pract_place_id','pract_list',null,pract_place_filter.value,false);
		  hide_show('pract_place_filter_cancel_btn','hide');
	}
	 
}
function do_filter_if_enter_press(e)
{
    key = e.keyCode || e.which;    
    if(key == 13) pract_place_filter_do();
}
</script>

<a name="pract_place_filter_do_btn" id="pract_place_filter_do_btn" value="фильтр" title="применить фильтр" onclick="javascript:pract_place_filter_do();">
<img src="images/filter.gif" alt="сбросить фильтр" >
</a>	 

<a name="pract_place_filter_cancel_btn" id="pract_place_filter_cancel_btn" value="очистить" title="сбросить фильтр" onclick="javascript:pract_place_filter_cancel();" style="display:none;">
<img src="images/del_multi_filter.gif" alt="фильтр" >
</a>
	<p>
	Тема диплома <span class=warning>*</span><br><textarea name=dipl_name id=dipl_name cols=75 rows=6><?php echo getFormItemValue('dipl_name'); ?></textarea> <p>
	 
	 <table>
	 <tr>
		  <td>
		  Дата защиты <span class=warning>*</span> (<small> например 13.06.2007</small>)
		  </td>
		  <td style="padding-left:40px;">
		  Гриф утверждения <a class=help title="выделение цветом элементов списка">Ц</a>
		  </td>
	 </tr>
	 <tr>
	 <td>
	 <input type=text maxlength=10 size=15 id=date_act name=date_act value="<?php if (isset($res_edit)) {echo f_ro(DateTimeCustomConvert(substr($res_edit['date_act'],0,10),'d','mysql2rus'));}else if (isset($_POST['date_act'])) {echo $_POST['date_act'];}else {  
	 //дата защиты с учетом 1 июня тек.учеб.года
	 if ($def_settings['date_start']>date("Y").'-06-01') 
		 {echo '01.06.'.(date("Y")+1);} 
		 else {echo '01.06.'.date("Y");} 
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
		  </script>  	  
	 </td>
	 <td style="padding-left:40px;">
	 <select name="diplom_confirm" id="diplom_confirm" style="width:300;">	 
	<?php
	$query='select id,name,color_mark from `diplom_confirms` order by name desc';
	echo getFrom_ListItemValue($query,'id','name','diplom_confirm',null,true);
	?>
	
</select><p>
	 </td>
	 </tr>
	
	 <tr>
	 <td>
	Оценка <br>
<select name="study_mark" id="study_mark" style="width:200;"> 
	<?php
	$query='select id,name from study_marks order by name_short desc';
	echo getFrom_ListItemValue($query,'id','name','study_mark');
	?>	
</select> <?php echo sprav_edit_link('study_marks');?>
	 </td>
	 <td style="padding-left:40px;">
	 Номер ГЭК <br/>
         <select name="gak_num">
             <?php
             $query = "select id, title from sab_commission where year_id = ".CUtils::getCurrentYear()->getId()." order by title asc";
             echo getFrom_ListItemValue($query, "id", "title", "gak_num");
             ?>
         </select>
	 <td>
	 </td>
	 </tr>
	 </table>
<p> 
	протокол рекомендации в аспирантуру (при наличии) <br>
<select name="protocol_2aspir_id" id="protocol_2aspir_id" style="width:200;" > 
	<?php
		$query_='select pt.id, concat(p.num," от ",DATE_FORMAT(p.date_text,"%d.%m.%Y") ," (", 
			   (select count(*) from diploms d where d.protocol_2aspir_id=pt.id),")") as name 
			   from protocol_2aspir pt left join protocols p on p.id=pt.protocol_id order by 2 desc';
echo getFrom_ListItemValue($query_,'id','name','protocol_2aspir_id');
		?>
</select>							       
	<p> 
	Защита на иностранном языке<br>
<select name="foreign_lang" id="foreign_lang" style="width:300;"> 
	<?php
	$query='select id,name from language order by name';
	echo getFrom_ListItemValue($query,'id','name','foreign_lang');
	?>
	
</select> <?php echo sprav_edit_link('language');?> <p> 
	Рецензент <br>
<select name="recenz_id" id="recenz_id" style="width:300;"> 
	<?php
	$query='select k.id,k.fio from kadri k where kadri_role(k.id,",") like "%реценз%" order by k.fio';
	echo getFrom_ListItemValue($query,'id','fio','recenz_id');
	?>	
</select> <?php echo sprav_edit_link('kadri');?>
<br>
c рецензент вне списка <a class=help title="автозаполнение">А</a>
 <p> 

	Комментарий<br><input type=text size=100 name=comment value="<?php echo getFormItemValue('comment'); ?>"> <p>
	<input type=button onclick=javascript:check_form(); value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>
</div> 
<?php
}

else {
	if (!isset($_GET['archiv'])) {
		$query_='select count(id) from `diploms` where 1 and (date_act<"'.$def_settings['date_start'].'") ';
		if ($kadri_id>0) {
            $query_.=' and `diploms`.`kadri_id`="'.$kadri_id.'"';
        }
		$archiv_cnt=intval(getScalarVal($query_),10);
		
		echo '<a href="?'.$query_string.'&archiv" title="защиты прошлых учебных лет">архив: '.$archiv_cnt.'</a><br>';
	} else {
		$query_='select count(id) from `diploms` where 1 and (date_act>="'.$def_settings['date_start'].'" or date_act is NULL) ';
		if ($kadri_id>0) {
            $query_.=' and `diploms`.`kadri_id`="'.$kadri_id.'"';
        }
		$cur_cnt=intval(getScalarVal($query_),10);
 		echo '<a href="?'.reset_param_name(reset_param_name($query_string,'archiv'),'page').'" title="защиты прошлых учебных лет">дипломные проекты текущего учебного года: '.$cur_cnt.'</a><br>';
    }
    $archiv_query=' and (date_act>="'.$def_settings['date_start'].'" or date_act is NULL)';
    if (isset($_GET['archiv'])) {
        $archiv_query=' and (date_act<"'.$def_settings['date_start'].'" )';
    }
    $search_query='';
    if ($q!='') {
        echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
        $search_query=' and (convert(diploms.dipl_name USING utf8) like "%'.$q.'%" or
					convert(pp.name USING utf8) like "%'.$q.'%" or 
					convert(students.fio USING utf8) like "%'.$q.'%" or '.
					($kadri_id==0?'convert(kadri.fio USING utf8) like "%'.$q.'%" or ':' ').
					'convert(study_groups.name USING utf8) like "%'.$q.'%" or 
					convert(k2.fio_short USING utf8) like "%'.$q.'%" or 					
					diploms.date_act like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or 
					dp.date_preview like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
					convert(diploms.gak_num USING utf8) like "%'.$q.'%" or 
					convert(diploms.comment USING utf8) like "%'.$q.'%")';
    }

	$table_headers = array(
		1 => array(
            'утв.','10'
        ),
		array(
            'тема','200'
        ),
		array(
            'место практики','10')
	);
		
    if ($kadri_id==0) {
        array_push($table_headers, array('преподаватель','100'));
    }
		  
    array_push($table_headers,
        array(
            'студент','100'
        ),
        array(
            'уч.группа','50'
        ),
        array(
            'пред. защита','50'
        ),
        array(
            'дата защиты','50'
        ),
        array(
            'ин/яз.','20'
        ),
        array(
            'аспир.','20'
        ),
        array(
            'рец.','20'
        ),
        array(
            'оц.','20'
        ),
        array(
            '№ГАК','20'
        )
	);

    $def_sort=1;
    if ($sort<1 && $sort>=cont($table_headers)) {
        $sort=$def_sort;
    }
 
//	-----------------------групповые операции начало------------------------------

    if (isset($_GET['gr_act']) && isset($_POST['diplom_confirm']))	{
        $diplom_confirm=intval($_POST['diplom_confirm']);
        $err=false;

        while (list($key, $value) = each ($_POST)) {
            if (strstr($key,"checkbox_tab_item_")) {
                $act_item_id=intval(preg_replace("/\D/","",$key));
                $query_gr_act='update diploms set diplom_confirm='.$diplom_confirm.' where id='.$act_item_id.' limit 1';
		    
                if (!($res=mysql_query($query_gr_act))) {
                    $err=true;
                    echo '<div class=warning> ошибка группового обновления записи id='.$act_item_id.'</div>';
                }
            }
        }

        if ($err==true)	{
            echo '<div class=warning> Произошли ошибки при выполнении массовой операции </div>';
        } else {
            echo '<div class=success> Выполнение массовой операции успешно</div>';
        }
    }
//	-----------------------групповые операции конец------------------------------

//выборка для показа списочной таблицы записей

$query='
    SELECT
        dc.name as dc_name,
        diploms.dipl_name,
        pp.name as pract_place,
        commission.title as commission_title, '.
	    ($kadri_id==0?'kadri.fio as kadri_fio,':'').'
	    students.fio as student_fio,
	    study_groups.name as group_name,
	    dp.date_preview,
	    diploms.date_act,
	    language.name as foreign_lang,
	    diploms.protocol_2aspir_id,
	    k2.fio_short as rez_fio,
	    study_marks.name_short as study_mark, diploms.gak_num,
kadri.id as kadri_id,diploms.comment,diploms.id,students.id as student_id,users.id as user_id,recenz_id,diploms.recenz,dc.color_mark as dc_color_mark        
	FROM diploms
	    left join students on diploms.student_id=students.id
		left join kadri on diploms.kadri_id=kadri.id
		left join kadri k2 on diploms.recenz_id=k2.id
		left join study_groups on study_groups.id=students.group_id 
		left join users on users.kadri_id=kadri.id 
		left join language on language.id=diploms.foreign_lang 
        left join pract_places pp on pp.id=diploms.pract_place_id
		left join study_marks on study_marks.id=diploms.study_mark 
		left join diplom_confirms dc on dc.id=diploms.diplom_confirm
		left join  (select student_id,max(date_preview) as date_preview from diplom_previews group by student_id) dp
		  on dp.student_id=diploms.student_id
		  
		left join sab_commission as commission on
			commission.id = diploms.gak_num
		';

if (isset($kadri_id) & $kadri_id!=0) {
    $search_query.=' and kadri.id="'.$kadri_id.'"';
}

if ($notconfirm) {
    $search_query.=' and (diploms.diplom_confirm is null or diploms.diplom_confirm=0) ';
}

$query=$query." where 1 ".$archiv_query."".$search_query;

    /**
     * Сортировки разнообразные
     * 1 - Статус утверждения
     * 2 - Тема диплома
     * 3 - Место практики
     * 4 - Преподаватель
     * 5 - Студент
     * 6 - Учебная группа
     * 7 - Дата предзащиты
     * 8 - Дата защиты
     * 9 - Иностранный язык
     * 10 - Протокол рекомендации в аспирантуру
     * 11 - Рецензент
     * 12 - Оценка
     * 13 - Номен ГАК
     */
    $orderParams = array(
        1 => "dc.name",
        2 => "diploms.dipl_name",
        3 => "pp.name",
        4 => "kadri.fio",
        5 => "students.fio",
        6 => "study_groups.name",
        7 => "dp.date_preview",
        8 => "diploms.date_act",
        9 => "language.name",
        10 => "diploms.protocol_2aspir_id",
        11 => "k2.fio",
        12 => "study_marks.name",
        13 => "commission.title"
    );
    $order = $orderParams[$sort];

    $res=mysql_query($query.' order by '.$order.' '.$stype.' limit '.(($page-1)*$pgVals).','.$pgVals);
//echo $query;

if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode) {
    echo '<p class="notinfo"><a href="'.WEB_ROOT.'_modules/_diploms/?action=add"> Добавить</a><p>';
    if ($filt_str_display!='') {
        echo '<div class=text><img src="images/filter.gif" alt="фильтр" border=0>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; сбросить фильтр<a class=button href="?'.reset_param_name_ARR($query_string,array('kadri_id','page','q','notconfirm')).'" title="сбросить фильтр"><img src="images/del_multi_filter.gif" alt="сбросить фильтр" border=0></a></div>';
    }
}
	if (mysql_num_rows($res)==0) {
	 
	if (!isset($_GET['archiv'])) echo '<p class=warning style="font-size:12pt; text-align:center;">в текущем году записей не найдено, попробуйте поискать в  <a href="?'.reset_param_name($query_string,'archiv').'&archiv">архиве</a> ';
	else echo '<p class=warning style="font-size:12pt; text-align:center;">в архиве записей не найдено, попробуйте поискать в  
		<a href="?'.reset_param_name($query_string,'archiv').'">текущем году</a> ';
	if ($search_query!='') echo ', либо <a href="?">сбросить фильтр</a>';
	echo '</p>';
	 
	 }
	else {

if (!isset($_GET['save']) && !isset($_GET['print'])) {
//-------------------------------------  списочная таблица -----------------------------------------------------	
	echo '<table width=99% class="notinfo" border=0><tr>';	
	echo '<td align=left colspan=2>';
	
		
	
	$add_string=reset_param_name($query_string,'kadri_id');	//для фильтра по преп-лю
	$add_string=reset_param_name($add_string,'page');	//для перехода к первой странице сортировки по преп-лю
	
	
 	if ($view_all_mode) {
	  	?>
	Преподаватель <select name="kadri_id" id="kadri_id" style="width:300;" onChange="javascript:window.location.href='?<?php echo reset_param_name($query_string,'kadri_id');?>&kadri_id='+this.options[this.selectedIndex].value;"> 
	<?php
        /*
		$query_='SELECT kadri.id, concat(kadri.fio, " (",count(*),") ", " - ", kadri_role(kadri.id,",")) AS fio
  FROM    (   kadri 
           INNER JOIN
              diploms 
           ON (kadri.id = diploms.kadri_id))       
WHERE kadri.id is not null '.$archiv_query.'  
GROUP by kadri.id
ORDER BY kadri.fio ASC';
echo getFrom_ListItemValue($query_,'id','fio','kadri_id');
        */
            foreach (CStaffManager::getAllPersons()->getItems() as $person) {
                echo '<option value="'.$person->getId().'">'.$person->getName().'</option>';
            }
		?>
</select>
	<?php
		  echo '&nbsp;&nbsp;<input type=button value="Все" onclick=javascript:window.location.href="'.$main_page.'";>&nbsp;&nbsp;';
		  }
 	else {echo 'Преподаватель: <b>'.getScalarVal('select fio from kadri where id="'.$kadri_id.'"').'</b>'; }
	
		echo showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'');
		?>
		  <br>
		  <label class=text><input type=checkbox name=not_confirm id=not_confirm onclick="javascript:window.location.href='?<?php echo reset_param_name($_SERVER['QUERY_STRING'],'notconfirm');?>&notconfirm='+this.checked;" <?php echo ($notconfirm?'checked':'')  ?>  >
		 показать только не утвержденные (<span class=warning><?php echo getScalarVal('select count(*) from ('.($notconfirm?$query:$query.' and (diploms.diplom_confirm is null or diploms.diplom_confirm=0)').')t'); ?></span>)</label>
		<?php
		echo ' </td> 
	<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; 
		<input type=button value="Найти" title="поиск проводится в текущем разделе (архив или тек.уч.год) выбором соот.ссылки" OnClick=javascript:go2search(\''.$kadri_id.'\',\'';
			if (isset($_GET['archiv'])) {echo 'archiv';}
		echo '\');>
		<div class=text style="text-align:right"> кроме полей: ин.яз.,оц. <br>
		Поиск по дате в формате дд.мм.гггг или гггг-мм-дд</div></td>
	</tr></table>';}

//-----------------------------------------------------
$itemCnt=getScalarVal('select count(*) from ('.$query.')t');

if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {$pages_cnt=floor($itemCnt/$pgVals);}
else {$pages_cnt=($itemCnt/$pgVals)+1;}

$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';
//-----------------------------------------------------
echo '<form name="order_list" id="order_list" method="POST" action="?'.reset_param_name($query_string,'gr_act').'&gr_act">
<table name=tab1 id=tab1 border=1 cellpadding="0" cellspacing="0" width="99%"><tr align="center" class="title" height="30">';
	if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode) {
		echo '
		<td width="10"><input type=checkbox name="checkbox_all" id="checkbox_all" title="групповые операции" onClick="javascript:mark_all_checkbox(this.id,\'order_list\');"> </td>
		<td width="50" class="notinfo"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}

$add_string=reset_param_name($query_string,'sort');
	
//------------------------------------------- шапка списочной таблицы -начало-----------------------------------------------------
	echo '<td width="50">№</td>';

	for ($i=1;$i<=count($table_headers);$i++)
	{
		echo '<td width="'.$table_headers[$i][1].'">'.print_col($i,$table_headers[$i][0]).'</td>';
	}
	if (!isset($_GET['save']) && !isset($_GET['print']) ) 
		{echo '<td width="100" class="notinfo">комментарий</td>';}
//------------------------------------------- шапка списочной таблицы -конец-----------------------------------------------------
	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};
	$i=0;
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		echo '<tr align="left" class="row_light" '.$bgcolor.' id="row'.$tmpval['id'].'" valign="top" >';
		if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode) {
		  echo '<td width="10"><input type=checkbox id="checkbox_tab_item_'.$tmpval['id'].'" name="checkbox_tab_item_'.$tmpval['id'].'" title="выбор элемента"> </td>
			<td align="center" > 
		  	<a href="javascript:del_confirm_act(\''.f_ro(substr($tmpval['dipl_name'],0,50)).'...\',\'?item_id='.$tmpval['id'].'&type=del&'.reset_param_name_ARR($query_string,array('item_id','type')).'\');" title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="'.WEB_ROOT.'_modules/_diploms/?action=edit&id='.$tmpval["id"].'" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		$i++;
		echo '<td>&nbsp;'.($i+($page-1)*$pgVals).'</td>';
		echo '<td title="'.$tmpval['dc_name'].'" '.($tmpval['dc_color_mark']!=''?' style="background-color:'.$tmpval['dc_color_mark'].';"':'').'>&nbsp;</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['dipl_name']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['pract_place']).'</td>';
		if ($kadri_id==0) echo '<td>&nbsp;<a href="p_lecturers.php?onget=1&idlect='.$tmpval['user_id'].'" title="о преподавателе">'.color_mark($q,$tmpval['kadri_fio']).'</td>';//
		//echo '<td>&nbsp;<a href="students_view.php?item_id='.$tmpval['student_id'].'&type=edit" title="о студенте">'.color_mark($q,$tmpval['student_fio']).'</a></td>';
		echo '<td>&nbsp;<a href="'.WEB_ROOT."_modules/_students/?action=edit&id=".$tmpval['student_id'].'" title="о студенте">'.color_mark($q,$tmpval['student_fio']).'</a></td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['group_name']).'</td>';
		
		
		$date_act=$tmpval['date_preview'];		
		$date_act=substr($date_act,0,10);
		$date_act=DateTimeCustomConvert($date_act,'d','mysql2rus');
		echo '<td>&nbsp;'.color_mark($q,$date_act).'</td>';

		$date_act=$tmpval['date_act'];		
		$date_act=substr($date_act,0,10);
		$date_act=DateTimeCustomConvert($date_act,'d','mysql2rus');
		echo '<td>&nbsp;'.color_mark($q,$date_act).'</td>';
		$foreign_lang='';
		if (trim($tmpval['foreign_lang'])!='') {$foreign_lang='<a href="#" title="'.f_ro($tmpval['foreign_lang']).'">+</a>';}
		echo '<td>&nbsp;'.$foreign_lang.'</td>';
		
		$protocol_2aspir='';
		if (intval($tmpval['protocol_2aspir_id'])>0)
		{
		  $prot_stat=getRowSqlVar('select p.id, concat(p.num," от ",DATE_FORMAT(p.date_text,"%d.%m.%Y")) as name
		  from protocol_2aspir p2a
		  left join protocols p on p.id=p2a.protocol_id
		  where p2a.id='.intval($tmpval['protocol_2aspir_id']));
		  $protocol_2aspir='<a href="protocols_view.php?item_id='.intval($prot_stat[0]['id']).'&type=view" title="перейти к протоколу">прот. '.$prot_stat[0]['name'].'</a>';}
		echo '<td>&nbsp;'.$protocol_2aspir.'</td>';
		
		
		$recenz='';
		if (trim($tmpval['recenz'])!='' || trim($tmpval['rez_fio'])!='') 
			{
			 if ($showRez) {
			  	$recenz=color_mark($q,f_ro($tmpval['rez_fio']));
				if (trim($tmpval['recenz'])!='') {$recenz.='&nbsp; <a href="#" title="'.f_ro($tmpval['recenz']).'">+</a>';} 
				  }
			 else {$recenz='<a href="#" title="'.f_ro($tmpval['rez_fio']).'-'.f_ro($tmpval['recenz']).'">+</a>';}
			 }
		echo '<td>&nbsp;'.$recenz.'</td>';

		echo '<td>&nbsp;'.$tmpval['study_mark'].'</td>';
		//echo '<td>&nbsp;'.$tmpval['gak_num'].'</td>';
		echo '<td><a href="'.WEB_ROOT.'_modules/_state_attestation/?action=edit&id='.$tmpval['gak_num'].'">'.$tmpval['commission_title'].'</a>&nbsp;</td>';

		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.color_mark($q,$tmpval['comment']).'</td>';}
	}
echo '</table>';
?>
<script language="javascript">
	 markTableRowsInit('tab1');	//сделать выделение строки в IE
</script>
	 <?php
	 if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode) {
	 ?>
	 <span class=text>утвердить несколько дипломных проектов </span>
	 <select id="diplom_confirm" name="diplom_confirm" style="width:200;" title="гриф утверждения"><?php 
		  $query='select id,name,color_mark from `diplom_confirms` order by name desc';
		  echo getFrom_ListItemValue($query,'id','name','diplom_confirm',null,true);
	 ?>
	 </select>	 
	 <input type=button value="Ok" onClick="javascript:start_gr_event('order_list');" style="width:40;">
</form>	
	 <?php
}

//-------------------------------------------------------
echo '<div align="center"> страницы ';
$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo getPagenumList($pages_cnt,$page,6,'page',$add_string,'');
echo '</div>';
//--------------------------------------------------------

$add_string=reset_param_name($add_string,'pgVals'); 
echo '<br>макс.число записей на странице:  <input type=text value="'.$pgVals.'" name="pgVals" id="pgVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgValsCh(\''.$add_string.'\');" value=Ok>
	<p> Всего записей: '.$itemCnt.'</div>'; 	
	}
//-------------------------------------списочная таблица -конец----------------------------------------------------

//постраничный вывод списка тем (по 10 тем)
	
	}
?>
<div class=text>
	<b>Примечание</b> <br>	
	<ul>
	<li><?php mark_new("21.12.2011")?>для подбора и выбора  "Место практики" используйте фильтр <img src="images/filter.gif" alt="сбросить фильтр" >. Выборка производится по наименованиям и примечаниям мест практики</li>
	  <li>при редактировании записи текущего учебного года Вы можете задать поиск студента в архиве, используя для этого галочку "архив" в форме правки записи</li>
	  <li>использование галочки "архив" в форме правки записи требуется в случае если диплом относится к текущему году (не заполнена дата защиты), а студент диплома - к архиву (учебная группа отнесена к прошлому учебному году) </li>
	  <li>"Тема дипломного проекта", "Рецензент" используются при формировании Предзащиты студента</li>
	  <li>Вы можете использовать массовое утверждение дипломных проектов выбрав раздел "утвердить несколько дипломных проектов" в списочной форме</li>
	  <li>дата предзащиты формируется из задачи <a href="diploms_preview.php">"Пред.защита дипл.проектов"</a></li>
	</ul>
</div>	
<?php
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>
