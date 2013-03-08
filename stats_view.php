<?php
include 'authorisation.php';

/*  значения по умолчанию */
$days4stat=7;	//число дней для статистики
$date_from=date('Y-m-d',mktime(0,0,0, date("m"),date("d")-$days4stat+1,  date("Y"))  );
$date_to=date('Y-m-d');
$query_str=$_SERVER['QUERY_STRING'];
//echo $query_str;
$Convert2UTF=false; //изменить параметр при некорректном отражение Кирилицы в ajax-объектах статистики

if (isset($_GET['date_from']) && $_GET['date_from']!='') {$date_from=DateTimeCustomConvert($_GET['date_from'],'d','rus2mysql');}
if (isset($_GET['date_to']) && $_GET['date_to']!='') {$date_to=DateTimeCustomConvert($_GET['date_to'],'d','rus2mysql');}

$days4stat=round((strtotime($date_to)-strtotime($date_from) )/3600/24,0)+1;

$where_sql='and date(time_stamp)>="'.$date_from.'" and date(time_stamp)<="'.$date_to.'" and is_bot=0';

//define("maxCntDate_c", 0);

//===============================================================================
if (isset($_GET['part1'])) {    //загрузка первой части страниц

    $outstr='<td width=250 valign=top>';
    $query='SELECT host_ip,trim(host_name) as host_name,count(*)as nums	 
             FROM `'.$sql_stats_base.'`.`stats`  
             where 1 '.$where_sql.'   
             group by 1,2 order by 3 desc limit 0,20';
    //echo $query;
    $res=mysql_query($query);
    $outstr.= '<div class="title">адреса пользователей</div>
    <table><tr bgcolor="#B3B3FF" class="text"><td width=30></td><td width=100>DNS/IP адреса</td><td width=40>запросов</td></tr>';
    $i=0;
    while ($a=mysql_fetch_array($res))
    {	$i++;
            if (($i/2)<>round($i/2)) {$outstr.= "<tr  bgcolor=#E6E6FF class=text>\n";}
            else {$outstr.= "<tr  bgcolor=#D7D7FF class=text>\n";}
             $host_name=$a['host_name'];
             $outstr.= '<td>'.$i.'</td><td>'.echoIf($host_name!='',$host_name.' ('.$a['host_ip'].')',$a['host_ip']).'</td><td>'.$a['nums'].'</td></tr>';
    } 
    
    $outstr.= '</table>';
    $outstr.= '</td>';
    //-----------------------------------------------------------------------
    $outstr.= '<td width=300 valign=top>';
    //--------------
    $query=
    'SELECT s.url,T.title,count(*)as nums 
    FROM `'.$sql_stats_base.'`.`stats` s  left join (select name,title from pg_uploads union select url,name from tasks)T on T.name=s.url 
    where 1 '.$where_sql.' 
    group by 1,2 order by 3 desc limit 0,20';
    
    $res=mysql_query($query);
    //$outstr.= $query;
    $outstr.= '<div class="title">посещенные страницы</div>
    <table><tr bgcolor="#B3B3FF" class="text"><td width=30 class=text></td><td width=150>url-адрес</td><td width=40>запросов</td></tr>';
    $i=0;
    while ($a=mysql_fetch_array($res))
    {	$i++;
            if (($i/2)<>round($i/2)) {$outstr.= "<tr  bgcolor=#E6E6FF class=text>\n";}
            else {$outstr.= "<tr  bgcolor=#D7D7FF class=text>\n";}
            if ($a['title']=='') {$a['title']=$a['url'];}
            
            $outstr.= '<td>'.$i.'</td><td><a href="'.$a['url'].'">'.$a['title'].'</a></td><td>'.$a['nums'].'</td></tr>';
    } 
    
    $outstr.= '</table>';
    $outstr.= '</td>';
    //---------------------+находим доп.максимум посещений--------------------------------------------------
    $maxCntDate=0;	
    $outstr.= '<td width=250 valign=top>';
    
    $query=
    'select sum(nums) as nums,sum(wap_nums) as wap_nums,time_stamp as time_stamp from (
    (SELECT count(*) as nums,0 as wap_nums,	date(time_stamp) as time_stamp  
            FROM `'.$sql_stats_base.'`.`stats`  where 1 '.$where_sql.' 
            group by 3 order by 3 desc limit 0,20)
    union
    (SELECT 0 as nums,count(*) as wap_nums,	date(time_stamp) as time_stamp  
            FROM `'.$sql_stats_base.'`.`stats`  where 1 '.$where_sql.'  and `q_string` like "%wap%"
            group by 3 order by 3 desc limit 0,20) 
    )T group by time_stamp order by time_stamp desc limit 0,20';
    
    $res=mysql_query($query);
    $outstr.= '<div class="title">дата посещения</div>
    <table><tr bgcolor="#B3B3FF" class=text><td width=30></td><td width=150>дата посещения</td><td width=40>запросов</td></tr>';
    $i=0;
    while ($a=mysql_fetch_array($res))
    {	$i++;
            if ($maxCntDate<$a['nums']) {$maxCntDate=$a['nums'];}
             if (($i/2)<>round($i/2)) {$outstr.= "<tr  bgcolor=#E6E6FF class=text>\n";}
            else {$outstr.= "<tr  bgcolor=#D7D7FF class=text>\n";}
             $outstr.= '<td>'.$i.'</td><td>'.DateTimeCustomConvert(substr($a['time_stamp'],0,10),'d','mysql2rus').'</td><td>'.$a['nums'].' / '.$a['wap_nums'].'</td></tr>';
    } 
    
    $outstr.= '</table>';
    
