<?php
include 'authorisation.php';

$main_page='aspirants_view.php';
$page=1;
$q='';		//отбор по группе аспирантов
$q='';			//строка поиска
$pgVals=20;	//число данных о аспиранте на странице по умолчанию
$query_string=$_SERVER['QUERY_STRING'];
$year_id=0;	//отбор по году
$filt_str_display="";

if (isset($_GET['q'])) {$q=$_GET['q'];}

if (isset($_GET['kadri_id'])) {
    $kadri_id = $_GET['kadri_id'];
    $filt_str_display = $filt_str_display.' руководителю;';
} else {
    $kadri_id = "";
}
if (array_key_exists("year_id", $_GET)) {
    $year_id = $_GET['year_id'];
} else {
    $year_id = CUtils::getCurrentYear()->getId();
}

if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}
if (isset($_GET['pageVals']) && $_GET['pageVals']<=99 && $_GET['pageVals']>=1) {$pgVals=$_GET['pageVals'];}
//----------------------------------------------------------
if (isset($_GET['type'])) {
    if ($_GET['type']=='del') {
        $res=mysql_query($query);

        $query_string=reset_param_name($query_string,'type');
        $query_string=reset_param_name($query_string,'order_id');
        header('Location:'.$main_page.'?'.$query_string);
    }
}
//------------------------------------------------------------------------

include ('master_page_short.php');

?>
<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}
.err {color:red;font-family:Arial;}
</style>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">

<script language="JavaScript">
var main_page='aspirants_view.php';	//for redirect & links
function del_confirm(id,num)
{
	 if (confirm('Удалить данные о аспиранте: '+num+' ?')) 
	 	{window.location.href=main_page+'?order_id='+id+'&type=del'+'<?php echo '&page='.$_GET['page'].'&q='.$_GET['q'];?>';} 
} 
function year_id(q)
{
 if (document.getElementById('year_id').value!=0)
	{ window.location.href=main_page+"?year_id="+document.getElementById('year_id').value+"&"+q;}
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

function check_form()
{
 	a = new Array(
	 	new Array('kadriFio',''),
		new Array('kandidWork_name','')
	);
requireFieldCheck(a,'order_form');
 
} 
function FIO_sokr()
{
//сокращенное ФИО от полного
var fio= document.order_form.kadriFio.value.toString();
var fio_sokr='';
var start_id_1=0,start_id_2=0;

start_id_1=fio.indexOf(' ',2);
start_id_2=fio.indexOf(' ',start_id_1+1);
fio_sokr=fio.substring(0,start_id_1)+' '+fio.substring(start_id_1+1,start_id_1+2)+'.'+fio.substring(start_id_2+1,start_id_2+2)+'.';
document.order_form.kadriFioShort.value=fio_sokr;

}

</script>
<script type="text/javascript" src="scripts/jquery.autocomplete.js"></script>
<LINK href="css/autocomplete.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
	//массив полей автозаполнения: имя поля (#id), тип запроса к БД для выборки
	var fieldsArr=new Array(
		new Array("#kadriFio","kadriFio"),
		new Array("#kandidWork_name","kandidWork_name")
	);
</script>
<script type="text/javascript" src="scripts/autocomplete_custom.js"></script>

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
}

//групп операции 	-----------------------------------------------

	echo '<h4 class="notinfo"> Данные об обучающихся* аспирантах. <a href="lect_anketa_view.php">список сотрудников кафедры</a>
	<div class=text>*срок завершения обучения не ранее '.date('Y').' года включительно</div> </h4>	';

