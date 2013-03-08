<?php
include "config.php";

include $portal_path."authorisation.php";
include $portal_path."master_page_short.php";

$id_year=0;	// текущий уч.год
if (isset($_GET['id_year'])) $id_year=intval($_GET['id_year']);

$limit=0;	// число записей на странице
if (isset($_GET['limit'])) $limit=intval($_GET['limit']);

$page_number=0;	//число страниц
if (isset($_GET['page_number'])) $page_number=intval($_GET['page_number']);

?>

<LINK REL="STYLESHEET" TYPE="text/css" HREF="indplan.css">

<p class="main"><?php echo $head_title;?></p>

<?php

$query='SELECT id, name
		     FROM time_intervals
		     where id in (select distinct id_year FROM nauch_met_rab union select distinct id_year FROM uch_org_rab union select distinct id_year FROM uch_vosp_rab)
		     ORDER BY name desc';
		     
$select1=mysql_query($query) or die ("Возникла ошибка : ".mysql_error());
    echo'<table><tr><td><b>учебный год: </b>';
    if (!isset($_GET['save']) && !isset($_GET['print']))
    { echo '<select name="id_year" id="id_year" ONCHANGE="top.location.href=\'?id_year=\'+this.options[this.selectedIndex].value">';
   $res_edit=null;
   echo getFrom_ListItemValue($query,'id','name','id_year');   
   echo'</select>';}
    else {
	if ($id_year>0)
	    echo getScalarVal('select name FROM time_intervals where id='.$id_year.' limit 0,1');

	}
    echo '</td></tr></table><br>';
