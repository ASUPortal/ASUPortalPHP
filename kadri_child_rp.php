<?php
include 'authorisation.php';
//отчет с базами практики студентов



//------------------------------------------------------------------------
include ('master_page_short.php');

$page=1;
$q='';			//строка поиска
$pgVals=20;	//число записей на странице по умолчанию
$query_string=$_SERVER['QUERY_STRING'];
$sort=1;
if (isset($_GET['sort'])) {$sort=intval($_GET['sort']);}

$stype='asc';		//тип сортировки столбца
if (isset($_GET['stype']) && ($_GET['stype']=='desc' || $_GET['stype']=='asc')) {$stype=$_GET['stype'];}

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
<style type="text/css">
  tr.title {font-size:13px; font-family:Arial; }
  tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}
  .err {color:red;font-family:Arial;}
  .pp_name {font-size:14px; font-family:Arial;background-color:#dddddd;}
  .kd_fio {font-size:13px; font-family:Arial;font-style:italic;font-weight:bold;}
  .st_fio {font-size:12px; font-family:Arial;}
</style>

<link rel="stylesheet" type="text/css" href="css/print.css" media="print">

<?php /* настройки myltiSelect   */  ?>
<link rel="stylesheet" type="text/css" href="_ajax_templ/multiSelect/styles.css" >
<script src="_ajax_templ/multiSelect/jquery.inlinemultiselect-1.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
    $('select.none').inlinemultiselect({triggerPopup:{'empty':'','nonempty':'','disabled':''}});
    $('select.imglink').inlinemultiselect({'formName':'formPT',triggerPopup:{'empty':'<img src="images/new_elem.gif" border=0 title="добавить элементы" width="16" height="18" /><span style="color:#090;font-style:italic;padding-left:3px;">Выбрать...</span>',
					'nonempty':'<img src="images/toupdate.png" border=0 title="изменение списка" width="12" height="13" /><span style="color:#00f;font-style:italic;padding-left:3px;">Сменить...</span>',
					'disabled':'<img src="images/toupdateD.png" border=0 title="изменение недоступно" width="12" height="13" /><span style="color:#f00;font-style:italic;padding-left:3px;">disabled</span>'}});
});  
</script>

<script type="text/javascript">    
var main_page="<?php echo $curpage;?>";
function pgVals()
{
 var pageVal=document.getElementById('pageVals');
 if (pageVal.value>0 && pageVal.value<100) {
 	window.location.href='?<?php echo reset_param_name($query_string,'pageVals');?>&pageVals='+pageVal.value;}
 else {alert('необходимо: '+pageVal.title);}
 } 
function go2search(kadri_id,search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {
	  	href_addr='q='+search_query+'&<?php echo reset_param_name(reset_param_name($query_string,'q'),'page'); ?>';
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
} 
	
</script>
<?php


$q='';
if (isset($_GET['q'])) {$q=$_GET['q'];}


$pt_id=array();
if (isset($_GET['pt_id'])) {$pt_id=$_GET['pt_id'];}

if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}
if (isset($_GET['pageVals']) && $_GET['pageVals']<=99 && $_GET['pageVals']>=1) {$pgVals=$_GET['pageVals'];}

	echo '<h4 class="notinfo">'.$pg_title.' за  &nbsp;  ';
	echo '<u>'.$def_settings['year_name'].'</u> учебный год</h4>	';

if ($q!='') {
$search_query='and (LOWER(k.fio) like "%'.strtolower($q).'%" or 
					LOWER(d.name_short) like "%'.strtolower($q).'%" or
					rt.child_birth like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or					
					LOWER(rt.child_pol) like "%'.strtolower($q).'%")';}

$table_headers=array(
		1=>array('ФИО преподавателя','200'),
		2=>array('долж.','100'),
		3=>array('пол ребенка','100'),
		4=>array('дата рождения ребенка','100'),
		5=>array('полных лет ребенку','100')
		);
$def_sort=1;
if ($sort<1 || $sort>count($table_headers))  {$sort=$def_sort;}

//-----------------------------------------------начало списочной таблицы  
$year_id_all=$def_settings['year_id'];$i=1;
if (isset($_GET['year']) && ($_GET['year']>0 && $_GET['year']<10) ) { $year_id_all=$_GET['year'];}



$query='SELECT k.fio,d.name_short as dolgnost,rt.child_pol,rt.child_birth,
(YEAR(CURRENT_DATE)-YEAR(rt.child_birth)) - (RIGHT(CURRENT_DATE,5)<RIGHT(rt.child_birth,5)) AS child_age,
k.id as kadri_id,k.fio_short, kadri_role(k.id,",") as pt_name  
from kadri k 
left join dolgnost d on d.id=k.dolgnost 
inner join (
  SELECT kc.birth_date as child_birth,p.name as child_pol,kc.kadri_id
  FROM `kadri_childs` kc left join pol p on p.id=kc.pol_id
  WHERE 1)
rt on rt.kadri_id=k.id ';


if (is_array($pt_id) && count($pt_id)>0) {
  $search_query.=' and exists(select * from kadri_in_ptypes kpt where kpt.kadri_id=k.id and kpt.person_type_id in(';
  for ($j=0;$j<count($pt_id);$j++) {$search_query.=''.$pt_id[$j].', ';}
  $search_query=preg_replace('/\, $/i','',$search_query);	//удаляем последнюю запятую
  $search_query.=') )';
  }


//if ($search_query!='') {$query.=$search_query;}
    if (isset($search_query)) {
        $query=$query." where 1 ".$search_query." order by $sort $stype";
    } else {
        $query=$query." where 1 order by $sort $stype";
    }

