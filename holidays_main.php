<?php
//include 'sql_connect.php';

function q_print($result,$table)
{
$rows=mysql_num_rows($result);
$cols=mysql_num_fields($result);
$holidayLen=30;                 //число символом в выводе названия праздника
$str_out='';

$str_out= $str_out."<table name=tab_user border=0 cellpadding=0 cellspacing=0 width=170 align=center title='события'>\n";
    for ($i=0;$i<$rows;$i++) {
       $str_out=$str_out. "<tr>";
//       for ($j=2;$j<$cols-2;$j++) {$str_out=$str_out. "<td width=40 class=news><b>".substr(mysql_result($result,$i,$j-1),0,5).
//       "</b></td><td class=news>".substr(mysql_result($result,$i,$j),0,$holidayLen)."...</td>";}

       for ($j=2;$j<$cols-2;$j++) {$str_out=$str_out.'<td width=40 class=TEXT><b>'.substr(mysql_result($result,$i,$j-1),0,5).
       '</b></td><td class=TEXT><a href="javascript:alert(\''.str_replace("'","&quot;",mysql_result($result,$i,$j)) .'\');" title="'.mysql_result($result,$i,$j).'">'
       .substr(mysql_result($result,$i,$j),0,$holidayLen).'</a>...</td>';}


       $str_out= $str_out."</tr>\n";

                             }
    $str_out= $str_out."\n</table>";
return $str_out;
//    $str_out= "Всего найдено записей: <b>$rows</b><br>  ";
}
//----------------------------------------------

$months=array ('января','февраля','марта','апреля','мая','июня',
'июля','августа','сентября','октября','ноября','декабря');

$pg='';$pg_next='';
if (isset($_GET['pg'])) {$pg=$_GET['pg'];}
if (isset($_GET['pg_next'])) {$pg_next=$_GET['pg_next'];}

$str_out='';

if ($pg=='') {$pg=1;}
if ($pg_next=='') {$pg_next=1;}

$str_out= "<!--table name=main_hol border=0 align=center width=170><tr><td class=middle>События
</td></tr></table-->";

/*$link=mysql_connect($sql_host,$sql_login,$sql_passw);
if (!$link) {$str_out= $str_out."Ошибка: не возможно подключить MySQL-сервер, возможно Вы не прошли авторизацию<br>";return;}
if (!mysql_select_db($sql_base)) {$str_out=$str_out."Ошибка: не возможно подключить базу SQL-сервера<br>"; return;}
*/
//$sortColumn=0;
$q_date_now='';            //дата сегодня
$q_date_now=date("d").date(".m");

//$tomorrow  = mktime (0,0,0,date("m"),date("d")+1,date("Y"));
$day_next='';
$day_next=date("d",mktime (0,0,0,date("m"),date("d")+1,date("Y")));

$mon_next=date(".m");
if ($day_next=="01")
   {$mon_next=date(".m",mktime (0,0,0,date("m")+01,date("d"),date("Y")));   }

//$str_out= "day=$day_next,  mon_next=$mon_next<br>";

//$q_date_soon=(date ("d")+1)+date(".m");   //дата завтра


$rows_4_pg=3;    //число праздников на лист
$bg=0;
//--------------сегодня----------------------------------------------
$query='SELECT * FROM `holidays` WHERE 1 AND `date_hol` '
        . ' LIKE \''.$q_date_now.'%\'';

//echo  strval(day_next),"<br>";


$result=mysql_query($query);
$rowsNow=mysql_num_rows($result);
$tablename='';

if ($rowsNow==0) {/*$str_out= "<span class='menu'>Cегодня знаменательных дат нет :(</span></br>";*/}
else {
    $day_now=intval(date("m"));
    //echo ' day_now'.$day_now;
    $str_out= $str_out."<br><div class='news'>Сегодня ".date("d").'&nbsp;'.$months[$day_now-1]."....</div> ";

    if ($rowsNow/$rows_4_pg==intval($rowsNow/$rows_4_pg)) {$pg_end=$rowsNow/$rows_4_pg;}
    else {$pg_end=intval($rowsNow/$rows_4_pg)+1;}

    if ($pg=='all') {;}
    else
      {$bg=($pg-1)*$rows_4_pg;
      //$en=$bg+$rows_4_pg;
      $query=$query.' LIMIT '.$bg.','.$rows_4_pg.'';}

    $result=mysql_query($query);
    $str_out=$str_out.q_print($result,$tablename,$str_out);
//echo $str_out;
//exit;
//-------------------------
}
//$str_out= "<hr>end";

//------------завтра---------------------------------------------------
$q_date_soon=strval($day_next).strval($mon_next);   //дата завтра
//$str_out= "q_date_soon=$q_date_soon";

$query='SELECT * FROM `holidays` WHERE 1 AND `date_hol` '
        . ' LIKE \''.$q_date_soon.'%\'';
//echo "q_date_soon=",$q_date_soon,"<br>";

$result=mysql_query($query);
$rowsTom=mysql_num_rows($result);


if ($rowsTom==0) {/*$str_out= "<span class='menu'>Завтра знаменательных дат нет :(</span></br>";*/}
else {
      $mon_next=intval(str_replace('.','',$mon_next));
      //$mon_next=intval(str_replace('0','',$mon_next));
      $str_out= $str_out."<br><div class='news'>Завтра ". $day_next." &nbsp;".$months[$mon_next-1]."... </div>";

      if ($rowsTom/$rows_4_pg==intval($rowsTom/$rows_4_pg)) {$pg_end=$rowsTom/$rows_4_pg;}
      else {$pg_end=intval($rowsTom/$rows_4_pg)+1;}

      if ($pg_next=='all') {;}
      else
        {$bg=($pg_next-1)*$rows_4_pg;
        //$en=$bg+$rows_4_pg;
        $query=$query.' LIMIT '.$bg.','.$rows_4_pg.'';}

      $result=mysql_query($query);
$str_out=$str_out.q_print($result,$tablename,$str_out);
      //-------------------------
}

if ($rowsTom+$rowsNow<5) {$str_out= $str_out."<br><div class='news'> Скоро.... </div>";
$limRows=strval(5- $rowsTom-$rowsNow);
$mon_next=date("m",mktime (0,0,0,date("m")+1,date("d"),date("Y")));
//$str_out= $mon_next,". .",$limRows;

$query='SELECT * FROM `holidays` WHERE left(`date_hol`,2)>\''.strval($day_next).'\''.
               ' AND substring(`date_hol`,4,2)=\''.date("m").'\' UNION '.
       'SELECT * FROM `holidays` WHERE left(`date_hol`,2)>=\'01\''.
               'AND substring(`date_hol`,4,2)=\''.strval($mon_next).'\' LIMIT 0,'.$limRows.'';
//echo "query soon=",$query,"<br>";

$result=mysql_query($query);
$str_out=$str_out.q_print($result,$tablename,$str_out);
$rowsSoon=mysql_num_rows($result);

}
$str_holidays=$str_out;

//echo $str_holidays;
//$str_out= "<hr>end";
?>
