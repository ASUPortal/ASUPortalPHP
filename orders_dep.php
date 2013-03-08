<?php
include ('authorisation.php');
$main_page='orders_dep.php';
$page=1;
$filtr='';		//отбор по типу приказа
$q='';			//строка поиска
$pgVals=20;	//число приказов на странице по умолчанию
$query_string=$_SERVER['QUERY_STRING'];

if (isset($_GET['filtr'])) {$filtr=$_GET['filtr'];}
if (isset($_GET['q'])) {$q=$_GET['q'];}
if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}
if (isset($_GET['pgVals']) && $_GET['pgVals']<=99 && $_GET['pgVals']>=1) {$pgVals=$_GET['pgVals'];}
//----------------------------------------------------------

if (isset($_GET['type']) && $write_mode!==true) {header('Location:'.$main_page);exit();}

if (isset($_GET['type']) & $_GET['type']=='del')
	{
	//echo '!!!!!!!del!!!!!!';
	$query='delete from orders_dep where id="'.$_GET['order_id'].'"';	
	//echo $query;
	$res=mysql_query($query);
	header('Location:'.$main_page.'?page='.$page.'&filtr='.$filtr);
		
	}

include 'master_page_short.php';

?>
<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}
.err {color:red;font-family:Arial;}
</style>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
<script type="text/javascript" src="scripts/calendar_init.js"></script>

