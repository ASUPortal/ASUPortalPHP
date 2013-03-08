<?php
include ('authorisation.php');
include ('master_page_short.php');
?>
<script language="javascript">
function date_test()
{
 var dateTo=parseInt(document.getElementById('dateTo').value);
 var dateFrom=parseInt(document.getElementById('dateFrom').value);
 //alert(dateTo);alert(dateFrom);
 if (dateTo<dateFrom) {alert('Дата окончания раньше даты начала.');}
 else {document.dateFilter.submit();}
 
} 
function resetFilter()
{
 var dateTo=document.getElementById('dateTo');
 var dateFrom=document.getElementById('dateFrom');
 dateTo.selectedIndex=0;	
 dateFrom.selectedIndex=0;	
 document.dateFilter.submit();
 
} 
</script>
<style>
	td.kadriItem {BORDER-BOTTOM: 1px solid; BORDER-COLOR: #EEEEEE; HEIGHT: 20px;}
</style>

<p align='center'> Рейтинг преподавателей по публикациям </p>
<?php if (!isset($_GET['save']) && !isset($_GET['print']))  { ?>

<div id="div_filter" class=text style="text-align:center;display:none;">
	<form name=dateFilter action="" method="get" id="dateFilter">
	дата начала<select name="dateFrom" id="dateFrom">
	<?php
		$query='select distinct `year` from `izdan` where TRIM(`year`)<>"" order by `year` desc';
		echo getFrom_ListItemValue($query,'year','year','dateFrom');
	?>
	</select> 
	окончания<select name="dateTo" id="dateTo">
	<?php
		$query='select distinct `year` from `izdan` where TRIM(`year`)<>"" order by `year` desc';
		echo getFrom_ListItemValue($query,'year','year','dateTo');
	?>
	</select> 
	<input type=button onClick='resetFilter()' value="x" title="сбросить фильтр">
	<input type=button onClick='date_test()' value='Ok'>
	</form>
</div>
<div class=text style="text-align:center;">
	<a href="#" onclick="hide_show('div_filter');">отобрать публикации по дате</a>
</div>
<?php

	echo"<div style='text-align:right;'>
	<a class=text href='?".$_SERVER["QUERY_STRING"]."&save&attach=xls' title='Выгрузить для анализа' target='_blank'>Передать в MS Excel</a>&nbsp;&nbsp;&nbsp;
	<a class=text href='?".$_SERVER["QUERY_STRING"]."&print' title='Распечатать' target='_blank'>Печать</a></div>";}
$rateArr=array();

$rateArr[0]=array('пособия, монографии и учебники',0.5,'`izdan`.type_book=1 or `izdan`.type_book=27');
$rateArr[1]=array('пособия или учебники с гр. УМО',1,'`izdan`.type_book=2 or `izdan`.type_book=28');
$rateArr[2]=array('тезисы',0.02,'`izdan`.type_book=18 or `izdan`.type_book=19 or `izdan`.type_book=20 or `izdan`.type_book=23 or `izdan`.type_book=24 or `izdan`.type_book=3 or `izdan`.type_book=4 or `izdan`.type_book=5 or `izdan`.type_book=6 or `izdan`.type_book=7 or `izdan`.type_book=8 or `izdan`.type_book=9 or `izdan`.type_book=10 or `izdan`.type_book=11 or `izdan`.type_book=12 or `izdan`.type_book=13 or `izdan`.type_book=14 or `izdan`.type_book=15 or `izdan`.type_book=16 or `izdan`.type_book=17');
$rateArr[3]=array('статья ВАК',0.1,'`izdan`.type_book=26');
$rateArr[4]=array('методические указания',0.01,'`izdan`.type_book=22');
$rateArr[5]=array('свид-во об офиц. регистрации программ',0.03,'`izdan`.type_book=21 or `izdan`.type_book=25');

$dateTo='0';
$dateFrom='0';
if (isset($_GET['dateTo']) && $_GET['dateTo']>0) {$dateTo=$_GET['dateTo'];}
if (isset($_GET['dateFrom']) && $_GET['dateFrom']>0) {$dateFrom=$_GET['dateFrom'];}

$dateWhere='';	//sql-условие отбора по дате публикации
if ($dateTo>0) //отбор по дате
{$dateWhere=' and `year`>='.$dateFrom.' and `year`<='.$dateTo;}

$query='';
for ($i=0;$i<=5;$i++)
{	$cols='';
	for ($j=0;$j<=5;$j++) {//формируем список столбов показателей
		 if ($j==$i) {$cols.=' (count(*)*'.$rateArr[$i][1].') as "col'.$i.'",';}
		 else {$cols.=' 0 as "col'.$j.'",';}
	 }
	 $cols=preg_replace('/,$/','',$cols);	//	удаляем последнюю запятую
$query.='	SELECT `works`.`kadri_id`, '.$cols.'  
			FROM `works` 
			LEFT JOIN `izdan` ON `izdan`.id = `works`.`izdan_id`
			where ('.$rateArr[$i][2].') '.$dateWhere.' 
			group by `works`.`kadri_id`
			union';
	
}
$query=preg_replace('/union$/','',$query);	//	удаляем последний union


$query='select `kadri`.`fio_short` as `kadri_id`,
 (sum(col0)+sum(col1)+sum(col2)+sum(col3)+sum(col4)+sum(col5)) as itog,
 sum(col0) as "'.$rateArr[0][0].'", sum(col1) as "'.$rateArr[1][0].'",
 sum(col2) as "'.$rateArr[2][0].'", sum(col3) as "'.$rateArr[3][0].'",
 sum(col4) as "'.$rateArr[4][0].'", sum(col5) as "'.$rateArr[5][0].'"
 from ('.$query.')T inner join kadri on kadri.id=T.kadri_id  group by `T`.`kadri_id`,`kadri`.`fio_short` order by itog desc';

$result = mysql_query($query);

    ?>
<table style="FONT-SIZE: 8pt; font-family:Arial; " cellspacing="0" cellpadding="0" border="0" width="99%">	
<tr style="font-weight:bold;text-align:center;">
  <td width="120" style="BORDER-BOTTOM: 1px solid"><align="center">ФИО</td>
  <td width="200" style=" BORDER-BOTTOM: 1px solid"><align="center">итого</td>
  <td width="80" style=" BORDER-BOTTOM: 1px solid"><align="center"><a href="#" title="пособия, монографии, учебники">пос-я, моног., учеб.</a></td>
  <td width="80" style=" BORDER-BOTTOM: 1px solid"><align="center"><a href="#" title="пособия или учебники с гр. УМО">пос-я, учеб.с УМО</a> </td>
  <td width="60" style=" BORDER-BOTTOM: 1px solid"><align="center"><a href="#" title="тезисы">тезисы</a></td>
  <td width="60" style=" BORDER-BOTTOM: 1px solid"><align="center"><a href="#" title="статьи ВАК">ст. ВАК</a></td>
  <td width="60" style=" BORDER-BOTTOM: 1px solid"><align="center"><a href="#" title="методические указания">МУ</a></td>
  <td width="60" style=" BORDER-BOTTOM: 1px solid"><align="center"><a href="#" title="свид-во об офиц. регистрации программ">св. рег-и прог.</a></td>
</tr>

<?php
$summ0=0;
$summ1=0;
$summ2=0;
$summ3=0;
$summ4=0;
$summ5=0;

if (isset($_GET['save']) ) { //при экспорте
	if (isset($_GET['attach']) && $_GET['attach']=='xls'  ) {		//attach=xls
      header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0');
      header('Pragma: no-cache');
      header('Content-Type: application/x-msexcel; charset=windows-1251; format=attachment;');
      header('Content-Disposition: attachment; filename=rating.xls');
	}
        while ($line = mysql_fetch_array($result,MYSQL_ASSOC)) {
        print "\t<tr>\n";
        $summ0+=$line[$rateArr[0][0]];
        $summ1+=$line[$rateArr[1][0]];
        $summ2+=$line[$rateArr[2][0]];
        $summ3+=$line[$rateArr[3][0]];
        $summ4+=$line[$rateArr[4][0]];
        $summ5+=$line[$rateArr[5][0]];
        $it_p=$line[$rateArr[0][0]]+$line[$rateArr[1][0]]+$line[$rateArr[2][0]]+$line[$rateArr[3][0]]+$line[$rateArr[4][0]]+$line[$rateArr[5][0]];
        //$it2+=$it_p;
echo "<td width='120'><div align=\"left\">".$line['kadri_id']."</div></td>
<td align=\"center\">".$it_p."</td>
<td width=\"113\"><div align=\"center\">".$line[$rateArr[0][0]]."</div></td>
<td width=\"113\"><div align=\"center\">".$line[$rateArr[1][0]]."</div></td>
<td width=\"113\"><div align=\"center\">".$line[$rateArr[2][0]]."</div></td>
<td width=\"113\"><div align=\"center\">".$line[$rateArr[3][0]]."</div></td>
<td width=\"113\"><div align=\"center\">".$line[$rateArr[4][0]]."</div></td>
<td width=\"118\"><div align=\"center\">".$line[$rateArr[5][0]]."</div></td>";
	   		//foreach ($line as $col_value) {  print "\t\t<td>$col_value</td>\n";   }
        print "\t</tr>\n";
    }
}
else {//вывод с диаграммой при печати и в браузере
        $i=1;
		while ($line = mysql_fetch_array($result,MYSQL_ASSOC)) {
        print "\t<tr>\n";
        $summ0+=$line[$rateArr[0][0]];
        $summ1+=$line[$rateArr[1][0]];
        $summ2+=$line[$rateArr[2][0]];
        $summ3+=$line[$rateArr[3][0]];
        $summ4+=$line[$rateArr[4][0]];
        $summ5+=$line[$rateArr[5][0]];
        $it_p=$line[$rateArr[0][0]]+$line[$rateArr[1][0]]+$line[$rateArr[2][0]]+$line[$rateArr[3][0]]+$line[$rateArr[4][0]]+$line[$rateArr[5][0]];

		$lin=$rateArr[0][0];

echo "
<td class='kadriItem' width='120'><div align=\"left\">".$i.' '.$line['kadri_id']."</div></td>
<td class='kadriItem' align=\"left\"><img src=images/blue.JPG width=".(int)(20*$line[$rateArr[0][0]])." height=20><img src=images/g.JPG width=".(int)(20*$line[$rateArr[1][0]])." height=20 ><img src=images/kor.JPG width=".(int)(20*$line[$rateArr[2][0]])." height=20 ><img src=images/roz.JPG width=".(int)(20*$line[$rateArr[3][0]])." height=20 ><img src=images/kr.JPG width=".(int)(20*$line[$rateArr[4][0]])." height=20><img src=images/zel.JPG width=".(int)(20*$line[$rateArr[5][0]])." height=20 >".$it_p."</td>
<td class='kadriItem' align=\"left\"><img src=images/blue.JPG width=".(int)(20*$line[$rateArr[0][0]])." height=20>". $line[$rateArr[0][0]]."</td>
<td class='kadriItem' align=\"left\"><img src=images/g.JPG width=".(int)(20*$line[$rateArr[1][0]])." height=20 >". $line[$rateArr[1][0]]."</td>
<td class='kadriItem' align=\"left\"><img src=images/kor.JPG width=".(int)(20*$line[$rateArr[2][0]])." height=20 >". $line[$rateArr[2][0]]."</td>
<td class='kadriItem' align=\"left\"><img src=images/roz.JPG width=".(int)(20*$line[$rateArr[3][0]])." height=20 >". $line[$rateArr[3][0]]."</td>
<td class='kadriItem' align=\"left\"><img src=images/kr.JPG width=".(int)(20*$line[$rateArr[4][0]])." height=20 >". $line[$rateArr[4][0]]."</td>
<td class='kadriItem' align=\"left\"><img src=images/zel.JPG width=".(int)(20*$line[$rateArr[5][0]])." height=20 >". $line[$rateArr[5][0]]."</td>

\t</tr>\n
";    
$i++;
}
}
    $it1=$summ0+$summ1+$summ2+$summ3+$summ4+$summ5;
    $tAlign='left';
	if (isset($_GET['save'])) {$tAlign='center';}
    echo '<tr style="font-weight:bold;">
			  <td align="center" style="BORDER-TOP: 1px solid;">Общий итог</td>
	          <td align="'.$tAlign.'" style="BORDER-TOP: 1px solid;text-align:'.$tAlign.';">'.$it1.'</td>
              <td align="'.$tAlign.'" style="BORDER-TOP: 1px solid;text-align:'.$tAlign.';">'.$summ0.'</td>
			  <td align="'.$tAlign.'" style="BORDER-TOP: 1px solid;text-align:'.$tAlign.';">'.$summ1.'</td>
			  <td align="'.$tAlign.'" style="BORDER-TOP: 1px solid;text-align:'.$tAlign.';">'.$summ2.'</td>
			  <td align="'.$tAlign.'" style="BORDER-TOP: 1px solid;text-align:'.$tAlign.';">'.$summ3.'</td>
			  <td align="'.$tAlign.'" style="BORDER-TOP: 1px solid;text-align:'.$tAlign.';">'.$summ4.'</td> 
			  <td align="'.$tAlign.'" style="BORDER-TOP: 1px solid;text-align:'.$tAlign.';">'.$summ5.'</td>              
    </tr>';
   print "</table>\n";
echo '<p align=left class=text>Всего преподавателей: '.mysql_num_rows($result).'</p>';

  
        /* Освобождаем память от результата */
    mysql_free_result($result);
    /* Закрываем соединение */
    mysql_close($link);
?>