if (isset($_POST['kadriFio']))
{
	if ($_POST['kandidWork_name']!="") 
	{
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'order_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}
		 //проверка наличия аспиранта в списке сотрудников -> обновление раздела: "Кандид.диссертация"
		 $query='select id from kadri where fio like "%'.$_POST['kadriFio'].'%"';
		 $asp_id=getScalarVal($query);
		 //echo ' asp_id='.$asp_id;
		 
		 //аспирант уже есть, обновление раздела: "Кандид.диссертация"
	 if ($asp_id>0) {
		 $query='select id from `disser` where `kadri_id`="'.f_ri($asp_id).'" and `disser_type`="кандидат"';
		 $disser_id=getScalarVal($query);
		 if ($disser_id>0) // у аспиранта уже есть "Кандид.диссертация"
		 {$err=true; echo '<div class="warning">У выбранного аспиранта уже внесена запись по кандид.диссертации</div>';}
		 else {
		 //echo 'Правка темы.';
		 $query="insert into `disser`(`scinceMan`,`tema`,`comment`,`god_zach`,`kadri_id`,`disser_type`) 
		 values ('".f_ri($_POST["scinceMan"])."','".f_ri($_POST["kandidWork_name"])."',
		 '".f_ri($_POST["comment"])."','".f_ri($_POST["elem23"])."','".f_ri($asp_id)."','кандидат')";
		}

		 if ($res=mysql_query($query) && !$err) {

			//header("Location: ".$main_page);
		  	echo '<div class=success> Запись по кандид.диссертации обновлена  успешно.'.$onEditRemain_text.'</div><br>';
		 	//echo "Location:".$main_page;
					 
		 				}
		 else {echo '<div class="warning">Запись не обновлена .<p>&nbsp;</div>';$err=true;}
		 //echo $query;
	 }
	 else {//создаем нового аспиранта в списке сотрудников
		 $err=false;
		 $query="insert into `kadri`(`fio`,`fio_short`) 
		 values ('".f_ri($_POST["kadriFio"])."','".f_ri($_POST["kadriFioShort"])."')";
		 $query_asp='insert into kadri_in_ptypes(kadri_id,person_type_id) values((select max(id) from kadri),6)';
		 if ($res=mysql_query($query) && mysql_query($query_asp)) {
			   echo '<div class=success> Запись аспиранта в списке сотрудников добавлена  успешно.</div><br>';
		  }
		 else {echo '<div class="warning">Запись аспиранта в списке сотрудников не добавлена .<p>&nbsp;</div>';$err=true;}
	 	 
		 //выбираем ID нового аспиранта и добавляем ему кандид.диссертацию
		 $query='select id from kadri where fio like "%'.$_POST['kadriFio'].'%"';
		 $asp_id=getScalarVal($query);
		 $query="insert into `disser`(`scinceMan`,`tema`,`comment`,`god_zach`,`kadri_id`,`disser_type`) 
		 values ('".f_ri($_POST["scinceMan"])."','".f_ri($_POST["kandidWork_name"])."',
		 '".f_ri($_POST["comment"])."','".f_ri($_POST["elem23"])."','".f_ri($asp_id)."','кандидат')";
		 
		 if ($res=mysql_query($query)) {echo '<div class=success> Запись по кандид.диссертации обновлена  успешно.</div><br>';	}
		 else {echo '<div class="warning">Запись по кандид.диссертации не обновлена.<p>&nbsp;</div>';$err=true;}
	 	 
		 }
	 }
	 
	else {echo '<div class="warning">Часть обязательных данных не заполнено .<br>&nbsp;</div>';$err=true;}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'type'),'order_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
} 

