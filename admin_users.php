<?php
include ('authorisation.php');
include ('master_page_short.php');

$main_page=$curpage;//'diploms_view.php';
$page=1;
$group_id='';		//отбор списочной таблицы
$q='';			//строка поиска
$pgVals=20;	//число записей на странице по умолчанию
$sort=4;	//столбец сортировки
$stype='asc';		//тип сортировки столбца

$filt_str_display='';	//отражение по чему идет отбор
$err=false;		//нет ошибок при изменении БД
$item_id=0;
$type='';		//тип действия

$query_string=$_SERVER['QUERY_STRING'];
//group_id sort  q  archiv  page  pgVals

if (isset($_GET['group_id']) && intval($_GET['group_id'])>0) {$group_id=intval($_GET['group_id']);$filt_str_display=$filt_str_display.' группе;';}
if (isset($_GET['q']) && $_GET['q']!='') {$q=$_GET['q'];$filt_str_display=$filt_str_display.'  поиску;';}
if (isset($_GET['page']) && $_GET['page']>1) {$page=intval($_GET['page']);$filt_str_display=$filt_str_display.'  странице;';}
if (isset($_GET['pgVals']) && $_GET['pgVals']<=99 && $_GET['pgVals']>=1) {$pgVals=intval($_GET['pgVals']);$filt_str_display=$filt_str_display.' числу записей;';}

if (isset($_GET['sort'])) {$sort=$_GET['sort'];}
if (isset($_GET['type'])) {$type=$_GET['type'];}
if (isset($_GET['item_id']) && intval($_GET['item_id']>0)) {$item_id=intval($_GET['item_id']);}
if (isset($_GET['stype']) && $_GET['stype']=='desc') {$stype=$_GET['stype'];}

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


function del_record($query,$title)
{	//удаление записей с отражением сообщения о результате операции
	 if ($query=='') $msg='<div class=warning>нет запроса для удаления</b></div>';
	 else {
		  $msg='';
		  if (mysql_query($query)) $msg='<div >запись <span class=success>успешно удалена</span> из <b>'.$title.'</b></div>';
		  else $msg='<div >запись <span class=warning>не удалена</span> из <b>'.$title.'</b></div>';
	 }
	 return $msg;
}
function getTabComment($tabName)
{
    $res='';
    if ($tabName!='') {
        $q_val='show table status like "'.$tabName.'"';
        $res_arr=getRowSqlVar($q_val);
        $res=$res_arr[0]['Comment'];	
    }
    return $res;
}	
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
function go2search(group_id,search_path)
{
 	var search_query=document.getElementById('q').value;
 	var href_addr='';
 	
 	if (search_query!='') {href_addr='q='+search_query+'&group_id='+group_id;
		if (search_path=='archiv') {href_addr='archiv&'+href_addr+'';}
	 	window.location.href=main_page+'?'+href_addr;
	 }
 	else {alert('Введите строку поиска');}
} 
function pgVals(query_str)
{
 	var pageCnt= parseInt(document.getElementById('pgVals').value);
 	if (pageCnt>0 && pageCnt<100) {document.location.href=main_page+'?'+query_str+'&pgVals='+pageCnt;}
 	else {alert('Введите значение с 1 до 99.');}
} 
function check_form()
{
 	a = new Array(
	 	new Array('fio',''),
	 	new Array('fio_short','')
	);
	requireFieldCheck(a,'item_form');	
}
function lp_is_active(act_status)
{	//активации логина и пароля для смены
	//true-активировать,false-заблокировать
  var login=document.getElementById('login');
  var password=document.getElementById('password');
  if (login!=null && password!=null)
    {
    login.disabled=!act_status;password.disabled=!act_status;
    }    
}
function fil_fio(fio)
{
//alert(fio);
    document.item_form.fio.value=fio;FIO_sokr();
}
function FIO_sokr()
{
//сокращенное ФИО от полного
var fio='';
try {fio= document.item_form.fio.value.toString();}
catch (e) {fio= document.item_form.fio.value.toString();}

var fio_sokr='';
var start_id_1=0,start_id_2=0;

//alert (fio);

start_id_1=fio.indexOf(' ',2);
start_id_2=fio.indexOf(' ',start_id_1+1);

fio_sokr=fio.substring(0,start_id_1)+' '+fio.substring(start_id_1+1,start_id_1+2)+'.'+fio.substring(start_id_2+1,start_id_2+2)+'.';

try {document.item_form.fio_short.value=fio_sokr;}
catch (e) {document.item_form.fio_short.value=fio_sokr;}
}
</script>

