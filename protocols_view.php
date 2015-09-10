<?php
include ('authorisation.php');
//include ('sql_connect.php');
//include 
//session_start();
//include ('sql_connect_empty.php');


$main_page=$curpage;//'protocols_view.php';
$page=1;
$q='';			//строка поиска
$pgVals=20;	//число данных о протоколе на странице по умолчанию
$query_string=$_SERVER["QUERY_STRING"];
$err=false;		//нет ошибок при изменении БД
$on_control_filter='';

$sort=2;
$stype='desc';

if (isset($_GET['stype']) && ($_GET['stype']=='desc' || $_GET['stype']=='asc')) {$stype=$_GET['stype'];}
if (isset($_GET['sort'])) {$sort=intval($_GET['sort']);}

//$files_path='protocols/attachement/';

if (isset($_GET['q'])) {$q=$_GET['q'];}
if (isset($_GET['page']) && $_GET['page']>1) {$page=$_GET['page'];}
if (isset($_GET['pgVals']) && $_GET['pgVals']<=99 && $_GET['pgVals']>=1) {$pgVals=$_GET['pgVals'];}

if (isset($_GET['on_control_filter']) && $_GET['on_control_filter']=='on') 
	{$on_control_filter=$_GET['on_control_filter'];}
//----------------------------------------------------------
if (isset($_GET['type']) & $_GET['type']=='del' & $write_mode===true)
	{
	//echo '!!!!!!!del!!!!!!';
	$query='delete from protocols where id="'.$_GET['item_id'].'"';	
	//echo $query;
	$res=mysql_query($query);
	
	$query='delete from protocol_visit where protocol_id="'.$_GET['item_id'].'"';
	//echo $query;
	$res=mysql_query($query);
	
	$query='delete from protocol_details where protocol_id="'.$_GET['item_id'].'"';
	//echo $query;
	$res=mysql_query($query);
	
	header('Location:'.$main_page.'?page='.$page);
		
	}

    // <abarmin date="23.09.2012">
    // ошибка undefined index save
    if (array_key_exists("save", $_GET)) {
        if ($_GET['save']==1)
        {
            header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0');
            header('Pragma: no-cache');
            header('Content-Type: application/msword; charset=windows-1251; format=attachment;');
            header('Content-Disposition: attachment; filename=items.doc');
            //table_print($result,'select',$tablename);return;
        }
    }
    // </abarmin>

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
<script type="text/javascript" src="scripts/protocols.js"></script>


<script language="JavaScript">
var main_page='protocols_view.php';	//for redirect & links
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
var err=false;
var msg='Не заполнены поля информации о протоколе: ';

if (document.item_form.num.value=='') {err=true;msg=msg+' номер;';}
if (document.item_form.date_text.value=='') {err=true;msg=msg+' дата;';}
if (document.item_form.program_content.value=='') {err=true;msg=msg+' повестка дня;';}
//if (document.item_form.table_kadri_on_item_0.value==0){err=true;msg=msg+' присутствовали;';}
//num_item
if (err==true) {alert(msg);} else {document.item_form.submit();}
 
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