//-------------------------
$query='SELECT date(time_stamp),count(*) as nums
            FROM `'.$sql_stats_base.'`.`stats`  where 1 '.$where_sql.'  
             group by 1 order by 1 desc limit 0,20';
    //$outstr.= $query;
	//$outstr.='maxCntDate_c='.constant(maxCntDate_c);
    $res=mysql_query($query);
    $outstr.= '<div class="title">дата посещения (диаграмма)</div>
    <table><tr bgcolor="#B3B3FF" class=text><td width=30></td><td width=150>число запросов</td><td width=40>% от макс.</td></tr>';
    $i=0;
    while ($a=mysql_fetch_array($res))
    {	$i++;
            if (($i/2)<>round($i/2)) {$outstr.= "<tr  bgcolor=#E6E6FF class=text align=left>\n";}
            else {$outstr.= "<tr  bgcolor=#D7D7FF class=text align=left>\n";}
             $outstr.= '<td>'.$i.'</td><td align=left><hr size=20 width="'.(130*($a['nums']/$maxCntDate)).'" color="#B3B3FF"
             style="color:#B3B3FF; background-color:#B3B3FF;text-align:left;">'.
                    '</td><td>'.(round($a['nums']/$maxCntDate,4)*100).' %</td></tr>';
    } 
    
    $outstr.= '</table>';