//добавление тем
if (isset($_GET['type']) && $_GET['type']=='add')
{
?>
<div><a href="<?php echo 	$main_page.'?'.reset_param_name(reset_param_name($query_string,'order_id'),'type');?>">К списку аспирантов </a></div>
<h4> Ввод новой записи по аспиранту <span class=text>(алтернативный способ вводу через список сотрудников кафедры)</span> </h4>
<div class="forms_under_border" style="width:99%;">
<form name="order_form" method="post" action="" id="order_form">

Научный руководитель <b>(с кафедры)</b>:  <br><select name="scinceMan" id="scinceMan" style="width:400;">
		<?php
		//для преподавателя позиционируем на его ФИО
		if (intval($_SESSION['kadri_id'])>0) {$res_edit['scinceMan']=intval($_SESSION['kadri_id']);}
		
		$listQuery='select k.id,concat(k.fio," (",kadri_role(k.id,","),")") as caption 
			from kadri k order by k.fio';
		echo getFrom_ListItemValue($listQuery,'id','caption','scinceMan');
		?>
		</select>
<p>
	ФИО(полностью) аспиранта* (<span class=text> начните набор с фамилии,например Иванов -> <b>автозаполнение</b> </span>)
	<br><input type=text size=100 name=kadriFio id=kadriFio onChange="FIO_sokr();" value="<?php echo getFormItemValue('kadriFio'); ?>" title="ФИО аспиранта полное"> <p>
	ФИО(кратко) аспиранта (<span class=text> начните набор с фамилии,например Иванов -> <b>автозаполнение</b> </span>)
	<br><input type=text size=100 name=kadriFioShort id=kadriFioShort value="<?php echo getFormItemValue('kadriFioShort'); ?>" title="ФИО аспиранта кратко"> <p>
	Тема: * (<span class=text> начните набор  -> <b>автозаполнение</b> </span>)<br>
	<textarea name=kandidWork_name cols=75 rows=6 id=kandidWork_name title="тема"><?php echo getFormItemValue('kandidWork_name'); ?></textarea> <p>

	Год защиты: <span class=text>(<b>по умолчанию</b>: текущий год+3)</span> <br><input name="elem23" type=text value="<?php echo intVal(date("Y"))+3; ?>" size=10 maxlength="4"><p>

	Доп.информация<br><input type=text size=100 name=comment id=comment value="<?php echo getFormItemValue('comment'); ?>"> <p>
	<input type=button onclick=javascript:check_form(); value="Добавить" &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>
</div> 
<?php
}
else
{
if (!isset($_GET['save']) && !isset($_GET['print'])) {
	echo '<p class="notinfo"><a href="?type=add&'.$_SERVER["QUERY_STRING"].'" style="padding-right:40px;">Добавить</a>';
	 if (!isset($_GET['archiv'])) {				 
			 $query_='select count(*) from `disser` where `disser_type`="кандидат" and `date_end`<"'.$def_settings['date_start'].'" ';
			 if ($kadri_id>0) {$query_.=' and `scinceMan`="'.$kadri_id.'"';}
			 $archiv_cnt=intval(getScalarVal($query_),10);
			 
			 echo '<a href="?'.$query_string.'&archiv" title="записи прошлых учебных лет">архив: '.$archiv_cnt.'</a><br>';
		 }
		 else {
			 $query_='select count(*) from `disser` where `disser_type`="кандидат" and (`date_end`>="'.$def_settings['date_start'].'" or `date_end` is NULL) ';
			 if ($kadri_id>0) {$query_.=' and `scinceMan`="'.$kadri_id.'"';}
			 $cur_cnt=intval(getScalarVal($query_),10);
			 echo '<a href="?'.reset_param_name(reset_param_name($query_string,'archiv'),'page').'" title="записи прошлых учебных лет">записи текущего учебного года: '.$cur_cnt.'</a><br>';}	

	echo '<p><table width=99% class="notinfo"><tr>';
	echo '<td align=left width=150>';
?>
	Руководитель </td><td> <select name="kadri_id" id="kadri_id" style="width:200;" onChange="javascript:window.location.href='?<?php echo reset_param_name($query_string,'kadri_id');?>&kadri_id='+this.options[this.selectedIndex].value;"> 
<?php
	$query="select d.scinceMan as id,concat(k2.fio,' (',count(*),')') as `name` 
		  from disser d inner join kadri k2 on k2.id=d.scinceMan 
		  where `disser_type`='кандидат' ".
		  (isset($_GET['archiv'])?' and `date_end`<"'.$def_settings['date_start'].'" ':' and (`date_end`>="'.$def_settings['date_start'].'" or `date_end` is NULL) ')." 
		  group by d.scinceMan order by `name`";
echo getFrom_ListItemValue($query,'id','name','kadri_id');

	echo '</select> &nbsp;&nbsp; ';
	echo '<input type=button value="Все" onclick=javascript:window.location.href="'.$main_page.'";>&nbsp;&nbsp;
	<input type=button value="Печать" onclick=javascript:window.location.href="?print=1&'.$_SERVER["QUERY_STRING"].'";>&nbsp;&nbsp;
	<input type=button value="в Word" onclick=javascript:window.location.href="?save&attach=doc&'.$_SERVER["QUERY_STRING"].'"></td> 
	<td align=right><input type=text name="q" id="search_query" width=50 value=""> &nbsp; <input type=button value="Найти" 
	OnClick=javascript:go2search();></td>
	</tr></table>
	<p>';}


if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b> <a href="?'.reset_param_name($query_string,'q').'">сбросить поиск</a></div><br>';
$search_query=' and (k.fio like "%'.$q.'%" or 
					ss.`name_short` like "%'.$q.'%" or 
					sf.`name` like "%'.$q.'%" or 
					k2.fio like "%'.$q.'%" or
					d.`tema` like "%'.$q.'%" or
					d.`god_zach` like "%'.$q.'%" or
					d.`comment` like "%'.$q.'%")';}
//if ($kadri_id>0) {$search_query=' and students.id in (select distinct student_id from diploms where kadri_id="'.$kadri_id.'")';}	//поиск по дипл.руководителю

$sort=1;
if (isset($_GET['sort']) && $_GET['sort']>1 && $_GET['sort']<=7) {$sort=$_GET['sort'];}

if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?"> сбросить фильтр </a></div>';}

//-----------------------------------------------начало списочной таблицы  
$query="SELECT k.`fio`,ss.`name_short` as `sc_spec`,sf.`name` as `sc_form`,k2.fio as scinceMan, d.`tema`,d.`god_zach`,d.date_end,d.`comment`,
	d.id,d.kadri_id,d.scinceMan as scinceManId,`u`.`id` as `user_id`  
FROM `disser` d 
left join kadri k on k.id=d.`kadri_id` 
left join kadri k2 on k2.id=d.scinceMan 
left join specialities_science ss on ss.id=d.`science_spec_id`
left join study_forms sf on sf.id=d.`study_form_id`
left join users u on u.kadri_id=k2.id
WHERE `disser_type`='кандидат'";

if ($kadri_id>0) {$query.=' and `scinceMan`="'.$kadri_id.'"';}
//if ($year_id!=0) {$query.=' and year(`date_end`)="'.$year_id.'"';}
if (isset($_GET['archiv'])) $query.=' and `date_end`<"'.$def_settings['date_start'].'" ';
else $query.=' and (`date_end`>="'.$def_settings['date_start'].'" or `date_end` is NULL) ';

    if (isset($search_query)) {
        $query.=" ".$search_query." order by ".$sort." ASC ";
    } else {
        $query.=" order by ".$sort." ASC ";
    }


$res=mysql_query($query.'limit '.(($page-1)*$pgVals).','.$pgVals);
//echo $query.'limit '.(($page-1)*$pgVals).','.$pgVals;

echo '<form name=order_list id=order_list action="" method="post"><table name=tab1 border=1 cellpadding="0" cellspacing="0" width="99%">
<tr align="center" class="title" height="30">';

$add_string='';
if (isset($_GET['q']) && $_GET['q']!='')  {$add_string=$add_string.'&q='.$_GET['q'];};
if (isset($_GET['print']))  {$add_string=$add_string.'&print='.$_GET['print'];};
if (isset($_GET['page']))  {$add_string=$add_string.'&page='.$_GET['page'];};
if (isset($_GET['pageVals']))  {$add_string=$add_string.'&pageVals='.$_GET['pageVals'];};

	if (!isset($_GET['save']) && !isset($_GET['print'])) {
		echo '		
		<td width="40" class="notinfo"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}
	echo '<td width="30">№</td>';
	echo '<td width="100"><a href="?'.$add_string.'&sort=1" title="сортировать">ФИО</a></td>';
	echo '<td width="50"><a href="?'.$add_string.'&sort=2" title="сортировать">номер спец-ти</a></td>';
	echo '<td width="50"><a href="?'.$add_string.'&sort=3" title="сортировать">форма обуч.</a></td>';
	echo '<td width="100"><a href="?'.$add_string.'&sort=4" title="сортировать">руководитель</a></td>';		
	echo '<td width="200" align=center><a href="?'.$add_string.'&sort=5" title="сортировать">тема</a></td>';	
	echo '<td width="50" align=center><a href="?'.$add_string.'&sort=6" title="сортировать">год защиты</a></td>';
	echo '<td width="50" align=center><a href="?'.$add_string.'&sort=7" title="сортировать">дата окончания</a></td>';
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
		  <td align="center"> 
		  <a href="lect_anketa.php?kadri_id='.$tmpval['kadri_id'].'&action=update" onclick="" title="для удаления необходимо удалить запись из списка аспирантов или отредактировать данные кандид.диссертации">
			<img src="images/todeleteD.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="spav_other.php?type=kandid&kadri_id='.$tmpval['kadri_id'].'&id='.$tmpval['id'].'" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		$i++;
		echo '<td>&nbsp;'.($i+($page-1)*$pgVals).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['fio']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['sc_spec']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['sc_form']).'</td>';
		echo '<td>&nbsp;<a href="p_lecturers.php?onget=1&idlect='.$tmpval['user_id'].'" title="перейти на страничку преподавателя">'.color_mark($q,$tmpval['scinceMan']).'</a></td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['tema']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['god_zach']).'</td>';
		echo '<td>&nbsp;'.DateTimeCustomConvert($tmpval['date_end'],'d','mysql2rus').'</td>';
					
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.$tmpval['comment'].'</td>';}
		echo "</tr>\n";
	}
