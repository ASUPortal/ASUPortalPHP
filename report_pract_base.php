<?php
include 'authorisation.php';
//отчет с базами практики студентов


$page=1;
$q='';			//строка поиска
$pageVals=20;	//число записей на странице по умолчанию
$query_string=$_SERVER['QUERY_STRING'];

$filt_str_display="";

$item_id=0;$speciality_id=0;

$groupArrGlobal=array(
	array('sp_name','специальность',1),
	array('pp_name','место практики',2),
	array('kd_fio','руководитель',3)
	);	//массив уровней группировки

$getArrGr=array('','','');	//массив входных параметров сортировки из Get

$groupArr=array();
$groupArr=$groupArrGlobal;	

if (isset($_GET['gr0']) && isset($_GET['gr1']) && isset($_GET['gr2']))
{
	for ($i=0;$i<count($getArrGr);$i++)	{$getArrGr[$i]=$_GET['gr'.$i];}
	
	$groupArrTmp=array('sp_name'=>0,'pp_name'=>1,'kd_fio'=>2);
	
		for ($i=0;$i<count($getArrGr);$i++)	//формируем массив группировочных данных
		{ 	$groupArr[$i]=$groupArrGlobal[$groupArrTmp[$getArrGr[$i]]];	 	}
}
//------------------------------------------------------------------------
include ('master_page_short.php');

//print_r($def_settings);
if (isset($_GET['q'])) {$q=$_GET['q'];}
if (isset($_GET['item_id']) && intval($_GET['item_id'])>0) {$item_id=intval($_GET['item_id']);}
if (isset($_GET['speciality_id']) && intval($_GET['speciality_id'])>0) {$speciality_id=intval($_GET['speciality_id']);}


if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}
if (isset($_GET['pageVals']) && $_GET['pageVals']<=99 && $_GET['pageVals']>=1) {$pageVals=$_GET['pageVals'];}

//выбираем настройки по умолчанию для фильтрации по дате
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
.pp_name {font-size:14px; font-family:Arial;background-color:#dddddd;}
.kd_fio {font-size:13px; font-family:Arial;font-style:italic;font-weight:bold;}
.st_fio {font-size:12px; font-family:Arial;}
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
	  	href_addr='q='+search_query+'&<?php echo reset_param_name(reset_param_name($query_string,'q'),'page'); ?>';
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
} 
function test_liter_order(){//проверка порядка сортировки для вывода литературы
	var val_tmp_i='';
	var val_tmp_j='';
	
	var cur_val='';
	var err=false;
	
	var sp_name=document.getElementById('gr0');
	var pp_name=document.getElementById('gr1');
	var kd_fio=document.getElementById('gr2');
	
	if (sp_name.value==pp_name.value || sp_name.value==kd_fio.value) {err=true;}
	if (sp_name.value==pp_name.value || pp_name.value==kd_fio.value) {err=true;}
	if (sp_name.value==kd_fio.value || pp_name.value==kd_fio.value) {err=true;}

	if (err==false) {order_list.submit(); }
	else {if (!confirm('Обнаужено совпадение в порядке группировки.\n\n Вы можете исправить порядок группировки или использовать указанный Вами порядок. \n\nИсправить ?')) order_list.submit();}
} 	
</script>
<?php
//групп операции 	-----------------------------------------------

	echo '<h4 class="notinfo">'.$pg_title.' за <u>'.$def_settings['year_name'].'</u> учебный год</h4>	';
	if (isset($_GET['archiv'])) {echo '<a href="?">текущий год</a>';}
	else {echo '<a href="?archiv">архив</a>';}
	
if ($q!='') {
$search_query='and (LOWER(pp.name) like "%'.strtolower($q).'%" or 
					LOWER(tw.name) like "%'.strtolower($q).'%" or 
					LOWER(kd.fio) like "%'.strtolower($q).'%" or 
					LOWER(st.fio) like "%'.strtolower($q).'%")';}
$sort=1;
if (isset($_GET['sort']) && $_GET['sort']>1 && $_GET['sort']<=7) {$sort=$_GET['sort'];}