<script language="JavaScript">
var main_page='orders_dep.php';	//for redirect & links
function del_confirm(id,num)
{
	 if (confirm('Удалить приказ № '+num+' ?')) 
	 	{window.location.href=main_page+'?order_id='+id+'&type=del'+'<?php echo '&page='.$_GET['page'].'&filtr='.$_GET['filtr'];?>';} 
} 
function filter()
{
 if (document.getElementById('orders_type').value!=0)
	{ window.location.href=main_page+"?filtr="+document.getElementById('orders_type').value;}
 else {window.location.href=main_page;}
} 
function go2search(filtr)
{
 	var search_query=document.getElementById('q').value;
 	if (search_query!='') {window.location.href=main_page+'?q='+search_query+'&filtr='+filtr;}
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
function pgVals(filtr)
{
 	var pageCnt= parseInt(document.getElementById('pgVals').value);
 	if (pageCnt>0 && pageCnt<100) {document.location.href=main_page+'?pgVals='+pageCnt+'&filtr='+filtr;}
 	else {alert('Введите значение с 1 до 99.');}
 	
	 //if (pageCnt>0 && pageCnt<100) {alert('ok');}
	 
	//alert(pageCnt);
} 
function check_form()
{
var err=false;
//var msg='Не заполнены поля приказа: ';
var date_act=document.getElementById('date_order');

if (date_check(date_act.value,true)) 
	 {
	  err=true;
	  alert('Дата приказа не существует. воспользуйтесь календарем;');
	 }
else {
	 var a = new Array(
		 new Array('orders_type','тип приказа'),	
		 new Array('num_order','номер приказа'),
		 new Array('title_order','заголовок приказа'),
		 new Array('text_order','текст приказа')
	 );	 
	 requireFieldCheck(a,'order_form');
}
} 
</script>
<?php


//добавление приказа
//echo '<br><br>';
if (isset($_POST['date_order']))
{
	if ($_POST['date_order']!="" & $_POST['title_order']!="" & $_POST['text_order']!="" & $_POST['num_order']!="" & $_POST['orders_type']!=0) 
	{
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}

	 //обновление приказа
	 if (isset($_GET['type']) & $_GET['type']=='edit' & isset($_GET['order_id']) & $_GET['order_id']!='') {
		 echo 'Правка приказа.';
		 $query="update orders_dep set orders_type='".f_ri($_POST["orders_type"])."',num='".f_ri($_POST["num_order"])."',date='".f_ri($_POST["date_order"])."',
		 	title='".f_ri($_POST["title_order"])."',text='".f_ri($_POST["text_order"])."',comment='".f_ri($_POST["comment_order"])."' where id='".f_ri($_GET["order_id"])."'";

		 if ($res=mysql_query($query)) {echo '<div class=success>Приказ обновлен  успешно.'.$onEditRemain_text.'</div>&nbsp;';}
		 else {$err=true;echo '<div class="err">Приказ не обновлен .<p>&nbsp;</div>';}
		 //echo $query;
	 }
	 
	 //новый приказ
	 if (isset($_GET['type']) & $_GET['type']=='add') {
		 echo 'Добавление приказа.';
		 $query="insert into orders_dep(orders_type,num,date,title,text,comment) 
		 	values('".f_ri($_POST["orders_type"])."','".f_ri($_POST["num_order"])."','".f_ri($_POST["date_order"])."','".f_ri($_POST["title_order"])."','".f_ri($_POST["text_order"])."','".f_ri($_POST["comment_order"])."')";

	$res_news=true;
	//заносим в новости кафедры
	if ($_POST['for_news']=='on') {
	    $number_rand="N";$i=0;
	    while($number_rand=="N")
	     {
	      //mt_srand(time());
	      $i++;
		  $rand=date('d_m_Y').'_'.$i.'.txt';//mt_rand(0,100000);
	      $res01=mysql_query ('select * from news where file="'.$rand.'"');
	      if (mysql_num_rows($res01)==0)
	       {
	        $number_rand="Y";
	       }
	      else
	       {
	        $number_rand="N";
	       }
	     }
		/*$notice=fopen("./news/".$rand,"w");
	    flock($notice, LOCK_EX);
	    fwrite($notice,$_POST['text_order']);
	    fflush($notice);
	    flock($notice, LOCK_UN);
	    fclose($notice);*/
			 
		 $res_news=true;
		 $title='приказ № '.$_POST['num_order'].' от '.$_POST['date_order'].'('.$_POST['title_order'].')';
		 $res_news=mysql_query ('insert into news (news_type,file,title,image,file_attach) 
			values ("order_dep","'.f_ri($_POST['text_order']).'","'.f_ri($title).'","'.date("Y-m-d H:i:s").'","N","N")');
			}
		 
		 if ($res_news & $res=mysql_query($query)) {echo '<div class=success>Приказ "'.$_POST['title_order'].'" добавлен успешно.'.$onEditRemain_text.'</div>&nbsp;';}
		 else {echo '<div class="err">Приказ не добавлен.<p>&nbsp;</div>';}
	 }
	 
	 }
	else {$err=true;echo '<div class="err">Часть обязательных данных не заполнено .<br>&nbsp;</div>';}
if (!$err && !$onEditRemain) {
 	$query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
 	echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
} 

if (isset($_GET['type']) & $_GET['type']=='edit')
{
	if (isset($_GET['order_id']) & $_GET['order_id']!="")
	{echo '<h4>Правка приказа</h4>';
	$query="select * from orders_dep where id='".$_GET['order_id']."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="err">не выбран приказ для правки</h4>';}	
}

//добавление приказов
if (isset($_GET['type']) & $_GET['type']=='add' || $_GET['type']=='edit')
{
?>
<p><a href="orders_dep.php"> Просмотр приказов </a><p>
<h4> Ввод нового приказа </h4>
<form name="order_form" id="order_form" method="post" action="">
Тип приказа* &nbsp; <select name="orders_type" id="orders_type" style="width:500;"> 
		<option value="0">...выберите тип приказа...</option>';
		<?php
		$query='select id,name from orders_dep_type';
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if ($res_edit) { if ($res_edit['orders_type']==$a['id']) {$select_val=' selected';} } 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
			}
		?>
</select><p>
	Дата* &nbsp; <input type=text maxlength=10 size=15 name="date_order" id="date_order" value="<?php if (isset($res_edit)) {echo $res_edit['date'];} ?>"> 
	<button type="reset" id="f_trigger_date_order">...</button>
		  <script type="text/javascript">
	      Calendar.setup({
		  inputField     :    "date_order",      // id of the input field
		  ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
		  showsTime      :    false,            // will display a time selector
		  button         :    "f_trigger_date_order",   // trigger for the calendar (button ID)
		  singleClick    :    true,           // double-click mode false
		  step           :    1                // show all years in drop-down boxes (instead of every other year as default)
	      });
		  </script>
	<a href="javascript:day_now();">Сегодня</a>&nbsp;&nbsp;&nbsp;
	Номер* &nbsp; <input type=text size=20 name=num_order value="<?php if (isset($res_edit)) {echo $res_edit['num'];} ?>"> <p>	
	Заголовок*<br><input type=text size=100 name=title_order value="<?php if (isset($res_edit)) {echo $res_edit['title'];} ?>"> <p>
	Текст приказа*<br><textarea name=text_order cols=75 rows=6><?php if (isset($res_edit)) {echo $res_edit['text'];} ?></textarea> <p>
	Комментарий<br><input type=text size=100 name=comment_order value="<?php if (isset($res_edit)) {echo $res_edit['comment'];} ?>"> <p>
	<input type=checkbox name=for_news>Разместить в новостях кафедры <p>
	<input type=button onclick=javascript:check_form(); value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> &nbsp;&nbsp;&nbsp; 
	<input type=reset value=Очистить> 
</form>

<?php
}

else 
	{
	echo '<h4 class="notinfo"> Приказы.</h4>';

if (!isset($_GET['save']) && !isset($_GET['print'])) {
	if ($write_mode===true)
		{echo '<p class="notinfo"><a href="orders_dep.php?type=add"> Добавить приказ </a><p>';}
	echo '<table width=99% class="notinfo" border=0><tr>';
	echo '<td align=left width="*" colspan=2>
	Тип приказа* &nbsp; <select name="orders_type" id="orders_type" style="width:100;"> 
			<option value="0">тип приказа</option>';
			$query='select id,name from orders_dep_type';
			$res=mysql_query($query);
			while ($a=mysql_fetch_array($res)) 	{
			 	$select_val='';
				 if ($_GET['filtr']) { if ($filtr==$a['id']) {$select_val=' selected';} } 
				echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['name'].'</option>';
				}
	echo '</select> &nbsp;&nbsp; ';
	echo '<input type=button value="Отобрать" onclick="javascript:filter();">&nbsp;&nbsp;';
	echo '<input type=button value="Все" onclick=javascript:window.location.href="orders_dep.php";>&nbsp;&nbsp;
	'.showPrintSaveOpt('print&doc',$_SERVER['QUERY_STRING'],'').'</td> 
	<td align=right><input type=text name="q" id="q" width=50 value=""> &nbsp; <input type=button value="Найти" OnClick=javascript:go2search('.$filtr.');></td>
	</tr></table>
	<p>';}


if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
$search_query=' and (orders_dep.date like "%'.$q.'%" or 
					orders_dep.title like "%'.$q.'%" or 
					orders_dep.text like "%'.$q.'%" or 
					orders_dep.num like "%'.$q.'%" or 
					orders_dep.comment like "%'.$q.'%")';}

$query="SELECT orders_dep.id, date, num, title, text, orders_dep.comment,orders_dep_type.name as orders_type, 
	concat(substring(date,7,4),'.',substring(date,4,2),'.',substring(date,1,2)) as date1,ABS(num)as num1  
		from orders_dep left join
 	orders_dep_type on orders_dep_type.id=orders_dep.orders_type where 1";
if (isset($filtr) & $filtr!=0) {$query=$query.' and orders_type="'.$filtr.'" ';}
//else {$query=" ".$search_query." ";}

$query=$query.$search_query;	

	$sort=0;
	if (isset($_GET['sort'])) {$sort=$_GET['sort'];}
	if ($sort>0 && $sort<6) {
	 	$sort_add=" order by ".$sort." DESC "; 

		if ($sort==2) {$sort_add=" order by date1 DESC ";}
		if ($sort==3) {$sort_add=" order by num1 DESC ";} 
							}
	else {$sort_add=" order by  date1 DESC ";}	//orders_dep.id
	
	$res=mysql_query($query.$sort_add.'limit '.(($page-1)*$pgVals).','.$pgVals);
//echo $query.$sort_add.'limit '.(($page-1)*$pgVals).','.$pgVals;
//-----------------------------------------------------
$itemCnt=getScalarVal('select count(*) from ('.$query.')t');

if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {$pages_cnt=floor($itemCnt/$pgVals);}
else {$pages_cnt=($itemCnt/$pgVals)+1;}

$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';
//-----------------------------------------------------
echo '<form name=order_list id=order_list>
<table name=tab1 border=1 cellpadding="0" cellspacing="0" width="99%"><tr align="center" class="title" height="30">';
	if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode===true) {
		echo '<td width="50" class="notinfo"><img src="images/todelete.png" title="Удалить">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка"></td>';}
	echo '<td width="50"><a href="?filtr='.$_GET['filtr'].'&sort=1" title="сортировать">тип</a></td>';
	echo '<td width="50"><a href="?filtr='.$_GET['filtr'].'&sort=2" title="сортировать">дата </a></td>';
	echo '<td width="50"><a href="?filtr='.$_GET['filtr'].'&sort=3" title="сортировать">номер </a></td>';
	echo '<td width="200"><a href="?filtr='.$_GET['filtr'].'&sort=4" title="сортировать">заголовок</a></td>';
	echo '<td width="400"><a href="?filtr='.$_GET['filtr'].'&sort=5" title="сортировать">текст</a></td>';
	if (!isset($_GET['save']) && !isset($_GET['print'])) 
		{echo '<td width="100" class="notinfo">комментарий</td>';}

	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};

	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		echo '<tr align="left" class="main" '.$bgcolor.' valign="top">';
		if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode===true) {
		  echo '<td align="center"> <a href=javascript:del_confirm(\''.f_ro($tmpval['id']).'\',\''.str_replace(" ","_",f_ro($tmpval['num'])).'\') title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;
			<a href="orders_dep.php?order_id='.$tmpval['id'].'&type=edit" title="Правка">
			<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';}
		echo '<td>&nbsp;'.$tmpval['orders_type'].'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['date']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['num']).'</td>';
		echo '<td>&nbsp;'.color_mark($q,$tmpval['title']).'</td>';

		$tmpval['text']=preg_replace("/\n/","<br>",$tmpval['text']);
		echo '<td align=justify>&nbsp;'.color_mark($q,$tmpval['text']).'</td>';
		if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 	echo '<td class="notinfo">&nbsp;'.$tmpval['comment'].'</td>';}
	}
echo '</table></form>';

//постраничный вывод списка приказов (по 10 приказов)
/*if (isset($filtr) & $filtr!=0) {$query='select id from orders_dep where orders_type="'.$filtr.'"';}
else {$query='select id from orders_dep';}
//echo $query;*/
$res=mysql_query($query);
$pages_cnt=floor(mysql_num_rows($res)/$pgVals)+1;

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

if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>