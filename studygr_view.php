<?php
include 'authorisation.php';
//работа со студенческими учебными группами

$page=1;
$q='';			//строка поиска
$pageVals=20;	//число записей на странице по умолчанию
$query_string=$_SERVER['QUERY_STRING'];

$filt_str_display="";

$item_id=0;$year_id=0;
$stype='asc';		//тип сортировки столбца
$sort=1;

//----------------------------------------------------------
if (isset($_GET['action']) && $_GET['action']=='del' && intval($_GET['item_id'])>0)
	{
	//echo '!!!!!!!del!!!!!!';
	$query='delete from `study_groups` where id="'.intval($_GET['item_id']).'"';	
	//echo $query;
	$res=mysql_query($query);

	$query_string=reset_param_name($query_string,'action');
	$query_string=reset_param_name($query_string,'item_id');
	header('Location:'.$curpage.'?'.$query_string);
			
	}
//------------------------------------------------------------------------
include ('master_page_short.php');

if (isset($_GET['q'])) {$q=$_GET['q'];}
if (isset($_GET['item_id']) && intval($_GET['item_id'])>0) {$item_id=intval($_GET['item_id']);}
if (isset($_GET['year_id']) && intval($_GET['year_id'])>0) {$year_id=intval($_GET['year_id']);}

if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}
if (isset($_GET['pageVals']) && $_GET['pageVals']<=99 && $_GET['pageVals']>=1) {$pageVals=$_GET['pageVals'];}
if (isset($_GET['stype']) && $_GET['stype']=='desc') {$stype=$_GET['stype'];}
if (isset($_GET['sort'])) {$sort=$_GET['sort'];}

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

?>
<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}
.err {color:red;font-family:Arial;}
</style>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
<script type="text/javascript" src="scripts/calendar_init.js"></script>
<script language="JavaScript">
var main_page="<?php echo $curpage;?>";
function pgVals()
{
 var pageVal=document.getElementById('pageVals');
 if (pageVal.value>0 && pageVal.value<100) {
 	window.location.href='?<?php echo reset_param_name($query_string,'pageVals');?>&pageVals='+pageVal.value;}
 else {alert('необходимо: '+pageVal.title);}
 } 