$res_PP=mysql_query($query.' limit '.(($page-1)*$pgVals).','.$pgVals);

//echo '<textarea>'.$query.' limit '.(($page-1)*$pgVals).','.$pgVals.'</textarea>';

if (!isset($_GET['save']) && !isset($_GET['print'])) {
	echo '<table width=99% class="notinfo" border=0><tr>';
	echo '<td align=left width=350>	
	<form method=get id=formPT name=formPT>';
}
    echo '<span class=text> Тип сотрудника:  
        <select class="'.echoIf(!isset($_GET['save']) && !isset($_GET['print']),'imglink','none').'" multiple="multiple" name="pt_id" id="pt_id">'; 

	$query_='select pt.id,concat(pt.name," (",IFNULL(cnt.cnt,"0"),")") as name from person_types pt left join
		(
		select count(*) as cnt,person_type_id as cnt_id
		from kadri_in_ptypes kpt
		where kpt.kadri_id in (SELECT kc.kadri_id
		  FROM kadri_childs kc)
		group by person_type_id
		) 
	cnt on cnt.cnt_id=pt.id
 	order by pt.name';
 	//echo ' query_='.$query_;
	echo getFrom_ListItemValue($query_,'id','name','pt_id',true);	
	echo '</select></span>';
	
if (!isset($_GET['save']) && !isset($_GET['print'])) {
      echo '</form> </td><td> ';
	echo '<input type=button value="Все" onclick=javascript:window.location.href="'.$curpage.'";>&nbsp;&nbsp;
        <td align=right>'.showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'').
	'<input type=text name="q" id="q" width=50 value=""> &nbsp; <input type=button value="Найти" 
	OnClick=javascript:go2search();></td>
	</tr></table>';
echo '<p>';
}


if (mysql_num_rows($res_PP)==0) {echo '<p class=warning style="width:80%;text-align:center;">В текущем учебном году записей не обнаружено. Подробнее смотрите в <u>Примечание</u></p>';}
else {
 

if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b> <a href="?'.reset_param_name(reset_param_name($query_string,'q'),'page').'">сбросить поиск</a></div><br>';}
    if (isset($filt_str_display)) {
        if ($filt_str_display!='') {
            echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?"> сбросить фильтр </a></div>';
        }
    }

?>

<table name=tab1 cellpadding="0" cellspacing="0" width="" border="1">
<tr height="30" align=center class=title>
<?php
$add_string=reset_param_name($query_string,'sort');	
	echo '<td width="50">№</td>';

	for ($i=1;$i<=count($table_headers);$i++)
	{
		echo '<td width="'.$table_headers[$i][1].'">'.print_col($i,$table_headers[$i][0]).'</td>';
	}	
?>
</tr>
<?php
				  
//echo $query;
//$res=mysql_query($query);	// у кого нет нагрузки в указанному году и он ППС

//текущая дата для расчета ставки по актуальным приказам ОК

//echo $query_orders;
$i=0;
while ($a=mysql_fetch_array($res_PP)) 
	{
	$i++;	
	echo '<tr class=text height="20" >
	<td>&nbsp;'.($i+($page-1)*$pgVals).'</td><td>&nbsp;<a href="lect_anketa.php?kadri_id='.$a['kadri_id'].'&action=update"  title="в анкету">'.$a['fio_short'].'</a></td>
	<td>&nbsp;'.$a['dolgnost'].'</td>
	<td>&nbsp;<a href="lect_anketa.php?kadri_id='.$a['kadri_id'].'&action=update" title="к анкете сотрудника">'.$a['child_pol'].'</a></td>
	<td>&nbsp;<a href="lect_anketa.php?kadri_id='.$a['kadri_id'].'&action=update" title="к анкете сотрудника">'.DateTimeCustomConvert($a['child_birth'],'d','mysql2rus').'</a></td>
	<td>&nbsp;<a href="lect_anketa.php?kadri_id='.$a['kadri_id'].'&action=update" title="к анкете сотрудника">'.$a['child_age'].'</a></td>	
	</tr>';
			
	}

//------------------------------------- у кого есть нагрузка даже если не ППС----------------------------------------

 ?>
</table>
<?php
//постраничный вывод списка данных о (по 10)

//оптимизация для подсчета числа страниц с учетом всех условий фильтрации
$itemCnt=getScalarVal('select count(*) from ('.$query.')t');

if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {$pages_cnt=floor($itemCnt/$pgVals);}
 else {$pages_cnt=floor($itemCnt/$pgVals)+1;}

echo '<div align="left"> страницы ';
$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;
echo getPagenumList($pages_cnt,$page,6,'page',$add_string,'');
echo '</div>';

//--------------------------------------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo ' макс.число строк на странице:  <input type=text value="'.$pgVals.'" name="pageVals" id="pageVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgVals();" value=Ok>
	<p> Всего записей: '.$itemCnt.'</div>'; 	
}


}	
if (!isset($_GET['save']) && !isset($_GET['print'])) {

?>
<div class=text>
	<b>Примечание:</b> 
	<ul>
	<li>сотрудник должен иметь записи по детям в анкете;</li>
	<li>данная табличная форма является отчетом и служит только для <b>просмотра</b> ранее введенных сведений;</li>
	<li>"Тип сотрудника" позволяет выбрать <b>несколько</b> записей для сводной группировки;</li>
	<li>для <b>правки</b> сведений о сотруднике, кликните по его ФИО, либо по сведениям о детях в таблице;</li>	
      </ul>
</div>	
<?php	
}
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>