echo '</table>';
?> 
</form>

<?php
//постраничный вывод списка данных о аспиранте (по 10)
$res=mysql_query($query);

//echo $query;
//$pages_cnt=floor(mysql_num_rows($res)/$pgVals)+1;
if (floor(mysql_num_rows($res)/$pgVals)==mysql_num_rows($res)/$pgVals) {$pages_cnt=floor(mysql_num_rows($res)/$pgVals);}
 else {$pages_cnt=floor(mysql_num_rows($res)/$pgVals)+1;}

$add_string=reset_param_name($query_string,'page');

echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';
//--------------------------------------------------------
echo '<br>макс.число строк на странице:  <input type=text value="'.$pgVals.'" name="pageVals" id="pageVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pageVals(\''.reset_param_name($query_string,'pageVals').'\');" value=Ok>
	<p> Всего строк: '.mysql_num_rows($res).'</div>'; 	
?>
<div class=text>
	<b>Примечание</b> <ul>
	<li>обучающиеся в н.в. аспиранты, отраженные в списочной таблицы выше, указываются на <a href="p_lecturers.php"><u>персональных страницах преподавателей</u></a> в разделе "Подготовка аспирантов ";</li>
	<li>перенос записей в архив происходит по дате окончания обучения в аспирантуре;</li>
	<li>редактирование записи приводит к открытию анкеты сотрудника в разделе "образование"</li>
	</ul>
</div>	
<?php	
}
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>