function check_form()
{
 	a = new Array(
	 	new Array('name','')
	);
requireFieldCheck(a,'item_form');
 
} 
function go2search(kadri_id,search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {
	  	href_addr='q='+search_query+'&<?php echo reset_param_name($query_string,'q'); ?>';
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
} 
function test_copy()	//для массовых операций
{
 	a = new Array(
	 	new Array('year_id_copy',''),
	 	new Array('speciality_id_copy','')
	);	 	
	requireFieldCheck_mass_operation(a,'item_list');
}
</script>
<?php
	echo '<h4 class="notinfo">'.$pg_title.'</h4>	';

//массовые операции 	----------------------------------------------------------------------------
$err=false;
$year_id_copy=intval($_POST['year_id_copy'],10);
$speciality_id_copy=intval($_POST['speciality_id_copy'],10);
$query_mas='';
$cnt_mas=0;
//echo 'year_id_copy='.$year_id_copy.'  speciality_id_copy='.$speciality_id_copy;
if ($year_id_copy>0 || $speciality_id_copy>0) {
	
	while (list($key, $value) = each ($_POST)) {
	 	if 	  (strstr($key,"checkbox_h_copy_")) {
			$mail_id=substr($key,strpos($key,'copy_')+5);	//выдираем ID из названий чекбоксов
			//echo '<div>$mail_id='.$mail_id.'</div>';
			
		$query_mas="update  `study_groups` set ";
		if ($speciality_id_copy>0)	$query_mas.="`speciality_id`='".$speciality_id_copy."',"; 
		if ($year_id_copy>0)	$query_mas.="`year_id`='".$year_id_copy."',"; 
		$query_mas=substr($query_mas,0,strlen($query_mas)-1);	//удаляем последнюю ","
		
		$query_mas.=" where id='".$mail_id."' limit 1";
		//echo $query;

		if (!mysql_query($query_mas)) {$err=true;echo '<div class=warning> ошибки массовой операции</div>';}
		else {$cnt_mas++;}		
		}	   }
	
	if ($err==true)	{echo '<div class=warning> Произошли ошибки </div>';}
	else {echo '<div class=success> Массовая операция успешно завершена для <font size=+1>'.$cnt_mas.'</font> элементов</div>';}
}
//----------------------------------------------------------------------------------------------

if (isset($_POST['name']))
{
	if ($_POST['name']!="") 
  {
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'action'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

	 if ($_GET['action']=='add') {	//добавление
		 $query="insert into `study_groups`(`name`,man_cnt,speciality_id,head_student_id,year_id,comment,photogallery_link,curator_id)
		 values ('".f_ri($_POST["name"])."',
		 '".intval($_POST["man_cnt"])."',
		 '".intval($_POST["speciality_id"])."',
		 '".intval($_POST["head_student_id"])."',
		 '".intval($_POST["year_id"])."',
		 '".f_ri($_POST["comment"])."',
		 '".f_ri($_POST["photogallery_link"])."',
		 '".intval($_POST["curator_id"])."'
        )";

		 if ($res=mysql_query($query) && !$err) {
		  	echo '<div class=success> Запись добавлена  успешно.'.$onEditRemain_text.'</div><br>';
		 				}
		 else {echo '<div class="warning">Запись не добавлена .<p>&nbsp;</div>';$err=true;}
	 }
	 else if ($_GET['action']=='edit') //обновление
	 {
	  	if ($item_id>0) {
		 //SELECT id,name,comment,man_cnt,speciality_id,head_student_id,year_id FROM `study_groups ` where id='20'
		 $query="update  `study_groups` set 
		 	`name`='".f_ri($_POST["name"])."',
			`man_cnt`='".intval($_POST["man_cnt"])."',
			`speciality_id`='".intval($_POST["speciality_id"])."',
			`head_student_id`='".intval($_POST["head_student_id"])."',
			`year_id`='".intval($_POST["year_id"])."',
			`comment`='".f_ri($_POST["comment"])."',
            `photogallery_link`='".f_ri($_POST["photogallery_link"])."',
            `curator_id`=".intval($_POST["curator_id"])."
		where id='".$item_id."'";		
	      //echo $query;
		 if ($res=mysql_query($query) && !$err) {
		  	echo '<div class=success> Запись обновлена  успешно.'.$onEditRemain_text.'</div><br>';
		 				}
		 else {echo '<div class="warning">Запись не обновлена .<p>&nbsp;</div>';$err=true;}	
		}	    
	 }
  }
	 
	else {echo '<div class="warning">Часть обязательных данных не заполнено .<br>&nbsp;</div>';$err=true;}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'action'),'item_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
} 

if (isset($_GET['action']) && $_GET['action']=='edit')
{
	
	if ($item_id>0)
	{//echo '<h4>Правка темы</h4>';
	$query="SELECT id,name,comment,man_cnt,speciality_id,head_student_id,year_id, photogallery_link,curator_id
			FROM `study_groups` where id='".$item_id."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	//print_r($res_edit);echo $query;
	}
	else {echo '<h4 class="warning">не выбрана Запись для правки</h4>';}	
}