//------------------------
    $outstr.= '</td>';
    
    if ($Convert2UTF) echo iconv('windows-1251', 'utf-8', $outstr);
    else echo $outstr;
    die();    
}
if (isset($_GET['part2'])) {    
    //-------------------------ниж. строка статистики----------------------------------------------------
    $outstr= '<td width=250 valign=top>';
    $query='SELECT users.id,users.fio as val,count(*) as nums
            FROM `'.$sql_stats_base.'`.`stats`  left join users on users.id=stats.user_name 
            where 1 '.$where_sql.'  
            group by 1,2 order by 3 desc limit 0,20';
    $res=mysql_query($query);
    $outstr.= '<div class="title">активность зарег.пользователей</div>
    <table><tr bgcolor="#B3B3FF" class=text><td width=30></td><td width=150>дата посещения</td><td width=40>запросов</td></tr>';
    $i=0;
    while ($a=mysql_fetch_array($res))
    {	$i++;
            if (($i/2)<>round($i/2)) {$outstr.= "<tr  bgcolor=#E6E6FF class=text>\n";}
            else {$outstr.= "<tr  bgcolor=#D7D7FF class=text>\n";}
             $outstr.= '<td>'.$i.'</td><td><a href="p_lecturers.php?onget=1&idlect='.$a['id'].'">'.$a['val'].'</a></td><td>'.$a['nums'].'</td></tr>';
    } 
    
    $outstr.= '</table>';
    $outstr.= '</td>';
    //-----------------------------------------------------------------------------
    $outstr.= '<td width=250 valign=top>';
    $query='SELECT agent as val,count(*)as nums	
             FROM `'.$sql_stats_base.'`.`stats`  
             where 1 '.$where_sql.'  
             group by 1 order by 2 desc limit 0,20';
    $res=mysql_query($query);
    $outstr.= '<div class="title">браузеры и ОС</div>
    <table><tr bgcolor="#B3B3FF" class=text><td width=30></td><td width=200>имя</td><td width=40>запросов</td></tr>';
    $i=0;
    while ($a=mysql_fetch_array($res))
    {	$i++;
            if (($i/2)<>round($i/2)) {$outstr.= "<tr  bgcolor=#E6E6FF class=text>\n";}
            else {$outstr.= "<tr  bgcolor=#D7D7FF class=text>\n";}
             $outstr.= '<td>'.$i.'</td><td>'.$a['val'].'</td><td>'.$a['nums'].'</td></tr>';
    } 
    
    $outstr.= '</table>';
    $outstr.= '</td>';
    //-----------------------------------------------------------------------------
    $outstr.= '<td width=250 valign=top>';
$query='SELECT referer,count(*) as nums
            FROM `'.$sql_stats_base.'`.`stats`  where 1 '.$where_sql.'  
             group by 1 order by 2 desc limit 0,20';
    //$outstr.= $query;
    $res=mysql_query($query);
    $outstr.= '<div class="title">ссылающиеся сайты</div>
    <table><tr bgcolor="#B3B3FF" class=text><td width=30></td><td width=150>сайт</td><td width=40>запросов</td></tr>';
    $i=0;
    while ($a=mysql_fetch_array($res))
    {	$i++;
            if (($i/2)<>round($i/2)) {$outstr.= "<tr  bgcolor=#E6E6FF class=text align=left>\n";}
            else {$outstr.= "<tr  bgcolor=#D7D7FF class=text align=left>\n";}
             $outstr.= '<td>'.$i.'</td><td align=left><a href="'.$a['referer'].'">'.substr($a['referer'],0,20).' ...</a></td>
             <td>'.$a['nums'].' </td></tr>';
    } 
    
    $outstr.= '</table>';    
    $outstr.= '</td>';
    if ($Convert2UTF) echo iconv('windows-1251', 'utf-8', $outstr);
    else echo $outstr;
    
    die();   
}
if (isset($_GET['part3']))
{
    $outstr= '<td width=250 valign=top>';    
    $outstr.= '</td>';
    //-----------------------------------------------------------------------------
    $outstr.= '<td width=250 valign=top colspan=2>';
    $query='SELECT agent,count(*) as nums
            FROM `'.$sql_stats_base.'`.`stats`  where 1 '.str_replace('is_bot=0','is_bot=1',$where_sql).
             ' group by 1 order by 2 desc limit 0,20';
    //$outstr.= $query;
    $res=mysql_query($query);
    $outstr.= '<div class="title">поисковые роботы</div>
    <table><tr bgcolor="#B3B3FF" class=text><td width=30></td><td width=150>робот</td><td width=40>запросов</td></tr>';
    $i=0;
    while ($a=mysql_fetch_array($res))
    {	$i++;
            if (($i/2)<>round($i/2)) {$outstr.= "<tr  bgcolor=#E6E6FF class=text align=left>\n";}
            else {$outstr.= "<tr  bgcolor=#D7D7FF class=text align=left>\n";}
             $outstr.= '<td>'.$i.'</td><td align=left><a href="'.$a['agent'].'">'.substr($a['agent'],0,20).' ...</a></td>
             <td>'.$a['nums'].' </td></tr>';
    } 
    
    $outstr.= '</table>';
    $outstr.= '</td>';
    if ($Convert2UTF) echo iconv('windows-1251', 'utf-8', $outstr);
    else echo $outstr;
    die();   
}
//===============================================================================
include 'master_page_short.php';

?>
<script type="text/javascript" src="scripts/calendar_init.js"></script>