//-----------------------------------------------начало списочной таблицы  
$query='select sp.name as sp_name,pp.name as pp_name,kd.fio as kd_fio,st.fio as st_fio, pp.id as pp_id,dp.id as dp_id,tw.name as tw_name 
		 	from `pract_places` pp 
		 		left join towns tw on tw.id=pp.town_id 
				inner join diploms dp on dp.pract_place_id=pp.id
			 	inner join students st on dp.student_id=st.id
				left join study_groups sg on sg.id=st.group_id 
				left join specialities sp on sp.id=sg.speciality_id 
				left join kadri kd on kd.id=dp.kadri_id
				where (dp.date_act>="'.$def_settings['date_start'].'" or sg.year_id="'.$def_settings['year_id'].'"';
if (isset($_GET['archiv'])) {$query.=' or 1>0 ';}
$query.=')';

if ($speciality_id>0) {$query.=' and sp.id="'.$speciality_id.'" ';}
if (isset($search_query)) {
    if ($search_query!='') {$query.=$search_query;}
}
$query.=' order by '.$groupArr[0][2].','.$groupArr[1][2].','.$groupArr[2][2].',4 ';
//echo '$query='.$query;
$res_PP=mysql_query($query.' limit '.(($page-1)*$pageVals).','.$pageVals);

if (mysql_num_rows($res_PP)==0) {echo '<p class=warning style="width:80%;text-align:center;">В текущем учебном году записей не обнаружено. Возможно не указана предполагаемая дата защиты дипломного проекта, либо она раньше <u>'.DateTimeCustomConvert(substr($def_settings['date_start'],0,10),'d','mysql2rus').'</u>. Подробнее смотрите в <u>Примечание</u></p>';}
else {
 
if (!isset($_GET['save']) && !isset($_GET['print'])) {
	echo '<table width=99% class="notinfo"><tr>';
	echo '<td align=left width=350>
	Специальность &nbsp;  
	<select name="speciality_id" id="speciality_id" style="width:200;" onChange=javascript:window.location.href="?"+this.id+"="+this.options[this.selectedIndex].value+"&'.reset_param_name(reset_param_name($query_string,'speciality_id'),'page').'">'; 

	$query_='select id,name from specialities order by name';
	echo getFrom_ListItemValue($query_,'id','name','speciality_id');
	
	echo '</select> </td><td> ';
	echo '<input type=button value="Все" onclick=javascript:window.location.href="'.$curpage.'";>&nbsp;&nbsp;
	<input type=button value="Печать" onclick=javascript:window.location.href="?print=1&'.$_SERVER["QUERY_STRING"].'";>&nbsp;&nbsp;
	<input type=button value="в Word" onclick=javascript:window.location.href="?save&attach=doc&'.$_SERVER["QUERY_STRING"].'"></td> 
	<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; <input type=button value="Найти" 
	OnClick=javascript:go2search();></td>
	</tr></table>';
echo '<p>';

?>
<form name=order_list id=order_list action="" method="get"> порядок группировки:
<?php
	if (isset($_GET['archiv'])) {echo '<input type=hidden id=archiv name=archiv>';}
	
	
	for ($j=0;$j<count($groupArr);$j++) 
	{
	echo ($j+1).' <select name="gr'.$j.'" id="gr'.$j.'">';
	for ($i=0;$i<count($groupArrGlobal);$i++) 
		{$selected='';
		 //if ($i==$j+1){$selected=' selected';}
		 if (isset($_GET['gr'.$j]))
		 	{
			  if ($groupArrGlobal[$i][0]==$_GET['gr'.$j]){$selected=' selected';}
			 }
		 else if ($groupArrGlobal[$i][0]==$groupArrGlobal[$j+1][0]) {$selected=' selected';}
		echo '<option value='.$groupArrGlobal[$i][0].''.$selected.'>'.$groupArrGlobal[$i][1].'</option>';}
	echo '</select> &nbsp;'; 	//groupArrGlobal
	} 	
	echo '<input type=button value=Ok onClick=javascript:test_liter_order();> &nbsp; 	
	<input type=button value="по умолчанию" title="восстановить параметры по умолчанию" 
	onClick=window.location.href="?">';
?>
</form>
<?php		
	}


if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b> <a href="?'.reset_param_name(reset_param_name($query_string,'q'),'page').'">сбросить поиск</a></div><br>';}
if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?"> сбросить фильтр </a></div>';}

?>
<?php
$tab=' &nbsp; &nbsp; &nbsp; ';
$flag1=false;$flag2=false;
$i=0;
$elemCnt=mysql_num_rows($res_PP);

$res_pp=mysql_fetch_array($res_PP);

	while ($i<$elemCnt)	
	{
   	     $paramVal1=$res_pp[$groupArr[0][0]];
 		 //вывод Специальности или 1-уровня группировки
		 echo '<b>'.$res_pp[$groupArr[0][0]].'</b><br>';
		 
		 while ($paramVal1==$res_pp[$groupArr[0][0]] && $i<$elemCnt) 		 
		 {		  
   	       
		   $paramVal2=$res_pp[$groupArr[1][0]];
 		   //вывод Баз практик или 2-уровня группировки
		   if ($groupArr[0][0]!=$groupArr[1][0]) 
		   echo ' &nbsp; <span class="pp_name">'.$groupArr[1][1].': '.color_mark($q,$res_pp[$groupArr[1][0]]).color_mark($q,$tw_name_add).'</span><br>';		   

   	       while ($paramVal2==$res_pp[$groupArr[1][0]] && $paramVal1==$res_pp[$groupArr[0][0]] && $i<$elemCnt)
		   {
			  $paramVal3=$res_pp[$groupArr[2][0]];

		   //вывод ФИО руководителей или 3-уровня группировки
		   if ($groupArr[1][0]!=$groupArr[2][0]) echo $tab.'<span class="kd_fio">'.$groupArr[2][1].': '.color_mark($q,$res_pp[$groupArr[2][0]]).'</span><br>';

		   while ($paramVal3==$res_pp[$groupArr[2][0]] && $paramVal2==$res_pp[$groupArr[1][0]] && $paramVal1==$res_pp[$groupArr[0][0]] && $i<$elemCnt)
		   		{
				//вывод ФИО студентов
		   		echo $tab.$tab.'<span class="st_fio">студент: '.color_mark($q,$res_pp['st_fio']).'</span><br>';
		   		$res_pp=mysql_fetch_array($res_PP);
		   		$i++;
		   		}
		  	}		  	
		 }
	}
?> 

<?php
//постраничный вывод списка данных о (по 10)

//оптимизация для подсчета числа страниц с учетом всех условий фильтрации
//$query=$query." ".$search_query." ";
//echo '$query='.$query;
$res=mysql_query($query);

if (floor(mysql_num_rows($res)/$pageVals)==mysql_num_rows($res)/$pageVals) {$pages_cnt=floor(mysql_num_rows($res)/$pageVals);}
 else {$pages_cnt=floor(mysql_num_rows($res)/$pageVals)+1;}

echo '<div align="left"> страницы ';

$add_string=reset_param_name($query_string,'page');

for ($i=1;$i<=$pages_cnt;$i++) {if ($i!=$page) {echo '<a href="?page='.$i.'&'.$add_string.'"> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}}
//--------------------------------------------------------
echo '<br>макс.число строк на странице:  <input type=text value="'.$pageVals.'" name="pageVals" id="pageVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgVals();" value=Ok>
	<p> Всего строк: '.mysql_num_rows($res).'</div>'; 	
}	
?>
<div class=text>
	<b>Примечание</b> <br>
	<p> -отчет имеет древовидную структуру вида: Группа1\Группа2\Группа3\Студент, например, Специальность\МестоПрактики\Руководитель\Студент <br/>
	Под группами подразумеваются уровни группировки (Специальность\МестоПрактики\Руководитель) </p>
	<p>-при группировки можно "выбрасывать" уровни группировки, т.е. не выводить их в списке. Это выполняется заданием одинакового порядка для разных групп, например если Вы хотите сгруппировать все записи только по "<u>Руководитель</u>", надо в "<u>порядок группировки</u>" указать для всех чисел 1,2,3 запись руководитель и на запрос системы о совпадении уровней ответить "<u>Отмена</u>".</p>
	<p>-если родительский уровень группировки не имеет подстроки, это может значить, что в тек.учебном году (по дате предполагаемой защиты диплома или дате действия учебной группы студента) нет дипломного проекта;</li> 
	</p>
	
</div>	
<?php	

if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>