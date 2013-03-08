<?php
include 'authorisation.php';

$kadri_id=0;
if (isset($_GET['kadri_id']) && intval($_GET['kadri_id'])>0) {$kadri_id=intval($_GET['kadri_id']);}

$item_id=0;
if (isset($_GET['item_id']) && intval($_GET['item_id'])>0) {$item_id=intval($_GET['item_id']);}

$view_all_mode=false;	//обзор всех записей, а не тольео своих
if ($_SESSION['task_rights_id']==2 || $_SESSION['task_rights_id']==4) $view_all_mode=true;

$write_mode=false;	//возможность записи, а не только просмотра
if ($_SESSION['task_rights_id']==3 || $_SESSION['task_rights_id']==4) $write_mode=true;

if ($item_id==0 && !$write_mode) die('<div class=error>Вам доступен режим формирования формы для печати. Перейдите к списку удостоверений, закрыв это окно.</div>
	<p><a href="javascript:window.close();">Закрыть окно</p>');

if ($item_id==0 && !$view_all_mode && (!isset($_GET['kadri_id']) || $_GET['kadri_id']!=$_SESSION['kadri_id'])) 
	{header('Location:?kadri_id='.$_SESSION['kadri_id'].'');}	

if ($item_id==0 && $kadri_id>0 && $write_mode)
{
	$query="select kadri.fio,kadri.fio_short,dolgnost.name as dolgnost_name,passp_seria,passp_nomer from kadri left join dolgnost on kadri.dolgnost=dolgnost.id where kadri.id=".$_GET['kadri_id']." limit 0,1";
	$going_res=mysql_query($query);

	$a=mysql_fetch_array($going_res);
	$fio=$a['fio'];
	$fio_short=$a['fio_short'];
	$dolgnost=$a['dolgnost_name'];
	$psp_seria=$a['passp_seria'];
	$psp_num=$a['passp_nomer'];

	if (trim($dolgnost)=='') {$dolgnost='<font color=red>не указано</font>';}
	if (trim($psp_seria)=='') {$psp_seria='<font color=red>не указано</font>';}
	if (trim($psp_num)=='') {$psp_num='<font color=red>не указано</font>';}
}



if ($item_id>0 && !$view_all_mode) //проверяем принадлежность записи сотруднику
{
	$in_kadri=intval(getScalarVal('select id from filial_going where kadri_id='.intval($_SESSION['kadri_id'])));
	if ($in_kadri<=0) $item_id=0;
	
}

if ($item_id>0)	//заполняем бланк данными, если режим печати
{
	$query='SELECT k.fio_short,
       f.name AS filial_name,
       fg.day_cnt,
       DATE_FORMAT(fg.day_start,\'%d.%m.%Y\') as day_start,
       DATE_FORMAT(fg.day_end,\'%d.%m.%Y\') as day_end,
       fa.name AS filial_act,
       t.name AS transport_type,
       k.fio,       
       d.name AS dolg_name,
       k.passp_seria,
       k.passp_nomer
  FROM ((((filials f
           RIGHT OUTER JOIN filial_going fg
              ON (f.id = fg.filial_id))
          LEFT OUTER JOIN filial_actions fa
             ON (fa.id = fg.filial_act_id))
         LEFT OUTER JOIN kadri k
            ON (k.id = fg.kadri_id))
        LEFT OUTER JOIN dolgnost d
           ON (d.id = k.dolgnost))
       LEFT OUTER JOIN transport t
          ON (t.id = fg.transport_type_id)
	where fg.id="'.$item_id.'" limit 0,1'; 
// echo 'query='.$query;
 $res=mysql_query($query);
 
  if (mysql_num_rows($res)>0)
  {
    $res_edit=mysql_fetch_array($res);
  	if (isset($_GET['save']))	//формируем rtf-файл
	{
		$f_name_tml='_modules/docs_tpl/com_report.rtf';
		$f_name_out='filia_com.rtf';
		$data = array('comm_fio'=>$res_edit['fio'],
		      'comm_dolg'=>$res_edit['dolg_name'],
		      'comm_town'=>'г. '.$res_edit['filial_name'],
		      'comm_fio_short'=>$res_edit['fio_short'],
		      'comm_otdel'=>'кафедры АСУ',             
		      'comm_days'=>$res_edit['day_cnt'],
		      'comm_dstart'=>$res_edit['day_start'],
		      'comm_dend'=>$res_edit['day_end'],
		      'comm_target'=>$res_edit['filial_act'],              
		      'comm_pnum'=>$res_edit['passp_nomer'],
		      'comm_pseria'=>$res_edit['passp_seria'],
		      'comm_transport'=>$res_edit['transport_type']
		      );
		makeDoc($f_name_tml,$f_name_out,$data);	
		break;
	}
	
	//$res_edit['day_start']=DateTimeCustomConvert($res_edit['day_start'],'d','mysql2rus');
  	//$res_edit['day_end']=DateTimeCustomConvert($res_edit['day_end'],'d','mysql2rus');
   //print_r($res_edit);
	$filial_name=$res_edit['filial_name'];
	$day_cnt=$res_edit['day_cnt'];
	$day_start=$res_edit['day_start'];
	$day_end=$res_edit['day_end'];
	$filial_act=$res_edit['filial_act'];
	$transport_type=$res_edit['transport_type'];
	//$kadri_id=$res_edit['kadri_id'];

	//$fio_res=mysql_query("select kadri.fio,kadri.fio_short,kadri.passp_seria,kadri.passp_nomer,dolgnost.name as dolgnost_name from kadri left join dolgnost on kadri.dolgnost=dolgnost.id where kadri.id=".$kadri_id." limit 0,1");
	//echo "select fio from kadri where id=".$_GET['kadri_id'];
	//$a=mysql_fetch_array($fio_res);
	$fio=$res_edit['fio'];
	$fio_short=$res_edit['fio_short'];
	$psp_seria=$res_edit['passp_seria'];
	$psp_num=$res_edit['passp_nomer'];
	$dolgnost=$res_edit['dolg_name'];

	//$res_edit['fio_short']=$fio_short;
	//$res_edit['psp_seria']=$psp_seria;
	//$res_edit['psp_num']=$psp_num;	
	//$res_edit['dolgnost']=$dolgnost;	
	
	if (trim($res_edit['dolg_name'])=='') {$dolgnost='<font color=red>не указано</font>';}
	if (trim($res_edit['passp_seria'])=='') {$psp_seria='<font color=red>не указано</font>';}
	if (trim($res_edit['passp_nomer'])=='') {$psp_num='<font color=red>не указано</font>';}
   
   }
 }

?>
<html>

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1251">
<script type="text/javascript" src="scripts/calendar_init.js"></script>
<script type="text/javascript" src="scripts/function.js"></script>

<title>Печать формы по ОКУД 0301024</title>
<style>
<!--
 /* Font Definitions */
/*	вывод подчеркиваний в форме	*/
span.term{float:left;background: #FFF;padding-bottom:2px;padding-right:10px;font-size:10.0pt;}
div.value{border-bottom: 1px solid black; font-weight:bold;font-size:10.0pt;}
    td.btm_b {border-bottom: solid black 1px; font-weight:bold;font-size:10.0pt; padding-left:15px;}
    td.norm {white-space: nowrap;padding:1px;font-size:10.0pt; }


 @font-face
	{font-family:Verdana;
	panose-1:2 11 6 4 3 5 4 4 2 4;}
@font-face
	{font-family:Tahoma;
	panose-1:2 11 6 4 3 5 4 4 2 4;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman";}
h1
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:27.0pt;
	margin-bottom:.0001pt;
	line-height:150%;
	page-break-after:avoid;
	font-size:10.0pt;
	font-family:"Times New Roman";}
h2
	{margin:0cm;
	margin-bottom:.0001pt;
	text-align:center;
	page-break-after:avoid;
	font-size:12.0pt;
	font-family:"Times New Roman";}
h3
	{margin-top:6.0pt;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:0cm;
	margin-bottom:.0001pt;
	text-align:center;
	line-height:150%;
	page-break-after:avoid;
	font-size:10.0pt;
	font-family:"Times New Roman";}
h4
	{margin:0cm;
	margin-bottom:.0001pt;
	text-align:justify;
	page-break-after:avoid;
	font-size:10.0pt;
	font-family:"Times New Roman";}
h6
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:198.0pt;
	margin-bottom:.0001pt;
	page-break-after:avoid;
	font-size:10.0pt;
	font-family:"Times New Roman";}
p.MsoHeading7, li.MsoHeading7, div.MsoHeading7
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:54.0pt;
	margin-bottom:.0001pt;
	line-height:150%;
	page-break-after:avoid;
	font-size:10.0pt;
	font-family:"Times New Roman";
	font-weight:bold;}
p.MsoHeader, li.MsoHeader, div.MsoHeader
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman";}
p.MsoFooter, li.MsoFooter, div.MsoFooter
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:12.0pt;
	font-family:"Times New Roman";}
p.MsoBodyText, li.MsoBodyText, div.MsoBodyText
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:10.0pt;
	font-family:"Times New Roman";}