//добавление тем
if (isset($_GET['action']) && ($_GET['action']=='add' || $_GET['action']=='edit'))
{
?>
<div><a href="<?php echo 	$curpage.'?'.reset_param_name(reset_param_name($query_string,'item_id'),'action');?>">
К списку записей </a></div>
<h4> <?php if ($_GET['action']=='edit') echo 'Правка существующей '; else echo 'Ввод новой ';?> записи </h4>

<div class="forms_under_border" style="width:99%;">
<form name="item_form" method="post" action="" id="item_form">

Наименование <font color=red>*</font> <br>
<input type=text maxlength=10 size=15 name="name" id="name" value="<?php if (isset($res_edit)) {echo $res_edit['name'];} ?>" title="Наименование"> 
<p>
Число студентов <br>
<input type=text maxlength=10 size=15 name="man_cnt" id="man_cnt" value="<?php if (isset($res_edit)) {echo $res_edit['man_cnt'];} ?>"> 
<p>

Учебная специальность  <b>(с кафедры)</b>:  <br>
<select name="speciality_id" id="speciality_id" style="width:400;" title="Учебная специальность">
		<?php
		//для преподавателя позиционируем на его ФИО
		$listQuery='select id,name from specialities order by name';
		echo getFrom_ListItemValue($listQuery,'id','name','speciality_id');
		?>
		</select> <a href="spravochnik_edit.php?sprav_id=9&spr_type=3#id_9">специальности</a>
<p>
Староста группы:  <br><select name="head_student_id" id="head_student_id" style="width:400;">
		<?php
		$listQuery='select id,fio from students order by fio';
		echo getFrom_ListItemValue($listQuery,'id','fio','head_student_id');
		?>
		</select> <a href="students_view.php">студенты</a>
<p>
Учебный год:  <br><select name="year_id" id="year_id" style="width:400;">
		<?php
		$listQuery='select id,name from time_intervals order by name desc';
		//при добавлении указываем тек.уч.год по-умолчанию
		if ($_GET['action']=='add') {$res_edit['year_id']=getScalarVal('select year_id from settings');}
		echo getFrom_ListItemValue($listQuery,'id','name','year_id');
		?>
		</select> <a href="spravochnik_edit.php?sprav_id=17&spr_type=0#id_17">учебные года</a> 
<p>
Куратор <br/>
(из числа преподавателей кафедры):  <br><select name="curator_id" id="curator_id" style="width:400;">
		<?php
		$listQuery='select id,fio from kadri order by 2';
		//при добавлении указываем тек.уч.год по-умолчанию
		//if ($_GET['action']=='add') {$res_edit['year_id']=getScalarVal('select year_id from settings');}
		echo getFrom_ListItemValue($listQuery,'id','fio','curator_id');
		?>
		</select> <a href="lect_anketa_view.php">список сотрудников</a>
<p>
Ссылка на фотогалерею
<br>с корня фотогалереи, например, index.php?cat=1<br><input type=text size=100 name=photogallery_link id=photogallery_link value="<?php echo getFormItemValue('photogallery_link'); ?>"> <p>
<p>
Доп.информация (описание)<br>
<textarea cols=80 rows=4 name=comment id=comment><?php echo getFormItemValue('comment'); ?></textarea>
<p>
<input type=button onclick=javascript:check_form(); value="<?php if ($_GET['action'] && $_GET['action']=='edit') {echo 'Обновить';} else {echo 'Добавить';}?>" &nbsp;&nbsp;&nbsp;
<input type=reset value=Очистить> 
</form>
</div> 
<?php
}
else
{
	if (!isset($_GET['archiv'])) {
		$query_='select count(*) from study_groups sg
			   where sg.year_id!="'.$def_settings['year_id'].'" and sg.year_id>0';
		$archiv_cnt=intval(getScalarVal($query_),10);
		
		echo '<a href="?'.$query_string.'&archiv">группы прошлых учебных лет: '.$archiv_cnt.'</a><br>';
	}
	else {
		$query_='select count(*) from study_groups sg 
			   where sg.year_id="'.$def_settings['year_id'].'" or sg.year_id is null or sg.year_id=0';
		$cur_cnt=intval(getScalarVal($query_),10);
 		echo '<a href="?'.reset_param_name(reset_param_name($query_string,'archiv'),'page').'">группы текущего года: '.$cur_cnt.'</a><br>';}

if (!isset($_GET['save']) && !isset($_GET['print'])) {
	echo '<p class="notinfo"><a href="?action=add&'.$_SERVER["QUERY_STRING"].'"> Добавить</a> <br/>
	<b>Справочники:</b> <a href="students_view.php">студенты</a> &nbsp;
	<a href="spravochnik_edit.php?sprav_id=9&spr_type=3#id_9">специальности</a> &nbsp;
	<a href="spravochnik_edit.php?sprav_id=17&spr_type=0#id_17">учебные года</a> &nbsp;
	<p>';
	echo '<table width=99% class="notinfo"><tr>';
	echo '<td align=left width=350>
	Учебный год &nbsp;  
	<select name="year_id" id="year_id" style="width:200;" onChange=javascript:window.location.href="?"+this.id+"="+this.options[this.selectedIndex].value+"&'.reset_param_name(reset_param_name($query_string,'year_id'),'page').'">'; 

	$query='SELECT ti.id,concat(ti.name, " (",count(*),")") as caption   
			FROM study_groups sg 
			left join time_intervals ti on ti.id=sg.year_id
group by ti.id,ti.name 
order by 2 ASC limit 0,1000';
	echo getFrom_ListItemValue($query,'id','caption','year_id');
	
	echo '</select> </td><td> ';
	echo '<input type=button value="Все" onclick=javascript:window.location.href="'.$curpage.'";>&nbsp;&nbsp;
	<input type=button value="Печать" onclick=javascript:window.location.href="?print=1&'.$_SERVER["QUERY_STRING"].'";>&nbsp;&nbsp;
	<input type=button value="в Word" onclick=javascript:window.location.href="?save&attach=doc&'.$_SERVER["QUERY_STRING"].'"></td> 
	<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; <input type=button value="Найти" 
	OnClick=javascript:go2search();></td>
	</tr></table>
	<p>';}


if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b> <a href="?'.reset_param_name($query_string,'q').'">сбросить поиск</a></div><br>';
$search_query='and (LOWER(sg.`name`) like "%'.strtolower($q).'%" or 
					LOWER(sg.`man_cnt`) like "%'.strtolower($q).'%" or 
					LOWER(sp.name) like "%'.strtolower($q).'%" or
					LOWER(st.fio) like "%'.strtolower($q).'%" or
					LOWER(ti.name) like "%'.strtolower($q).'%" or
					LOWER(sg.comment) like "%'.strtolower($q).'%")';}
$sort=1;
if (isset($_GET['sort']) && $_GET['sort']>1 && $_GET['sort']<=5) {$sort=$_GET['sort'];}

if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?"> сбросить фильтр </a></div>';}

//-----------------------------------------------начало списочной таблицы  
if ($year_id>0) {$archiv_query='';}
else {
$archiv_query=' and (sg.year_id="'.$def_settings['year_id'].'" or sg.year_id is null or sg.year_id=0) ';
if (isset($_GET['archiv'])) {$archiv_query=' and (sg.year_id!="'.$def_settings['year_id'].'" and sg.year_id is not null and sg.year_id!=0) ';}
}

$query="SELECT sg.name, man_cnt, sp.name as sp_name,st.fio,ti.name as year_name,
    kadri.fio_short as curator_fio_short,sg.photogallery_link,sg.comment,
    sg.id,sg.speciality_id,users.id as curator_user_id
FROM study_groups sg 
left join students st on st.id=sg.head_student_id 
left join specialities sp on sp.id=sg.speciality_id 
left join time_intervals ti on ti.id=sg.year_id
left join kadri on kadri.id=sg.curator_id
left join users on users.kadri_id=kadri.id  
";
if ($year_id>0) {$search_query.=' and sg.year_id='.$year_id;}

$query.=" where 1 ".$archiv_query.$search_query." order by ".$sort." ".$stype." ";

$res=mysql_query($query.'limit '.(($page-1)*$pageVals).','.$pageVals);
//echo $query.'limit '.(($page-1)*$pageVals).','.$pageVals;

echo '<form name=item_list id=item_list action="" method="post"><table name=tab1 border=1 cellpadding="0" cellspacing="0" width="99%">
<tr align="center" class="title" height="30">';

	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '		
		<td class="notinfo" width=80>
		<input type=checkbox name="checkbox_del_all" id="checkbox_del_all" title="выделить\снять выделение со всех элементов" onClick="javascript:mark_all_checkbox(this.name,\'item_list\',0);">
		<img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}
	echo '<td width="30">№</td>';
	echo '<td width="100"><a href="?'.reset_param_name(reset_param_name($query_string,'sort'),'page').'&sort=1" title="сортировать">Наименование</a></td>';
	echo '<td width="100"><a href="?'.reset_param_name(reset_param_name($query_string,'sort'),'page').'&sort=2" title="сортировать">Число студентов</a></td>';
	echo '<td width="50"><a href="?'.reset_param_name(reset_param_name($query_string,'sort'),'page').'&sort=3" title="сортировать">Специальность</a></td>';
	echo '<td width="100"><a href="?'.reset_param_name(reset_param_name($query_string,'sort'),'page').'&sort=4" title="сортировать">Староста</a></td>';		
	echo '<td width="100"><a href="?'.reset_param_name(reset_param_name($query_string,'sort'),'page').'&sort=5" title="сортировать">Учебный год</a><a class=help title="подробнее" href="#year_detail">?</a></td>';	
	echo '<td width="100"><a href="?'.reset_param_name(reset_param_name($query_string,'sort'),'page').'&sort=6" title="сортировать">Куратор</a></td>';		
    echo '<td width="100"><a href="?'.reset_param_name(reset_param_name($query_string,'sort'),'page').'&sort=7" title="сортировать">Фотогалерея</a></td>';
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
		  <td align="center" width=80><input type=checkbox name="checkbox_h_copy_'.$tmpval['id'].'" title=""> &nbsp;
		  <a href="#del" onclick=javascript:del_confirm_act("'.str_replace(" ","&nbsp;",f_ro($tmpval['name'])).'","?item_id='.$tmpval['id'].'&action=del") title="удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="?item_id='.$tmpval['id'].'&action=edit" title="править">
			<img src="images/toupdate.png" alt="Правка" border="0"></a>
			</td>';}
		$i++;
		echo '<td>&nbsp;'.$i.'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['name']).'</a></td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['man_cnt']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['sp_name']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['fio']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['year_name']).'</td>';

        echo '<td>&nbsp;'.
        echoIf($tmpval['curator_user_id']!='',
            '<a href="p_lecturers.php?onget=1&idlect='.$tmpval['curator_user_id'].'">'.color_mark($q,$tmpval['curator_fio_short']).'</a>',
            color_mark($q,$tmpval['curator_fio_short']));
        echo '</td>';

        echo '<td>&nbsp;'.
        echoIf($tmpval['photogallery_link']!='',
            '<a href="_photo_cpg/'.$tmpval['photogallery_link'].'"><img border=0 src="images/design/file_types/img_file.gif"</a>',
            '');
        echo '</td>';

		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	$max_com_signes=40;
			if (strlen($tmpval['comment'])>$max_com_signes) echo '<td class="notinfo">&nbsp;<a href="" title="'.str_replace('\n','',$tmpval['comment']).'">'.substr($tmpval['comment'],0,$max_com_signes).' ...'.'</a></td>';
			else echo '<td class="notinfo">&nbsp;'.$tmpval['comment'].'</td>';}
		echo "</tr>\n";
	}