<?php
if ($type=='del' && $item_id>0)
{
	 $fio=getScalarVal('select FIO from users where id="'.$item_id.'" limit 0,1');	 
	 if ($fio!='') {
		  
		  echo del_record('delete from users where id='.$item_id,getTabComment('users'));
		  
		  echo del_record('delete from documents where id='.$item_id,getTabComment('documents'));
		  echo del_record('delete from files where id='.$item_id,getTabComment('files'));
		  echo del_record('delete from biography where id='.$item_id,getTabComment('biography'));
		  echo del_record('delete from news where user_id_insert='.$item_id,getTabComment('news'));
		  
		  echo del_record('delete from task_in_user where user_id='.$item_id,getTabComment('task_in_user'));
		  echo del_record('delete from user_in_group where user_id='.$item_id,getTabComment('user_in_group'));
		  
		  if (!$onEditRemain) {
		    echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'\"",4000);</script>';		}  
	 }
}


if (isset($_POST['fio']))
{
	if ($_POST['fio']!='' && $_POST['fio_short']!='')
	{
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

	 //обновление темы
	 if ($type=='edit' && $item_id>0) {
		 //echo 'Правка темы.';
		 $query="update users set FIO='".f_ri($_POST["fio"])."',FIO_short='".f_ri($_POST["fio_short"])."',status='".f_ri($_POST["status"])."', kadri_id='".intval($_POST["kadri_id"])."' ,".
         echoIf($_POST['lp_change']=='on', "login='{$_POST["login"]}', password=md5('{$_POST["password"]}'), ", "").
			"comment='".f_ri($_POST["comment"])."'
			  where id='".$item_id."'";
              //echo $query;
		 if ($res=mysql_query($query)) {
		  	echo '<div class=success> Запись обновлена  успешно.'.$onEditRemain_text.'</div><br>';
		 				}
		 else {echo '<div class="warning">Запись не обновлена .<p>&nbsp;</div>';$err=true;}
	 }
	 
	 //новая запись
	 if ($type=='add') {
		 $query="insert into users(FIO,FIO_short,login,password,kadri_id,reg_date_time,comment,status)
		 	values('".f_ri($_POST["fio"])."','".f_ri($_POST["fio_short"])."','".f_ri($_POST["login"])."',md5('".f_ri($_POST["password"])."'),
			 '".intval($_POST["kadri_id"])."',NOW(),'".f_ri($_POST["comment"])."','".f_ri($_POST["status"])."')";
		 
		  //echo $query;
         if ($res=mysql_query($query)) {
	  	echo '<div class=success> Запись "'.$_POST['fio'].'" добавлена успешно.'.$onEditRemain_text.'</div><br>';
			}
		 else {echo '<div class="warning">Запись не добавлена. Возможно запись с таким логином там уже есть</div><br>';$err=true;}
	 }
	 
	 }
	else {echo '<div class="warning">Часть обязательных данных не заполнено .<br>&nbsp;</div>';$err=true;}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
} 