if ($id_year>0)
{
if (!isset($_GET['save']) && !isset($_GET['print']))
{echo '<a class=text href="?'.$_SERVER['QUERY_STRING'].'&save&attach=doc">Выгрузить в MS Word</a><br>';
echo '<a class=text href="?'.$_SERVER['QUERY_STRING'].'&print">Распечатать</a><br><br>';}
//-----------------------------------2ой раздел----------------------------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class="notinfo" href="?id_year='.$id_year.'&limit=1&page_number=1&id_razdel2">1. Учебно - и организационно-методическая работа</a>';
}
if (isset($_GET['id_razdel2']))
{
    $limit=$limit;
    $start=$limit*($page_number-1);

    $sql="SELECT SQL_CALC_FOUND_ROWS id,fio FROM kadri WHERE id IN (SELECT id_kadri FROM uch_org_rab WHERE id_year=".$id_year.") ORDER BY fio LIMIT ".$start.",".$limit."";
    $mysql=mysql_query($sql);
    $check=mysql_num_rows($mysql);
    if (!$check)
    echo '<h3>Нет данных</h3>';
    else
    {
    $sql="SELECT FOUND_ROWS()";
    $count_all=mysql_query($sql);
    $all=mysql_fetch_array($count_all);
    if (!isset($_GET['save']) && !isset($_GET['print'])) {
    echo "<p>Кол-во преподавателей на стр.: <select name=limit ONCHANGE='top.location.href=this.options[this.selectedIndex].value'><option value='?id_year=".$id_year."&page_number=1&limit=1&id_razdel2' ";if ($limit==1) echo"selected"; echo">1</option>
    <option value=?id_year=".$id_year."&page_number=1&limit=3&id_razdel2 "; if ($limit==3) echo"selected"; echo">3</option><option value=?id_year=".$id_year."&page_number=1&limit=5&id_razdel2 "; if ($limit==5) echo"selected"; echo">5</option>
    <option value=?id_year=".$id_year."&page_number=1&limit=10&id_razdel2 "; if ($limit==10) echo"selected"; echo">10</option><option value=?id_year=".$id_year."&page_number=1&limit=20&id_razdel2 "; if ($limit==20) echo"selected"; echo">20</option><option value=?id_year=".$id_year."&page_number=1&limit=".$all[0]."&id_razdel2 "; if ($limit==$all[0]) echo"selected"; echo">Все</option></select></p>"; }


    echo '<table border=1 class="indplan"><tr class="indplan"><th rowspan=2>ФИО</th><th colspan=5>Выполненные работы</th><th colspan=5>Невыполненные работы</th></tr>
	<tr class="indplan"><th>Наименование работ</th><th>Планируемое количество</th><th>Срок выполнения</th><th>Вид отчётности</th><th>Примечание</th>
	<th>Наименование работ</th><th>Планируемое количество</th><th>Срок выполнения</th><th>Вид отчётности</th><th>Примечание</th></tr>';

    while ($kadri=mysql_fetch_array($mysql))
    {
	$sql1="SELECT *,(SELECT name FROM spravochnik_vidov_rabot WHERE id=id_vidov_rabot) AS spr_name FROM uch_org_rab WHERE id_year=".$id_year." AND id_kadri=".$kadri['id']." AND id_otmetka='1'";
	$sql2="SELECT *,(SELECT name FROM spravochnik_vidov_rabot WHERE id=id_vidov_rabot) AS spr_name FROM uch_org_rab WHERE id_year=".$id_year." AND id_kadri=".$kadri['id']."  AND id_otmetka='2'";
	$mysql1=mysql_query($sql1);
	$mysql2=mysql_query($sql2);
	$count=mysql_num_rows($mysql1);
    $count1=mysql_num_rows($mysql2);
    if ($count>=$count1)
             {
    	      $count=$count;
             }
    else
                   {
                    $count=$count1;
                   }
        $i=0;
	while($i!=$count)
	         {
	 $name1=mysql_fetch_array($mysql1);
     $name2=mysql_fetch_array($mysql2);
     echo '<tr><td>'.$kadri['fio'].'</td><td>'.$name1['spr_name'].'</td><td>'.$name1[6].'</td><td>'.f_ri(DateTimeCustomConvert($name1[5],'d','mysql2rus')).'</td><td>'.$name1[7].'</td><td>'.$name1[4].'</td>';

	 if($name2 && $name2[5]<date('Y-m-d'))
     echo '<td  bgcolor=tomato title="Данная работа была просрочена">'.$name2['spr_name'].'</td><td  bgcolor=tomato title="Данная работа была просрочена">'.$name2[6].'</td><td bgcolor=tomato title="Данная работа была просрочена">'.f_ri(DateTimeCustomConvert($name2[5],'d','mysql2rus')).'</td><td  bgcolor=tomato title="Данная работа была просрочена">'.$name2[7].'</td><td  bgcolor=tomato title="Данная работа была просрочена">'.$name2[4].'</td></tr>';
     else
	 echo '<td>'.$name2['spr_name'].'</td><td>'.$name2[6].'</td><td>'.f_ri(DateTimeCustomConvert($name2[5],'d','mysql2rus')).'</td><td>'.$name2[7].'</td><td>'.$name2[4].'</td></tr>';

     $i++;
	         }

  	 }
echo '</table>';
   $href_number=ceil($all[0]/$limit);//кол-во ссылок страниц
    if (!isset($_GET['save']) && !isset($_GET['print'])) {
    echo"<br><br>
    			<table class='white' align=center border=0>
    		<tr>";
    		 if ($page_number!=1)echo"<td width=120 align=left><a href='?id_year=".$id_year."&id_razdel2&page_number=".($page_number-1)."&limit=".$limit."'>&lt; Предыдущая </a></td>";else echo"<td width=120 align=left>&nbsp;</td>";
    		for($i=1;$i<=$href_number;$i++) { if ($i!=$page_number)echo "<td><a href='?id_year=".$id_year."&id_razdel2&page_number=".$i."&limit=".$limit."'>&nbsp;".$i."&nbsp;</a></td>"; else echo"<td><font color=gainsboro>&nbsp;".$page_number."&nbsp;</font></td>";}
    		echo"<td width=120 align=right>"; if($page_number!=$href_number)echo"<a href='?id_year=".$id_year."&id_razdel2&page_number=".($page_number+1)."&limit=".$limit."'> Следующая &gt;</a>";else echo"&nbsp;"; echo"</td>
    		</tr>
    			</table>";}
 }
 }

 //-----------------------------------3ий раздел----------------------------------------------
 if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class=aind href="?id_year='.$id_year.'&limit=1&page_number=1&id_razdel3">2. Научно-методическая и госбюджетая научно-исследовательская работа</a>';
}
if (isset($_GET['id_razdel3']))
{
    $limit=$limit;
    $start=$limit*($page_number-1);
    $sql="SELECT SQL_CALC_FOUND_ROWS id,fio FROM kadri WHERE id IN (SELECT id_kadri FROM nauch_met_rab WHERE id_year=".$id_year.") ORDER BY fio LIMIT ".$start.",".$limit."";

    $mysql=mysql_query($sql);
    $check=mysql_num_rows($mysql);
    if (!$check)
    echo '<h3>Нет данных</h3>';
    else{
    $sql="SELECT FOUND_ROWS()";
    $count_all=mysql_query($sql);
    $all=mysql_fetch_array($count_all);
     if (!isset($_GET['save']) && !isset($_GET['print'])) {
     echo "<p>Кол-во преподавателей на стр.: <select name=limit ONCHANGE='top.location.href=this.options[this.selectedIndex].value'><option value='?id_year=".$id_year."&page_number=1&limit=1&id_razdel3' ";if ($limit==1) echo"selected"; echo">1</option>
    <option value=?id_year=".$id_year."&page_number=1&limit=3&id_razdel3 "; if ($limit==3) echo"selected"; echo">3</option><option value=?id_year=".$id_year."&page_number=1&limit=5&id_razdel3 "; if ($limit==5) echo"selected"; echo">5</option>
    <option value=?id_year=".$id_year."&page_number=1&limit=10&id_razdel3 "; if ($limit==10) echo"selected"; echo">10</option><option value=?id_year=".$id_year."&page_number=1&limit=20&id_razdel3 "; if ($limit==20) echo"selected"; echo">20</option><option value=?id_year=".$id_year."&page_number=1&limit=".$all[0]."&id_razdel3 "; if ($limit==$all[0]) echo"selected"; echo">Все</option></select></p>";
      }
    echo '<table border=1 align=center class="indplan"><tr class="indplan"><th>ФИО</th><th>Тема, наименование работы</th><th>Планируемое количество</th><th>Планируемое количество часов</th><th>Срок выполнения</th><th>Вид отчётности</th>
	<th>Примечание</th></tr>';

	    while ($kadri=mysql_fetch_array($mysql))
    {
	$sql1="SELECT *,(SELECT name FROM spravochnik_vidov_rabot WHERE id=id_vidov_rabot) AS spr_name  FROM nauch_met_rab WHERE id_year=".$id_year." AND id_kadri=".$kadri['id']."";
	$mysql1=mysql_query($sql1);
	while($name1=mysql_fetch_array($mysql1))
	{
     echo '<tr><td>'.$kadri['fio'].'</td><td>'.$name1['spr_name'].'</td><td>'.$name1[8].'</td><td>'.$name1[6].'</td><td>'.f_ri(DateTimeCustomConvert($name1[5],'d','mysql2rus')).'</td><td>'.$name1[7].'</td><td>'.$name1[4].'</td></tr>';
   	 }

	 }
echo '</table>';
$href_number=ceil($all[0]/$limit);//кол-во ссылок страниц
    if (!isset($_GET['save']) && !isset($_GET['print'])) {
    echo"<br><br>
    			<table class='white' align=center border=0>
    		<tr>";
    		 if ($page_number!=1)echo"<td width=120 align=left><a href='?id_year=".$id_year."&id_razdel3&page_number=".($page_number-1)."&limit=".$limit."'>&lt; Предыдущая </a></td>";else echo"<td width=120 align=left>&nbsp;</td>";
    		for($i=1;$i<=$href_number;$i++) { if ($i!=$page_number)echo "<td><a href='?id_year=".$id_year."&id_razdel3&page_number=".$i."&limit=".$limit."'>&nbsp;".$i."&nbsp;</a></td>"; else echo"<td><font color=gainsboro>&nbsp;".$page_number."&nbsp;</font></td>";}
    		echo"<td width=120 align=right>"; if($page_number!=$href_number)echo"<a href='?id_year=".$id_year."&id_razdel3&page_number=".($page_number+1)."&limit=".$limit."'> Следующая &gt;</a>";else echo"&nbsp;"; echo"</td>
    		</tr>
    			</table>"; }
 }
 }
//-----------------------------------4ий раздел----------------------------------------------
 if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class=aind href="?id_year='.$id_year.'&limit=1&page_number=1&id_razdel4">3. Учебно-воспитательная работа</a>';
}
if (isset($_GET['id_razdel4']))
{
    $limit=$limit;
    $start=$limit*($page_number-1);

    $sql="SELECT SQL_CALC_FOUND_ROWS id,fio FROM kadri WHERE id IN (SELECT id_kadri FROM uch_vosp_rab WHERE id_year=".$id_year.") ORDER BY fio LIMIT ".$start.",".$limit."";
    $mysql=mysql_query($sql);
    $check=mysql_num_rows($mysql);
    if (!$check)
    echo '<h3>Нет данных</h3>';
    else{
    $sql="SELECT FOUND_ROWS()";
    $count_all=mysql_query($sql);
    $all=mysql_fetch_array($count_all);
     if (!isset($_GET['save']) && !isset($_GET['print'])) {
     echo "<p>Кол-во преподавателей на стр.: <select name=limit ONCHANGE='top.location.href=this.options[this.selectedIndex].value'><option value='?id_year=".$id_year."&page_number=1&limit=1&id_razdel4' ";if ($limit==1) echo"selected"; echo">1</option>
    <option value=?id_year=".$id_year."&page_number=1&limit=3&id_razdel4 "; if ($limit==3) echo"selected"; echo">3</option><option value=?id_year=".$id_year."&page_number=1&limit=5&id_razdel4 "; if ($limit==5) echo"selected"; echo">5</option>
    <option value=?id_year=".$id_year."&page_number=1&limit=10&id_razdel4 "; if ($limit==10) echo"selected"; echo">10</option><option value=?id_year=".$id_year."&page_number=1&limit=20&id_razdel4 "; if ($limit==20) echo"selected"; echo">20</option><option value=?id_year=".$id_year."&page_number=1&limit=".$all[0]."&id_razdel4 "; if ($limit==$all[0]) echo"selected"; echo">Все</option></select></p>";
     }

    echo '<table border=1 align=center class="indplan"><tr class="indplan"><th rowspan=2>ФИО</th><th colspan=5>Выполненные работы</th><th colspan=5>Невыполненные работы</th></tr>
	<tr class=indplan><th>Виды работ</th><th>Планируемое количество часов</th><th>Срок выполнения</th><th>Примечание</th><th>Номер группы</th>
	<th>Виды работ</th><th>Планируемое количество часов</th><th>Срок выполнения</th><th>Примечание</th><th>Номер группы</th></tr>';
    while ($kadri=mysql_fetch_array($mysql))
    {
	$sql1="SELECT *,(SELECT name FROM spravochnik_vidov_rabot WHERE id=id_vidov_rabot) AS spr_name FROM uch_vosp_rab WHERE id_year=".$id_year." AND id_kadri=".$kadri['id']." AND id_otmetka='1'";
	$sql2="SELECT *,(SELECT name FROM spravochnik_vidov_rabot WHERE id=id_vidov_rabot) AS spr_name FROM uch_vosp_rab WHERE id_year=".$id_year." AND id_kadri=".$kadri['id']." AND id_otmetka='2'";
	$mysql1=mysql_query($sql1);
	$mysql2=mysql_query($sql2);
	$count=mysql_num_rows($mysql1);
    $count1=mysql_num_rows($mysql2);
    if ($count>=$count1)
      {
    	$count=$count;
      }
    else
        {
        $count=$count1;
        }
        $i=0;
	while($i!=$count)
	{
	 $name1=mysql_fetch_array($mysql1);
     $name2=mysql_fetch_array($mysql2);
	 echo '<tr><td>'.$kadri['fio'].'</td><td>'.$name1['spr_name'].'</td><td>'.$name1[7].'</td><td>'.f_ri(DateTimeCustomConvert($name1[6],'d','mysql2rus')).'</td><td>'.$name1[5].'</td><td>'.$name1[4].'</td>';
	 if($name2[6] && $name2[6]<date('Y-m-d'))
	 echo '<td bgcolor=tomato title="Данная работа была просрочена">'.$name2['spr_name'].'</td><td bgcolor=tomato title="Данная работа была просрочена">'.$name2[7].'</td><td bgcolor=tomato title="Данная работа была просрочена">'.f_ri(DateTimeCustomConvert($name2[6],'d','mysql2rus')).'</td><td bgcolor=tomato title="Данная работа была просрочена">'.$name2[5].'</td><td bgcolor=tomato title="Данная работа была просрочена">'.$name2[4].'</td></tr>';
	 else
	 echo '<td>'.$name2['spr_name'].'</td><td>'.$name2[7].'</td><td>'.f_ri(DateTimeCustomConvert($name2[6],'d','mysql2rus')).'</td><td>'.$name2[5].'</td><td>'.$name2[4].'</td></tr>';
	 $i++;
	 }


	 }
echo '</table>';
$href_number=ceil($all[0]/$limit);//кол-во ссылок страниц
    if (!isset($_GET['save']) && !isset($_GET['print'])) {
    echo"<br><br>
    			<table class='white' align=center border=0>
    		<tr>";
    		 if ($page_number!=1)echo"<td width=120 align=left><a href='?id_year=".$id_year."&id_razdel4&page_number=".($page_number-1)."&limit=".$limit."'>&lt; Предыдущая </a></td>";else echo"<td width=120 align=left>&nbsp;</td>";
    		for($i=1;$i<=$href_number;$i++) { if ($i!=$page_number)echo "<td><a href='?id_year=".$id_year."&id_razdel4&page_number=".$i."&limit=".$limit."'>&nbsp;".$i."&nbsp;</a></td>"; else echo"<td><font color=gainsboro>&nbsp;".$page_number."&nbsp;</font></td>";}
    		echo"<td width=120 align=right>"; if($page_number!=$href_number)echo"<a href='?id_year=".$id_year."&id_razdel4&page_number=".($page_number+1)."&limit=".$limit."'> Следующая &gt;</a>";else echo"&nbsp;"; echo"</td>
    		</tr>
    			</table>";}
 }
 }
 if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class=aind href="ind_index.php">Назад</a>';}
}
else
{
echo '<p align=center><h3>Выберите учебный год.</h3><br><a href="ind_index.php" class="notinfo">Назад</a></p>';
}
?>