p.MsoBodyText2, li.MsoBodyText2, div.MsoBodyText2
	{margin:0cm;
	margin-bottom:.0001pt;
	text-align:center;
	font-size:10.0pt;
	font-family:"Times New Roman";}
p.MsoBodyText3, li.MsoBodyText3, div.MsoBodyText3
	{margin:0cm;
	margin-bottom:.0001pt;
	text-align:center;
	font-size:12.0pt;
	font-family:"Times New Roman";
	font-weight:bold;}
a:link, span.MsoHyperlink
	{font-family:Verdana;
	color:blue;
	font-weight:normal;
	text-decoration:none none;}
a:visited, span.MsoHyperlinkFollowed
	{color:purple;
	text-decoration:underline;}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{margin:0cm;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:Tahoma;}
-->
</style>
<script language="javascript">
function set_val(gr_name,val_name)
{ //установка одинаковых параметров группе элементов, например для всех списков "Командируется в", "командированному в"и др. 
//'filial_id'
 //alert('gr_name='+gr_name+', val_name='+val_name);

 if (gr_name.indexOf('filial_id')!=-1) {
  try {
   document.getElementById('filial_id').selectedIndex=val_name;
   document.getElementById('filial_id2').selectedIndex=val_name;
   }
  catch (e) {alert('ошибка '+gr_name);}
  }
 else if (gr_name.indexOf('filial_act')!=-1) {
  try {
   document.getElementById('filial_act_id').selectedIndex=val_name;
   //document.getElementById('filial_act_id2').selectedIndex=val_name;
   }
  catch (e) {alert('ошибка '+gr_name);}
  }
 else alert('else');

} 
function set_val_text(gr_name,val_name)
{
//аналогично функции set_val для текстовых полей, а не списков
  if (gr_name.indexOf('day_cnt')!=-1) {
  try {
   document.getElementById('day_cnt').value=val_name;
   document.getElementById('day_cnt2').value=val_name;
   }
  catch (e) {alert('ошибка '+gr_name);}
  }
 else if (gr_name.indexOf('day_start')!=-1) {
  try {
   document.getElementById('day_start').value=val_name;
   document.getElementById('day_start2').value=val_name;
   }
  catch (e) {alert('ошибка '+gr_name);}
  }
 else if (gr_name.indexOf('day_end')!=-1) {
  try {
   document.getElementById('day_end').value=val_name;
   document.getElementById('day_end2').value=val_name;
   }
  catch (e) {alert('ошибка '+gr_name);}
  }
 else alert('else');

 
}
function check_form()
{
 	a = new Array(
	 	new Array('kadri_id',''),
	 	new Array('filial_act_id',''),
	 	new Array('filial_id',''),
	 	new Array('day_cnt',''),
	 	new Array('day_start',''),
	 	new Array('day_end',''),
	 	new Array('transport_id','')
	);
	requireFieldCheck(a,'form1');
 
} 