if ($type=='edit')
{
	if ($item_id>0)
	{//echo '<h4>Правка темы</h4>';
	$query="select * from users where id='".$item_id."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="warning">не выбрана Запись для правки</h4>';}	
}

//добавление тем
if ($type=='add' || $type=='edit')
{
?>
<div><a href="<?php echo 	$main_page.'?'.reset_param_name(reset_param_name($query_string,'item_id'),'type');?>">Список записей </a></div>
<h4> <?php if($type=='edit') {echo 'Правка';} else {echo 'Ввод новой ';} ?>  записи </h4>
<div class="forms_under_border" style="width:99%;">
<form name="item_form" id="item_form" method="post" action="">

ФИО пользователя(полностью) <span class=warning>*</span> <br>
<input type=text size=50 name=fio id=fio value="<?php echo getFormItemValue('FIO'); ?>" onChange="FIO_sokr();" title="ФИО пользователя(полностью)"><p>

ФИО пользователя(кратко)<span class=warning>* </span><br>
<input type=text size=30 name=fio_short id=fio_short value="<?php echo getFormItemValue('FIO_short'); ?>" title="ФИО пользователя(кратко)"><p>

Сотрудник кафедры <br>
<select name="kadri_id" id="kadri_id" style="width:300;" onChange="javascript:fil_fio(this.options[this.selectedIndex].text);">
		<?php
		$query='select k.id,concat(k.fio," (",kadri_role(k.id,","),")")  as name
            from kadri k order by k.fio';
		echo getFrom_ListItemValue($query,'id','name','kadri_id');
		?>
</select><a href="lect_anketa_view.php">список сотрудников</a> <p>

статус<br>
<span>
<label><input type="radio" name="status" value="администратор" id="b0" <?php if(getFormItemValue('status')!='преподаватель') echo 'checked'; ?> >администратор <span class=text>(скрыт для просмотра в открытой части)</span></label>
<label><input type="radio" name="status" value="преподаватель" id="b1" <?php if(getFormItemValue('status')=='преподаватель') echo 'checked'; ?> >преподаватель </label>
</span>
<p>
<label><input type=checkbox id=lp_change name=lp_change onclick="javascript:lp_is_active(this.checked);" >обновить логин и пароль</label>
	<table id=lp_div class=text>
	  <tr>
	    <td>логин</td>
	    <td>пароль</td>
	  </tr>
	  <tr>
	    <td><input type="text" name="login" id=login value="<?php echo getFormItemValue('login'); ?>" class="text2" disabled></td>
	    <td><input type="password" name="password" id=password value="" class="text2" disabled></td>
	  <tr>
	</table>

	Комментарий<br><input type=text size=100 name=comment value="<?php echo getFormItemValue('comment'); ?>"> <p>
	<input type=button onclick=javascript:check_form(); value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>
</div> 
<?php
}

else 
	{

	?><h4 class="notinfo"> <?php echo $pg_title;?>
        <div class=text>связанные задачи: права пользователей-  <a href="user_access.php">групповые</a>,
        <a href="user_access_persons.php">персональные</a></div></h4><?php

		$query_='select count(*) from `diploms` where 1 and (date_act<"'.$def_settings['date_start'].'" or date_act is NULL) ';
		if ($group_id>0) {$query_.=' and `diploms`.`group_id`="'.$group_id.'"';}
		$archiv_cnt=intval(getScalarVal($query_),10);

$search_query='';
if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
$search_query=' and (convert(u.FIO USING utf8) like "%'.$q.'%" or
					convert(u.FIO_short USING utf8)  like "%'.$q.'%" or
					convert(u.status USING utf8)  like "%'.$q.'%" or
					convert(u.login USING utf8) like "%'.$q.'%" or
					u.reg_date_time like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
                    ua.last_datetime like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%" or
                    convert(t.name USING utf8) like "%'.$q.'%" or
					convert(u.comment USING utf8) like "%'.$q.'%")';}

	$table_headers=array(
		1=>array('ФИО','200'),
		2=>array('логин','10'),
		3=>array('статус','100'),
		4=>array('сотрудник','100'),
		5=>array('группы','100'),
		6=>array('персон.задач','50'),
		7=>array('дата регистрации','50'),
		8=>array('дата посещения'),
		9=>array('страница','20')
		);
$sort=1;
if (isset($_GET['sort']) && $_GET['sort']>1 && $_GET['sort']<count($table_headers)) {
	 $sort=$_GET['sort'];
 }

//выборка для показа списочной таблицы записей

$query='select u.FIO,u.login,u.status,u.kadri_id, 0,
	 (select count(*) from task_in_user tiu where tiu.user_id=u.id) as tiu_cnt,
	 u.reg_date_time,ua.last_datetime,t.name as t_name,u.comment,u.id,u.FIO_short,t.url as t_url
        from users u
            left join `'.$sql_stats_base.'`.`user_activity` ua on ua.user_id=u.id
               left join `tasks` t on t.`url`=ua.`last_page`
        ';

if ($group_id>0)
	{$search_query.=' and u.id in (select distinct user_id from user_in_group where group_id='.$group_id.') ';}

$query=$query." where 1 ".$search_query." order by ".$sort." ".$stype." ";
	
$res=mysql_query($query.' limit '.(($page-1)*$pgVals).','.$pgVals);
//echo $query;

if (!isset($_GET['save']) && !isset($_GET['print'])) {
	echo '<p class="notinfo"><a href="?type=add&'.$_SERVER["QUERY_STRING"].'"> Добавить</a><p>';
if ($filt_str_display!='') {echo '<div class=text>включена фильтрация по: <b style="color:#FF0000;">'.$filt_str_display.'</b> &nbsp; &nbsp; <a href="?'.reset_param_name_ARR($query_string,array('group_id','page','q')).'"> сбросить фильтр </a></div>';}
}

if (!isset($_GET['save']) && !isset($_GET['print'])) {
//-------------------------------------  списочная таблица -----------------------------------------------------	
	echo '<table width=99% class="notinfo" border=0><tr>';	
	echo '<td align=left colspan=2>';
	
	$add_string=reset_param_name($query_string,'group_id');	//для фильтра по глав.фильтру
	$add_string=reset_param_name($add_string,'page');	//для перехода к первой странице сортировки по глав.фильтру
 	
	  	?>
	Группа <select name="group_id" id="group_id" style="width:300;" onChange="javascript:window.location.href='?<?php echo reset_param_name($query_string,'group_id');?>&group_id='+this.options[this.selectedIndex].value;">
	<?php

$query_main_filter='SELECT ug.`id` , concat( ug.`comment` , " (", count( * ) , ")" ) AS name
FROM `user_groups` ug
inner JOIN `user_in_group` uig ON uig.group_id = ug.id
inner join users u on u.id=uig.user_id
GROUP BY ug.`comment` order by 2 limit 0,100';
echo getFrom_ListItemValue($query_main_filter,'id','name','group_id');
		?>
</select>
	<?php
		echo '&nbsp;&nbsp;<input type=button value="Все" onClick="javascript:window.location.href=\'?\';">&nbsp;&nbsp;';
	
		echo showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'');
		echo ' </td> 
	<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; 
		<input type=button value="Найти" OnClick=javascript:go2search(\''.$group_id.'\',\'';
			if (isset($_GET['archiv'])) {echo 'archiv';}
		echo '\');>
		<div class=text style="text-align:right"> кроме полей: ин.яз.,оц. <br>
		Поиск по дате в формате дд.мм.гггг или гггг-мм-дд</div></td>
	</tr></table>';}
	if (mysql_num_rows($res)==0) {
        echo '<p class=warning style="font-size:12pt; text-align:center;">записей не найдено, попробуйте <a href="?">сбросить фильтр</a>';
	 }
	else {

//-----------------------------------------------------
$itemCnt=getScalarVal('select count(*) from ('.$query.')t');

if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {$pages_cnt=floor($itemCnt/$pgVals);}
else {$pages_cnt=($itemCnt/$pgVals)+1;}

$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';
//-----------------------------------------------------
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
		echo '<td width="'.$table_headers[$i][0].'">'.print_col($i,$table_headers[$i][0]).'</td>';
	}
	if (!isset($_GET['save']) && !isset($_GET['print'])) 
		{echo '<td width="100" class="notinfo">комментарий</td>';}
//------------------------------------------- шапка списочной таблицы -конец-----------------------------------------------------
	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};
	$i=0;
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		echo '<tr align="left" class="main" '.$bgcolor.' valign="top">';
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		  echo '<td align="center"> 
		  	<a href="javascript:del_confirm_act(\''.str_replace(" ","_",f_ro($tmpval['FIO'])).'\',\'?item_id='.$tmpval['id'].'&type=del&'.$query_string.'\');" title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="?item_id='.$tmpval['id'].'&type=edit&'.$query_string.'" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		$i++;
		echo '<td>&nbsp;'.($i+($page-1)*$pgVals).'</td>';
		echo '<td>&nbsp;<a href="p_lecturers.php?onget=1&idlect='.$tmpval['id'].'" title="биография">'.color_mark($q,$tmpval['FIO']).'</td>';//
		echo '<td>&nbsp;'.color_mark($q,$tmpval['login']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['status']).'</td>';
        $foreign_lang='';
		if (intval($tmpval['kadri_id'])>0) {$foreign_lang='+';}
		echo '<td>&nbsp;'.$foreign_lang.'</td>';

    $query_gr="SELECT ug.comment AS gr_name,ug.color_mark,ug.id
			FROM `user_groups` ug
			INNER JOIN user_in_group uig ON uig.group_id = ug.id
			WHERE uig.user_id ='".intval($tmpval['id'])."' order by ug.comment LIMIT 0, 30";
        $grList=getRowSqlVar($query_gr);

        $grListStr='';
        for ($k=0;$k<count($grList);$k++)
            $grListStr.='<a href="user_access.php?group_id='.$grList[$k]['id'].'" title="просмотреть задачи в группе"><font color="'.$grList[$k]['color_mark'].'">'.$grList[$k]['gr_name'].'</font></a>;';
        echo '<td>&nbsp;'.$grListStr.'</td>';

    $personTaskCnt=intval($tmpval['tiu_cnt']);//  getScalarVal('select count(*) from task_in_user where user_id="'.intval($tmpval['id']).'"');
    if ($personTaskCnt==0) $personTaskCnt='';
    echo '<td>&nbsp;<a href="user_access_persons.php?user_id='.$tmpval['id'].'" title="просмотреть персональные задачи">'.$personTaskCnt.'</a></td>';

		if ($tmpval['reg_date_time']=='0000-00-00 00:00:00') $tmpval['reg_date_time']='';
        $date_act=$tmpval['reg_date_time'];
		$date_act=substr($date_act,0,10);
		$date_act=DateTimeCustomConvert($date_act,'d','mysql2rus');
		echo '<td>&nbsp;'.color_mark($q,$date_act).'</td>';

		if ($tmpval['last_datetime']=='0000-00-00 00:00:00') $tmpval['last_datetime']='';
        $date_act=$tmpval['last_datetime'];
		$date_act=substr($date_act,0,10);
		$date_act=DateTimeCustomConvert($date_act,'d','mysql2rus');
		echo '<td>&nbsp;'.color_mark($q,$date_act).'</td>';

		echo '<td>&nbsp;<a href="'.$tmpval['t_url'].'">'.color_mark($q,$tmpval['t_name']).'</a></td>';

		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.color_mark($q,$tmpval['comment']).'</td>';}
	}