echo '</table>';

if (!isset($_GET['save']) && !isset($_GET['print'])) {
?>учебный год: &nbsp;  
	<select name="year_id_copy" id="year_id_copy" style="width:200;" title="учебный год">
<?php
	$listQuery='SELECT ti.id,concat(ti.name, " (",(select count(*) from study_groups sg where sg.year_id=ti.id),")") as caption   
			FROM time_intervals ti 
order by 2 ASC limit 0,1000';
	echo getFrom_ListItemValue($listQuery,'id','caption','year_id');
?>	
	</select> &nbsp;   
	учебная специальность: 
	<select name="speciality_id_copy" id="speciality_id_copy" style="width:200;" title="учебная специальность">
		<?php
		//для преподавателя позиционируем на его ФИО
		$listQuery='select s.id,concat(s.name," (",(select count(*) from study_groups sg where sg.speciality_id=s.id),")") as name from specialities s order by 2';
		echo getFrom_ListItemValue($listQuery,'id','name','speciality_id');
		?>
	</select>
	<input type=button value="Ok" onClick="javascript:test_copy();">	
<?php } ?>	
</form>

<?php
//постраничный вывод списка данных о (по 10)

//оптимизация для подсчета числа страниц с учетом всех условий фильтрации
//$query=$query." ".$search_query." ";