</script>
</head>

<body lang=RU link=blue vlink=purple>

<?php if (!isset($_GET['save']) && !isset($_GET['print'])) {?>
<form action="filial_going.php?action=add" method="post" id="form1" name="form1">
<div align=center style="border-width:1px; border-style:solid; padding:5px; background-color:#ebeaff;">
	<a href="filial_going.php"> к списку </a> &nbsp;
	<a href="filial_going.php?action=add"> альтернативная форма добавления </a> &nbsp;
	<input type=text id=comment name=comment value="<?php echo getFormItemValue('comment'); ?>"> примечание &nbsp;
	<input type=button value="сохранить и напечатать" onclick="javascript:check_form();"> &nbsp; 
	<input type=button value="сохранить" onclick="javascript:check_form();">
</div>	
<?php } ?>

<table width="100%" align="center" border=0><tr><td>
<p class=MsoNormal align=center style='text-align:center'><u><span
style='font-size:10.0pt'>Государственное образовательное учреждение высшего
профессионального образования</span></u></p>

<p class=MsoNormal align=center style='text-align:center'><u>«Уфимский
государственный авиационный технический университет»</u></p>

<div align="right">
<table border=0 cellspacing=0 cellpadding=0 width=250>
 <tr>
  <td width=166 valign=top style='width:124.5pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=right style='text-align:right'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=84 valign=top style='width:63.0pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt'>Код</span></p>
  </td>
 </tr>
 <tr>
  <td width=166 valign=top style='width:124.5pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=right style='text-align:right'><span
  style='font-size:10.0pt'>Форма по ОКУД</span></p>
  </td>
  <td width=84 valign=top style='width:63.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt'>0301024</span></p>
  </td>
 </tr>
 <tr>
  <td width=166 valign=top style='width:124.5pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=right style='text-align:right'><span
  style='font-size:10.0pt'>по ОКПО</span></p>
  </td>
  <td width=84 valign=top style='width:63.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt'>02069438</span></p>
  </td>
 </tr>
</table>
</div>
<p class=MsoNormal align=right style='text-align:right'><span style='font-size:
8.0pt'>&nbsp;</span></p>

<p class=MsoNormal align=right style='text-align:right'><b><span
style='font-size:8.0pt'>ПОСЛЕ ПОДПИСИ ИМЕЕТ</span></b></p>

<p class=MsoNormal align=right style='text-align:right'><b><span
style='font-size:8.0pt'>СИЛУ ПРИКАЗА ПО УГАТУ</span></b></p>

<div class=MsoNormal><span style='font-size:9.0pt'>РАЗРЕШАЮ </span></div>

<p class=MsoNormal style='line-height:150%'><b><span style='font-size:10.0pt;
line-height:150%'>Ректор УГАТУ</span></b><u><span style='font-size:10.0pt;
line-height:150%'>                                                               </span></u></p>

<p class=MsoNormal style='line-height:150%'><b><span style='font-size:10.0pt;
line-height:150%'>Проректор</span></b><u><span style='font-size:10.0pt;
line-height:150%'>                                                                         </span></u></p>

<p class=MsoBodyText style='line-height:150%'>«___»____________200__г.</p>

<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='margin-left:36.9pt;border-collapse:collapse;border:none'>
 <tr>
  <td width=371 valign=top style='width:278.55pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=84 valign=top style='width:63.0pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt'>Номер</span></p>
  </td>
  <td width=102 valign=top style='width:76.7pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt'>Дата</span></p>
  </td>
 </tr>
 <tr>
  <td width=371 valign=top style='width:278.55pt;border:none;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=right style='text-align:right'><b><span
  style='font-size:9.0pt'>РАЗРЕШЕНИЕ   НА  КОМАНДИРОВКУ  </span></b></p>
  </td>
  <td width=84 valign=top style='width:63.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=102 valign=top style='width:76.7pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
 </tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="100%" class=MsoNormal>
<tr valign="bottom">
<td class=norm>            Фамилия,&nbsp;имя,&nbsp;отчество</td>
<td width="100%" class=btm_b><?php    if (!isset($_GET['save']) && !isset($_GET['print']) ) {
		 ?>
		 <font color=red size=+1>*</font>
		 <select id="kadri_id" name="kadri_id" 
		 onChange="javascript:confirm_url('?kadri_id='+this.options[this.selectedIndex].value+'&<?php echo reset_param_name($query_string,'kadri_id');?>');" title="Фамилия, имя, отчество"><?php 
		$listQuery="select k.id,k.fio as name 
		 	from kadri k 
			order by k.fio";
		//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		echo getFrom_ListItemValue($listQuery,'id','name','kadri_id'); 
		?></select>	<?php	 	 
	 	}
		else { echo $fio;}
?></td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="100%" class=MsoNormal>
<tr valign="bottom">
<td class=norm>            Должность</td>
<td width="100%" class=btm_b><?php  echo $dolgnost; ?> каф. АСУ </td>
</tr>
</table>

<p class=MsoNormal align=center style='text-align:center'><span
style='font-size:8.0pt'>(сектор, лаборатория, отдел)</span></p>

<table border="0" cellspacing="0" cellpadding="0" width="100%" class=MsoNormal>
<tr valign="bottom">
<td class=norm>            Командируется&nbsp;в</td>
<td width="100%" class=btm_b><?php    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 ?>
		 <font color=red size=+1>*</font>
		 <select id="filial_id" name="filial_id" onChange="javascript:set_val(this.id,this.options[this.selectedIndex].index);" title="командируется в..."><?php 
		$listQuery="select id,name from  filials  order by name";
		//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		echo getFrom_ListItemValue($listQuery,'id','name','filial_id'); 
		?></select><?php	 	 
	 	}
		else { echo 'филиал г.'.$filial_name;}