if ($write_mode!==true && isset($_GET['type']) && ($_GET['type']=='edit' || $_GET['type']=='del'))
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
		 $query="update protocols set num='".f_ri($_POST["num"])."',date_text='".f_ri($date_text_modif)."',
		 	program_content='".f_ri($_POST["program_content"])."',
		 	comment='".f_ri($_POST["comment"])."' where id='".f_ri($_GET["item_id"])."'";

		 if ($res=mysql_query($query)) {
		  
					$protocol_id=0;$protocol_id=$_GET['item_id'];
					/*$query="select max(id)as max_id from protocols";
					$res=mysql_query($query);$a=mysql_fetch_array($res);
					if ($a['max_id']>0) {$protocol_id=$a['max_id'];}*/

//------------------------------вставка присутствующих\отсутсвующих------------------------------------------------------------------
			$err_visit_on=false;$err_visit_off=false;$err_details=false;

					$query_visit="delete from protocol_visit where protocol_id='".$protocol_id."'";
					if ($write_mode!==true || !mysql_query($query_visit)) {$err_visit_on=true;
						echo '<div class=warning>ошибки удаления старого состава сотрудников протокола. Выполнение прервано.</div>';}

					$query_visit="delete from protocol_details where protocol_id='".$protocol_id."'";
					if ($write_mode!=true || !mysql_query($query_visit)) {$err_details=true;
						echo '<div class=warning>ошибки удаления старого состава сотрудников протокола. Выполнение прервано.</div>';}

			 if ($protocol_id!=0 && $err_visit_on==false && $err_details==false) {//
				
				$err_visit_on=false;$err_visit_off=false;$err_opinions=false;
				$err=false;
//---------------------------------------------//-----------------------------//-----------------------//----------------
				
				while (list($key, $val) = each($_POST)) {
				    
				    if (substr($key,0,11)=='radio_item_' ) //фильтруем тока радио-кнопки
					{//echo "<hr>$key => !$val!\n";
					$kadri_id=substr($key,11);
					
					if ($val=='1') {//кто не прогулял
						$query_visit="insert into protocol_visit(protocol_id,kadri_id,visit_type) 
							values('".$protocol_id."','".$kadri_id."','1')";
							//echo "query_visit_on=$query_visit";
					if (!mysql_query($query_visit)) {$err_visit_on=true;$err=true;}
					}
					if ($val=='0') {//добавляем кто прогулял с причиной прогула
						$query_visit="insert into protocol_visit(protocol_id,kadri_id,visit_type,matter_text) 
							values('".$protocol_id."','".$kadri_id."','0','".$_POST['table_kadri_off_matter_item_'.$kadri_id]."')";
						//echo "\nquery_visit_off=$query_visit<hr>";
					if (!mysql_query($query_visit)) {$err_visit_on=true;$err=true;}
					}
								
					
					}
				}
				
				for ($i=1;$i<=$_POST['max_protocols_details']+1;$i++) 
					{
					if ($_POST['kadri_id_'.$i]!=0) {
					$_POST['section_id_'.$i]=str_replace("_","",$_POST['section_id_'.$i]);	//убираем все нечисловые символы
					
					if ($_POST['on_control_'.$i]=='on') {$_POST['on_control_'.$i]=1;}
					else {$_POST['on_control_'.$i]=0;}
					
					$query_details="insert into protocol_details(protocol_id,section_id,kadri_id,text_content,opinion_id,
							on_control,opinion_text) 
						values('".$protocol_id."','".$_POST['section_id_'.$i]."',
						'".$_POST['kadri_id_'.$i]."','".$_POST['text_content_'.$i]."','".$_POST['opinion_id_'.$i]."',
						'".$_POST['on_control_'.$i]."','".$_POST['opinion_text_'.$i]."')";
					if (!mysql_query($query_details)) {$err_opinions=true;$err=true;}
					//echo '<hr>i='.$i.'<br> query_details='.$query_details.'!!!<hr>';
					}
					}
				  if ($err_visit_on==true) {echo '<div class=warning>ошибки вставки присутствующих</div>';}
				  if ($err_visit_off==true) {echo '<div class=warning>ошибки вставки отсутствующих</div>';}
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
		 

		 $query="insert into protocols(num,date_text,program_content,comment) 
		 	values('".f_ri($_POST["num"])."','".f_ri($date_text_modif)."','".f_ri($_POST["program_content"])."','".f_ri($_POST["content"])."')";
		 //echo $query;
		 $res_news=true;
		 if ($res_news & $res=mysql_query($query)) 
		 	{
					$protocol_id=0;
					$query="select max(id)as max_id from protocols";
					$res=mysql_query($query);$a=mysql_fetch_array($res);
					if ($a['max_id']>0) {$protocol_id=$a['max_id'];}

//------------------------------вставка присутствующих\отсутсвующих------------------------------------------------------------------
			 if ($protocol_id!=0) {//
				$err=false;
				$err_visit_on=false;$err_visit_off=false;$err_opinions=false;
//---------------------------------------------//-----------------------------//-----------------------//----------------
				
				while (list($key, $val) = each($_POST)) {
				    
				    if (substr($key,0,11)=='radio_item_' ) //фильтруем тока радио-кнопки
					{//echo "<hr>$key => !$val!\n";
					$kadri_id=substr($key,11);
					
					if ($val=='1') {//кто не прогулял
						$query_visit="insert into protocol_visit(protocol_id,kadri_id,visit_type) 
							values('".$protocol_id."','".$kadri_id."','1')";
							//echo "query_visit_on=$query_visit";
					if (!mysql_query($query_visit)) {$err_visit_on=true;}
					}
					if ($val=='0') {//добавляем кто прогулял с причиной прогула
						$query_visit="insert into protocol_visit(protocol_id,kadri_id,visit_type,matter_text) 
							values('".$protocol_id."','".$kadri_id."','0','".$_POST['table_kadri_off_matter_item_'.$kadri_id]."')";
						//echo "\nquery_visit_off=$query_visit<hr>";
					if (!mysql_query($query_visit)) {$err_visit_on=true;$err=true;}
					}
								
					
					}
				}

				/*for ($i=0;$i<count($_POST);$i++) //['max_table_kadri_on']
					{
					if (substr($_POST[$i])
					if ($_POST['radio_item_'.$i]==1) {
					$query_visit="insert into protocol_visit(protocol_id,kadri_id,visit_type) 
						values('".$protocol_id."','".$_POST['table_kadri_on_item_'.$i]."','1')";
					if (!mysql_query($query_visit)) {$err_visit_on=true;}}
					
					if ($_POST['table_kadri_off_item_'.$i]==0) {
					$query_visit="insert into protocol_visit(protocol_id,kadri_id,visit_type,matter_text) 
						values('".$protocol_id."','".$_POST['table_kadri_off_item_'.$i]."','0','".$_POST['table_kadri_off_matter_item_'.$i]."')";
					if (!mysql_query($query_visit)) {$err_visit_off=true;}}
					
					}
				
				for ($i=0;$i<$_POST['max_table_kadri_off'];$i++) 
					{
					if ($_POST['table_kadri_off_item_'.$i]!=0) {
					$query_visit="insert into protocol_visit(protocol_id,kadri_id,visit_type,matter_text) 
						values('".$protocol_id."','".$_POST['table_kadri_off_item_'.$i]."','0','".$_POST['table_kadri_off_matter_item_'.$i]."')";
					if (!mysql_query($query_visit)) {$err_visit_off=true;}}
					}*/
//---------------------------------------------//-----------------------------//-----------------------//----------------				
				
				for ($i=1;$i<=$_POST['max_protocols_details']+1;$i++) 
					{
					if ($_POST['kadri_id_'.$i]!=0) {
					$_POST['section_id_'.$i]=str_replace("_","",$_POST['section_id_'.$i]);	//убираем все нечисловые символы
					
					if ($_POST['on_control_'.$i]=='on') {$_POST['on_control_'.$i]=1;}
					else {$_POST['on_control_'.$i]=0;}

					$query_details="insert into protocol_details(protocol_id,section_id,kadri_id,text_content,opinion_id,
						on_control,opinion_text) 
						values('".$protocol_id."','".$_POST['section_id_'.$i]."',
						'".$_POST['kadri_id_'.$i]."','".$_POST['text_content_'.$i]."','".$_POST['opinion_id_'.$i]."',
						'".$_POST['on_control_'.$i]."','".$_POST['opinion_text_'.$i]."')";
					if (!mysql_query($query_details)) {$err_opinions=true;$err=true;}
					//echo '<hr>i='.$i.'<br> query_details='.$query_details.'!!!<hr>';
					}
					}
				
				  if ($err_visit_on==true) {echo '<div class=warning>ошибки вставки присутствующих</div>';}
				  if ($err_visit_off==true) {echo '<div class=warning>ошибки вставки отсутствующих</div>';}
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
		
		echo '<h4 class="notinfo"> Данные о протоколе для печати и экспорта.</h4>';
			if ($write_mode===true)
			{ echo '<p><a href="'.$main_page.'?item_id='.$_GET['item_id'].'&type=edit">Правка </a>  &nbsp;'; }
			echo '<a href="'.$main_page.'">Просмотр списка протоколов </a>';
		echo " <div style='text-align:right;'>
			 	<a class=text href='?".$_SERVER["QUERY_STRING"]."&save&attach=doc' title='Выгрузить'>Передать в MS Word</a>&nbsp;&nbsp;&nbsp;
				<a class=text target='_blank' href='?".$_SERVER["QUERY_STRING"]."&print' title='Распечатать'>Печать</a></div>"; }

	$query="select * from protocols where id='".$_GET['item_id']."'";
	$res=mysql_query($query);
	$res_view=mysql_fetch_array($res);
	if (mysql_num_rows($res)==1) { //если есть протокол такой
	 	//$res_view['date_text']=substr($res_view['date_text'],6,2).'.'.substr($res_view['date_text'],4,2).'.'.substr($res_view['date_text'],0,4);
		 echo '<div align=center>ПРОТОКОЛ № <b>'.$res_view['num'].'</b><br>заседания кафедры АСУ</div>
		 <div align=right>от <b>'.DateTimeCustomConvert($res_view['date_text'],'d','mysql2rus').'</b> г.</div>';

//----------------------------------------------------------------------------------------
$row_id_on=0; $row_id_off=0;$str_visit_on='';$str_visit_off='';
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
 //$row_id_on=0; $row_id_off=0;
 //$str_visit_on='';$str_visit_off='';
//----------------------------------------------------------------------------------------
		echo '<div align=center><b>ПОВЕСТКА ДНЯ</b></div>';
		$res_view['program_content']=str_replace("\r\n","<br>",f_ro($res_view['program_content']));
		echo '<div align=left>'.$res_view['program_content'].'</div><p>&nbsp;</p>';

$query="select protocol_details.protocol_id,protocol_details.section_id,kadri.fio_short,
			protocol_details.text_content,protocol_opinions.name as opinion_name,on_control,opinion_text   
		from protocol_details 
			left join kadri on kadri.id=protocol_details.kadri_id 
			left join protocol_opinions on protocol_opinions.id=protocol_details.opinion_id
		where protocol_details.protocol_id='".$_GET['item_id']."' order by protocol_details.section_id";
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

    // <abarmin date="23.09.2012">
    // ошибки undefined index
    if (array_key_exists("type", $_GET)){
        if ($_GET['type']=='edit') {
            if (isset($_GET['item_id']) & $_GET['item_id']!="") {
                $query="select * from protocols where id='".$_GET['item_id']."'";
                $res=mysql_query($query);
                $res_edit=mysql_fetch_array($res);
            } else {
                echo '<h4 class="err">не выбраны данные о протоколе для правки</h4>';
            }
        }
    }
    // </abarmin>

    //добавление данных о протоколе
    // <abarmin date="23.09.2012">
    // ошибка Undefined index: type
if (array_key_exists("type", $_GET)) {
    if ($_GET['type']=='add' || $_GET['type']=='edit') {
        if ($_GET['type']=='edit') {echo '<h4>Правка данных о протоколе</h4>';
	        echo '<p><a href="?item_id='.$_GET['item_id'].'&type=view">Печать</a>&nbsp;&nbsp;&nbsp;
	        <!--a href="?item_id='.$_GET['item_id'].'&type=copy">Копировать</a>&nbsp;&nbsp;&nbsp;-->';
        } else {
            echo '<h4>Ввод новых данных о протоколе</h4>';
        }
?>
 &nbsp;  &nbsp; <a href="<?php echo $main_page;?>">Просмотр списка протоколов </a><p>
<form name="item_form" method="post" action=""><table> 
<tr><td>номер * </td><td><input type=text size=20 name=num value="<?php echo getFormItemValue('num'); ?>"></tr>
<tr><td>дата *</td><td><input type=text size=20 name=date_text 
value="<?php if (isset($res_edit)) {
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
<tr><td>повестка дня *</td><td><textarea name=program_content rows=10 cols=60><?php echo getFormItemValue('program_content'); ?></textarea></td></tr>
<tr><td>комментарий </td><td><textarea name=comment rows=4 cols=60><?php echo getFormItemValue('comment'); ?></textarea></td></tr>
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
	<table border=0 width="100%">
		<!--tr width="310"><td>Присутствовали</td><td width="*">Отсутствовали</td></tr-->
		<tr><td>Посещение:
            <?php
                // <abarmin date="23.09.2012">
                // ошибка undefined index type
                if (array_key_exists("type", $_GET)) {
                    if ($_GET["type"] == "add") {
                        echo '<div class=text>
                        <ul>
                         <li><font color="red"><b>красным</b></font> выделены обязательные к присутствию преподаватели (сумма ставок, по которым срок договора еще не прошел >=1 ) </li>
                         <li>сотрудник заносится в группу "пропущенных по умолчанию сотрудников", если он не входит в группу обязательных к присутствию</li>
                         <li>для проверки размера <strong>общей</strong> (суммарной) ставки по <strong>работающим</strong>(не истекщим) договорам, Вы можете обратиться к задаче <a href="kadri_time_table.php">Табель сотрудников </a></li>
                         <li>если Вы <b>хотите всех пропустить</b>, не указывая в протоколе, кликните по заголовку ниже <b>"пропустить"</b></li>
                         <li>если Вы хотите вернуть выборку <b>по умолчанию</b>, после изменения, нажмите <b>"Очистить"</b> </li>
                         </ul>
                         </div>';
                    }
    			}
                // </abarmin>
            ?>
			</td><td></td></tr>
		<tr><td valign="top" align="left">

<?php 	//кто был на заседании
 $row_id_on=0; $row_id_off=0;
//-----------------------------------
        // <abarmin date="23.09.2012">
        // ошибка unidefined index item_id
        $query="select protocol_visit.protocol_id,protocol_visit.kadri_id from protocol_visit left join kadri on kadri.id=protocol_visit.kadri_id
		  where protocol_visit.protocol_id='";
        if (array_key_exists("item_id", $_GET)) {
            $query .= $_GET["item_id"];
        }
        $query .= "' and visit_type=1 order by kadri.fio_short";
        // </abarmin>
		$res_=mysql_query($query);
		$kadri_str_on='';
		while ($array_on=mysql_fetch_array($res_))
		{$kadri_str_on=$kadri_str_on.'!'.$array_on['kadri_id'].'?';$row_id_on++;}//получаем список kadri_id, кто был
		//echo ' kadri_str_on='.$kadri_str_on;

        // <abarmin date="23.09.2012">
        // ошибка undefined index item_id
        $query="select protocol_visit.protocol_id,protocol_visit.kadri_id,protocol_visit.matter_text
		 	from protocol_visit left join kadri on kadri.id=protocol_visit.kadri_id
		  	where protocol_visit.protocol_id='";
        if (array_key_exists("item_id", $_GET)) {
            $query .= $_GET["item_id"];
        }
        $query .= "' and visit_type=0 order by kadri.fio_short";
        // </abarmin>
		$res_=mysql_query($query);
		$kadri_str_off='';
		while ($array_off=mysql_fetch_array($res_))
		{$kadri_str_off=$kadri_str_off.'!'.$array_off['kadri_id'].'?';$row_id_off++;}//получаем список kadri_id, кого не было
//-----------------------------------
		
		//формирование списка сотрудников для отметки присутствия
        /*
         * <abarmin date="23.09.2012">
         * Добавил выбиратор типов сотрудников
         */
        $personTypeQuery = "
            select
                id, concat(upper(mid(name_short,1,1)), mid(name_short,2)) as name_short
            from
                person_types
            order by
                name_short asc
        ";
        $personTypes = array();
        $personTypesSelected = array();
        // выбранные по умолчанию типы (в массив включать id)
        $personTypesSelected = array(
            1 => 1,
            6 => 6
        );
        $personTypesRes = mysql_query($personTypeQuery) or die(mysql_error());
        while ($type = mysql_fetch_assoc($personTypesRes)) {
            $personTypes[$type["id"]] = $type["name_short"];
        }
        echo '
        <table border="0" class="text" width="700" id="personTable">
 		    <tr class="header">
 		        <td width=40>№</td><td width=300>ФИО
		        <td width=50><a href="#none" title="отметить все" onclick=javascript:mark_all_radio("none");>пропустить</a></td>
		        <td width=50><a href="#view" title="отметить все" onclick=javascript:mark_all_radio("1");>был</a></td>
		        <td width=50><a href="#not" title="отметить все" onclick=javascript:mark_all_radio("0");>не был</a></td>
		        <td width=200>причина отсутствия</td>
		        <td>
		            <div id="personTypesButton"><img src="/'.$root_folder.'images/tango/16x16/actions/go-down.png" style="cursor:pointer;" onclick="protocols_showPersonTypeSelector(); return false; "/></div>
		            <div id="personTypeSelector" style="display:none; position:absolute; border:1px solid blue; width:200px; margin-left:-200px; background:#ffffff; padding:3px;">';
                foreach ($personTypes as $key=>$value) {
                    echo '<p><input type="checkbox" name="personTypesSelected[]" value="'.$key.'" ';
                    if (array_key_exists($key, $personTypesSelected)) {
                        echo ' checked';
                    }
                    echo ' onclick="protocols_updatePersonListByType(); " />'.$value.'</p>';
                }
        echo '
		            </div>
		        </td>
		    </tr>';

    /*
     * 1. Добавляем выбиратор типов присутствующих на заседании сотрудников
     * 2. В нем по умолчанию должны быть выбраны:
     *      - ппс
     *      - аспиратны
     *      - еще кто-то там, у кого срок действия приказа не закончился
     */

    $query='
        SELECT k.id AS kadri_id, k.fio_short, k.fio
	    FROM kadri k
		inner join
		    kadri_in_ptypes as p on
		        p.kadri_id = k.id
	    where k.id in (
			SELECT od.kadri_id
			FROM `orders` od
			WHERE cast(concat(substring(od.date_end,7,4),".",substring(od.date_end,4,2),".",substring(od.date_end,1,2)) as datetime)>=now()
			group by od.kadri_id
			having sum(od.rate)>=1
		) and p.person_type_id in ('.implode(", ",$personTypesSelected).')
	    ORDER BY k.fio_short';
    // </abarmin>
$res_=mysql_query($query) or die(mysql_error());
$i=0;
 while ($z=mysql_fetch_array($res_))	//вывод по номерам пар (number)
 	{
	 $checked_on='';$checked_off='';$checked_none='';$matter_text='';$display_matter='none';
	 //echo '<br>kadri_id= !'.$z['kadri_id'].'?';
	 if (strstr($kadri_str_on,'!'.$z['kadri_id'].'?')) {$checked_on='checked';}
	 else {
		  if (strstr($kadri_str_off,'!'.$z['kadri_id'].'?')) {
		    $checked_off='checked';$display_matter='';
		 	$query="select matter_text from protocol_visit 
			  	where protocol_id='".$_GET['item_id']."' and visit_type=0 and kadri_id='".$z['kadri_id']."'";
			//echo ' query='.$query;
			$res=mysql_query($query);
			$array_off=mysql_fetch_array($res);
			$matter_text=$array_off['matter_text'];
			}
		  else {$checked_none='checked';}  
		  }
	 $kadri_color='grey';

	 //проверка преподавателя с учетом присутствия (при правке протокола)
	    if ($checked_on=='checked') {
            $kadri_color='green';
        }else {
		     $kadri_color='red';
		     $checked_off='checked';$display_matter='';$row_id_off++;
	    }
	 
	    echo '<tr height=30><td>'.($i+1).'</td><td><div id="person_'.$z['kadri_id'].'" name="person_'.$z['kadri_id'].'" style="color:'.$kadri_color.';"><b>'.$z['fio'].'</b></div></td>
	 		<td>
			<input type=radio name="radio_item_'.$z['kadri_id'].'" value="none" '.$checked_none.' title="пропустить" 
			 	onClick=javascript:hide_show_matter("'.$z['kadri_id'].'","hide",this.value)></td><td>
			<input type=radio name="radio_item_'.$z['kadri_id'].'" value="1" title="был" '.$checked_on.' 
				onClick=javascript:hide_show_matter("'.$z['kadri_id'].'","hide",this.value)></td><td>
	 		<input type=radio name="radio_item_'.$z['kadri_id'].'" value="0" title="не был" '.$checked_off.' 
			 	onClick=javascript:hide_show_matter("'.$z['kadri_id'].'","view",this.value)></td><td>
			<input type=text id="table_kadri_off_matter_item_'.$z['kadri_id'].'" name="table_kadri_off_matter_item_'.$z['kadri_id'].'" style="display:'.$display_matter.'; width:190;" 
				value="'.$matter_text.'"></td>
		    <td>&nbsp;</td>
		    </tr>';	$i++;
    }
echo 	'</table>';
//-------------------------------------
$query='SELECT distinct k.id AS kadri_id, k.fio_short, k.fio
	    FROM kadri k
	    where k.id not in (
			SELECT od.kadri_id
			FROM `orders` od
			WHERE cast(concat(substring(od.date_end,7,4),".",substring(od.date_end,4,2),".",substring(od.date_end,1,2)) as datetime)>=now()			
			group by od.kadri_id
			having sum(od.rate)>=1
		  )
	    ORDER BY k.fio_short';
$res_=mysql_query($query);
$i=0;

    //<abarmin date="23.09.2012">
    // поправил форматирование
    echo '
        <div class=button style="text-align:center;">
            <a  href="#show_slave" onclick="javascript:hide_show(\'kadri_slave\');"> отразить\скрыть пропущенных по умолчанию сотрудников (<div id="uncompulsoryPersonsCount" style="display:inline;">'.mysql_num_rows($res_).'</div>) </a>
        </div>
        <div id=kadri_slave name=kadri_slave style="display:none;">';
    echo '<table border=0 class=text width=700 id="tablePersonUncompulsory">';
    // </abarmin>
    while ($z=mysql_fetch_array($res_))	//вывод по номерам пар (number)
 	{
	 $checked_on='';$checked_off='';$checked_none='';$matter_text='';$display_matter='none';
	 //echo '<br>kadri_id= !'.$z['kadri_id'].'?';
	 if (strstr($kadri_str_on,'!'.$z['kadri_id'].'?')) {$checked_on='checked';}
	 else {
		  if (strstr($kadri_str_off,'!'.$z['kadri_id'].'?')) {
		    $checked_off='checked';$display_matter='';
		 	$query="select matter_text from protocol_visit 
			  	where protocol_id='".$_GET['item_id']."' and visit_type=0 and kadri_id='".$z['kadri_id']."'";
			//echo ' query='.$query;
			$res=mysql_query($query);
			$array_off=mysql_fetch_array($res);
			$matter_text=$array_off['matter_text'];
			}
		  else {$checked_none='checked';}  
		  }
	 $kadri_color='grey';
	    $kadri_color='#767676'; 
	 
	 echo '<tr height=30><td>'.($i+1).'</td><td><div id="person_'.$z['kadri_id'].'" name="person_'.$z['kadri_id'].'" style="color:'.$kadri_color.';"><b>'.$z['fio'].'</b></div></td>
	 		<td>
			<input type=radio name="radio_item_'.$z['kadri_id'].'" value="none" '.$checked_none.' title="пропустить" 
			 	onClick=javascript:hide_show_matter("'.$z['kadri_id'].'","hide",this.value)></td><td>
			<input type=radio name="radio_item_'.$z['kadri_id'].'" value="1" title="был" '.$checked_on.' 
				onClick=javascript:hide_show_matter("'.$z['kadri_id'].'","hide",this.value)></td><td>
	 		<input type=radio name="radio_item_'.$z['kadri_id'].'" value="0" title="не был" '.$checked_off.' 
			 	onClick=javascript:hide_show_matter("'.$z['kadri_id'].'","view",this.value)></td><td>
			<input type=text id="table_kadri_off_matter_item_'.$z['kadri_id'].'" name="table_kadri_off_matter_item_'.$z['kadri_id'].'" style="display:'.$display_matter.'; width:190;" 
				value="'.$matter_text.'"></td></tr>';	$i++;
	}
 	echo'<tr><td width=40>№</td><td width=200>ФИО
		 <td width=50><a href="#none" title="отметить все" onclick=javascript:mark_all_radio("none");>пропустить</a></td>
		 <td width=50><a href="#view" title="отметить все" onclick=javascript:mark_all_radio("1");>был</a></td>
		 <td width=50><a href="#not" title="отметить все" onclick=javascript:mark_all_radio("0");>не был</a></td>
		 <td width=200>причина отсутствия</td></tr>';
	echo '</table>';
	echo '</div>';
//-------------------------------------
	
	
?>
<table border=0 class=text width=700>
      <tr><td colspan=2 width=90></td><td></td>
<td width=50>
	<input type="text" id="max_table_kadri_on" name="max_table_kadri_on" value="<?php echo $row_id_on;?>"  size=3 width=3 title="число присутствующих сотрудников" disabled>
</td><td width=50>
		<input type="text" id="max_table_kadri_off" name="max_table_kadri_off" value="<?php echo $row_id_off;?>" size=3 width=3 title="число непришедших сотрудников" disabled>
</td><td width=200></td></tr>
<?php
	echo '</table>';
?>
	</td><td valign="top" align="left">

<?php	//кто прогулял заседание
        // <abarmin date="23.09.2012">
        // ошибка undefined index item_id
	 	$query="select matter_text,protocol_id,kadri_id from protocol_visit where protocol_id='";
        if (array_key_exists("item_id", $_GET)) {
            $query .= $_GET["item_id"];
        }
        $query .= "' and visit_type=0";
        // </abarmin>
		//echo $query;
		$res_=mysql_query($query);
 /*while ($z=mysql_fetch_array($res_))	//вывод по номерам пар (number)
 	{	persons_select_chart('table_kadri_off_item_'.$row_id_off,'kadri',$z['kadri_id'],'300');
	 	echo '<input type="text" name="table_kadri_off_matter_item_'.$row_id_off.'" size="20" value="'.$z['matter_text'].'"> причина отсутствия';$row_id_off++;	}*/
?>
	</td></tr>
		
	</table>
</td></tr>

<tr><td colspan=2>
<hr style="color:#ccccff; background-color:#ccccff; height:5; size:20;">
<a name=program></a>
<?php 
$row_id_detail=1;

    // <abarmin date="23.09.2012">
    // ошибка undefined index item_id
    $query="select id,protocol_id,section_id,kadri_id,text_content,opinion_id,on_control,opinion_text from protocol_details
	where protocol_id='";
    if (array_key_exists("item_id", $_GET)) {
        $query .= $_GET["item_id"];
    }
    $query .= "' order by section_id";
    // </abarmin>
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
		   
		   <?php
		   $checked='';
		   if ($z['on_control']==1) {$checked=' checked';}
		   
		   ?>
		   <label title="позволяет отслеживать выполнение в общем списке протоколов"><input type="checkbox" name="on_control_<?php echo $row_id_detail;?>" <?php echo $checked;$checked='';?> > <span class=warning>на контроле</span> </label> &nbsp;  &nbsp;
		   <input type="file" name="file_name_<?php echo $row_id_detail;?>" disabled> прикрепленный файл 

<br>
      <a href="#top">наверх</a> &nbsp;
      <a href="?">просмотр списка протоколов</a> &nbsp; &nbsp; &nbsp;
      <?php
//---------------------------- выписки по путевкам начало      
      if (getScalarVal('select count(*) from protocol_trips where protocol_id='.intval($_GET['item_id']).' and section_id='.$z['section_id'].' ')==0) {?>
            <a href="?item_id=<?php echo $_GET['item_id'];?>&s_id=<?php echo $z['section_id'];?>&type=addtrip" title="о распределении путевок в Авиатор">сформировать выписку по путевкам</a>
      <?php } else {  ?>
	    <a onclick="javascript:del_confirm_act('выписку к протоколу.\n Все привязанные сотрудники будут удалены из путевок','?item_id=<?php echo $_GET['item_id'];?>&s_id=<?php echo $z['section_id'];?>&type=deltrip');" href="#" title="о распределении путевок в Авиатор">удалить выписку по путевкам
	    <?php
	    $kadri_cnt=intval(getScalarVal('select count(*) from protocol_trip_details where trip_id in
					   (select id from protocol_trips where protocol_id ='.intval($_GET['item_id']).' and section_id='.$z['section_id'].')'),0);
	    if ($kadri_cnt>0) echo ' (найдено сотрудников: '.$kadri_cnt.')';
	    ?></a>
      <?php }
//---------------------------- выписки по путевкам окончание      

//---------------------------- реком.в аспирантуру начало      
      if (getScalarVal('select count(*) from protocol_2aspir where protocol_id='.intval($_GET['item_id']).' and section_id='.$z['section_id'].' ')==0) {?>
            <a href="?item_id=<?php echo $_GET['item_id'];?>&s_id=<?php echo $z['section_id'];?>&type=addaspir" title="о рекомендации студентов в аспирантуру">сформировать выписку с рекомендацией</a>
      <?php } else {  ?>
	    <a onclick="javascript:del_confirm_act('выписку с рекомендацией в аспирантуру.\n Протокол исчезнет в форме дипломного проекта','?item_id=<?php echo $_GET['item_id'];?>&s_id=<?php echo $z['section_id'];?>&type=delaspir');" href="#" title="о рекомендации студентов в аспирантуру">удалить выписку с рекомендацией
	    <?php
	    $items_cnt=intval(getScalarVal('select count(*) from diploms where protocol_2aspir_id in
					   (select id from protocol_2aspir where protocol_id ='.intval($_GET['item_id']).' and section_id='.$z['section_id'].')'),0);
	    if ($items_cnt>0) echo ' (найдено дипломных проектов: '.$items_cnt.')';
	    ?></a>
      <?php }
//---------------------------- реком.в аспирантуру окончание      
      ?>
      
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
		   <label title="позволяет отслеживать выполнение в общем списке протоколов"><input type="checkbox" name="on_control_<?php echo $row_id_detail;?>"> <span class=warning>на контроле</span> </label> &nbsp;  &nbsp;
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
	if (isset($res_edit)) {
        // <abarmin date="23.09.2012">
        // ошибка undefined index item_id
        echo '<input type=button value="Печать" name=view_btn onclick=javascript:document.location.href="?item_id=';
        if (array_key_exists("item_id", $_GET)) {
            echo $_GET["item_id"];
        }
        echo '&type=view";>';
        // </abarmin>
    }
?>
 </td></tr>

</table>
<input type="hidden" id=max_protocols_details name="max_protocols_details" value="<?php echo $row_id_detail;?>">
</form>

<?php
    }
}
    // </abarmin>
//else
//---------------------------------------------------создание выписки для путевки в Авиатор
if (isset($_GET['item_id']) &&  isset($_GET['type']) && intval($_GET['item_id'])>0)
{
      //------------------------------- выписки по путевкам начало 
      if (isset($_GET['s_id']) && $_GET['type']=='addtrip' && intval($_GET['s_id'])>0)
      {
      $query_add_trip='insert into protocol_trips(`date_act` , `protocol_id` , `section_id`)
	    values(\''.date("Y-m-d").'\','.intval($_GET['item_id']).','.intval($_GET['s_id']).')';
      if (mysql_query($query_add_trip) && mysql_affected_rows()>0) {
	    echo '<div class=success>выписка успешно добавлена</div>';
	    echo '<a href="protocol_trip.php?trip_id='.getScalarVal('select max(id) from protocol_trips').'">перейти к выписке</a>';
	    }
	    else echo '<div class=warning>выписка не добавлена</div>';
      }
       if ($_GET['type']=='deltrip' && intval($_GET['s_id'])>0)
      {
      
      $query_del_trip1='delete from protocol_trip_details where trip_id in
	    (select id from protocol_trips where  `protocol_id`='.intval($_GET['item_id']).' and section_id='.intval($_GET['s_id']).');';
      $query_del_trip2='delete from protocol_trips where `protocol_id`='.intval($_GET['item_id']).' and section_id='.intval($_GET['s_id']).';';
      //echo $query_del_trip;
      if (mysql_query($query_del_trip1) && mysql_query($query_del_trip2) && mysql_affected_rows()>0) {
	    echo '<div class=success>выписка успешно удалена</div>';
	    echo '<a href="protocols_view.php?item_id='.intval($_GET['item_id']).'&type=edit#program">перейти к последнему протоколу</a>';
	    }
      else echo '<div class=warning>выписка не удалена</div>';
	    
      }
      //------------------------------- выписки по путевкам окончание 
      
      //------------------------------- реком.в аспирантуру начало
      if (isset($_GET['s_id']) && $_GET['type']=='addaspir' && intval($_GET['s_id'])>0)
      {
      $query_add='insert into protocol_2aspir(`date_act` , `protocol_id` , `section_id`)
	    values(\''.date("Y-m-d").'\','.intval($_GET['item_id']).','.intval($_GET['s_id']).')';
      if (mysql_query($query_add) && mysql_affected_rows()>0) {
	    echo '<div class=success>выписка успешно добавлена</div>';
	    echo '<a href="diploms_view.php">перейти к допломным проектам</a>';
	    }
	    else echo '<div class=warning>выписка не добавлена</div>';
      }
       if ($_GET['type']=='delaspir' && intval($_GET['s_id'])>0)
      {
     $query_del1='update diploms set protocol_2aspir_id=null where protocol_2aspir_id in
	    (select id from protocol_2aspir where  `protocol_id`='.intval($_GET['item_id']).' and section_id='.intval($_GET['s_id']).');';      

      $query_del2='delete from protocol_2aspir where `protocol_id`='.intval($_GET['item_id']).' and section_id='.intval($_GET['s_id']).';';
      //echo $query_del_trip;
      if (mysql_query($query_del1) && mysql_query($query_del2) && mysql_affected_rows()>0) {
	    echo '<div class=success>выписка успешно удалена</div>';
	    echo '<a href="protocols_view.php?item_id='.intval($_GET['item_id']).'&type=edit#program">перейти к последнему протоколу</a>';
	    }
      else echo '<div class=warning>выписка не удалена</div>';
	    
      }      //------------------------------- реком.в аспирантуру окончание       
      //item_id=336&s_id=1&type=addtrip
     echo '<p><a href="?">к списку протоколов</a></p>'; 
}//
//---------------------------------------------------
if (!isset($_GET['item_id']) && !isset($_GET['type']))
	{
	echo '<h4 class="notinfo"> Данные о протоколах.</h4>';

if (!isset($_GET['save']) && !isset($_GET['print'])) {
	if ($write_mode===true) {echo '<p class="notinfo"><a href="?type=add"> Добавить</a><p>';}
	echo '<table width=99% class="notinfo"><tr>';
	echo '
	<td width="*" align=right> 
	<input type=button value="Печать" onclick=javascript:window.location.href="?print=1&'.$_SERVER["QUERY_STRING"].'";>&nbsp;&nbsp;
	<input type=button value="в Word" onclick=javascript:window.location.href="?save&attach=doc&'.$_SERVER["QUERY_STRING"].'">&nbsp;&nbsp;';
?>	
	<input type=text name="q" id="search_query" width=50 value=""> &nbsp; <input type=button value="Найти" OnClick=javascript:go2search("");>
	</td>
	</tr></table>

<?php	}


if ($q!='') {echo '<div>Поиск: <b><u>'.$q.'</u></b></div><br>';
//$q4date='';
$search_query=' and (protocols.program_content like "%'.$q.'%" or 
					protocols.comment like "%'.$q.'%" or 
					protocols.date_text like "%'.DateTimeCustomConvert($q,'d','rus2mysql').'%")';}
if ($on_control_filter=='on') 
	{$on_control_query=' and protocols.id in(select distinct protocol_id from protocol_details where on_control=1)';}

if ($sort<1 && $sort>7) {$sort=2;}

    // <abarmin date="23.09.2012">
    // ошибка undefined variable
    $query="SELECT num,date_text, program_content,  0,
	    (SELECT count(*) FROM protocol_trip_details ptd
	     INNER JOIN protocol_trips pt ON pt.id = ptd.trip_id
	     where pt.protocol_id=protocols.id) as trip_cnt,
		(SELECT count(*) FROM protocol_2aspir pa
	     INNER JOIN diploms d ON pa.id = d.protocol_2aspir_id
	     where pa.protocol_id=protocols.id) as asp_cnt,		 
       comment,id
      FROM protocols where 1 ";
    if (isset($search_query)) {
        $query .= $search_query;
    }
    if (isset($on_control_query)) {
        $query .= $on_control_query;
    }
    $query .= " order by ".$sort." ".$stype." ";
    // </abarmin>

$res=mysql_query($query.'limit '.(($page-1)*$pgVals).','.$pgVals);

//echo $query;

?>
<!--//---------------------------------начало списочная форма ------------------------------------------------- -->
<form name=item_list action="" method="get">
<?php if (!isset($_GET['save']) && !isset($_GET['print'])) { 

$itemCnt=getScalarVal('select count(*) from ('.$query.')t');

if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {$pages_cnt=floor($itemCnt/$pgVals);}
else {$pages_cnt=($itemCnt/$pgVals)+1;}

$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';


?>
<label><input type=checkbox name="on_control_filter" 
	<?php if ($on_control_filter=='on') {echo 'checked';} ?> > отражать протоколы только на контроле </label>
<input type=submit value="ok">
<?php } 

?>

<table name=tab1 border=1 cellpadding="5" cellspacing="0" width="99%"><tr align="center" class="title" height="30">

<?php
	if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode===true) {
		echo '<td width="60" class="notinfo"><img src="images/todelete.png" title="Удалить" border="0">&nbsp;&nbsp;&nbsp;<img src="images/toupdate.png" title="Правка" border="0"></td>';}
	echo '<td width="30">№</td>';
	echo '<td width="60">'.print_col(2,'дата').'</td>';
	echo '<td width="*">текст повестки</td>';
	
	
	if (!isset($_GET['save']) && !isset($_GET['print'])) 
		{echo '<td width="">на контроле <span style="font-weight:normal;"> (пункт, ФИО, содержание )</span></td>';
		 echo '<td width="*">'.print_col(5,'выпис. по путевкам').'</td>';
		 echo '<td width="*">'.print_col(6,'реком.в аспир.').'</td>';
		 echo '<td width="100" class="notinfo">'.print_col(7,'комментарий').'</td>';	}
		
	$bgcolor='';	
	if (!isset($_GET['save']) && !isset($_GET['print'])) {$bgcolor=' bgcolor="#DFEFFF"';};

	$i=0;
	while ($tmpval=mysql_fetch_array($res))	//вывод показателей
	{
		$sum=0;
		echo '<tr align="left" class="main" '.$bgcolor.' valign="top">';
		if (!isset($_GET['save']) && !isset($_GET['print']) && $write_mode===true) {
		    echo '<td align="center" width="60"> <a href="javascript:del_confirm(\''.f_ro($tmpval['id']).'\',\'№';
            // <abarmin date="23.07.2009">
            // ошибка undefined index page
            $page = 1;
            if (array_key_exists("page", $_GET)) {
                $page = $_GET['page'];
            }
            echo f_ro($tmpval['num']).' от '.f_ro($tmpval['date_text']).'\',\''.f_ro($page).'\')" title="Удалить">';
            // </abarmin>
            echo '<img src="images/todelete.png" alt="Удалить" border="0"></a>&nbsp;&nbsp;&nbsp;';
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
		 	 echo '<td class="notinfo">';
			 //выборка на контроле---------------------------------------------------------------
            // <abarmin date="23.09.2012">
            // ошибка undefined variable search_query
			$query_control="SELECT protocol_details.id as details_id,protocol_details.section_id,protocol_details.text_content,
			 	kadri.fio_short FROM protocol_details 
			 	left join  kadri on kadri.id=protocol_details.kadri_id 
			where protocol_id='".$tmpval['id']."' and on_control=1 ";
            if (isset($search_query)) {
                $query_control .= $search_query;
            }
            $query_control .= " order by section_id,kadri_id ASC limit 0,20";
            // </abarmin>
			 //echo $query_control;
			 $res_control=mysql_query($query_control);
			 while ($a_control=mysql_fetch_array($res_control))
			 {
			  
			  echo ''.$a_control['section_id'].' <b>'.$a_control['fio_short'].'</b> '.
			  	substr($a_control['text_content'],0,strpos($a_control['text_content'],' ',40)).''.
				'<a href="?item_id='.$tmpval['id'].'&type=edit#detailsId_'.$a_control['details_id'].'" title="перейти к пункту для правки"> <b>... -></b></a><br style="font-size:2pt;"><br style="font-size:2pt;">';
			  }
			 //echo $query;			 
			 
		 	 echo '&nbsp;</td>';
			 echo '<td class="notinfo">&nbsp;'.echoIf(intval($tmpval['trip_cnt'])>0,intval($tmpval['trip_cnt']),'').'</td>';
			 echo '<td class="notinfo">&nbsp;'.echoIf(intval($tmpval['asp_cnt'])>0,intval($tmpval['asp_cnt']),'').'</td>';
			 echo '<td class="notinfo">&nbsp;'.$tmpval['comment'].'</td>';}
	}
?>
</table></form>
<?php
//---------------------------------конец списочная форма -------------------------------------------------

//постраничный вывод списка данных о протоколе (по 10)
/*if (isset($filtr) & $filtr!=0) {$query='select id from items_dep where items_type="'.$filtr.'"';}
else {$query='select id from items_dep';}
*/
//echo $query;
/*
$res=mysql_query($query);
//$pages_cnt=floor(mysql_num_rows($res)/$pgVals)+1;
if (floor(mysql_num_rows($res)/$pgVals)==mysql_num_rows($res)/$pgVals) {$pages_cnt=floor(mysql_num_rows($res)/$pgVals);}
 else {$pages_cnt=floor(mysql_num_rows($res)/$pgVals)+1;}
*/
//echo '<div align="left"> страницы ';
//$add_string=reset_param_name($query_string,'pgVals');
//$add_string="&pgVals=".$pgVals;
/*
if (isset($_GET['q']) && $_GET['q']!='')  {$add_string=$add_string.'&q='.$_GET['q'];};
if (isset($_GET['print']))  {$add_string=$add_string.'&print='.$_GET['print'];};
if (isset($_GET['sort']))  {$add_string=$add_string.'&sort='.$_GET['sort'];};
*/

//echo ' strstr($query_string,\'&\')='. strstr($query_string,'&').'! '.$query_string.'<br>';
/*
$itemCnt=getScalarVal('select count(*) from ('.$query.')t');

if (floor($itemCnt/$pgVals)==$itemCnt/$pgVals) {$pages_cnt=floor($itemCnt/$pgVals);}
else {$pages_cnt=($itemCnt/$pgVals)+1;}
*/
$add_string=reset_param_name($query_string,'page');//"&pgVals=".$pgVals;

echo '<div align=center>страницы '.getPagenumList($pages_cnt,$page,6,'page',$add_string,'').'</div>';

/*
for ($i=1;$i<=$pages_cnt;$i++) {if ($i!=$page) 
{echo '<a href="?'.reset_param_name($query_string,'page').'&page='.$i.'"> '.$i.' </a>';} else {echo ' <b>'.$i.'</b> ';}}

*/
//--------------------------------------------------------
echo '<br>макс.число строк на странице:  <input type=text value="'.$pgVals.'" name="pgVals" id="pgVals" size=10 title="число с 1-99"> &nbsp;
	<input type=button onclick="javascript:pgVals(\'\');" value=Ok>
	<p> Всего строк: '.mysql_num_rows($res).'</div>'; 	
	}

if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} ?>

<?php include('footer.php'); ?>