echo '</table></form>';
	}
//-------------------------------------списочная таблица -конец----------------------------------------------------

//постраничный вывод списка тем (по 10 тем)
echo '<div align="center"> страницы ';
$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo getPagenumList($pages_cnt,$page,6,'page',$add_string,'');
echo '</div>';
//--------------------------------------------------------

$add_string=reset_param_name($add_string,'pgVals');// preg_replace("/(&pgVals=)(\d+)/x","",$add_string);		//убрать число страниц через RegExp
echo '<br>макс.число записей на странице:  <input type=text value="'.$pgVals.'" name="pgVals" id="pgVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgVals(\''.$add_string.'\');" value=Ok>
	<p> Всего записей: '.$itemCnt.'</div>'; 	
	}
?>
<div class=text>
	 <b>Примечание:</b><br>
	 <ul>
		  <li>после добавления пользователя список его задач пуст, поэтому пользователя требуется <a href="user_access.php">включить в одну из групп,</a>либо <a href="user_access_persons.php">указать персональные права</a>;</li>
		  <li>для <b>просмотра списка задач</b> в группе пользователя или списка персональных задач достаточно кликнуть по наименованию группы или числу персональных задач.</li>
		  <li>при статусе "Преподаватель" пользователи отмечаются как Преподаватели на публичных страницах сайта (префик страниц p_)</li>
	 </ul>
</div>
<?php

if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>