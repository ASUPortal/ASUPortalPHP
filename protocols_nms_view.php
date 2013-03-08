<?php
$pg_title='Протоколы НМС';
include ('authorisation.php');
//include ('sql_connect.php');

$main_page=$curpage;
$page=1;
$q='';			//строка поиска
$pgVals=20;	//число данных о протоколе на странице по умолчанию
$query_string=$_SERVER["QUERY_STRING"];
$err=false;		//нет ошибок при изменении БД

$sort=2;
$stype='desc';

if (isset($_GET['stype']) && ($_GET['stype']=='desc' || $_GET['stype']=='asc')) {$stype=$_GET['stype'];}
if (isset($_GET['sort'])) {$sort=intval($_GET['sort']);}

//$files_path='protocols/attachement/';

if (isset($_GET['q'])) {$q=$_GET['q'];}
if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}
if (isset($_GET['pgVals']) && $_GET['pgVals']<=99 && $_GET['pgVals']>=1) {$pgVals=$_GET['pgVals'];}

//----------------------------------------------------------
if (isset($_GET['type']) & $_GET['type']=='del' && $write_mode===true)
	{
	//echo '!!!!!!!del!!!!!!';
	$query='delete from protocols_nms where id="'.$_GET['item_id'].'"';	
	//echo $query;
	$res=mysql_query($query);
	

	$query='delete from protocol_nms_details where protocol_id="'.$_GET['item_id'].'"';
	//echo $query;
	$res=mysql_query($query);
	
	header('Location:'.$main_page.'?page='.$page);
		
	}

include ('master_page_short.php');

