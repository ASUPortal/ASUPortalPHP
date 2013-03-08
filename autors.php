<?php  include ('sql_connect.php'); ?>

<html>
<head>
<title>ФИО соавторов</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>

<?php
 if (isset($_GET['izdan_id']) && $_GET['izdan_id']!="")    {
  $query='select kadri.fio as fio
     from kadri
      inner join works on works.kadri_id=kadri.id
      left join izdan i on i.id=works.izdan_id
     where i.id='.$_GET['izdan_id'].' and works.kadri_id<>i.kadri_id
     order by 1';
  $res=mysql_query($query);
  //echo "query=".$query."<hr>";
$izdan_name=getScalarVal('select name from izdan where id='.$_GET['izdan_id']);
$main_athor=getScalarVal('select k.fio from izdan i left join kadri k on k.id=i.kadri_id where i.id='.$_GET['izdan_id']);
$i=0;
echo "<p>Публикация: <strong>$izdan_name</strong></p>";
echo "<p>главный автор (автор внесения сведений о публикации):<strong>$main_athor</strong></p>";

echo "<h4> ФИО соавторов...</h4>";
echo '<table border="0" width="400">';

if (mysql_num_rows($res)==0) {echo '<tr bgcolor="#D7D7FF" height=40><td width=50 align=center>соавторы не найдены</td></tr>'; }
else

while ($a=mysql_fetch_array($res))
{ $i++;

  echo '<tr bgcolor="#D7D7FF" height=40><td width=50 align=center>'.$i. '</td><td>&nbsp;&nbsp;'.$a['fio'].'</td></tr>';
    $izdan_name=$a['izdan_name'];
}

echo "</table>";
}
?>


<p><a href="javascript:window.close();">Заркрыть...</a>
<?php include('footer.php'); ?>