?></td>
</tr>
</table>


<div style='font-size:8.0pt;text-align:center;'>(город, предприятие, учреждение)</div>


<table border="0" cellspacing="0" cellpadding="0" width="100%" class=MsoNormal>
<tr valign="bottom">
<td class=norm>сроком&nbsp;на</td>
<td class=btm_b width=10%><?php    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 ?>
		 <font color=red size=+1>*</font>
		 <input onchange="javascript:set_val_text(this.id,this.value);" type=text id="day_cnt" name="day_cnt" size=4 maxlength="4" value="<?php echo getFormItemValue('day_cnt');?>" title="сроком на...">	
		 <?php	 	 
	 	}
		else { echo $day_cnt;}
?></td>
<td class=norm>суток&nbsp;с</td>
<td class=btm_b width=20%><?php    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 ?>
		 <font color=red size=+1>*</font>
		 <input type=text id="day_start" name="day_start" size=10 maxlength="10" value="<?php echo getFormItemValue('day_start');?>" onchange="javascript:set_val_text(this.id,this.value);" title="дата начала">	
		 <button type="reset" id="f_trigger_date_start">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "day_start",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_start",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
	<?php	 	 
	 	}
		else { echo $day_start;}
?></td>
<td class=norm>по</td>
<td width="50%" class=btm_b><?php    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 ?>
		 <font color=red size=+1>*</font>
		 <input type=text id="day_end" name="day_end" size=10 maxlength="10" value="<?php echo getFormItemValue('day_end');?>" onchange="javascript:set_val_text(this.id,this.value);" title="дата окончания">	
		 <button type="reset" id="f_trigger_date_end">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "day_end",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_end",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
		 <?php	 	 
	 	}
		else { echo $day_end;}
?></td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="100%" class=MsoNormal>
<tr valign="bottom">
<td class=norm>            Цель&nbsp;командировки</td>
<td width="100%" class=btm_b><?php    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 ?>
		 <font color=red size=+1>*</font>
		 <select id="filial_act_id" name="filial_act_id" onChange="javascript:set_val(this.id,this.options[this.selectedIndex].index);" title="цель командировки"><?php 
		$listQuery="select id,name from filial_actions order by name";
		//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		echo getFrom_ListItemValue($listQuery,'id','name','filial_act_id'); 
		?></select>	<?php	 	 
	 	}
		else { echo $filial_act;}
?></td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr valign="bottom">
<td class=norm>            Отнести&nbsp;на&nbsp;тему&nbsp;№</td>
<td class=btm_b width=20%>&nbsp;</td>
<td class=norm>           по&nbsp;бюджету&nbsp;§§</td>
<td class=btm_b width=20%>&nbsp;</td>
<td class=norm>           рук.&nbsp;темы</td>
<td width="100%" class=btm_b>&nbsp;</td>
</tr>
</table>

<p class=MsoNormal style='margin-left:387.0pt;text-align:justify'><span
style='font-size:8.0pt'>(ф.,и.,о.)</span></p>

<h3 style='margin-top:0cm;line-height:normal'>Подпись командируемого __________________________________</h3>

<p class=MsoNormal style='line-height:130%'><span style='font-size:10.0pt;
line-height:130%'>Согласовано:</span></p>

<table style='font-size:8.0pt;padding-left:40px;font-weight:bold;' >
	<tr><td>Проректор __________________________________</td><td width=40></td><td>Зав.кафедрой ______________________________________</td></tr>
	<tr><td>Декан ______________________________________</td><td width=40></td><td>Начальник НИЧ _____________________________________</td></tr>
	<tr><td>Руководитель темы __________________________</td><td width=40></td><td>Начальник ФУ ______________________________________</td></tr>
</table>

<p class=MsoNormal style='margin-left:126.0pt;line-height:150%'><b><span
style='font-size:8.0pt;line-height:150%'>Гл. бухгалтер </span></b><u><span
style='font-size:8.0pt;line-height:150%'>                                                                              </span></u></p>

<p align="center" style="border-top:solid 1px black; border-bottom:solid 1px black;">ЛИНИЯ ОТРЕЗА </p>

<table  border=0 cellspacing=0 cellpadding=0 width=100%
 style='width:526.6pt;'>
 <tr>
  <td width=267 valign=top style='width:200.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoBodyText align=center style='text-align:center'><span
  style='font-size:8.0pt'>&nbsp;</span></p>
  <p class=MsoBodyText2>Федеральное агентство по образованию</p>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt'> </span></p>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:8.0pt'>Государственное образовательное учреждение высшего
  профессионального образования</span></p>
  <p class=MsoBodyText2><b><span style='font-size:9.0pt'>&nbsp;</span></b></p>
  <p class=MsoBodyText2><b><span style='font-size:9.0pt'>УФИМСКИЙ
  ГОСУДАРСТВЕННЫЙ АВИАЦИОННЫЙ ТЕХНИЧЕСКИЙ УНИВЕРСИТЕТ</span></b></p>
  <p class=MsoBodyText2>&nbsp;</p>
  <p class=MsoBodyText2 style='line-height:150%'>«___»____________200_г.</p>
  <p class=MsoHeader align=center style='text-align:center;line-height:150%'>№_____________</p>
  </td>
  <td width=435 valign=top style='width:326.0pt;padding:0cm 5.4pt 0cm 5.4pt'><br>
  <h2 style='line-height:150%'><span style='font-size:9.0pt;line-height:150%'>КОМАНДИРОВОЧНОЕ УДОСТОВЕРЕНИЕ</span></h2>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr valign="bottom">
