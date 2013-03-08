<?php
include "config.php";

include $portal_path."authorisation.php";
include $portal_path."master_page_short.php";

//Рейтинговая таблица

if (!(isset($_GET['id_year']) && isset($_GET['page_number']) && isset($_GET['limit'])))
header("location:?page_number=1&limit=20&id_year=0");
else
{
if (isset($_POST['checkbox_arr']))
    			{
                include("graph.php");
                }



?>
<LINK REL="STYLESHEET" TYPE="text/css" HREF="rating_style.css">
<script type="text/javascript"  src="rating_javascript.js"></script>

<?php
$sort=6;
$stype='desc';
if (isset($_GET['stype']) && $_GET['stype']=='asc') {$stype=$_GET['stype'];}
if (isset($_GET['sort']) && $_GET['sort']>=1 && $_GET['sort']<6)$sort=$_GET['sort'];


$select1=mysql_query('SELECT  ti.id, ti.name, count(*) AS count FROM time_intervals ti 
		     INNER JOIN summa_ballov sb ON ti.id=sb.id_year 
		     GROUP BY ti.id, ti.name 
		     ORDER BY ti.name desc') or die ("Возникла ошибка");
	if (!isset($_GET['save']) && !isset($_GET['print']))
	{echo "<br><br><a href='index_rating.php' class='notinfo'>Назад</a><br>";}
	echo "<h3 align=center>Рейтинговая таблица за ";
	if (!isset($_GET['save']) && !isset($_GET['print']))
	{echo'<select name="id_year" ONCHANGE="top.location.href=this.options[this.selectedIndex].value"><option value="?page_number='.$_GET['page_number'].'&limit='.$_GET['limit'].'&id_year='.$_GET['id_year'].'"></option>';
    while ($year=mysql_fetch_array($select1))
    {echo'<Option Value="?page_number='.$_GET['page_number'].'&limit='.$_GET['limit'].'&id_year='.$year['id'].'"  '; if ($_GET['id_year']==$year['id'])echo'selected'; echo'>'.$year['name'].'('.$year['count'].')</Option>';}
    	   		echo'</select>';
    }else while ($year=mysql_fetch_array($select1)){if ($_GET['id_year']==$year['id'])echo $year['name'];}
	echo" учебный год.</h3>";
if ($_GET['id_year']!=0)
{


         $limit=$_GET['limit'];
         $start=$limit*($_GET['page_number']-1);
$sql="SELECT SQL_CALC_FOUND_ROWS kadri.fio AS name ,summa_ballov.* ,(summa_ballov.zvanie+summa_ballov.dolzhnost+summa_ballov.nauch_met_uch_rab+summa_ballov.vichet) AS total FROM summa_ballov INNER JOIN kadri ON summa_ballov.id_kadri=kadri.id WHERE id_year=".$_GET['id_year']." ORDER BY ".$sort." ".$stype."  LIMIT ".$start.",".$limit."";
    $mysql=mysql_query($sql) or die("Возникла ошибка");
 if(mysql_num_rows($mysql))
 {
	$sql="SELECT FOUND_ROWS()";
    $count_all=mysql_query($sql);
    $all=mysql_fetch_array($count_all);
    if (!isset($_GET['save']) && !isset($_GET['print']))
    {echo "<table><tr>
    		<td>
    	Кол-во преподавателей на стр.: <select name=limit ONCHANGE='top.location.href=this.options[this.selectedIndex].value'>
    	<option value='?id_year=".$_GET['id_year']."&page_number=1&limit=20&sort=".$_GET['sort']."&stype=".$_GET['stype']."' ";if ($_GET['limit']==20) echo"selected"; echo">20</option>
    	<option value=?id_year=".$_GET['id_year']."&page_number=1&limit=30&sort=".$_GET['sort']."&stype=".$_GET['stype']." "; if ($_GET['limit']==30) echo"selected"; echo">30</option>
    	<option value=?id_year=".$_GET['id_year']."&page_number=1&limit=40&sort=".$_GET['sort']."&stype=".$_GET['stype']." "; if ($_GET['limit']==40) echo"selected"; echo">40</option><option value=?id_year=".$_GET['id_year']."&page_number=1&limit=".$all[0]."&sort=".$_GET['sort']."&stype=".$_GET['stype']."  "; if ($_GET['limit']==$all[0]) echo"selected"; echo">Все</option>
    									</select>";


       echo'</td>

   	   <td align=right>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   	   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   	   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   	   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
       if (!isset($_GET['save']) && !isset($_GET['print']))
{echo '<a class=text href="?'.$_SERVER['QUERY_STRING'].'&save&attach=doc">Выгрузить в MS Word</a><br>';
echo '<a class=text href="?'.$_SERVER['QUERY_STRING'].'&print">Распечатать</a>';}
 echo'</td>
       		   </tr></table>';
     }

     $table_headers=array(
		1=>array('ФИО','200'),
		2=>array('Звание','50'),
		3=>array('Должность','50'),
		4=>array('Работа','50'),
		5=>array('Вычеты','50'),
		6=>array('Сумма','50'),

		);
    echo"<p><FORM action='' method='POST' name='myForm'><table border=1  class=indplan><tr class=indplan><th>&nbsp;</th>";
    for ($i=1;$i<=count($table_headers);$i++)
	{
		if (!isset($_GET['save']) && !isset($_GET['print']))
		echo '<th width="'.$table_headers[$i][1].'">'.print_col($i,$table_headers[$i][0]).'</th>';
		else
		echo '<th width="'.$table_headers[$i][1].'">'.$table_headers[$i][0].'</th>';
	}
    echo"</tr>";
      $i=1+($limit*$_GET['page_number']-$limit);
 While($summa=mysql_fetch_array($mysql))
    {
    	echo"<tr  onmouseover=this.style.background='Turquoise' onmouseout=this.style.background='#DFEFFF' ><td width=5%>";
    	if (!isset($_GET['save']) && !isset($_GET['print'])) echo"<input type='checkbox' id='checkbox_arr[]' name='checkbox_arr[]' value='".$summa['id_kadri']."'>";
    	echo $i."</td><td width=30%>";
    	if (!isset($_GET['save']) && !isset($_GET['print'])) echo"<a class='table_name' href='index_rating.php?prosmotr&id_year=".$_GET['id_year']."&id_kadri=".$summa['id_kadri']."'>";
    	echo $summa['name']."</a></td><td width=10%>".str_replace('.',',',$summa['zvanie'])."</td><td width=10%>".str_replace('.',',',$summa['dolzhnost'])."</td><td width=10%>".str_replace('.',',',$summa['nauch_met_uch_rab'])."</td><td width=10%>".str_replace('.',',',$summa['vichet'])."</td><td width=10%>".str_replace('.',',',($summa['zvanie']+$summa['dolzhnost']+$summa['nauch_met_uch_rab']+$summa['vichet']))."</td></tr>";
       $i++;
    }

    echo"</table>";
    if (!isset($_GET['save']) && !isset($_GET['print']))
     echo"<br><a href='#' ONCLICK='toggleCheckAll(true);'>Отметить все</a>&nbsp;<a href='#' ONCLICK='toggleCheckAll(false);'>Снять все</a>";


    $href_number=ceil($all[0]/$limit);//кол-во ссылок страниц
    if (!isset($_GET['save']) && !isset($_GET['print']))
    {echo"<br><br>
    			<table align=center border=0>
    		<tr>";
    		 if ($_GET['page_number']!=1)echo"<td width=120 align=left><a class='notinfo' href='?id_year=".$_GET['id_year']."&page_number=".($_GET['page_number']-1)."&limit=".$_GET['limit']."&sort=".$_GET['sort']."&stype=".$_GET['stype']."'>&lt; Предыдущая </a></td>";else echo"<td width=120 align=left>&nbsp;</td>";
    		for($i=1;$i<=$href_number;$i++) { if ($i!=$_GET['page_number'])echo "<td><a class='notinfo' href='?id_year=".$_GET['id_year']."&page_number=".$i."&limit=".$_GET['limit']."&sort=".$_GET['sort']."&stype=".$_GET['stype']."'>&nbsp;".$i."&nbsp;</a></td>"; else echo"<td><font color=gainsboro>&nbsp;".$_GET['page_number']."&nbsp;</font></td>";}
    		echo"<td width=120 align=right>"; if($_GET['page_number']!=$href_number)echo"<a class='notinfo' href='?id_year=".$_GET['id_year']."&page_number=".($_GET['page_number']+1)."&limit=".$_GET['limit']."'> Следующая &gt;</a>";else echo"&nbsp;"; echo"</td>
    		</tr>
    			</table>";
    }
                  if (isset($_POST['checkbox_arr']))
    			{
                for ($z=1;$z<=$count_images;$z++)
                	{
                echo"<br><img src='/images/pChart/image_".$image."_".$z.".png' id='img'><br>";

                   	}
                }
               if (!isset($_GET['save']) && !isset($_GET['print']))
      { echo"<br><br><p><a href='?".$_SERVER['QUERY_STRING']."&graph' class='notinfo'>Диаграмма</a></p>";
       }         if(isset($_GET['graph']) && !isset($_GET['save']) && !isset($_GET['print']))
                {
       echo"<table><tr><td>
       			<fieldset><legend>Тип диаграммы</legend><input type='radio' name='graph_type' value=20 checked>Столбиковая диаграмма 1<br><input type='radio' name='graph_type' value=12>Столбиковая диаграмма 2<br><input type='radio' name='graph_type' value=17 >Кривая </fieldset>
       					</td>
       					<td valign=top>
                <fieldset><legend>Сравнение с другим годом</legend>
                <select name='year' ><option value=0></option>";
                $select1=mysql_query('SELECT time_intervals.id, time_intervals.name FROM time_intervals INNER JOIN summa_ballov ON time_intervals.id=summa_ballov.id_year ORDER BY name') or die ("Возникла ошибка ");
                while ($year=mysql_fetch_array($select1))
    {if ($year['id']!=$_GET['id_year'])echo'<Option Value="'.$year['id'].'"  >'.$year['name'].'</Option>';}
                echo"</select></fieldset>
       					</td></tr></table>";

                echo"<p><input type='submit' id='graph' name='graph' title='Построить диаграмму' value='Построить'></p></FORM>";
                }

	}else echo"<h3>Нет данных.</h3>";
}else  echo"<h3>Выберите учебный год.</h3>";
		if (!isset($_GET['save']) && !isset($_GET['print']))
    {echo "<br><br><a href='index_rating.php' class='notinfo'>Назад</a><br><br>";}
}// if (isset($_GET['id_year']) && isset($_GET['page_number']) && isset($_GET['limit']))

?>

</body>

</html>