<?php


echo '<form method="get" action=""> <div class="main">Cтатистика посещений портала</div>  
	<div class="text" style="text-align:center">за <font size=+1 color="#FF0000">'.$days4stat.'</font> последних дней (с  
	<input type=text value='.DateTimeCustomConvert($date_from,'d','mysql2rus').' name="date_from" id="date_from" size="10">
	<button type="reset" id="f_trigger_date_from">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_from",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_from",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
	по  
	<input type=text value='.DateTimeCustomConvert($date_to,'d','mysql2rus').' name="date_to" id="date_to" size="10">
	<button type="reset" id="f_trigger_date_to">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_to",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_to",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>) <input type=submit value="ok"></div></form>';

$query='SELECT count(*)as total_vizitors,count(distinct host_ip) as host_num,count(distinct url) as url_num
    FROM `'.$sql_stats_base.'`.`stats` 
    where 1 '.str_replace('and is_bot=0','',$where_sql);
//echo $query;
$res=mysql_query($query);
$a=mysql_fetch_array($res);

$total_vizitors=$a['total_vizitors'];
$host_num=$a['host_num'];
$url_num=$a['url_num'];

$query='select count(*) wap_vizitors, count(distinct host_ip) wap_host,count(distinct url) wap_url 
        FROM `'.$sql_stats_base.'`.`stats`  where `q_string` like "%wap%" '.$where_sql.'';
//echo $query;
$res=mysql_query($query);
$a=mysql_fetch_array($res);

$query='select count(*) bot_vizitors, count(distinct host_ip) bot_host,count(distinct url) bot_url 
        FROM `'.$sql_stats_base.'`.`stats`  where 1 '.str_replace('is_bot=0','is_bot=1',$where_sql).'';
//echo $query;
$res=mysql_query($query);
$b=mysql_fetch_array($res);

echo '<div class="text" style="text-align:center;">
    всего посещений: <span class=warning>'.$total_vizitors.'</span>, уникальных посетителей: '.$host_num.', уникальных страниц:'.$url_num.', в т.ч.
    <ul>
    <li>мобильных(wap): '.$a['wap_vizitors'].', уникальных посетителей: '.$a['wap_host'].', уникальных страниц:'.$a['wap_url'].'</li>
    <li>поисковиков (bot): '.$b['bot_vizitors'].', уникальных посетителей: '.$b['bot_host'].', уникальных страниц:'.$b['bot_url'].'</li>
    </ul>
</div>';
//-----------------------------------------------------------------------
echo '<div class=main style="text-align:left;">Top-20 </div>';
?>
<script language="javascript">
    $.ajaxSetup({
      cache: false,
      dataType: "html",
      url: "stats_view.php",
      type: "GET"  
        });
    
    function load_stat(part_id)
    {
        
		$.ajax({                
          data:part_id+'&hidejq&'+'<?php echo $query_str;?>&',          
          success: function(html){
                //alert(part_id);
				//$("#"+part_id).attr("innerHTML",html);
				$("#"+part_id).html(html);
				
		
            }
       });
    }    
    //загрузка областей статистики
    load_stat('part1');
    load_stat('part2');
    load_stat('part3');
    
</script>
<?php
echo '<table border=0>';
echo '<tr id=part1><td colspan=3><img src="images/autocomplete_indicator.gif"> загрузка детальной статистики ...</td></tr>';
echo '<tr id=part2><td colspan=3><img src="images/autocomplete_indicator.gif"> загрузка детальной статистики ...</td></tr>';
echo '<tr id=part3><td colspan=3><img src="images/autocomplete_indicator.gif"> загрузка детальной статистики ...</td></tr>';
//---------------------------------------------------------------
echo'</table>';
echo '<p><a href="p_administration.php">К списку задач.</a><p>';

//mysql.close();
?>
<div class=text><b>Примечание</b>
<ul>
    <li>детальная статистика приводится только для пользователей, поисковые роботы исключаются</li>
    <li>информация по поисковым роботам в разделе "поисковые роботы" и в сводной статистике (в т.ч.)</li>
</ul>
</div>
<?php include('footer.php'); ?>