<td class=norm>Выдано</td>
<td width="100%" class=btm_b><?php    if (!isset($_GET['save']) && !isset($_GET['print']) ) {
		 ?>
		 <select id="kadri_id2" name="kadri_id2" 
		 onChange="javascript:confirm_url('?kadri_id='+this.options[this.selectedIndex].value+'&<?php echo reset_param_name($query_string,'kadri_id');?>');"><?php 
		$listQuery="select k.id,k.fio as name 
		 	from kadri k 
			order by k.fio";
		//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		echo getFrom_ListItemValue($listQuery,'id','name','kadri_id'); 
		?></select>	<?php	 	 
	 	}
		else { echo $fio;}
?></td>
</tr>
<tr valign="bottom">
<td width="100%" class=btm_b colspan=2>&nbsp;</td>
</tr>
</table>
  

  <p class=MsoNormal><div class=value></div>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:8.0pt'>(фамилия, имя, отчество)</span></p>
  
  <div class=value align=center>   
  <?php echo $dolgnost;?> каф. АСУ
  </div>
  
  <div class=MsoNormal align=center style='text-align:center'><span
  style='font-size:8.0pt'> (Должность)</span></div>
  <br>
  <p class=MsoNormal style='margin-right:-2.85pt;line-height:150%'><span
  style='font-size:10.0pt;line-height:150%'>Уфимского государственного
  авиационного технического университета</span></p>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr valign="bottom">
<td class=norm height=30>командированному&nbsp;в</td>
<td width="100%" class=btm_b>филиал&nbsp;г.&nbsp;<?php    if (!isset($_GET['save']) && !isset($_GET['print']) ) {
		 ?>
		 <select id="filial_id2" name="filial_id2" onChange="javascript:set_val(this.id,this.options[this.selectedIndex].value);"><?php 
		$listQuery="select id,name from  filials  order by name";
		//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		echo getFrom_ListItemValue($listQuery,'id','name','filial_id2'); 
		?></select>	<?php	 	 
	 	}
		else { echo $filial_name;}
?></td>
</tr>
<tr valign="bottom">
<td class=btm_b width="100%" colspan=2>&nbsp;</td>	
</tr>
</table>
  
  </td>
 </tr>
 <tr>
  <td colspan=2 valign=top style='padding-left:250px;'>
  
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr valign="bottom">
<td class=norm>Срок&nbsp;командировки&nbsp;«</td>
<td class=btm_b align=center width=20%><?php    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 ?>
		 <input onchange="javascript:set_val_text(this.id,this.value);" type=text id="day_cnt2" name="day_cnt2" size=4 maxlength="4" value="<?php echo getFormItemValue('day_cnt');?>">	
		 <?php	 	 
	 	}
		else { echo '<span style="font-size:10.0pt; line-height:130%">'.$day_cnt.'</span>';}
?></td>
<td class=norm>»&nbsp;суток&nbsp;c</td>
<td class=btm_b width=30% align=center><?php    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 ?>
		 <input type=text id="day_start2" name="day_start2" size=10 maxlength="10" value="<?php echo getFormItemValue('day_start');?>" onchange="javascript:set_val_text(this.id,this.value);">	
		 <button type="reset" id="f_trigger_date_start2">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "day_start2",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_start2",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
	<?php	 	 
	 	}
		else { echo $day_start;}
?></td>
<td class=norm>по</td>
<td width="50%" class=btm_b ><?php    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 ?>
		 <input type=text id="day_end2" name="day_end2" size=10 maxlength="10" value="<?php echo getFormItemValue('day_end');?>" onchange="javascript:set_val_text(this.id,this.value);">	
		 <button type="reset" id="f_trigger_date_end2">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "day_end2",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_end2",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
		 <?php	 	 
	 	}
		else { echo $day_end;}
?></td>
</tr>
</table>
  
<table border="0" cellspacing="0" cellpadding="0" width="100%" >
<tr valign="bottom">
<td class=norm>Разрешен&nbsp;проезд&nbsp;в</td>
<td width="100%" class=btm_b><?php    if (!isset($_GET['save']) && !isset($_GET['print']) ) {
		 ?>
		 <font color=red size=+1>*</font>
		 <select id="transport_id" name="transport_id" title="разрешен проезд"><?php 
		$listQuery="select id,name from transport order by name";
		//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		echo getFrom_ListItemValue($listQuery,'id','name','transport_type_id'); 
		?></select>	<?php	 	 
	 	}
		else { echo $transport_type.'е';}
?></td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="100%" >
<tr valign="bottom">
<td class=norm>Действителен&nbsp;по&nbsp;предъявлению&nbsp;паспорта&nbsp;серии</td>
<td class=btm_b><?php echo $psp_seria;?></td>
<td class=norm>№</td>
<td width="100%" class=btm_b><?php echo $psp_num;?></td>
</tr>
</table>

  </td>
 </tr>
</table>

<br>

<p align="center" class=MsoBodyText style="border-bottom:solid 1px black; font-weight:bold;">РЕКТОР (Проректор)</p>

