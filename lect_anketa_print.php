<?php
include ('authorisation.php');


//$bodyOnLoad=' onLoad="Click_color(\'c1\',3);"';

//include ('master_page_short.php');

//echo '';
?>
<html>
<head>
<title>анкета преподавателя</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<META Http-Equiv=Cache-Control Content='no-cache, max-age=0, must-revalidate, no-store'>
<meta http-equiv=PRAGMA content=NO-CACHE>
<link rel="stylesheet" type="text/css" href="css/print.css" media="print">
<!--<link rel="stylesheet" type="text/css" href="css/styles.css">-->

<style>
.text {	FONT: 14px Arial, Sans-serif,Tahoma; VERTICAL-ALIGN: top; COLOR: #000000; TEXT-ALIGN: justify ; text-decoration:none;}

.label {FONT: 13px Arial, Sans-serif,Tahoma; VERTICAL-ALIGN: top; COLOR: grey; TEXT-ALIGN: left ;TEXT-DECORATION: none;}
.label A {FONT: 13px Arial, Sans-serif,Tahoma; VERTICAL-ALIGN: top; COLOR: blue; TEXT-ALIGN: left ;TEXT-DECORATION: none;}
.label A:hover {	COLOR: #c42215; TEXT-DECORATION: none; }

</style>

<script language="JavaScript">
function help_msg()
{ alert('Печать анкеты преподавателя. \nТолько для просмотра и печати, не допускает модификации данных.');
}

function show_hide(name_) {
	if (name_=="publications") {
		//alert(name_);
		if (document.getElementById(''+"publications"+'').style.display=="none") {document.getElementById(''+"publications"+'').style.display="";}
		else {document.getElementById(''+"publications"+'').style.display="none";}
			
	}
	else if (name_>"")
	{
			if (name_=="Layer1") {
//				document.links[0].style="font-weight:bold;";
//				document.links[1].style="font-weight:normal;";
//				document.links[2].style="font-weight:normal;";
				document.getElementById(''+"Layer1"+'').style.display="";
				document.getElementById(''+"Layer2"+'').style.display="none";
				document.getElementById(''+"Layer3"+'').style.display="none"; 									}
			if (name_=="Layer2") {
				document.getElementById(''+"Layer2"+'').style.display="";
				document.getElementById(''+"Layer1"+'').style.display="none";
				document.getElementById(''+"Layer3"+'').style.display="none"; 									}
			if (name_=="Layer3") {
				document.getElementById(''+"Layer3"+'').style.display="";
				document.getElementById(''+"Layer2"+'').style.display="none";
				document.getElementById(''+"Layer1"+'').style.display="none"; 									}
	}
}
</script>

</head>
<script language="javaScript" src="scripts/tabs.js"></script>
<NOSCRIPT>
<h3>Для корректной работы форм ввода требуется включение JavaScript ....
<br> Дальнейшая работа невозможна. Обратитесь к администратору проекта ...<p></h3> </NOSCRIPT>

<?php
//последний  elem id=47
//session_start();	//для преподавателей доступ только к своим публикациям

$main_page='lect_anketa_print.php';

//echo 'userType='.$_SESSION['userType'].'<br>';
//echo 'kadri_id='.$_GET['kadri_id'].'<br>';
if ($view_all_mode!==true & (trim($_GET['kadri_id'])!=trim($_SESSION['kadri_id']) )	) 
	{header('Location:'.$main_page.'?kadri_id='.$_SESSION['kadri_id'].'&print=1');}

if (!isset($_GET['print']) && !isset($_GET['save'])) {header('Location:'.$main_page.'?print=1');}


if ($_GET['save']==1)
{
      header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0');
      header('Pragma: no-cache');
      header('Content-Type: application/msword; charset=windows-1251; format=attachment;');
      header('Content-Disposition: attachment; filename=anketa.doc');
      //table_print($result,'select',$tablename);return;
}
//-----------------------------update---------------------------------------------------------------------------
$disabled_val="";   //для вставки


      if ($_GET['kadri_id']!="") {
      //    echo $_GET['kadri_id']." !!!!! ";
          $query_all='select kadri.id , photo , fio ,fio_short, pol.name as pol,INN,insurance_num ,
          passp_seria , passp_nomer ,passp_place,passp_date, date_rogd ,
          language.name as language1 , language2 , work_place , dolgnost.name as dolgnost,
          zvanie.name as zvanie, stepen.name as stepen, add_work , tel_work , add_home , tel_home ,
          e_mail , site , stag_ugatu, stag_pps,stag_itogo, din_nauch_kar , ekspert_spec , ekspert_kluch_slova , nauch_eksper ,
          prepod_rabota , nagradi , primech 
          from ((((kadri left join pol on kadri.pol=pol.id) left join language on kadri.language1=language.id)
          left join dolgnost on kadri.dolgnost=dolgnost.id)
          left join zvanie on kadri.zvanie=zvanie.id)left join stepen on kadri.stepen=stepen.id 
          where kadri.id="'.$_GET['kadri_id'].'"';// limit 0,1
       //echo $query_all;
          if ( $res_all=mysql_query($query_all)) {$tmpval=mysql_fetch_array($res_all);
		  }    //выборка не пустая
          else {echo "ошибки в выборке. Никто не найден с номером=".$_GET['kadri_id'];/*exit;*/}
          //$disabled_val="disabled";
                                                              }	

//определяем число публикаций для вывода их кол-ва
    if (isset($_GET['kadri_id']))      {
    $tab_name='works';
    $res=mysql_query('select id from '.$tab_name.' where kadri_id='.trim($_GET['kadri_id']));
    $izdan_nums=mysql_num_rows($res);    }

?>

<body bgcolor="#FFFFFF" text="#000000" onload="javascript:Click_color('c1',3);">
<h4 class="notinfo">Анкета преподавателя: печать</h4>

<form name="anket_form" action="" method="post">
<?php if (!isset($_GET['save']) && !isset($_GET['print'])) { ?>

<table name=anketa cellpadding="0" cellspacing="0" width="777">
<tr valign="middle" height="44"  class=notinfo><td width=400>
<?php persons_select('print=1&kadri_id');?> &nbsp;&nbsp; 
<input type="button" value="Печать" onclick="javascript:window.print();" title="Вывод анкеты сразу на принтер"></td> 
<td> <input type="button" value="Справка" onclick="javascript:help_msg();"> &nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="button" value="в Word" onclick="javascript:window.location.href='lect_anketa_print.php?kadri_id=<?php echo trim($_GET['kadri_id']);?>&save=1'" title="Выгрузка анкеты в MS Word"> </td>  </tr>


<tr><td colspan=2>
	<table border="1" bordercolor="#F0F0F0" cellpadding="0" cellspacing="0" width="777">
      <tr bgcolor="#E6E6FF">
        
        <td height=40 id="c1" onMouseOver="newColor('c1');" onMouseOut="backColor('c1');" onClick="Click_color('c1',3);" width="250">
          <div align="center"><font size="3"><b><a href="javascript:show_hide('Layer1');">Общие сведения</a> </b></font></div>
        </td>
        <td height=40 id="c2" onMouseOver="newColor('c2');" onMouseOut="backColor('c2');" onClick="Click_color('c2',3);" width="250">
          <div align="center"><font size="3"><b><a href="javascript:show_hide('Layer2');">Образование, диссертации</a></b></font></div>
        </td>
        <td  height=40 id="c3" onMouseOver="newColor('c3');" onMouseOut="backColor('c3');" onClick="Click_color('c3',3);">
          <div align="center"><font size="3"><b><a href="javascript:show_hide('Layer3');">Трудовая и научная деятельность</b></font></div>
        </td>
      </tr>
    </table>
</td></tr></table>
<?php }
/*
if (!isset($_GET['kadri_id']) or $_GET['kadri_id']=="")
{
echo "<p><a href='p_administration.php'>К списку задач.</a></p>";
}
*/
?>

<div id="Layer1" style="display:"><table name=tab1 cellpadding="0" cellspacing="10" bgcolor="#FFFFFF" width="670">
<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Общие сведения:</b></td></tr>
    <?php if (!isset($_GET['save'])) {?>
	<tr valign="middle"><td width=200 class="label"> Фото </td><td>
		<?php if (isset($tmpval['photo']) && $tmpval['photo']!='') {echo '<img src="images/lects/small/sm_'.urlencode($tmpval['photo']).'" height=60>';}
		else {echo '<img src="images/no_photo.jpg">';} ?> 		</td> </tr>
	<?php } ?>
    <tr><td class="label"> Фамилия, имя, отчество </td> <td  class="text"> <?php if (isset($tmpval['fio'])) {echo $tmpval['fio'];} ?><p></td> </tr>
    <!--<tr><td class="label"> ФИО_краткое </td> <td  class="text"><?php if (isset($tmpval['fio_short'])) {echo $tmpval['fio_short'];} ?><p></td> </tr>-->

<tr><td class="label"> Пол </td> <td>

<?php
if (trim($tmpval['pol'])=='') {$tmpval['pol']='нет данных';}
echo $tmpval['pol'].'<p></td>  </tr>

<tr><td class="label"> Дата рождения </td> <td   class="text">'.$tmpval['date_rogd'].'<p></td> </tr>
<tr><td class="label"> ИНН </td> <td   class="text"> '.$tmpval['INN'].' страховой номер  '.$tmpval['insurance_num'].' <p> </td> </tr>
<tr><td class="label"> Паспортные данные: </td> <td   class="text">серия  '.$tmpval['passp_seria'].', номер '.$tmpval['passp_nomer'].'
 дата выдачи '.$tmpval['passp_date'].'<p></td> </tr>
<tr><td> </td><td   class="text">место выдачи '.$tmpval['passp_place'].'<p></td> </tr>';

if (trim($tmpval['language1'])=='') {$tmpval['language1']='нет данных';}
echo '<tr><td class="label">Иностранный язык:</td> <td   class="text"> '.$tmpval['language1'].'   <p></td>  </tr>';

if (trim($tmpval['work_place'])=='') {$tmpval['work_place']='нет данных';}
echo '<tr><td class="label"> Основное место работы<br>(для совместителей) </td> <td  class="text">'.$tmpval['work_place'].'<p></td> </tr>';

if (trim($tmpval['dolgnost'])=='') {$tmpval['dolgnost']='нет данных';}
echo '<tr><td class="label">Должность:</td> <td class="text"> '.$tmpval['dolgnost'].'  <p></td>  </tr>';

if (trim($tmpval['zvanie'])=='') {$tmpval['zvanie']='нет данных';}
echo '<tr><td class="label">Звание:</td> <td class="text">'.$tmpval['zvanie'].'  <p></td>  </tr>';

if (trim($tmpval['stepen'])=='') {$tmpval['stepen']='нет данных';}
echo '<tr><td class="label">Ученая степень:</td> <td class="text">'.$tmpval['stepen'].'<p></td>  </tr>';

?>
<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Контактная информация:</b></td></tr>
<tr><td height="27" class="label"> Адрес служебный: </td> <td height="27"   class="text"><?php if (isset($tmpval)) {echo $tmpval['add_work'];}?></td> </tr>
<tr><td class="label"> Телефон служебный: </td> <td   class="text"><?php if (isset($tmpval)) {echo $tmpval['tel_work'];} ?></td> </tr>
<tr><td class="label"> Адрес домашний: </td> <td   class="text"><?php if (isset($tmpval)) {echo $tmpval['add_home'];}?> </td> </tr>
<tr><td class="label"> Телефон домашний: </td> <td  class="text"><?php if (isset($tmpval)) {echo $tmpval['tel_home'];} ?></td> </tr>
<tr><td class="label"> Электронная почта: </td> <td  class="text"><?php if (isset($tmpval)) {echo $tmpval['e_mail'];}?></td> </tr>
<tr><td class="label"> Сайт в интернете: </td> <td  class="text"><?php if (isset($tmpval)) {echo $tmpval['site'];}?></td> </tr>
</table></div>

<div id="Layer2" style="display:none">
<table name=tab2 cellpadding="0" cellspacing="10" bgcolor="#FFFFFF" width="670">
<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Высшее образование:</b>&nbsp; &nbsp;
</td></tr>

	<?php
		 if (isset($tmpval)) {
		 	$query='select id , kadri_id , obraz_type , zaved_name , god_okonch , spec_name from obrazov where kadri_id="'.$_GET['kadri_id'].'"' ;
		 	$res=mysql_query($query);$i=1;
		 	if (mysql_num_rows($res)==0) { echo '<tr><td colspan=2 class="label">Нет данных<hr></td></tr>';}
			while ($tmpval_obraz=mysql_fetch_array($res))
		 	{
			echo '<tr><td colspan=2 class="label">'.$i;
			echo '<br>ВУЗ: <span class="text"> '.$tmpval_obraz['zaved_name']. '</span>, ';
		 	echo 'год окончания: <span class="text"> '.$tmpval_obraz['god_okonch']. '</span>, ';
		 	echo '<br>специальность в дипломе: <span class="text"> '.$tmpval_obraz['spec_name'].'</span>.';
		 	echo '<!--<br>Доп.информация: <span class="text"> '.$tmpval_obraz['spec_comment']. '</span>--></td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				}
	 ?>


<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Курсы повышения квалификации:</b>&nbsp; &nbsp;
</td></tr>
	<?php
		 if (isset($tmpval)) {
		 	$query='SELECT id, name , place , date_start , date_end , document , comment FROM courses where kadri_id="'.$_GET['kadri_id'].'"' ;
		 	$res=mysql_query($query);$i=1;
		 	if (mysql_num_rows($res)==0) { echo '<tr><td colspan=2 class="label">Нет данных<hr></td></tr>';}
		 	while ($tmpval_obraz=mysql_fetch_array($res))
		 	{
			echo '<tr><td colspan=2 class="label">'.$i;
			echo '<br>Название курсов: <span class="text"> '.$tmpval_obraz['name']. '</span>, ';
		 	echo '<br>Место проведения: <span class="text"> '.$tmpval_obraz['place']. '</span>, ';
		 	echo '<br>Время проведения: начало <span class="text"> '.$tmpval_obraz['date_start'].'</span>, окончание <span class="text"> '.$tmpval_obraz['date_end'].'</span>.';
		 	echo '<br>Документ по завершении: <span class="text"> '.$tmpval_obraz['document'].'</span>.';
		 	echo '<!--<br>Доп.информация: <span class="text"> '.$tmpval_obraz['comment']. '</span>--></td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				}
	 ?>


<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Кандидатская диссертация:</b>&nbsp; &nbsp;
</td></tr>
	<?php
		 if (isset($tmpval)) {
		 	$query='SELECT id,tema , spec_nom , god_zach , disser_type , comment , kadri_id FROM disser where kadri_id="'.$_GET['kadri_id'].'"  and disser_type="кандидат"' ;
		 	$res=mysql_query($query);$i=1;
		 	if (mysql_num_rows($res)==0) { echo '<tr><td colspan=2 class="label">Нет данных<hr></td></tr>';}
		 	while ($tmpval_kandid=mysql_fetch_array($res))
		 	{
			echo '<tr><td colspan=2 class="label">'.$i;
			echo '<br>Тема: <span class="text"> '.$tmpval_kandid['tema']. '</span>, ';
		 	echo '<br>Номер спец-ти по ВАК: <span class="text"> '.$tmpval_kandid['spec_nom']. '</span>, ';
		 	echo '<br>Год защиты: <span class="text"> '.$tmpval_kandid['god_zach'].'</span>';
		 	echo '<!--<br>Доп.информация: <span class="text"> '.$tmpval_kandid['comment']. '</span>--></td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				}
	 ?>


<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Докторская диссертация:</b>&nbsp; &nbsp;
</td></tr>
	<?php
		 if (isset($tmpval)) {
		 	$query='SELECT id,tema , spec_nom , god_zach , disser_type , comment , kadri_id FROM disser where kadri_id="'.$_GET['kadri_id'].'" and disser_type="доктор"' ;
		 	if (mysql_num_rows($res)==0) { echo '<tr><td colspan=2 class="label">Нет данных<hr></td></tr>';}
			$res=mysql_query($query);$i=1;
		 	while ($tmpval_doktor=mysql_fetch_array($res))
		 	{
			echo '<tr><td colspan=2 class="label">'.$i;
			echo '<br>Тема: <span class="text"> '.$tmpval_doktor['tema']. '</span>, ';
		 	echo '<br>Номер спец-ти по ВАК: <span class="text"> '.$tmpval_doktor['spec_nom']. '</span>, ';
		 	echo '<br>Год защиты: <span class="text"> '.$tmpval_doktor['god_zach'].'</span>';
		 	echo '<!--<br>Доп.информация: <span class="text"> '.$tmpval_doktor['comment']. '</span>--></td></tr>';
		 	$i++;
			echo '<tr><td colspan=2><hr></td></tr>';
		 	
			}				}
	 ?>

</table></div>


<div id="Layer3" style="display:none">
<table name=tab3 cellpadding="0" cellspacing="10" bgcolor="#FFFFFF" width="670">
<tr><td colspan=2 align="center" valign="bottom" height="27"><b>Трудовая и научная деятельность:</b></td></tr>
<tr><td class="label"> Стаж,полных лет  </td> <td class="text">в УГАТУ <?php if (isset($tmpval)) {echo $tmpval['stag_ugatu'];} ?> 
, ППС <?php if (isset($tmpval)) {echo $tmpval['stag_pps'];} ?> , общий <?php if (isset($tmpval)) {echo $tmpval['stag_itogo'];} ?> <p></td> </tr>

<tr><td width=200 class="label">Динамика научной карьеры<br> (должность, учреждение, годы):  </td>
      <td class="text">         <?php if (isset($tmpval)) {echo $tmpval['din_nauch_kar'];} ?>    <p></td>     </tr>
      
<tr><td class="label">Экспертная область:  </td> <td class="text"> <u>научная специальность</u><br> <?php if (isset($tmpval)) {echo $tmpval['ekspert_spec'];} ?>
<br>  <u>ключевые слова</u><br> <?php if (isset($tmpval)) {echo $tmpval['ekspert_kluch_slova'];} ?>  <p></td> </tr>

<tr><td class="label">Опыт научной экспертизы:</td><td class="text"> <?php if (isset($tmpval)) {echo $tmpval['nauch_eksper'];} ?> <p></td> </tr>

<tr><td class="label">Опыт преподавательской работы:</td> <td class="text"> <?php if (isset($tmpval)) {echo $tmpval['prepod_rabota'];} ?> <p></td> </tr>

<tr><td class="label">Научные награды:</td> <td class="text"><?php if (isset($tmpval)) {echo $tmpval['nagradi'];} ?> <p></td> </tr>

<tr><td class="label">Общее число публикаций на портале:  </td> <td class="text">
<?php

   $res=mysql_query('select works.id as works_id, works.izdan_id as izdan_id, kadri_id, name,grif, publisher,volume,
       year,copy,name from (works inner join izdan on works.izdan_id=izdan.id)
       left join izdan_type on izdan.type_book=izdan_type.id where kadri_id='.$_GET['kadri_id'].' GROUP BY works.izdan_id');
   
   if (mysql_num_rows($res)==0) {echo " публикаций  на портале не найдено";/*exit;*/}
   else 
   	{echo '<a href=\'javascript:show_hide("publications");\' title="открыть/скрыть список публикаций">'.mysql_num_rows($res).'</a>';
   	if ($_GET['save']==1) {echo '<div id="publications">';}
   	else {echo '<div id="publications"  style="display:none" class="text">';};
   	
		$i=1;
        while($a=mysql_fetch_array($res))  {
        echo $i.". ".$a['name'].", ".$a['grif'].", ".$a['publisher'].", ".$a['year'].", ".$a['volume'].", ".$a['name']."<p>";
				$i++;					}
                
	echo '</div>';   
   	}

?>
</td> </tr>

<tr><td class="label"> Примечание: </td> <td class="text"> <?php if (isset($izdan_nums)) {echo $tmpval['primech'];} ?></td> </tr>
</table></div>
<?php if (!isset($_GET['save']) && !isset($_GET['print'])) { ?>
<table>
<tr valign="middle" height="44"  class=notinfo><td width=300>
<input type="button" value="Печать" onclick="javascript:window.print();" title="Вывод анкеты сразу на принтер"></td> 
<td> <input type="button" value="Справка" onclick="javascript:help_msg();">&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="button" value="в Word" onclick="javascript:window.location.href='lect_anketa_print.php?kadri_id=<?php echo trim($_GET['kadri_id']);?>&save=1'" title="Выгрузка анкеты в MS Word"> </td>  </tr>
</table>
<?php }?>
</form>
</body>
<?php if (!isset($_GET['save']) && !isset($_GET['print'])) { ?>
<div class=notinfo> <a href="p_administration.php">К списку задач.</a></p></div>
<?php }
else {
?>
<div class=notinfo> <a href="#close" onclick="javascript:window.close();">Закрыть.</a></p></div>
<?php } ?>

<?php include('footer.php'); ?>