$res=mysql_query($query);
//echo $query;

if (floor(mysql_num_rows($res)/$pageVals)==mysql_num_rows($res)/$pageVals) {$pages_cnt=floor(mysql_num_rows($res)/$pageVals);}
 else {$pages_cnt=floor(mysql_num_rows($res)/$pageVals)+1;}

echo '<div align="left"> страницы ';

$add_string=reset_param_name($query_string,'page');

for ($i=1;$i<=$pages_cnt;$i++) {if ($i!=$page) {echo '<a href="?page='.$i.'&'.$add_string.'"> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}}
//--------------------------------------------------------
echo '<br>макс.число строк на странице:  <input type=text value="'.$pageVals.'" name="pageVals" id="pageVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgVals();" value=Ok>
	<p> Всего строк: '.mysql_num_rows($res).'</div>'; 	
?>
<div class=text>
	<b>Примечание</b> <br>
	<a name="year_detail"></a> 
	<ul>
	  <li>графа "Учебный год" используется при подборе учебных групп заданного года, например в отчете "Базы практик студентов-отчет"</li>
	  <li>так же графа "Учебный год" используется при подборе Студентов заданного года</li>
	  <li>графу "Специальность" важно заполнить для корректного включения учебных груп в отчеты</li>
	</ul>
</div>	
<?php	
}
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>