<div class=MsoBodyText align=center style='text-align:center;line-height:150%'>Отметки
о прибытии в пункты назначения и выбытии из них:</div>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=687
 style='width:515.6pt;margin-left:5.4pt;border-collapse:collapse'>
 <tr>
  <td width=169 valign=top style='width:126.8pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>Убыл</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>Убыл</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>Убыл</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>Убыл</span></p>
  </td>
 </tr>
 <tr>
  <td width=169 valign=top style='width:126.8pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>из_____________</span></p>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>«___»_________200_г.</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>из_____________</span></p>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>«___»_________200_г.</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>из_____________</span></p>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>«___»_________200_г.</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>из_____________</span></p>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>«___»_________200_г.</span></p>
  </td>
 </tr>
 <tr>
  <td width=169 valign=top style='width:126.8pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><b><span
  style='font-size:10.0pt'>Подпись</span></b></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><b><span
  style='font-size:10.0pt'>Подпись</span></b></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><b><span
  style='font-size:10.0pt'>Подпись</span></b></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><b><span
  style='font-size:10.0pt'>Подпись</span></b></p>
  </td>
 </tr>
 <tr>
  <td width=169 valign=top style='width:126.8pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>Прибыл</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>Прибыл</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>Прибыл</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>Прибыл</span></p>
  </td>
 </tr>
 <tr>
  <td width=169 valign=top style='width:126.8pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>в______________</span></p>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>«___»_________200_г.</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>в______________</span></p>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>«___»_________200_г.</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>в______________</span></p>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>«___»_________200_г.</span></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>в______________</span></p>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>«___»_________200_г.</span></p>
  </td>
 </tr>
 <tr>
  <td width=169 valign=top style='width:126.8pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><b><span
  style='font-size:10.0pt'>Подпись</span></b></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><b><span
  style='font-size:10.0pt'>Подпись</span></b></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><b><span
  style='font-size:10.0pt'>Подпись</span></b></p>
  </td>
  <td width=173 valign=top style='width:129.6pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><b><span
  style='font-size:10.0pt'>Подпись</span></b></p>
  </td>
 </tr>
</table>

<p class=MsoNormal>&nbsp;</p>

</td></tr></table>


<table width="100%" align="center" border=0 style="page-break-before: always;"><tr><td>
<p class=MsoNormal align=center style='text-align:center;line-height:150%'><b><span
style='font-size:10.0pt;line-height:150%;'>ЗАДАНИЕ НА КОМАНДИРОВКУ</span></b></p>

<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0
 style='margin-left:5.4pt;border-collapse:collapse;border:none'>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>№№ п-п.</span></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>Подробный план работы</span></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border-top:solid windowtext 1.0pt;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:none;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>Срок</span></p>
  </td>
 </tr>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><i><span
  style='font-size:10.0pt'>&nbsp;</span></i></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><i><span style='font-size:10.0pt'>&nbsp;</span></i></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0cm;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><i><span
  style='font-size:10.0pt'>&nbsp;</span></i></p>
  </td>
 </tr>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
 </tr>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
 </tr>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
 </tr>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
 </tr>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
 </tr>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
 </tr>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
 </tr>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
 </tr>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
 </tr>
 <tr style='height:17.0pt'>
  <td width=60 valign=top style='width:45.0pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:-5.4pt;
  margin-bottom:2.0pt;margin-left:0cm;text-align:center'><span
  style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=444 valign=top style='width:333.0pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
  <td width=168 valign=top style='width:126.0pt;border:none;border-bottom:solid windowtext 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:17.0pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0cm;margin-bottom:
  2.0pt;margin-left:0cm'><span style='font-size:10.0pt'>&nbsp;</span></p>
  </td>
 </tr>
</table>

<p class=MsoNormal>&nbsp;</p>

<h6>                   Подпись командируемого_________________________</h6>

<p class=MsoHeader>&nbsp;</p>

<p class=MsoHeader>&nbsp;</p>

<p class=MsoHeading7>Зав. кафедрой</p>

<p class=MsoNormal style='margin-left:54.0pt'><b><span style='font-size:10.0pt'>(рук.
лаборатории, отдел)_______________</span></b></p>

<p class=MsoNormal>&nbsp;</p>

<p class=MsoNormal>&nbsp;</p>

<p class=MsoNormal>&nbsp;</p>

<p align="center" style="border:solid 1px black; border-left:none; border-right:none;">ЛИНИЯ ОТРЕЗА </p>

<table class=MsoTableGrid border=0 cellspacing=0 cellpadding=0
 style='border-collapse:collapse'>
 <tr>
  <td width=347 valign=top style='width:260.5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt'>Государственное образовательное учреждение </span></p>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt'>высшего профессионального образования</span></p>
  <p class=MsoBodyText3 style='margin-top:0cm;margin-right:27.0pt;margin-bottom:
  0cm;margin-left:18.0pt;margin-bottom:.0001pt'><span style='font-size:9.0pt'>УФИМСКИЙ
  ГОСУДАРСТВЕННЫЙ АВИАЦИОННЫЙ ТЕХНИЧЕСКИЙ УНИВЕРСИТЕТ</span></p>
  <p class=MsoNormal style='text-align:left'><span style='font-size:10.0pt'>Должность
  </span><b><u><span style='font-size:10.0pt'><?php echo $dolgnost;?></span></u></b> 
  <span style='font-size:10.0pt'>Ф.И.О.</span><b><u><span
  style='font-size:10.0pt'>
<?php    if (!isset($_GET['save']) && !isset($_GET['print'])) {
		 ?>
		 <select id="kadri_id3" name="kadri_id3" 
		 onChange="javascript:confirm_url('?kadri_id='+this.options[this.selectedIndex].value+'&<?php echo reset_param_name($query_string,'kadri_id');?>');" style="width:80;"><?php 
		$listQuery="select k.id,k.fio_short as name 
		 	from kadri k 
			order by k.fio";
		//getFrom_ListItemValue($listQuery,$listId,$listName,$FormListItemName)
		echo getFrom_ListItemValue($listQuery,'id','name','kadri_id'); 
		?></select>	<?php	 	 
	 	}
		else { echo '<b><u>'.$fio_short.'</u></b>';}