//----------------------------------------------------------------------------------------------------
//print_r($_POST);
?>
<style>
tr.title {font-size:13px; font-family:Arial; background-color:#E6E6FF; }
tr.main {font-size:11px; font-family:Arial; text-align:left; font-weight:normal;}
.err {color:red;font-family:Arial;}
</style>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
<script type="text/javascript" src="scripts/calendar_init.js"></script>


<script language="JavaScript">
var main_page='<?php echo $main_page?>';	//for redirect & links
var on_cnt=0,off_cnt=0;
var grey='#767676';
var red='#FF0000';
var green='#00FF00';

function hide_show_matter(name_form,vis_type,radio_val)
{
//alert(name_form);
//alert(radio_val);
on_cnt=parseInt(document.getElementById('max_table_kadri_on').value);
off_cnt=parseInt(document.getElementById('max_table_kadri_off').value);
//alert(on_cnt+'-'+off_cnt);

if (radio_val=='0') {off_cnt++;document.getElementById('person_'+name_form).style.color='red';}
else {	if (radio_val=='1') {on_cnt++;document.getElementById('person_'+name_form).style.color='green';} 
		else {document.getElementById('person_'+name_form).style.color=grey;}	
	}


if (name_form!='') {
	if (vis_type=='view')
		{document.getElementById('table_kadri_off_matter_item_'+name_form).style.display='';}
	else {document.getElementById('table_kadri_off_matter_item_'+name_form).style.display='none';
		  document.getElementById('table_kadri_off_matter_item_'+name_form).value='';}	 
	}
document.getElementById('max_table_kadri_off').value=off_cnt;
document.getElementById('max_table_kadri_on').value=on_cnt;
}

function mark_all_radio(radio_type)
{
var elem_id='';

for (var i = 0; i < document.item_form.elements.length; i++) 
	{ if ((document.item_form.elements[i].type=='radio') && (document.item_form.elements[i].value==radio_type) ) 
		{
		//alert("The value of radioObj[" + i + "] is "+ document.item_form.elements[i].name);
		document.item_form.elements[i].checked=true;
		elem_id=document.item_form.elements[i].name;
		elem_id=elem_id.substr(11);
		//alert('elem_id='+elem_id);
		if (radio_type=='0') 
			{
			 try {	document.getElementById('table_kadri_off_matter_item_'+elem_id).style.display='';
			 		document.getElementById('person_'+elem_id).style.color='red';}
			 catch (e) {document.all['table_kadri_off_matter_item_'+elem_id].style.display='';
			 			document.all['person_'+elem_id].style.color='red';}
			}
		else {
		 	try {document.getElementById('table_kadri_off_matter_item_'+elem_id).style.display='none';} 
			catch (e) {document.all['table_kadri_off_matter_item_'+elem_id].style.display='none';}
			if (radio_type=='1') {
			 	try {document.getElementById('person_'+elem_id).style.color='green';}
			 	catch (e) {document.all['person_'+elem_id].style.color='green';}
			}
			else {
			 try {document.getElementById('person_'+elem_id).style.color=grey;}
			 catch (e) {document.all['person_'+elem_id].style.color=grey;}
			 }
			}
		} 
  	} 
 
} 
function del_confirm(id,num,page)
{
	 if (confirm('Удалить данные о протоколе: '+num+' ?')) 
	 	{window.location.href=main_page+'?item_id='+id+'&type=del'+'&page='+page;} 
} 
function go2search(filtr)
{
 	var search_query=document.getElementById('q').value;
 	if (search_query!='') {window.location.href=main_page+'?q='+search_query;}
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
 document.item_form.date_text.value=date_string;
 
} 
function pgVals(filtr)
{
 	var pageCnt= parseInt(document.getElementById('pgVals').value);
 	if (pageCnt>0 && pageCnt<100) {document.location.href=main_page+'?pgVals='+pageCnt;}
 	else {alert('Введите значение с 1 до 99.');}
 	
	 //if (pageCnt>0 && pageCnt<100) {alert('ok');}
	 
	//alert(pageCnt);
} 
function check_form()
{
 	a = new Array(
	 	new Array('num','номер'),
		new Array('date_text','дата'),
	 	new Array('program_content','повестка дня')		
	);
      requireFieldCheck(a,'item_form'); 
} 
var c=0; //счётчик количества строк
//var c_send=0; //счётчик количества строк для рассылки
function addline(tab_name)
{
	c=document.getElementById('max_'+tab_name).value;	
	c++; // увеличиваем счётчик строк
	s=document.getElementById(tab_name).innerHTML; // получаем HTML-код таблицы
	s=s.replace(/[\r\n]/g,''); // вырезаем все символы перевода строк
	re=/(.*)(<tr id=.*>)(<\/table>)/gi; 
                // это регулярное выражение позволяет выделить последнюю строку таблицы
	s1=s.replace(re,'$2'); // получаем HTML-код последней строки таблицы
	s2=s1.replace(/\_\d+/gi,'_'+c+''); // заменяем все цифры к квадратных скобках
                
		// на номер новой строки
//-----------------------------------------------------------------------------
var myExp = new RegExp("(rmline\\()\\d+\\,'"+tab_name+"'","gi"); //    формируем рег_выражения с учетом области для замены=tab_name
s2=s2.replace(myExp,'$1'+c+',\''+tab_name+'\'');
//-----------------------------------------------------------------------------

//	s2=s2.replace(/rmline\(\d+/gi,'$1'+c+')');

                // заменяем аргумент функции rmline на номер новой строки
	s=s.replace(re,'$1$2'+s2+'$3');
                // создаём HTML-код с добавленным кодом новой строки
	document.getElementById(tab_name).innerHTML=s;
//	alert(s);
	document.getElementById('max_'+tab_name).value=c;
	
	if (tab_name=='protocols_details') {//<input type=text name=protocols_details_cnt_
	 /*alert(s);
	 s=s.replace(/(<INPUT type="text" name="protocols_details_cnt_)\d(" value=")\d+(")/gi,'$1'+c+'$2'+(c+1)+'$3');
	 alert(s);*/
	 //document.getElementById('protocols_details_cnt').innerHTML=c;
	 }
	                // возвращаем результат на место исходной таблицы
//	alert(s);
	return false; // чтобы не происходил переход по ссылке
}
function rmline(q,tab_name)
{
                if (q==0)return false;
                if (c==0) return false; else c--;
                // если раскомментировать предыдущую строчку, то последний (единственный) 
                // элемент удалить будет нельзя.
           
	s=document.getElementById(tab_name).innerHTML;
	s=s.replace(/[\r\n]/g,'');
	re=new RegExp('<tr id="?newline"? nomer="?_'+q+'.*?<\\/tr>','gi');
                // это регулярное выражение позволяет выделить строку таблицы с заданным номером
	s=s.replace(re,'');
                // заменяем её на пустое место
	
	document.getElementById(tab_name).innerHTML=s;
	document.getElementById('max_'+tab_name).value=c;
	
	return false;
}
</script>
<?php
if (!isset($_GET['save']) && !isset($_GET['print'])) 
      echo '<h4 class="main">'.$pg_title.'</h4> ';

if ($write_mode===true && isset($_GET['type']) && ($_GET['type']=='edit' || $_GET['type']=='del'))
{header('Location:'.$main_page);echo '<h4>правка и удаление протоколов не предусмотрено</h4>';
echo '<a href="'.$main_page.'">К списку протоколов</a>';exit;}

//include ('menu.htm');
//include ('sql_connect.php');

//удаление данных о протоколе


//добавление данных о протоколе
//echo '<br><br>';
if (isset($_POST['num']))
{
		 //приводим дату к формату ггггммдд
		 $date_text_modif=DateTimeCustomConvert($_POST['date_text'],'d','rus2mysql');
		 //$date_text_modif=substr($_POST["date_text"],6,4).substr($_POST["date_text"],3,2).substr($_POST["date_text"],0,2);
	if ($_POST['num']!='' & $_POST['program_content']!='' & $_POST['date_text']!='') 
	{
		 //print_r($_POST);
		 $onEditRemain_text='';
		 $query_string=reset_param_name(reset_param_name($query_string,'type'),'item_id');
		 if (!$onEditRemain) {$onEditRemain_text=' автопереход к списку через 2 сек. или вручную <a href="'.$curpage.'?'.$query_string.'">по ссылке</a>';}


	 //обновление протокола
	 if (isset($_GET['type']) & $_GET['type']=='edit' & isset($_GET['item_id']) & $_GET['item_id']!='') {
		 //echo 'Правка данных о протоколе.';
		 $query="update protocols_nms set num='".f_ri($_POST["num"])."',date_text='".f_ri($date_text_modif)."',
		 	program_content='".f_ri($_POST["program_content"])."',
		 	comment='".f_ri($_POST["comment"])."' where id='".f_ri($_GET["item_id"])."'";

		 if ($res=mysql_query($query)) {
		  
					$protocol_id=0;$protocol_id=$_GET['item_id'];
					/*$query="select max(id)as max_id from protocols";
					$res=mysql_query($query);$a=mysql_fetch_array($res);
					if ($a['max_id']>0) {$protocol_id=$a['max_id'];}*/

//------------------------------вставка присутствующих\отсутсвующих------------------------------------------------------------------
			$err_details=false;					

					$query_visit="delete from protocol_nms_details where protocol_id='".$protocol_id."'";
					if ($write_mode!==true || !mysql_query($query_visit)) {$err_details=true;
						echo '<div class=warning>ошибки удаления старого состава сотрудников протокола. Выполнение прервано.</div>';}

			 if ($protocol_id!=0 && $err_details==false) {//
				
				$err_opinions=false;
				$err=false;
//---------------------------------------------//-----------------------------//-----------------------//----------------
				
				
				for ($i=1;$i<=$_POST['max_protocols_details']+1;$i++) 
					{					
					if ($_POST['kadri_id_'.$i]!=0) {
					$_POST['section_id_'.$i]=str_replace("_","",$_POST['section_id_'.$i]);	//убираем все нечисловые символы					
					
					$query_details="insert into protocol_nms_details(protocol_id,section_id,kadri_id,text_content,opinion_id,
							opinion_text) 
						values('".$protocol_id."','".$_POST['section_id_'.$i]."',
						'".$_POST['kadri_id_'.$i]."','".$_POST['text_content_'.$i]."','".$_POST['opinion_id_'.$i]."',
						'".$_POST['opinion_text_'.$i]."')";
					if (!mysql_query($query_details)) {$err_opinions=true;$err=true;}
					//echo '<hr>i='.$i.'<br> query_details='.$query_details.'!!!<hr>';
					}
					}
				  if ($err_opinions==true) {echo '<div class=warning>ошибки вставки пунктов программы</div>';}
			  										 				}
//------------------------------------------------------------------------------------------------																	   			  
		  
		  echo '<div class="success">Данные протокола обновлены. '.$onEditRemain_text.'</div>';}
		 else {echo '<div class="err">Данные протокола не обновлены .<p>&nbsp;</div>';}
		 //echo $query;
	 }
	 
	 //новый протокол
	 if (isset($_GET['type']) & $_GET['type']=='add') {
		 echo 'Добавление данных о протоколе.';

		 $_POST["date_text"]=trim($_POST["date_text"]);
		 

		 $query="insert into protocols_nms(num,date_text,program_content,comment) 
		 	values('".f_ri($_POST["num"])."','".f_ri($date_text_modif)."','".f_ri($_POST["program_content"])."','".f_ri($_POST["content"])."')";
		 //echo $query;
		 $res_news=true;
		 if ($res_news & $res=mysql_query($query)) 
		 	{
					$protocol_id=0;
					$query="select max(id)as max_id from protocols_nms";
					$res=mysql_query($query);$a=mysql_fetch_array($res);
					if ($a['max_id']>0) {$protocol_id=$a['max_id'];}

//------------------------------вставка присутствующих\отсутсвующих------------------------------------------------------------------
			 if ($protocol_id!=0) {//
				$err=false;
				$err_opinions=false;
				
//---------------------------------------------//-----------------------------//-----------------------//----------------				
				
				for ($i=1;$i<=$_POST['max_protocols_details']+1;$i++) 
					{
					if ($_POST['kadri_id_'.$i]!=0) {
					$_POST['section_id_'.$i]=str_replace("_","",$_POST['section_id_'.$i]);	//убираем все нечисловые символы
					

					$query_details="insert into protocol_nms_details(protocol_id,section_id,kadri_id,text_content,opinion_id,
						opinion_text) 
						values('".$protocol_id."','".$_POST['section_id_'.$i]."',
						'".$_POST['kadri_id_'.$i]."','".$_POST['text_content_'.$i]."','".$_POST['opinion_id_'.$i]."',
						'".$_POST['opinion_text_'.$i]."')";
					if (!mysql_query($query_details)) {$err_opinions=true;$err=true;}
					//echo '<hr>i='.$i.'<br> query_details='.$query_details.'!!!<hr>';
					}
					}
				
				  if ($err_opinions==true) {echo '<div class=warning>ошибки вставки пунктов программы</div>';}
			  										 				}
//------------------------------------------------------------------------------------------------																	   			  
			  echo '<div class="success">Данные о протоколе:" № '.$_POST['num'].' от '.f_ri($_POST["date_text"]).'" добавлены. '.$onEditRemain_text.'</div>';}
		 else {echo '<div class="err">Данные о протоколе не добавлены. Возможно такой протокол там уже есть<p>&nbsp;</div>';}
		 
			 
	
	//echo $query;
	 }
	 if (!$err && !$onEditRemain) {echo '<script language="Javascript">setTimeout("window.location.href=\"'.$curpage.'?'.$query_string.'\"",2000);</script>';}	
	 }
	else {echo '<div class="err">Часть обязательных данных не заполнено .<br>&nbsp;</div>';}
	
} 
//вывод протокола на экран для просмотра и печати
if (isset($_GET['item_id']) && isset($_GET['type']) && $_GET['type']=='view')
{
  if (!isset($_GET['save']) && !isset($_GET['print']))
			{
		
		echo '<p><b class="notinfo"> Печать и экспорт.</b> &nbsp; ';
			if ($write_mode===true)
			{ echo '<a href="'.$main_page.'?item_id='.$_GET['item_id'].'&type=edit">Правка </a>  &nbsp;'; }
			echo '<a href="'.$main_page.'?">К списку </a>';
		echo " </p><div style='text-align:right;'>
			 	<a class=text href='?".$_SERVER["QUERY_STRING"]."&save&attach=doc' title='Выгрузить'>Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
				<a class=text target='_blank' href='?".$_SERVER["QUERY_STRING"]."&print' title='Распечатать'>Печать</a></div>"; }

	$query="select * from protocols_nms where id='".$_GET['item_id']."'";
	$res=mysql_query($query);
	$res_view=mysql_fetch_array($res);
	if (mysql_num_rows($res)==1) { //если есть протокол такой
	 	//$res_view['date_text']=substr($res_view['date_text'],6,2).'.'.substr($res_view['date_text'],4,2).'.'.substr($res_view['date_text'],0,4);
		 echo '<div align=center>ПРОТОКОЛ НМС № <b>'.$res_view['num'].'</b><br>заседания кафедры АСУ</div>
		 <div align=right>от <b>'.DateTimeCustomConvert($res_view['date_text'],'d','mysql2rus').'</b> г.</div>';

//----------------------------------------------------------------------------------------
$row_id_on=0; $row_id_off=0;

$str_visit_on='';$str_visit_off='';
/*		//  формирование строки присутствующих\отсутствующих
	 	$query="select kadri.fio_short,protocol_visit.protocol_id,protocol_visit.kadri_id from protocol_visit inner join kadri on kadri.id=protocol_visit.kadri_id where protocol_visit.protocol_id='".$_GET['item_id']."' and protocol_visit.visit_type=1";
		//echo $query;
		$res_=mysql_query($query);
 while ($z=mysql_fetch_array($res_))	//вывод по номерам пар (number)
 	{ if ($str_visit_on=='') {$str_visit_on=$z['fio_short'];}
	  else {$str_visit_on=$str_visit_on.', '.$z['fio_short'];}
	  $row_id_on++;	
	  }
	 	$query="select kadri.fio_short,protocol_visit.protocol_id,protocol_visit.kadri_id from protocol_visit inner join kadri on kadri.id=protocol_visit.kadri_id where protocol_visit.protocol_id='".$_GET['item_id']."' and protocol_visit.visit_type=0";
		//echo $query;
		$res_=mysql_query($query);
 while ($z=mysql_fetch_array($res_))	//вывод по номерам пар (number)
 	{ if ($str_visit_off=='') {$str_visit_off=$z['fio_short'];}
	  else {$str_visit_off=$str_visit_off.', '.$z['fio_short'];}
	  $row_id_off++;	
	  }
	  

 		echo '<table border=0>
		 <tr valign=top align=left><td>ПРИСУТСТВОВАЛИ:</td><td>'.$str_visit_on.'</td></tr>
		 <tr valign=top align=left><td>ОТСУТСТВОВАЛИ:</td><td>'.$str_visit_off.'</td></tr>';
		 echo '</table><p>&nbsp;</p>';
		 */
 //$row_id_on=0; $row_id_off=0;
 //$str_visit_on='';$str_visit_off='';
//----------------------------------------------------------------------------------------
		echo '<div align=center><b>ПОВЕСТКА ДНЯ</b></div>';
		$res_view['program_content']=str_replace("\r\n","<br>",f_ro($res_view['program_content']));
		echo '<div align=left>'.$res_view['program_content'].'</div><p>&nbsp;</p>';

$query="select pnd.protocol_id,pnd.section_id,k.fio_short,
			pnd.text_content,po.name as opinion_name,pnd.opinion_text   
		from protocol_nms_details pnd
			left join kadri k on k.id=pnd.kadri_id 
			left join protocol_opinions po on po.id=pnd.opinion_id
		where pnd.protocol_id='".$_GET['item_id']."' order by pnd.section_id";
		//echo $query;
		$res_=mysql_query($query);
 echo '<table border=0>';
 while ($z=mysql_fetch_array($res_))	//вывод по номерам пар (number)
 	{ 
	  echo '<tr valign=top align=left><td wi><b>'.$z['section_id'].'</b></td><td>СЛУШАЛИ: </td><td><b>'.$z['fio_short'].'</b> - '.preg_replace("/\n/","<br>",$z['text_content']).'</td></tr>';	
 	 	echo '<tr valign=top align=left><td>&nbsp;</td><td>ПОСТАНОВИЛИ: </td><td><b>'.$z['opinion_name'].' '.$z['opinion_text'].'</b><p>&nbsp;</p></td></tr>';
	  }
echo '</table><p>&nbsp;</p>';
	 
echo '<table border=0 width=800><tr><td width="50%">Зав.кафедрой АСУ</td><td>Г.Г. Куликов</td></tr>
		<tr><td colspan=2>&nbsp;</td></tr>
		<tr><td>Секретарь</td><td width="50%">Г.И. Маврина</td></tr></table><p>&nbsp;</p>';	 
	 
	 } 
} 
if (isset($_GET['type']) & $_GET['type']=='edit')
{
	if (isset($_GET['item_id']) & $_GET['item_id']!="")
	{
	$query="select * from protocols_nms where id='".$_GET['item_id']."'";
	$res=mysql_query($query);
	$res_edit=mysql_fetch_array($res);
	}
	else {echo '<h4 class="err">не выбраны данные о протоколе для правки</h4>';}	
}

//добавление данных о протоколе
if (isset($_GET['type']) & $_GET['type']=='add' || $_GET['type']=='edit')
{
if ($_GET['type']=='edit') {echo '<b>Правка данных.</b>';
	echo '<p><a href="?item_id='.$_GET['item_id'].'&type=view">Печать</a>&nbsp;&nbsp;&nbsp;
	<!--a href="?item_id='.$_GET['item_id'].'&type=copy">Копировать</a>&nbsp;&nbsp;&nbsp;-->';}

else {echo '<b>Ввод новых данных.</b>';}
?>
 &nbsp;  &nbsp; <a href="<?php echo $main_page.'?';?>">К списку </a><p>
<form name="item_form" id=item_form method="post" action=""><table> 
<tr><td>номер * </td><td><input type=text size=20 name=num id=num value="<?php echo getFormItemValue('num'); ?>"></tr>
<tr><td>дата *</td><td><input type=text size=20 name=date_text id=date_text value="<?php if (isset($res_edit)) {
 	echo DateTimeCustomConvert($res_edit['date_text'],'d','mysql2rus');
	 } ?>"><button type="reset" id="f_trigger_date_act_sort">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_text",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act_sort",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
<a href="#" onClick="javascript:day_now();">Сегодня</a></td></tr>
<tr><td>повестка дня *</td><td><textarea name=program_content id=program_content rows=10 cols=60><?php echo getFormItemValue('program_content'); ?></textarea></td></tr>
<tr><td>комментарий </td><td><textarea name=comment id=comment rows=4 cols=60><?php echo getFormItemValue('comment'); ?></textarea></td></tr>
<tr><td colspan=2>
<input type=button onclick=javascript:check_form(); value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> 
	&nbsp;&nbsp;&nbsp; 
<input type=reset value=Очистить>
<?php 
	if (isset($res_edit)) 
	{echo '<input type=button value="Печать" name=view_btn onclick=javascript:document.location.href="?item_id='.$_GET['item_id'].'&type=view";>';} ?>
&nbsp; <a href="#program">к пунктам повестки</a> 
 
 </td></tr>

<tr><td colspan=2>
<hr style="color:#ccccff; background-color:#ccccff; height:5; size:20;">
<a name=program></a>
<?php 
$row_id_detail=1;

$query="select id,protocol_id,section_id,kadri_id,text_content,opinion_id,opinion_text from protocol_nms_details 
	where protocol_id='".$_GET['item_id']."' order by section_id";
		//echo $query;
		$res_=mysql_query($query);
		$res_edit=array();
 while ($z=mysql_fetch_array($res_))	//вывод по номерам пар (number)
 	{ 
	  echo '<a name="'.'detailsId_'.$z['id'].'"></a>';
	  echo '<input type=text name="section_id_'.$row_id_detail.'" value="_'.$z['section_id'].'" title="порядок" style="width:30"> ';
	  //<b>'.$z['section_id'].'.</b>
	  echo ' Слушали';
?>
		   <select name="kadri_id_<?php echo $row_id_detail;?>" style="width:300;">	  	 	
		  <?php 
		  $query='select id,fio from kadri order by fio';
		  $res_edit['kadri_id_'.$row_id_detail]=$z['kadri_id'];
		  echo getFrom_ListItemValue($query,'id','fio','kadri_id_'.$row_id_detail);
		  ?></select>
		  автор доклада<br>
<?php	  
	  echo '<textarea rows="6" cols="60" name="text_content_'.$row_id_detail.'">'.$z['text_content'].'</textarea> текст доклада';
	  
	  echo '<p>Постановили ';
	  echo '<select name="opinion_id_'.$row_id_detail.'" style="width:300;">';
	  	  	 	
		$query='select id,name from protocol_opinions order by name';
	        $res_edit['opinion_id_'.$row_id_detail]=$z['opinion_id'];
		echo getFrom_ListItemValue($query,'id','name','opinion_id_'.$row_id_detail);
		
		echo '</select>решение';?>
		  <br>
		  <textarea name="opinion_text_<?php echo $row_id_detail;?>" rows="2" cols="60"><?php echo $z['opinion_text'];?></textarea> дополнение к решению
		  <p>
		   
		   <input type="file" name="file_name_<?php echo $row_id_detail;?>" disabled> прикрепленный файл 

<br>
      <a href="#top">наверх</a> &nbsp;
      <a href="?">просмотр списка протоколов</a> &nbsp; &nbsp; &nbsp;
       
<hr style="color:#ccccff; background-color:#ccccff; height:5; size:20;">
		   <br>	 
		<?php
	  	$row_id_detail++;
		
		}
?>
<!---------------- начало форма новых данных + множественное добавление --------------------------------------->	

<div id="protocols_details" name="protocols_details" style="display:">
   <table border="0" cellspacing="0" cellpadding="3">
     <tr id="newline" nomer="_0">
       <td></td>
       <td valign="top" align="center">
	   <a href="#" onclick="return addline('protocols_details');" style="text-decoration:none"><img src="images/design/pl.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
    <tr id="newline" nomer="_0">
      <td><input type=text name="section_id_<?php echo $row_id_detail;?>" value="_<?php echo $row_id_detail;?>" title="порядок" style="width:30">
	  	Слушали 
		   <select name="kadri_id_<?php echo $row_id_detail;?>" style="width:300;">	  	 	
		  <?php 
		  $query='select id,fio from kadri order by fio';
		  echo getFrom_ListItemValue($query,'id','fio','kadri_id_'.$row_id_detail);
		  ?></select>
		
		автор доклада<br>
	 <textarea name="text_content_<?php echo $row_id_detail;?>" rows="6" cols="60"></textarea> текст доклада	  

	  	 <p>Постановили 
		   <select name="opinion_id_<?php echo $row_id_detail;?>" style="width:300;">	  	 	
		  <?php 
		  $query='select id,name from protocol_opinions order by name';
		  echo getFrom_ListItemValue($query,'id','name','opinion_id_'.$row_id_detail);
		  ?></select>
		   решение <br>
		   <textarea name="opinion_text_<?php echo $row_id_detail;?>" rows="2" cols="60"></textarea> дополнение к решению
		   <p>		   
		   <input type="file" name="file_name_<?php echo $row_id_detail;?>" disabled> прикрепленный файл <br>	 
	    
		</td>
      <td valign="top" align="center"><a href="#" onclick="return rmline(<?php echo $row_id_detail;?>,'protocols_details');" style="text-decoration:none"><img src="images/design/mn.gif" border="0" WIDTH="17" HEIGHT="18"></a></td></tr>
  </table>
</div>
<!---------------- конец форма новых данных + множественное добавление --------------------------------------->	
		
</td></tr>

<tr><td colspan=2>
<input type=button onclick=javascript:check_form(); value="<?php if (isset($res_edit)) {echo 'Изменить';} else {echo 'Добавить';} ?>"> 
	&nbsp;&nbsp;&nbsp; 
<input type=reset value=Очистить>
<?php 
	if (isset($res_edit)) 
	{echo '<input type=button value="Печать" name=view_btn onclick=javascript:document.location.href="?item_id='.$_GET['item_id'].'&type=view";>';} ?>
 </td></tr>

</table>
<input type="hidden" id=max_protocols_details name="max_protocols_details" value="<?php echo $row_id_detail;?>">
</form>

<?php
}//item_id=40&type=view
//else
//---------------------------------------------------создание выписки для путевки в Авиатор
if (isset($_GET['item_id']) &&  isset($_GET['type']) && $_GET['type']=='edit' && intval($_GET['item_id'])>0)
{
     echo '<p><a href="?">К списку</a></p>'; 
}//
//---------------------------------------------------
if (!isset($_GET['item_id']) && !isset($_GET['type']))
	{

if (!isset($_GET['save']) && !isset($_GET['print'])) {
	if ($write_mode===true) {echo '<p class="notinfo"><a href="?type=add"> Добавить</a><p>';}
	?>
	<table width=99% class="notinfo"><tr>
	
	<td width="*" align=right> 
	<form action="" method="get" name=sForm id=sForm>
	<input type=button value="Печать" onclick=javascript:window.location.href="?print=1&<?php echo $_SERVER["QUERY_STRING"];?>";>&nbsp;&nbsp;
	<input type=button value="в Word" onclick=javascript:window.location.href="?save&attach=doc&<?php echo $_SERVER["QUERY_STRING"];?>">&nbsp;&nbsp;	
	<input type=text name="q" title="текст для поиска" id="q" width=50 value=""> &nbsp;
	<input type=button value="Найти" OnClick="javascript:requireFieldCheck(new Array(new Array('q','')),'sForm');">
         </form>
	</td>
	</tr></table>

<?php	}


if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
//$q4date='';
$search_query=' and (protocols_nms.program_content like "%'.$q.'%" or 
					protocols_nms.comment like "%'.$q.'%" or 
					protocols_nms.date_text like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%")';}

if ($sort<1 && $sort>7) {$sort=2;}

$query="SELECT num,date_text, program_content,  comment,id
      FROM protocols_nms where 1 ".$search_query." order by ".$sort." ".$stype." ";

$res=mysql_query($query.'limit '.(($page-1)*$pgVals).','.$pgVals);

//echo $query;

?>
<!--//---------------------------------начало списочная форма ------------------------------------------------- -->
<?php if (!isset($_GET['save']) && !isset($_GET['print'])) { 

$itemCnt=getScalarVal('select count(*) from ('.$query.')t');

if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {$pages_cnt=floor($itemCnt/$pgVals);}
else {$pages_cnt=($itemCnt/$pgVals)+1;}

$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;


} 

if ($itemCnt<=0) echo '<div class=warning>записей не найдено</div>';
else {	//---- списочная форма начало--------------------------------
echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';
?>

<table name=tab1 border=1 cellpadding="5" cellspacing="0" width="99%"><tr align="center" class="title" height="30">

<?php
	if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode===true) {
		echo '<td width="60" class="notinfo"><img src="images/todelete.png" title="Удалить" border="0">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка" border="0"></td>';}
	echo '<td width="30">№</td>';
	echo '<td width="60">'.print_col(2,'дата').'</td>';
	echo '<td width="*">текст повестки</td>';
	
	
	if (!isset($_GET['save']) && !isset($_GET['print'])) 
		{echo '<td width="100" class="notinfo">'.print_col(4,'комментарий').'</td>';	}
		
	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};

	$i=0;
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		echo '<tr align="left" class="main" '.$bgcolor.' valign="top">';
		if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode==true) {
		  echo '<td align="center" width="60"> <a href="javascript:del_confirm(\''.f_ro($tmpval['id']).'\',\'№'.
		  	f_ro($tmpval['num']).' от '.f_ro($tmpval['date_text']).'\',\''.f_ro($_GET['page']).'\')" title="Удалить">
			<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;';
				echo '<a href="?item_id='.$tmpval['id'].'&type=edit" title="Правка">
				<img src="images/toupdate.png" alt="Правка" border="0"></a></td>';

			}
		$i++;
		echo '<td width="30">&nbsp;'.color_mark($q,$tmpval['num']).'</td>';
		
		$date_text=DateTimeCustomConvert($tmpval['date_text'],'d','mysql2rus');
		
		
		echo '<td width="60" valign=top> ';
		if (!isset($_GET['save']) && !isset($_GET['print'])) 
			{echo '<a href="?item_id='.$tmpval['id'].'&type=view" title="просмотреть">'.color_mark($q,$date_text).'</a>';}
		else {echo $date_text;}
		echo '</td>';
		$tmpval['program_content']=str_replace("\n","<br>",$tmpval['program_content']);
		echo '<td width="*">'.color_mark($q,$tmpval['program_content']).'&nbsp;</td>';
		if (!isset($_GET['save']) && !isset($_GET['print'])) {		 	 
			 echo '<td class="notinfo">&nbsp;'.$tmpval['comment'].'</td>';}
	}
?>
</table>
<?php
$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';
echo '<br>макс.число строк на странице:  <input type=text value="'.$pgVals.'" name="pgVals" id="pgVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgVals(\'\');" value=Ok>
	<p> Всего строк: '.mysql_num_rows($res).'</div>'; 	

}
//---- списочная форма конец--------------------------------
?>
<?php
//---------------------------------конец списочная форма -------------------------------------------------


//--------------------------------------------------------
	}

if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>