?>  
  </span></u></b></p>
  <p class=MsoBodyText2 style='margin-top:6.0pt'>Назначение аванса:</p>
  <table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0
   style='margin-left:5.4pt;border-collapse:collapse;border:none'>
   <tr style='page-break-inside:avoid;height:18.7pt'>
    <td width=83 style='width:62.2pt;border:solid windowtext 1.0pt;border-left:
    none;padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
    <p class=MsoNormal align=center style='text-align:center'><span
    style='font-size:10.0pt'>Дата</span></p>
    </td>
    <td width=169 colspan=2 style='width:126.8pt;border:solid windowtext 1.0pt;
    border-left:none;padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
    <p class=MsoNormal align=center style='text-align:center'><span
    style='font-size:10.0pt'>Содержание операции</span></p>
    </td>
    <td width=84 colspan=2 style='width:63.0pt;border-top:solid windowtext 1.0pt;
    border-left:none;border-bottom:solid windowtext 1.0pt;border-right:none;
    padding:0cm 5.4pt 0cm 5.4pt;height:18.7pt'>
    <p class=MsoNormal align=center style='text-align:center'><span
    style='font-size:10.0pt'>Сумма</span></p>
    </td>
   </tr>
   <tr style='page-break-inside:avoid;height:13.75pt'>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt;height:13.75pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=253 colspan=4 rowspan=2 valign=top style='width:189.8pt;
    border:none;border-bottom:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt;
    height:13.75pt'>
    <p class=MsoNormal style='margin-top:6.0pt'><span style='font-size:10.0pt'>
	Получен&nbsp;аванс&nbsp;«___»_________________</span></p>
    <p class=MsoNormal style='margin-bottom:6.0pt'><span style='font-size:9.0pt'>                            
    «____»___________________</span></p>
    </td>
   </tr>
   <tr style='page-break-inside:avoid;height:13.55pt'>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt;height:13.55pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
   <tr style='page-break-inside:avoid'>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=253 colspan=4 valign=top style='width:189.8pt;border:none;
    border-bottom:solid windowtext 1.0pt;padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal align=center style='text-align:center;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>Итого получено:</span></p>
    </td>
   </tr>
   <tr style='height:22.8pt'>
    <td width=83 style='width:62.2pt;border-top:none;border-left:none;
    border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt;height:22.8pt'>
    <p class=MsoNormal align=center style='text-align:center;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>Дата</span></p>
    </td>
    <td width=61 style='width:45.8pt;border-top:none;border-left:none;
    border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt;height:22.8pt'>
    <p class=MsoNormal align=center style='text-align:center;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>№ док.</span></p>
    </td>
    <td width=132 colspan=2 style='width:99.0pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt;height:22.8pt'>
    <p class=MsoNormal align=center style='text-align:center'><span
    style='font-size:10.0pt'>Расходы </span></p>
    <p class=MsoNormal align=center style='text-align:center'><span
    style='font-size:10.0pt'>по командировке</span></p>
    </td>
    <td width=60 style='width:45.0pt;border:none;border-bottom:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt;height:22.8pt'>
    <p class=MsoNormal align=center style='text-align:center;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>Сумма</span></p>
    </td>
   </tr>
   <tr>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=61 valign=top style='width:45.8pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=132 colspan=2 valign=top style='width:99.0pt;border-top:none;
    border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=60 valign=top style='width:45.0pt;border:none;border-bottom:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
   <tr>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=61 valign=top style='width:45.8pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=132 colspan=2 valign=top style='width:99.0pt;border-top:none;
    border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=60 valign=top style='width:45.0pt;border:none;border-bottom:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
   <tr>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=61 valign=top style='width:45.8pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=132 colspan=2 valign=top style='width:99.0pt;border-top:none;
    border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=60 valign=top style='width:45.0pt;border:none;border-bottom:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
   <tr>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=61 valign=top style='width:45.8pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=132 colspan=2 valign=top style='width:99.0pt;border-top:none;
    border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=60 valign=top style='width:45.0pt;border:none;border-bottom:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
   <tr>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=61 valign=top style='width:45.8pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=132 colspan=2 valign=top style='width:99.0pt;border-top:none;
    border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=60 valign=top style='width:45.0pt;border:none;border-bottom:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
   <tr>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=61 valign=top style='width:45.8pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=132 colspan=2 valign=top style='width:99.0pt;border-top:none;
    border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=60 valign=top style='width:45.0pt;border:none;border-bottom:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
   <tr>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=61 valign=top style='width:45.8pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=132 colspan=2 valign=top style='width:99.0pt;border-top:none;
    border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=60 valign=top style='width:45.0pt;border:none;border-bottom:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
   <tr>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=61 valign=top style='width:45.8pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=132 colspan=2 valign=top style='width:99.0pt;border-top:none;
    border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=60 valign=top style='width:45.0pt;border:none;border-bottom:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
   <tr>
    <td width=83 valign=top style='width:62.2pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=61 valign=top style='width:45.8pt;border-top:none;border-left:
    none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=132 colspan=2 valign=top style='width:99.0pt;border-top:none;
    border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=60 valign=top style='width:45.0pt;border:none;border-bottom:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
   <tr>
    <td width=83 valign=top style='width:62.2pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=61 valign=top style='width:45.8pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=132 colspan=2 valign=top style='width:99.0pt;border:none;
    padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>Всего:</span></p>
    </td>
    <td width=60 valign=top style='width:45.0pt;border:none;padding:0cm 5.4pt 0cm 5.4pt'>
    <p class=MsoNormal style='text-align:justify;line-height:150%'><span
    style='font-size:10.0pt;line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
   <tr height=0>
    <td width=78 style='border:none'></td>
    <td width=60 style='border:none'></td>
    <td width=105 style='border:none'></td>
    <td width=23 style='border:none'></td>
    <td width=59 style='border:none'></td>
   </tr>
  </table>
  <p class=MsoHeader style='margin-top:12.0pt;margin-right:0cm;margin-bottom:
  0cm;margin-left:9.05pt;margin-bottom:.0001pt;line-height:150%'><b><span
  style='font-size:10.0pt;line-height:150%'>Подпись подотчетного лица_______________ </span></b></p>
  </td>
  <td width=347 valign=top style='width:260.5pt;padding:0cm 5.4pt 0cm 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt'>Целесообразность расхода
  подтверждаю.</span></p>
  <p class=MsoBodyText>Письменный доклад о результатах командировки получил.</p>
  <p class=MsoBodyText>&nbsp;</p>
  
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr valign="bottom">
<td class=norm><strong>Зав.&nbsp;кафедрой</strong></td>
<td width="100%" class=btm_b>&nbsp;</td>
</tr>
</table>

  <p class=MsoNormal align=right style='text-align:right;line-height:150%'><span
  style='font-size:10.0pt;line-height:150%'>«___»______________________200__г.</span></p>
  <p class=MsoNormal style='margin-top:6.0pt;margin-right:0cm;margin-bottom:
  0cm;margin-left:17.85pt;margin-bottom:.0001pt;line-height:150%'><span
  style='font-size:10.0pt;line-height:150%'>Отчет проверен.</span></p>
  


<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr valign="bottom">
<td class=norm>К&nbsp;утверждению&nbsp;руб.</td>
<td width="100%" class=btm_b>&nbsp;</td>
</tr>
<tr>
<td colspan=2 width="100%" class=btm_b>&nbsp;</td>
</tr>
</table>
    
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr valign="bottom">
<td class=norm><b>Гл.&nbsp;бухгалтер</b></td>
<td width="100%" class=btm_b>&nbsp;</td>
</tr>
</table>  
  
  <p class=MsoNormal align=right style='text-align:right;line-height:150%'><span
  style='font-size:10.0pt;line-height:150%'>«___»____________________200__г.</span></p>
 
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr valign="bottom">
<td class=norm>Отчет&nbsp;утверждаю&nbsp;в&nbsp;сумме</td>
<td width="100%" class=btm_b>&nbsp;</td>
</tr>
<tr>
<td colspan=2 width="100%" class=btm_b>&nbsp;</td>
</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr valign="bottom">
<td class=norm><strong>Ректор</strong></td>
<td width="100%" class=btm_b>&nbsp;</td>
</tr>
</table>
 
  <p class=MsoNormal align=right style='text-align:right;line-height:150%'><span
  style='font-size:10.0pt;line-height:150%'>«___»______________________200__г.</span></p>
  <p class=MsoHeader align=center style='text-align:center;line-height:150%'><b><span
  style='font-size:10.0pt;line-height:150%;text-transform:uppercase;letter-spacing:
  1.0pt'>ПРОВОДКА</span></b></p>
  <table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0
   style='margin-left:5.4pt;border-collapse:collapse;border:none'>
   <tr style='height:20.5pt'>
    <td width=60 style='width:45.0pt;border:solid windowtext 1.0pt;padding:
    0cm 5.4pt 0cm 5.4pt;height:20.5pt'>
    <p class=MsoHeader align=center style='text-align:center'><span
    style='font-size:10.0pt'>Дебет</span></p>
    </td>
    <td width=156 style='width:117.0pt;border:solid windowtext 1.0pt;
    border-left:none;padding:0cm 5.4pt 0cm 5.4pt;height:20.5pt'>
    <p class=MsoHeader align=center style='text-align:center'><span
    style='font-size:10.0pt'>Кредит</span></p>
    </td>
    <td width=61 style='width:45.75pt;border:solid windowtext 1.0pt;border-left:
    none;padding:0cm 5.4pt 0cm 5.4pt;height:20.5pt'>
    <p class=MsoHeader align=center style='text-align:center'><span
    style='font-size:10.0pt'>Сумма</span></p>
    </td>
   </tr>
   <tr style='height:56.35pt'>
    <td width=60 valign=top style='width:45.0pt;border:none;border-right:solid windowtext 1.0pt;
    padding:0cm 5.4pt 0cm 5.4pt;height:56.35pt'>
    <p class=MsoHeader style='line-height:150%'><span style='font-size:10.0pt;
    line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=156 valign=top style='width:117.0pt;border:none;padding:0cm 5.4pt 0cm 5.4pt;
    height:56.35pt'>
    <p class=MsoHeader style='line-height:150%'><span style='font-size:10.0pt;
    line-height:150%'>&nbsp;</span></p>
    </td>
    <td width=61 valign=top style='width:45.75pt;border:none;padding:0cm 5.4pt 0cm 5.4pt;
    height:56.35pt'>
    <p class=MsoHeader style='line-height:150%'><span style='font-size:10.0pt;
    line-height:150%'>&nbsp;</span></p>
    </td>
   </tr>
  </table>
  <p class=MsoHeader style='margin-top:12.0pt;margin-right:0cm;margin-bottom:
  0cm;margin-left:9.05pt;margin-bottom:.0001pt;line-height:150%'><b><span
  style='font-size:10.0pt;line-height:150%'>Бухгалтер__________________________</span></b></p>
  <p class=MsoHeader align=right style='margin-top:12.0pt;margin-right:0cm;
  margin-bottom:0cm;margin-left:9.05pt;margin-bottom:.0001pt;text-align:right;
  line-height:150%'><span style='font-size:7.0pt;line-height:150%'>подготовлено: (с) портал АСУ, <?php echo date("Y"); ?> г.</span></p>
  <p class=MsoNormal>&nbsp;</p>
  </td>
 </tr>
</table>

<p class=MsoNormal>&nbsp;</p>

</td></tr></table>
<?php if (!isset($_GET['save']) && !isset($_GET['print'])) {?>
<div align=center style="border-width:1px; border-style:solid; padding:5px; background-color:#ebeaff;">
	<a href="filial_going.php"> к списку </a> &nbsp;
	<a href="filial_going.php?action=add"> альтернативная форма добавления </a> &nbsp;
	<input type=text id=comment name=comment value="<?php echo getFormItemValue('comment'); ?>"> примечание &nbsp;
	<input type=button value="сохранить и напечатать" onclick="javascript:check_form();"> &nbsp; 
	<input type=button value="сохранить" onclick="javascript:check_form();">
</div>	
</form>
<?php } ?>

<?php include('footer.php'); ?>