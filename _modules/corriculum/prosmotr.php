<?php
include "config.php";

include_once $portal_path.'authorisation.php';  // РїСѓС‚СЊ Рє С„Р°Р№Р»Сѓ Р°РІС‚РѕСЂРёР·Р°С†РёРё РЅР° РїРѕСЂС‚Р°Р»Рµ;
include_once $portal_path.'master_page_short.php';// РїСѓС‚СЊ Рє С„Р°Р№Р»Сѓ;
?>
<LINK REL="STYLESHEET" TYPE="text/css" HREF="indplan.css"> 
<script type="text/javascript"  src="indplan.js"></script> 
<script type="text/javascript" src="<?php echo $files_path;?>scripts/calendar.js"></script> 
<script type="text/javascript" src="<?php echo $files_path;?>scripts/calendar-setup.js"></script> 
<script type="text/javascript" src="<?php echo $files_path;?>scripts/lang/calendar-ru_win_.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $files_path;?>css/calendar-win2k-asu.css" title="win2k-cold-1" />
              
<p class="main"><?php echo $head_title;?></p>
              
<?php
include_once 'sql_individual_plan.inc'; // РїСѓС‚СЊ Рє С„Р°Р№Р»Сѓ РёРЅРґРёРІРёРґСѓР°Р»СЊРЅС‹Р№ РїР»Р°РЅ;
$select=mysql_query('SELECT id, fio FROM kadri ORDER BY fio') or die ("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° : ".mysql_error());// Р·Р°РїСЂРѕСЃ РЅР° РІС‹РІРѕРґ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЏ;
$select1=mysql_query('SELECT id, name FROM time_intervals  ORDER BY name') or die ("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° : ".mysql_error());// Р·Р°РїСЂРѕСЃ РЅР° РІС‹РІРѕРґ РіРѕРґР°;
// РІС‹РІРѕРґ СЃРµРјРµСЃС‚СЂРѕРІ РёР· С‚Р°Р±Р»РёС†С‹ plan;
//$sem1	= mysql_query("select * from plan where (`id_kadri` = '".$_GET['id_kadri']."' and `id_year` = '".$_GET['id_year']."' and `id_semestr` = '1')") or die ("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° : ".mysql_error());
//$sem2	= mysql_query("select * from plan where (`id_kadri` = '".$_GET['id_kadri']."' and `id_year` = '".$_GET['id_year']."' and `id_semestr` = '2')") or die ("Р’РѕР·РЅРёРєР»Р° РѕС€РёР±РєР° : ".mysql_error());

//$plan_1	= mysql_fetch_array($sem1);

echo '<table><tr><td valign=top><b>РџСЂРµРїРѕРґР°РІР°С‚РµР»СЊ: </b>'; // РґРѕР±Р°РІР»РµРЅРёРµ СЃС‚СЂРѕРєРё РїСЂРµРїРѕРґР°РІР°С‚РµР»СЊ;	
		if (!isset($_GET['save']) && !isset($_GET['print'])) { 
	// Р·Р°РїСЂРѕСЃ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЏ РїРѕ РіРѕРґСѓ	
	echo '<Select   name="id_kadri" ONCHANGE="top.location.href=this.options[this.selectedIndex].value"><option value="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'"></option>';
  	while ($fio=mysql_fetch_array($select))
   	{echo'<Option Value="?id_kadri='.$fio['id'].'&id_year='.$_GET['id_year'].'"  '; if ($_GET['id_kadri']==$fio['id'])echo'selected'; echo'>'.$fio['fio'].'</Option>';}
    echo'</Select>';} else {while ($fio=mysql_fetch_array($select)){if ($_GET['id_kadri']==$fio['id'])echo $fio['fio'];}}
    echo '      </td>
    	   		<td>
                <b>&nbsp;&nbsp;&nbsp; СѓС‡РµР±РЅС‹Р№ РіРѕРґ: </b>'; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo '<select name="id_year" ONCHANGE="top.location.href=this.options[this.selectedIndex].value"><option value="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'"></option>';
    while ($year=mysql_fetch_array($select1))
    {
    $check_sel=false;
    $fact1=mysql_query('SELECT id_kadri FROM fact WHERE id_year='.$year['id'].'');// РґРѕР±Р°РІР»РµРЅРёРµ СЂР°СЃРїРёСЃР°РЅРёСЏ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЏ РїРѕ СѓС‡РµР±РЅРѕРјСѓ РіРѕРґСѓ;
    if(mysql_num_rows($fact1)) $check_sel=true;
    else
    {$izmen1=mysql_query('SELECT id_kadri FROM izmen WHERE id_year='.$year['id'].'');// РґРѕР±Р°РІР»РµРЅРёРµ РёР·РјРµРЅРµРЅРёР№ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЏ РїРѕ СѓС‡РµР±РЅРѕРјСѓ РіРѕРґСѓ;
    if(mysql_num_rows($izmen1)) $check_sel=true;
    else
     {$nauch_met_rab1=mysql_query('SELECT id_kadri FROM nauch_met_rab WHERE id_year='.$year['id'].'');// РґРѕР±Р°РІР»РµРЅРёРµ РЅР°СѓС‡РЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРѕР№ СЂР°Р±РѕС‚С‹ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЏ РїРѕ СѓС‡РµР±РЅРѕРјСѓ РіРѕРґСѓ;
    if(mysql_num_rows($nauch_met_rab1)) $check_sel=true;
    else
      {$perechen_nauch_rab1=mysql_query('SELECT id_kadri FROM perechen_nauch_rab WHERE id_year='.$year['id'].'');// РґРѕР±Р°РІР»РµРЅРёРµ  РЅР°СѓС‡РЅС‹С… СЂР°Р±РѕС‚ РїРѕ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЋ Рё СѓС‡РµР±РЅРѕРјСѓ РіРѕРґСѓ;
    if(mysql_num_rows($perechen_nauch_rab1)) $check_sel=true;
    else
      {$uch_org_rab1=mysql_query('SELECT id_kadri FROM uch_org_rab WHERE id_year='.$year['id'].'');// РґРѕР±Р°РІР»РµРЅРёРµСѓС‡РµР±РЅРѕ-РѕСЂРіР°РЅРёР·Р°С†РёРѕРЅРЅРѕР№ СЂР°Р±РѕС‚С‹ РїРѕ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЋ Рё СѓС‡РµР±РЅРѕРјСѓ РіРѕРґСѓ;
    if(mysql_num_rows($uch_org_rab1)) $check_sel=true;
    else
        {$uch_vosp_rab1=mysql_query('SELECT id_kadri FROM uch_vosp_rab WHERE id_year='.$year['id'].''); // РґРѕР±Р°РІР»РµРЅРёРµ СѓС‡РµР±РЅРѕ-РІРѕСЃРїРёС‚Р°С‚РµР»СЊРЅРѕР№ СЂР°Р±РѕС‚С‹ РїРѕ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЋ Рё СѓС‡РµР±РЅРѕРјСѓ РіРѕРґСѓ;
    if(mysql_num_rows($uch_vosp_rab1)) $check_sel=true;
    else
        {$zakl1=mysql_query('SELECT id_kadri FROM zakl WHERE id_year='.$year['id'].''); // РґРѕР±Р°РІР»РµРЅРёРµ Р·Р°РєР»СЋС‡РµРЅРёСЏ, РІ СЃРѕРѕС‚РІРµС‚СЃС‚РІРёРё СЃ РїСЂРµРїРѕРґР°РІР°С‚РµР»РµРј Рё СѓС‡РµР±РЅС‹Рј РіРѕРґРѕРј;
    if(mysql_num_rows($zakl1)) $check_sel=true;

    }
    }
    }
    }
    }
    }
// С„РѕСЂРјРёСЂРѕРІР°РЅРёРµ Р·Р°РїСЂРѕСЃР° РїРѕ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЋ Рё СѓС‡РµР±РЅРѕРјСѓ РіРѕРґСѓ;
    echo'<Option Value="?id_kadri='.$_GET['id_kadri'].'&id_year='.$year['id'].'"  '; if ($_GET['id_year']==$year['id'])echo'selected'; echo'>'.$year['name'].'';if($check_sel) echo'&nbsp;(+)'; echo'</Option>';}
    	   		echo'</select>';} else {while ($year=mysql_fetch_array($select1)){if ($_GET['id_year']==$year['id'])echo $year['name'];}}echo '</td>
    	    </tr>
     </table><br>';
if ($_GET['id_kadri']!=0 && $_GET['id_year']!=0) // РѕР±СЂР°Р±РѕС‚РєР° Р·Р°РїСЂРѕСЃР°;
{
if (!isset($_GET['save']) && !isset($_GET['print'])) // РїСЂРѕРІРµСЂРєР° СЃСѓС‰РµСЃС‚РІРѕРІР°РЅРёСЏ РґР°РЅРЅС‹С… Рё СЃРѕС…СЂР°РЅРёС‚СЊ; 
{echo '<a class=text href="?'.$_SERVER['QUERY_STRING'].'&save&attach=doc">Р’С‹РіСЂСѓР·РёС‚СЊ РІ MS Word</a><br>'; // 
echo '<a class=text href="?'.$_SERVER['QUERY_STRING'].'&print">Р Р°СЃРїРµС‡Р°С‚Р°С‚СЊ</a><br><br>';}

//---------------------------------------------Р Р°Р·РґРµР» 1 РЈС‡РµР±РЅР°СЏ СЂР°Р±РѕС‚Р°--------------------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) // РїСЂРѕРІРµСЂРєР° РґР°РЅРЅС‹С…;
{echo '<a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id_razdel1">1. РЈС‡РµР±РЅР°СЏ СЂР°Р±РѕС‚Р°</a>';}
if  (isset($_GET['id_razdel1']))
{
// РѕРїРёСЃР°РЅРёРµ С‚Р°Р±Р»РёС†С‹ РЈС‡РµР±РЅР°СЏ СЂР°Р±РѕС‚Р°
echo'
 <table border=1 class=indplan>
 <tr class="indplan">
  <th rowspan="3">РќР°Р·РІР°РЅРёРµ СЂР°Р±РѕС‚</td>
  <th rowspan="3"><font size=-1 TITLE="Р·РЅР°С‡РµРЅРёСЏ Р·Р°РЅРѕСЃСЏС‚СЃСЏ РёР· РЅР°РіСЂСѓР·РєРё Р°РІС‚РѕРјР°С‚РёС‡РµСЃРєРё" style=cursor:help>РџР»Р°РЅ
  <br>РЅР° СЃРµ-<br>РјРµСЃС‚СЂ</font></td>

  <th colspan="7" align="center">РћСЃРµРЅРЅРёР№</td>
  <th rowspan="3"><font size=-1 TITLE="Р·РЅР°С‡РµРЅРёСЏ Р·Р°РЅРѕСЃСЏС‚СЃСЏ РёР· РЅР°РіСЂСѓР·РєРё Р°РІС‚РѕРјР°С‚РёС‡РµСЃРєРё" style=cursor:help>РџР»Р°РЅ
  <br>РЅР° СЃРµ-<br>РјРµСЃС‚СЂ</font></td>
  <th colspan="8" align="center">Р’РµСЃРµРЅРЅРёР№</td>
</tr>
<tr class=indplan>
 <th colspan="7" align="center">Р¤Р°РєС‚РёС‡РµСЃРєРё РІС‹РїРѕР»РЅРµРЅРѕ</td>  
 <th colspan="8" align="center">Р¤Р°РєС‚РёС‡РµСЃРєРё РІС‹РїРѕР»РЅРµРЅРѕ</td>
 </tr>
 <tr class=indplan>
 <th>СЃРµРЅС‚</td>
 <th>РѕРєС‚</td>
 <th>РЅРѕСЏР±</td>
 <th>РґРµРє</td>
 <th>СЏРЅРІ</td>
 <th>РёС‚РѕРіРѕ</td>
 <th>РїРѕ СЂР°СЃРїРёСЃР°РЅРёСЋ</th>
 <th>С„РµРІСЂ</td>
 <th>РјР°СЂС‚</td>
 <th>Р°РїСЂ</td>
 <th>РјР°Р№</td>
 <th>РёСЋРЅСЊ</td>
 <th>РёСЋР»СЊ</td>
 <th>РёС‚РѕРіРѕ</td>
 <th>РїРѕ СЂР°СЃРїРёСЃР°РЅРёСЋ</th>
 </tr>';

$i=1; // РїСЂРёСЃРІРѕРµРЅРёРµ РїРµСЂРµРјРµРЅРЅРѕР№ СЃС‚РѕР»Р±С†Сѓ;
While ($name=mysql_fetch_row($uch_rab)) { // РїСЂРёСЃРІРѕРµРЅРёРµ РїРµСЂРµРјРµРЅРЅРѕР№ С‚Р°Р±Р»РёС†С‹ СѓС‡РµР±РЅР°СЏ СЂР°Р±РѕС‚Р°;
	$n=$name[0];
	
	echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF">';// С†РІРµС‚ С‚Р°Р±Р»РёС†С‹;
	echo '<td>'.$name[1].'</td>';
 	echo '<td>'.$plan_1[($n-1)].'</td>'; // РґРѕР±Р°РІР»РµРЅРёРµ РїРµСЂРІРѕРіРѕ СЃС‚РѕР»Р±С†Р°;
	
// РІРІРѕРґ РґР°РЅРЅС‹С… РёР· СѓС‡РµР±РЅРѕРіРѕ РїР»Р°РЅР°;	
	$summa=0;$i=1;
	while (($i)!=6) {
		$sqlm="
			SELECT
				(rab_1) as rab_1,// Р›РµРєС†РёРё;
				(rab_2) as rab_2,// РџСЂР°РєС‚РёС‡РµСЃРєРёРµ Р·Р°РЅСЏС‚РёСЏ;
				(rab_3) as rab_3,// Р»Р°Р±РѕСЂР°С‚РѕСЂРЅС‹Рµ СЂР°Р±РѕС‚С‹;
				rab_4,// РљРѕРЅСЃСѓР»СЊС‚Р°С†РёРё;
				(rab_5) as rab_5,// РљСѓСЂСЃРѕРІРѕРµ РїСЂРѕРµРєС‚РёСЂРѕРІР°РЅРёРµ;
				(rab_6) as rab_6,// Р—Р°С‡РµС‚;
				(rab_7) as rab_7,// Р­РєР·Р°РјРµРЅ;
				rab_8,// РџСЂРѕРёР·РІРѕРґСЃС‚РІРµРЅРЅР°СЏ Рё РїСЂРµРґРґРёРїР»РѕРјРЅР°СЏ РїСЂР°С‚РёРєР°;
				rab_9,// Р РµС†РµРЅР·РёСЂРѕРІР°РЅРёРµ РєСѓСЂСЃРѕРІС‹С… РїСЂРѕРµРєС‚РѕРІ Рё РґРёРїР»РѕРјРЅС‹С… СЂР°Р±РѕС‚;
				rab_10,// Р”РёРїР»РѕРјРЅРѕРµ РїСЂРѕРµРєС‚РёСЂРѕРІР°РЅРёРµ;
				rab_11,//Р“РђРљ;
				rab_12,// РџРѕСЃРµС‰РµРЅРёРµ Р·Р°РЅСЏС‚РёР№;
				rab_13,//Р СѓРєРѕРІРѕРґСЃС‚РІРѕ  Р°СЃРїРёСЂР°РЅС‚Р°РјРё;
				rab_14,// Р—Р°РЅСЏС‚РёСЏ СЃ Р°СЃРїРёСЂР°РЅС‚Р°РјРё;
				rab_15// Р Р“Р 
			FROM fact // РґР°РЅРЅС‹Рµ РёР· СЂР°СЃРїРёСЃР°РЅРёСЏ;
			WHERE id_month=".$i." and id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year'].""; // РїСЂРѕРІРµСЂРєР° СЃСѓС‰РµСЃС‚РІРѕРІР°РЅРёСЏ РґР°РЅРЅС‹С…; 
		$mysqlm=mysql_query($sqlm);
		$res=mysql_fetch_array($mysqlm);
		
		echo '<td>'.round($res['rab_'.$n.''], 0).'</td>'; // РѕРєСЂСѓРіР»РµРЅРёРµ РґР°РЅРЅС‹С… РїРѕ РІРёРґР°Рј Р·Р°РЅСЏС‚РёР№;
		$i++; // СѓРІРµР»РёС‡РµРЅРёРµ СЃС‚РѕР»Р±С†Р° РЅР° 1;
		$summa=$summa+$res['rab_'.$n.'']; // РїРѕРґСЃС‡РµС‚ СЃСѓРјРјС‹ РїРѕ РІРёРґР°Рј Р·Р°РЅСЏС‚РёР№;
	}

	// РІС‹РІРѕРґ РёС‚РѕРіРѕ РїРѕ С‡Р°СЃС‚Рё РіРѕРґР°
	echo '<td>'.round($summa, 0).'</td>';
// РґРѕР±Р°РІР»РµРЅРёРµ Р·Р°РїСЂРѕСЃР° РїРѕ РІРёРґР°Рј Р·Р°РЅСЏС‚РёР№;	
	$sql_1 = "
		select ";
	// РїРѕРґСЃС‡РµС‚ РєРѕР»РёС‡РµСЃС‚РІР° С‡Р°СЃРѕРІ РїРѕ Р»Р°Р±РѕСЂР°С‚РѕСЂРЅС‹Рј Р·Р°РЅСЏС‚РёСЏРј;
	switch ($n) {
		case '1' : {
			$sql_1 .= "(count(*) * 4) as cnt"; 
			break;
		}
// РїРѕРґСЃС‡РµС‚ РєРѕР»РёС‡РµСЃС‚РІР° С‡Р°СЃРѕРІ РїРѕ РїСЂР°РєС‚РёС‡РµСЃРєРёРј Р·Р°РЅСЏС‚РёСЏРј;		
		case '2' : {
			$sql_1 .= "(count(*) * 2) as cnt";
			break;
		}
// РїРѕРґСЃС‡РµС‚ РєРѕР»РёС‡РµСЃС‚РІР° С‡Р°СЃРѕРІ РїРѕ Р»РµРєС†РёСЏРј;		
		case '3' : {
			$sql_1 .= "(count(*) * 2) as cnt";
			break;
		}
// РµСЃР»Рё РЅРµС‚ РґР°РЅРЅС‹С…, РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ СЃС‚Р°РІРёС‚ 0;		
		default: {
			$sql_1 .= "count(*) as cnt";
			break;
		}
	}		
	$sql_1 .= "
		from
			`time`
		where
			(id = ".$_GET['id_kadri']." and year = ".$_GET['id_year']." and month = 1 
	";// РїСЂРѕРІРµСЂРєР° РґР°РЅРЅС‹С… Рѕ РЅР°Р»РёС‡РёРё;
// РјРЅРѕР¶РµСЃС‚РІРµРЅРЅС‹Р№ РІС‹Р±РѕСЂ РїРѕ РІРёРґР°Рј Р·Р°РЅСЏС‚РёР№;	
	switch ($n) {
		case '1': {
			$sql_1 .= " and kind = 3)"; // РїСЂРёСЃРІРѕРµРЅРёРµ С‚РёРї Р·Р°РЅСЏС‚РёСЏ Р»РµРєС†РёСЏ;
			break; 
		}	
		case '2': {
			$sql_1 .= " and kind = 2)";// РїСЂРёСЃРІРѕРµРЅРёРµ С‚РёРї Р·Р°РЅСЏС‚РёСЏ РїСЂР°РєС‚РёС‡РµСЃРєРёРµ Р·Р°РЅСЏС‚РёСЏ;
			break;
		}	
		case '3': {
			$sql_1 .= " and kind = 1)";// РїСЂРёСЃРІРѕРµРЅРёРµ С‚РёРї Р·Р°РЅСЏС‚РёСЏ Р»Р°Р±РѕСЂР°С‚РѕСЂРЅР°СЏ СЂР°Р±РѕС‚Р°;
			break;
		}		
		default: {                            // РµСЃР»Рё РґР°РЅРЅС‹Рµ РѕС‚СЃСѓС‚СЃС‚РІСѓСЋС‚, С‚Рѕ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ СЃС‚Р°РІРёС‚СЃСЏ 0;
			$sql_1 .= " and kind = -1)";
			break; 
		}
	}
	//echo $sql_1;
	$time_res = mysql_query($sql_1) or die (mysql_error());// РµСЃР»Рё РґР°РЅРЅС‹С… РЅРµ С…РІР°С‚Р°РµС‚, Р·Р°РїСЂРѕСЃ РѕС‚СЃСѓС‚СЃС‚РІСѓРµС‚;
	
	echo '<td>'.mysql_result($time_res, 'cnt', 0).'</td>';	
	echo '<td>'.$plan_2[($n-1)].'</td>'; // РґРѕР±Р°РІР»РµРЅРёРµ РІС‚РѕСЂРѕРіРѕ СЃС‚РѕР»Р±С†Р°;
	$summa=0;
	// РІРІРѕРґ РґР°РЅРЅС‹С… РёР· СѓС‡РµР±РЅРѕРіРѕ РїР»Р°РЅР°;
	while (($i)!=12) {         // РёСЃС‚РёРЅР° РµСЃР»Рё СЃС‚РѕР»Р±РµС† РЅРµ СЂР°РІРµРЅ 12;
		$sqlm="
			SELECT
				((rab_1) as rab_1,// Р›РµРєС†РёРё;
				(rab_2) as rab_2,// РџСЂР°РєС‚РёС‡РµСЃРєРёРµ Р·Р°РЅСЏС‚РёСЏ;
				(rab_3) as rab_3,// Р»Р°Р±РѕСЂР°С‚РѕСЂРЅС‹Рµ СЂР°Р±РѕС‚С‹;
				rab_4,// РљРѕРЅСЃСѓР»СЊС‚Р°С†РёРё;
				(rab_5) as rab_5,// РљСѓСЂСЃРѕРІРѕРµ РїСЂРѕРµРєС‚РёСЂРѕРІР°РЅРёРµ;
				(rab_6) as rab_6,// Р—Р°С‡РµС‚;
				(rab_7) as rab_7,// Р­РєР·Р°РјРµРЅ;
				rab_8,// РџСЂРѕРёР·РІРѕРґСЃС‚РІРµРЅРЅР°СЏ Рё РїСЂРµРґРґРёРїР»РѕРјРЅР°СЏ РїСЂР°С‚РёРєР°;
				rab_9,// Р РµС†РµРЅР·РёСЂРѕРІР°РЅРёРµ РєСѓСЂСЃРѕРІС‹С… РїСЂРѕРµРєС‚РѕРІ Рё РґРёРїР»РѕРјРЅС‹С… СЂР°Р±РѕС‚;
				rab_10,// Р”РёРїР»РѕРјРЅРѕРµ РїСЂРѕРµРєС‚РёСЂРѕРІР°РЅРёРµ;
				rab_11,//Р“РђРљ;
				rab_12,// РџРѕСЃРµС‰РµРЅРёРµ Р·Р°РЅСЏС‚РёР№;
				rab_13,//Р СѓРєРѕРІРѕРґСЃС‚РІРѕ  Р°СЃРїРёСЂР°РЅС‚Р°РјРё;
				rab_14,// Р—Р°РЅСЏС‚РёСЏ СЃ Р°СЃРїРёСЂР°РЅС‚Р°РјРё;
				rab_15// Р Р“Р 
			FROM fact // РґР°РЅРЅС‹Рµ РёР· СЂР°СЃРїРёСЃР°РЅРёСЏ;
			WHERE id_month=".$i." and id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year'].""; // РїСЂРѕРІРµСЂРєР° РґР°РЅРЅС‹С… Рѕ РЅР°Р»РёС‡РёРё РїРѕ 1 СЃРµРјРµСЃС‚СЂСѓ;
		$mysqlm=mysql_query($sqlm);
		$res=mysql_fetch_array($mysqlm);
		echo '<td>'.round($res['rab_'.$n.''], 0).'</td>'; // РѕРєСЂСѓРіР»РµРЅРёРµ РґР°РЅРЅС‹С… РїРѕ РІРёРґР°Рј Р·Р°РЅСЏС‚РёР№;
		$i++;                               // СѓРІРµР»РёС‡РµРЅРёРµ СЃС‚РѕР»Р±С†Р° РЅР° 1;
		$summa=$summa+$res['rab_'.$n.''];  // РїРѕРґСЃС‡РµС‚ СЃСѓРјРјС‹ РїРѕ РІРёРґР°Рј Р·Р°РЅСЏС‚РёР№;
	}
	echo '<td>'.round($summa, 0).'</td>'; // РѕРєСЂСѓРіР»РµРЅРёРµ РґР°РЅРЅС‹С… РїРѕ РІРёРґР°Рј Р·Р°РЅСЏС‚РёР№;
	// С„РѕСЂРјРёСЂРѕРІР°РЅРёРµ Р·Р°РїСЂРѕСЃР°;
	$sql_1 = " 
		select ";	
	switch ($n) {                                 // РјРЅРѕР¶РµСЃС‚РІРµРЅРЅС‹Р№ РІС‹Р±РѕСЂ РїРѕ РІРёРґР°Рј Р·Р°РЅСЏС‚РёР№;
		case '1' : {
			$sql_1 .= "(count(*) * 4) as cnt";    // РїСЂРёСЃРІРѕРµРЅРёРµ С‚РёРї Р·Р°РЅСЏС‚РёСЏ Р»РµРєС†РёСЏ;
			break;
		}		
		case '2' : {
			$sql_1 .= "(count(*) * 2) as cnt";   // РїСЂРёСЃРІРѕРµРЅРёРµ С‚РёРї Р·Р°РЅСЏС‚РёСЏ РїСЂР°РєС‚РёС‡РµСЃРєРёРµ Р·Р°РЅСЏС‚РёСЏ;
			break;
		}		
		case '3' : {
			$sql_1 .= "(count(*) * 2) as cnt";   // РїСЂРёСЃРІРѕРµРЅРёРµ С‚РёРї Р·Р°РЅСЏС‚РёСЏ Р»Р°Р±РѕСЂР°С‚РѕСЂРЅР°СЏ СЂР°Р±РѕС‚Р°;
			break;
		}	
		default: {
			$sql_1 .= "count(*) as cnt";      // РµСЃР»Рё РґР°РЅРЅС‹Рµ РѕС‚СЃСѓС‚СЃС‚РІСѓСЋС‚, С‚Рѕ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ СЃС‚Р°РІРёС‚СЃСЏ 0;
			break;
		}
	}		
	$sql_1 .= "
		from
			`time`
		where
			(id = ".$_GET['id_kadri']." and year = ".$_GET['id_year']." and month = 2
	";	  // РїСЂРѕРІРµСЂРєР° РґР°РЅРЅС‹С… Рѕ РЅР°Р»РёС‡РёРё РїРѕ 2 СЃРµРјРµСЃС‚СЂСѓ; 
	
		// РјРЅРѕР¶РµСЃС‚РІРµРЅРЅС‹Р№ РІС‹Р±РѕСЂ РїРѕ РІРёРґР°Рј Р·Р°РЅСЏС‚РёР№;	
	switch ($n) {
		case '1': {
			$sql_1 .= " and kind = 3)"; // РїСЂРёСЃРІРѕРµРЅРёРµ С‚РёРї Р·Р°РЅСЏС‚РёСЏ Р»РµРєС†РёСЏ;
			break; 
		}	
		case '2': {
			$sql_1 .= " and kind = 2)";// РїСЂРёСЃРІРѕРµРЅРёРµ С‚РёРї Р·Р°РЅСЏС‚РёСЏ РїСЂР°РєС‚РёС‡РµСЃРєРёРµ Р·Р°РЅСЏС‚РёСЏ;
			break;
		}	
		case '3': {
			$sql_1 .= " and kind = 1)";// РїСЂРёСЃРІРѕРµРЅРёРµ С‚РёРї Р·Р°РЅСЏС‚РёСЏ Р»Р°Р±РѕСЂР°С‚РѕСЂРЅР°СЏ СЂР°Р±РѕС‚Р°;
			break;
		}		
		default: {                            // РµСЃР»Рё РґР°РЅРЅС‹Рµ РѕС‚СЃСѓС‚СЃС‚РІСѓСЋС‚, С‚Рѕ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ СЃС‚Р°РІРёС‚СЃСЏ 0;
			$sql_1 .= " and kind = -1)";
			break; 
		}
	}
	//echo $sql_1;
	$time_res = mysql_query($sql_1) or die (mysql_error()); // РµСЃР»Рё РґР°РЅРЅС‹С… РЅРµ С…РІР°С‚Р°РµС‚, Р·Р°РїСЂРѕСЃ РѕС‚СЃСѓС‚СЃС‚РІСѓРµС‚;
	
	echo '<td>'.mysql_result($time_res, 'cnt', 0).'</td>'; // РІС‹РІРѕРґ СЂРµР·СѓР»СЊС‚Р°С‚Р°;
	
	echo '</tr>';
}
// РџРѕРґСЃС‡РµС‚ РїРѕ СЃРµРјРµСЃС‚СЂР°Рј РїРѕ РєР°Р¶РґРѕРјСѓ РІРёРґСѓ Р·Р°РЅСЏС‚РёСЏ;
// РїСЂРѕРІРµСЂРєР° Рѕ РЅР°Р»РёС‡РёРё РїСЂРµРїРѕРґР°РІР°С‚РµР»СЏ Рё СѓС‡РµР±РЅРѕРіРѕ РіРѕРґР°;
	$sql	= "
		select                                       
			(rab_1 + rab_2 + rab_3 + rab_4 +
			 rab_5 + rab_6 + rab_7 + rab_8 +
			 rab_9 + rab_10 + rab_11 + rab_12 +
			 rab_13 + rab_14 + rab_15) as total
		from
			fact                               
		where
			id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']." 
		group by                               
			id_month
	";	//РіСЂСѓРїРїРёСЂРѕРІРєР° РїРѕ СЃРµРјРµСЃС‚СЂР°Рј;
	$res	= mysql_query($sql) or die (mysql_error()); // РµСЃР»Рё РґР°РЅРЅС‹Рµ РѕС‚СЃСѓС‚СЃС‚РІСѓСЋС‚, С‚Рѕ Р·Р°РїСЂРѕСЃ РЅРµ РѕСЃСѓС‰РµСЃС‚РІР»СЏРµС‚СЃСЏ;
	//$res	= mysql_fetch_array($res);
	$i = 0;                    // РїСЂРёСЃРІР°РёРІР°РµРј СѓС‡РµР±РЅРѕР№ СЂР°Р±РѕС‚Рµ 0;  
	echo '<tr>';                // РІРІРѕРґ СЃС‚СЂРѕРєРё Р’СЃРµРіРѕ;
	echo '<td><b>Р’СЃРµРіРѕ:</b></td>';	
	while ($i <= 16) {                // РёСЃС‚РёРЅР°, РµСЃР»Рё СЃС‚РѕР»Р±РµС† РјРµРЅСЊС€Рµ Р»РёР±Рѕ СЂР°РІРЅРѕ16;            
		$i++; 	                       // СѓРІРµР»РёС‡РµРЅРёРµ СЃС‚РѕР±С†Р° РЅР° 1;                    	
		if (($i != 1) && ($i != 7) && ($i != 8) && ($i != 9)) {   // РёСЃС‚РёРЅР°, РµСЃР»Рё СЃС‚РѕР»Р±РµС† РЅРµ СЂР°РІРµРЅ 1, РЅРµ СЂР°РІРµРЅ 7,РЅРµ СЂР°РІРµРЅ 8,РЅРµ СЂР°РІРµРЅ 9;
			$res1 = mysql_fetch_row($res);
			echo '<td><b>'.$res1[0].'</b></td>';
		} else {
			echo '<td></td>';
		}
	}
	echo '</tr>';
	
	//echo '<pre>';
	//echo($sql);

	echo '</table>';
	
	//echo '<pre>';
	//print_r($plan_2);  // РІС‹РІРѕРґ РґР°РЅРЅС‹С…;
	//echo '</pre>';
	
	if (!isset($_GET['save']) && !isset($_GET['print']))   // РїСЂРѕРІРµСЂРєР° РЅР° РЅР°Р»РёС‡РёРµ РґР°РЅРЅС‹С…;
{echo '<a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id_razdel_red">Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ СѓС‡РµР±РЅСѓСЋ СЂР°Р±РѕС‚Сѓ</a>';}
	}
//------------------Р РµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ "1. РЈС‡РµР±РЅР°СЏ СЂР°Р±РѕС‚Р°"-------------------------
if (isset($_GET['id_razdel_red']))
{
 //--------------Р’СЃС‚Р°РІРєР° РґР°РЅРЅС‹С… --------------------------
 if (isset($_POST['main']))
 {
 echo "<h3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ СЃРѕС…СЂР°РЅРµРЅС‹.</h3>";
   }
echo'<form method="POST" action="">
 <table border=1 class=indplan>
 <tr class=indplan>
  <th rowspan="3">РќР°Р·РІР°РЅРёРµ СЂР°Р±РѕС‚</td>
  <th rowspan="3"><font size=-1 TITLE="Р·РЅР°С‡РµРЅРёСЏ Р·Р°РЅРѕСЃСЏС‚СЃСЏ РёР· РЅР°РіСЂСѓР·РєРё Р°РІС‚РѕРјР°С‚РёС‡РµСЃРєРё" style=cursor:help>РџР»Р°РЅ
  <br>РЅР° СЃРµ-<br>РјРµСЃС‚СЂ</font></td>
  <th colspan="6" align="center">РћСЃРµРЅРЅРёР№</td>
  <th rowspan="3"><font size=-1 TITLE="Р·РЅР°С‡РµРЅРёСЏ Р·Р°РЅРѕСЃСЏС‚СЃСЏ РёР· РЅР°РіСЂСѓР·РєРё Р°РІС‚РѕРјР°С‚РёС‡РµСЃРєРё" style=cursor:help>РџР»Р°РЅ
  <br>РЅР° СЃРµ-<br>РјРµСЃС‚СЂ</font></td>
  <th colspan="7" align="center">Р’РµСЃРµРЅРЅРёР№</td>
</tr>
<tr class=indplan>
 <th colspan="6" align="center">Р¤Р°РєС‚РёС‡РµСЃРєРё РІС‹РїРѕР»РЅРµРЅРѕ</td>
 <th colspan="7" align="center">Р¤Р°РєС‚РёС‡РµСЃРєРё РІС‹РїРѕР»РЅРµРЅРѕ</td>
 </tr>
 <tr class=indplan>
 <th>СЃРµРЅС‚</td>
 <th>РѕРєС‚</td>
 <th>РЅРѕСЏР±</td>
 <th>РґРµРє</td>
 <th>СЏРЅРІ</td>
 <th>РёС‚РѕРіРѕ</td>
 <th>С„РµРІСЂ</td>
 <th>РјР°СЂС‚</td>
 <th>Р°РїСЂ</td>
 <th>РјР°Р№</td>
 <th>РёСЋРЅСЊ</td>
 <th>РёСЋР»СЊ</td>
 <th>РёС‚РѕРіРѕ</td>
 </tr>';
  $i=1;                                                          // РІРІРѕРґ РїРµСЂРµРјРµРЅРЅРѕР№;
 While ($name=mysql_fetch_row($spravochnik_uch_rab_sel_fact))    // РїСЂРёСЃРІРѕРµРЅРёРµ РёРјРµРЅРё РїРµСЂРµРјРµРЅРЅРѕР№ СЃРїСЂР°РІРѕС‡РЅРёРє СѓС‡РµР±РЅРѕР№ СЂР°Р±РѕС‚С‹;
 {
 $n=$name[0];
 	echo'<tr><td>'.$name[1].'</td>
 	<td>'.$resp1['rab_'.$n.''].'</td>';
 $summa=0;$i=1;
while (($i)!==6)  // РёСЃС‚РёРЅР°, РµСЃР»Рё СЃС‚РѕР»Р±РµС† СЂР°РІРµРЅ 6;
{
// Р·Р°РїСЂРѕСЃ РЅР° РІС‹РІРѕРґ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЏ, СЃРµРјРµСЃС‚СЂР° Рё СѓС‡РµР±РЅРѕРіРѕ РіРѕРґР°;
$sqlm="SELECT * FROM fact WHERE id_month=".$i." and id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
  $mysqlm=mysql_query($sqlm);
  $res=mysql_fetch_array($mysqlm);
echo '<td><input type="text" name="id'.$name[0].'m'.$i.'" value="'.$res['rab_'.$n.''].'" size="4"></td>';
 $i++;    // 
 $summa=$summa+$res['rab_'.$n.''];
}

echo '
<td>'.$summa.'</td>';
echo'<td>'.$resp2['rab_'.$n.''].'</td>';

$summa=0;
while (($i)!==12)
{
$sqlm="SELECT * FROM fact WHERE id_month=".$i." and id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
  $mysqlm=mysql_query($sqlm);
  $res=mysql_fetch_array($mysqlm);
echo '<td><input type="text" name="id'.$name[0].'m'.$i.'" value="'.$res['rab_'.$n.''].'" size="4"></td>';
 $i++;                                     //СѓРІРµР»РёС‡РµРЅРёРµ СЃС‚РѕР»Р±С†Р° РЅР° 1;
 $summa=$summa+$res['rab_'.$n.''];         // РїРѕРґСЃС‡РµС‚ СЃСѓРјРјС‹;
}
echo '
<td>'.$summa.'</td></tr>';
}
echo'</table><br><input type="submit" name="main" value="РЎРѕС…СЂР°РЅРёС‚СЊ"></form>';
}
 //---------------------------------------------Р Р°Р·РґРµР» 2 РЈС‡РµР±РЅРѕ-РѕСЂРіР°РЅРёР·Р°С†РёРѕРЅРЅР°СЏ СЂР°Р±РѕС‚Р°-------------------------
if (!isset($_GET['save']) && !isset($_GET['print']))   // РїСЂРѕРІРµСЂРєР° РґР°РЅРЅС‹С…;
{echo '<p><a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id_razdel2">2. РЈС‡РµР±РЅРѕ - Рё РѕСЂРіР°РЅРёР·Р°С†РёРѕРЅРЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєР°СЏ СЂР°Р±РѕС‚Р°</a>';}
if  (isset($_GET['id_razdel2']))
{
//---------------------------------------РІС‹РІРѕРґ С‚Р°Р±Р»РёС†С‹----------------------------------------
if (!$check)
echo '<h3>РќРµС‚ РґР°РЅРЅС‹С…</h3>';
else
{
echo '<table border=1 class=indplan>
<tr class=indplan><th>в„–</td><th>РќР°РёРјРµРЅРѕРІР°РЅРёРµ СЂР°Р±РѕС‚С‹</td><th>РџР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ</td><th>РЎСЂРѕРє РІС‹РїРѕР»РЅРµРЅРёСЏ</td>
<th>Р’РёРґ РѕС‚С‡С‘С‚РЅРѕСЃС‚Рё</td><th>РћС‚РјРµС‚РєР° Рѕ РІС‹РїРѕР»РЅРµРЅРёРё</td><th>РџСЂРёРјРµС‡Р°РЅРёРµ</td><th></td></tr>';
$i=1;
while ($line=mysql_fetch_array($uch_org_rab))
{
$sql2="SELECT name FROM spravochnik_vidov_rabot WHERE id=".$line['id_vidov_rabot']."";   // Р·Р°РїСЂРѕСЃ РїРѕ РІРёРґР°Рј СЂР°Р±РѕС‚;
$mysql2=mysql_query($sql2);
$res1=mysql_fetch_array($mysql2);
if ($line['otm']=='РЅРµС‚')
{
if($line['srok_vipolneniya']<date('Y-m-d'))
echo '<tr bgcolor=red onmouseover=this.style.background="tomato" onmouseout=this.style.background="red" title="Р”Р°РЅРЅР°СЏ СЂР°Р±РѕС‚Р° Р±С‹Р»Р° РїСЂРѕСЃСЂРѕС‡РµРЅР°">';
else
echo '<tr>';
}
else
echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF">';    // РѕС„РѕСЂРјР»РµРЅРёРµ;
echo '<td>'.$i.'</td><td>'.$res1[0].'</td><td>'.$line['kol_vo_plan'].'</td><td>'.f_ri(DateTimeCustomConvert($line['srok_vipolneniya'],'d','mysql2rus')).'</td>
<td>'.$line['vid_otch'].'</td><td>'.$line['otm'].'</td><td>'.$line['prim'].'</td>'; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo '<td><a href="javascript:del_confirm_act(\''.f_ro($line['id']).'\',\''.str_replace(" ","_",f_ro($res1[0])).'\');"><img src="'.$files_path.'images/todelete.png" alt="РЈРґР°Р»РёС‚СЊ" title="РЈРґР°Р»РёС‚СЊ"></a><p><a href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id='.$line['id'].'&update"><img src="'.$files_path.'images/toupdate.png" alt="Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ" title="Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ"></a></td></tr>';}
	$i++;
}
echo '</table>';
}
//------------------------------РџСЂРѕРІРµСЂРєР° РЅР° РїСѓСЃС‚С‹Рµ РїРѕР»СЏ РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё----------------
if ((isset($_POST['update'])) and !($empt))    // РґР°РЅРЅС‹Рµ РїРµСЂРµРґР°СЋС‚СЃСЏ С‡РµСЂРµР· СЃС‚Р°РЅРґР°СЂС‚РЅС‹Р№ РїРѕС‚РѕРє РґР°РЅРЅС‹С…;
 {
 echo'<h3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ РёР·РјРµРЅРµРЅС‹.</h3>';
  }
    if ($empt)
    {
    echo '<h3>Р’С‹ Р·Р°РїРѕР»РЅРёР»Рё РЅРµ РІСЃРµ РїРѕР»СЏ</h3>';
     }
//------------------------------РџСЂРѕРІРµСЂРєР° РЅР° РїСѓСЃС‚С‹Рµ РїРѕР»СЏ РїСЂРё Р”РѕР±Р°РІР»РµРЅРёРё----------------
if ((isset($_POST['add'])) and !($empt_add))   //РґР°РЅРЅС‹Рµ РїРµСЂРµРґР°СЋС‚СЃСЏ С‡РµСЂРµР· СЃС‚Р°РЅРґР°СЂС‚РЅС‹Р№ РїРѕС‚РѕРє РґР°РЅРЅС‹С…; 
 {
 echo'<h3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ РґРѕР±Р°РІР»РµРЅС‹.</h3>';
  }
    if ($empt_add)
    {
    echo '<h3>Р’С‹ Р·Р°РїРѕР»РЅРёР»Рё РЅРµ РІСЃРµ РїРѕР»СЏ</h3>';
     }
     if($duplicat)
     {
     echo '<h3>РЈС‡РµР±РЅРѕ- Рё РѕСЂРіР°РЅРёР·Р°С†РёРѕРЅРЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєР°СЏ СЂР°Р±РѕС‚Р° СЃ С‚Р°РєРёРј РЅР°Р·РІР°РЅРёРµРј СѓР¶Рµ СЃСѓС‰РµСЃС‚РІСѓРµС‚,
     РїСЂРѕРІРµСЂСЊС‚Рµ С†РµР»РµСЃРѕРѕР±СЂР°Р·РЅРѕСЃС‚СЊ РІРІРµРґРµРЅРёСЏ РґР°РЅРЅС‹С….</h3>';
     }
     if (!isset($_GET['save']) && !isset($_GET['print']))   // РїСЂРѕРІРµСЂРєР° РЅР° СЃРѕРѕС‚РІРµС‚СЃС‚РІРёРµ РґР°РЅРЅС‹С…;
{echo '<a  class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&add">Р”РѕР±Р°РІРёС‚СЊ СЂР°Р±РѕС‚Сѓ</a>';}

if(isset($_GET['delete']))
    	{
    	echo'<h3>Р—Р°РїРёСЃСЊ СѓСЃРїРµС€РЅРѕ СѓРґР°Р»РµРЅР°.</h3>';
    	}
}
//---------------------------------------------Р РµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ РЈС‡РµР±РЅРѕ-РѕСЂРіР°РЅРёР·Р°С†РёРѕРЅРЅРѕР№ СЂР°Р±РѕС‚С‹ ------------------------
if (isset($_GET['update']))
{
echo '<form method=POST action="?id_razdel2&id_kadri='.$_GET["id_kadri"].'&id_year='.$_GET["id_year"].'">  // РґР°РЅРЅС‹Рµ РїРµСЂРµРґР°СЋС‚СЃСЏ С‡РµСЂРµР· СЃС‚Р°РЅРґР°СЂС‚РЅС‹Р№ РїРѕС‚РѕРє РІРІРѕРґР°;
<table border=1 class="cent">';
$otmetka="SELECT id,name FROM otmetka";   // РІС‹Р±РёСЂР°РµС‚СЃСЏ РїРѕ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЋ РёР· С‚Р°Р±Р»РёС†С‹ otmetka;
$otm_sel=mysql_query($otmetka);
$spravochnik="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=2 ORDER BY id ";  // С„РѕСЂРјРёСЂСѓРµС‚СЃСЏ РїРѕ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЋ,РЅРѕСЂРјР° РІСЂРµРјРµРЅРё РёР· СЃРїСЂР°РІРѕС‡РЅРёРєР° РІРёРґРѕРІ СЂР°Р±РѕС‚ СЂР°Р·РґРµР» 2;
$spr_vid=mysql_query($spravochnik);
echo '<input type="hidden" name="a" value="'.$line['id'].'"><tr><td colspan=2><table border=1 class=indplan><caption><h4>Р’С‹Р±РµСЂРёС‚Рµ СЂР°Р±РѕС‚Сѓ</h4></caption><tr><th>РЎРїРёСЃРѕРє СѓС‡РµР±РЅРѕ- Рё РѕСЂРіР°РЅРёР·Р°С†РёРѕРЅРЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРёС… СЂР°Р±РѕС‚</td><th>РќРѕСЂРјС‹ РІСЂРµРјРµРЅРё РІ С‡Р°СЃР°С… </td><th></td></tr>';
 while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type=radio name="delete_rab" value="'.$name['id'].'" ';
  if ($name['id']==$line['id_vidov_rabot']) echo 'checked';
  echo'></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>РџР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ</td></tr><tr><td><input type="text" name="plan" value="'.$line['kol_vo_plan'].'" size="15"></td></tr>
<tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td>Р’РІРµРґРёС‚Рµ СЃСЂРѕРє РІС‹РїРѕР»РЅРµРЅРёСЏ</td></tr><tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td><input type="text" id=date_act value="'.f_ri(DateTimeCustomConvert($line['srok_vipolneniya'],'d','mysql2rus')).'" name="srok">
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",
        ifFormat       :    "%d.%m.%Y",
        showsTime      :    false,
        button         :    "f_trigger_date_act",
        singleClick    :    true,
        step           :    1
    });
	</script>
</td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РІРёРґ РѕС‚С‡С‘С‚РЅРѕСЃС‚Рё</td></tr><tr><td><input type="text" name="otch" value="'.$line['vid_otch'].'" size="30"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РѕС‚РјРµС‚РєСѓ Рѕ РІС‹РїРѕР»РЅРµРЅРёРё СЂР°Р±РѕС‚С‹</td></tr><tr><td><select name="danet">';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'" ';
if ($line['id_otmetka']==$otm_selt['id']){echo'selected';}
echo'>'.$otm_selt['name'].'</option>';
}
echo '</select></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РїСЂРёРјРµС‡Р°РЅРёРµ</td></tr><tr><td><input type="text" name="pri" value="'.$line['prim'].'" size="40"</td></tr>';

echo '</table><input type=submit name="update" value="РР·РјРµРЅРёС‚СЊ"></form>';
}
//---------------------------------------------Р’РІРѕРґ РґР°РЅРЅС‹С…--------------------------------------
if (isset($_GET['add']))  // РґР°РЅРЅС‹Рµ РїРµСЂРµРґР°СЋС‚СЃСЏ С‡РµСЂРµР· РїРµСЂРµРјРµРЅРЅРѕРµ РѕРєСЂСѓР¶РµРЅРёРµ;
{
$otmetka="SELECT id,name FROM otmetka";  // РїРѕ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЋ РёР· С‚Р°Р±Р»РёС†С‹ otmetka;
$otm_sel=mysql_query($otmetka);
$sql="SELECT * FROM uch_org_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";  // Р·Р°РїСЂРѕСЃ РёР· СѓС‡РµР±РЅРѕ-РѕСЂРіР°РЅРёР·Р°С†РёРѕРЅРЅРѕР№ СЂР°Р±РѕС‚С‹ РїРѕ РЅРѕРјРµСЂСѓ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЏ;
$uch_org_rab=mysql_query($sql);
echo '<table border=1 class="cent">';
$line=mysql_fetch_row($uch_org_rab);
echo '<form method=POST action="?id_kadri='.$_GET["id_kadri"].'&id_year='.$_GET["id_year"].'&id_razdel2">
<tr><td colspan=2><table border=1 class=indplan><caption><h4>Р’С‹Р±РµСЂРёС‚Рµ СЂР°Р±РѕС‚Сѓ</h4></caption><tr><th>РЎРїРёСЃРѕРє СѓС‡РµР±РЅРѕ- Рё РѕСЂРіР°РЅРёР·Р°С†РёРѕРЅРЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРёС… СЂР°Р±РѕС‚</td><th>РќРѕСЂРјС‹ РІСЂРµРјРµРЅРё РІ С‡Р°СЃР°С… </td><th></td></tr>';
while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type=radio name="delete_rab" value="'.$name['id'].'"></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>РџР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ</td></tr><tr><td><input type="text" name="plan"  size="15"></td></tr>
<tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td>Р’РІРµРґРёС‚Рµ СЃСЂРѕРє РІС‹РїРѕР»РЅРµРЅРёСЏ</td></tr><tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td><input type="text" id=date_act name="srok">
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",
        ifFormat       :    "%d.%m.%Y",
        showsTime      :    false,
        button         :    "f_trigger_date_act",
        singleClick    :    true,
        step           :    1
    });
	</script>
</td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РІРёРґ РѕС‚С‡С‘С‚РЅРѕСЃС‚Рё</td></tr><tr><td><input type="text" name="otch"  size="30"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РѕС‚РјРµС‚РєСѓ Рѕ РІС‹РїРѕР»РЅРµРЅРёРё СЂР°Р±РѕС‚С‹</td></tr><tr><td><select name="danet">';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'">'.$otm_selt['name'].'</option>';
}
echo '</select></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РїСЂРёРјРµС‡Р°РЅРёРµ</td></tr><tr><td><input type="text" name="pri" size="40"</td></tr>';

echo '</table><input type="submit" name="add" value="РЎРѕС…СЂР°РЅРёС‚СЊ"></form>';
}
//---------------------------------------------Р Р°Р·РґРµР» 3 РќР°СѓС‡РЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєР°СЏ СЂР°Р±РѕС‚Р°-----------------------------------
if (!isset($_GET['save']) && !isset($_GET['print']))
{echo '<p><a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id_razdel3=3">3. РќР°СѓС‡РЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєР°СЏ Рё РіРѕСЃР±СЋРґР¶РµС‚РЅР°СЏ РЅР°СѓС‡РЅРѕ-РёСЃСЃР»РµРґРѕРІР°С‚РµР»СЊСЃРєР°СЏ СЂР°Р±РѕС‚Р°</a>';}
if  (isset($_GET['id_razdel3']))
{
if (!$check)
echo '<h3>РќРµС‚ РґР°РЅРЅС‹С…</h3>';
else
//---------------------------------------РІС‹РІРѕРґ С‚Р°Р±Р»РёС†С‹----------------------------------------
 {
echo '<table border=1 class=indplan>
<tr class=indplan><th>в„–</td><th>РўРµРјР°, РЅР°РёРјРµРЅРѕРІР°РЅРёРµ СЂР°Р±РѕС‚С‹</td><th>РџР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ</td><th>РџР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ С‡Р°СЃРѕРІ</td>
<th>РЎСЂРѕРє РІС‹РїРѕР»РЅРµРЅРёСЏ</td><th>Р’РёРґ РѕС‚С‡С‘С‚РЅРѕСЃС‚Рё</td><th>РџСЂРёРјРµС‡Р°РЅРёРµ</td><th></td></tr>';
$i=1;
while ($line=mysql_fetch_row($nauch_met_rab))
{
$sql2="SELECT name FROM spravochnik_vidov_rabot WHERE id=".$line[3]."";
$mysql2=mysql_query($sql2);
$res1=mysql_fetch_row($mysql2);
echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF"><td>'.$i.'</td><td>'.$res1[0].'</td><td>'.$line[8].'</td><td>'.$line[6].'</td>
<td>'.f_ri(DateTimeCustomConvert($line[5],'d','mysql2rus')).'</td><td>'.$line[7].'</td><td>'.$line[4].'</td><td>'; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo'<a href="javascript:del_confirm_act(\''.f_ro($line[0]).'\',\''.str_replace(" ","_",f_ro($res1[0])).'\');"><img src="'.$files_path.'images/todelete.png" alt="РЈРґР°Р»РёС‚СЊ" title="РЈРґР°Р»РёС‚СЊ"></a><p><a href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id='.$line[0].'&update3"><img src="'.$files_path.'images/toupdate.png" alt="Р РµРґР°РєС‚РёСЂРІР°С‚СЊ" title="Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ"></a></td></tr>';}
	$i++;
	}
echo '</table>';
   }
  //------------------------------РџСЂРѕРІРµСЂРєР° РЅР° РїСѓСЃС‚С‹Рµ РїРѕР»СЏ РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё----------------
if ((isset($_POST['update3'])) and !($empt))  // РґР°РЅРЅС‹Рµ РїРµСЂРµРґР°СЋС‚СЃСЏ С‡РµСЂРµР· РїРµСЂРµРјРµРЅРЅРѕРµ РѕРєСЂСѓР¶РµРЅРёРµ;
 {
 echo'<h3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ РёР·РјРµРЅРµРЅС‹.</h3>';
  }
    if ($empt)
    {
    echo '<h3>Р’С‹ Р·Р°РїРѕР»РЅРёР»Рё РЅРµ РІСЃРµ РїРѕР»СЏ</h3>';
     }
//------------------------------РџСЂРѕРІРµСЂРєР° РЅР° РїСѓСЃС‚С‹Рµ РїРѕР»СЏ РїСЂРё Р”РѕР±Р°РІР»РµРЅРёРё----------------
if ((isset($_POST['add3'])) and !($empt_add))   // РґР°РЅРЅС‹Рµ РїРµСЂРµРґР°СЋС‚СЃСЏ С‡РµСЂРµР· РїРµСЂРµРјРµРЅРЅРѕРµ РѕРєСЂСѓР¶РµРЅРёРµ;
 {
 echo'<h3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ РґРѕР±Р°РІР»РµРЅС‹.</h3>';
  }
    if ($empt_add)
    {
    echo '<h3>Р’С‹ Р·Р°РїРѕР»РЅРёР»Рё РЅРµ РІСЃРµ РїРѕР»СЏ</h3>';
     }
     if ($duplicat)
    {
    echo '<h3>РќР°СѓС‡РЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєР°СЏ Рё РіРѕСЃР±СЋРґР¶РµС‚Р°СЏ РЅР°СѓС‡РЅРѕ-РёСЃСЃР»РµРґРѕРІР°С‚РµР»СЊСЃРєР°СЏ СЂР°Р±РѕС‚Р° СЃ С‚Р°РєРёРј РЅР°Р·РІР°РЅРёРµРј СѓР¶Рµ СЃСѓС‰РµСЃС‚РІСѓРµС‚,
     РїСЂРѕРІРµСЂСЊС‚Рµ С†РµР»РµСЃРѕРѕР±СЂР°Р·РЅРѕСЃС‚СЊ РІРІРµРґРµРЅРёСЏ РґР°РЅРЅС‹С….</h3>';
     }
     if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&add3">Р”РѕР±Р°РІРёС‚СЊ СЂР°Р±РѕС‚Сѓ</a>';}

   	if(isset($_GET['delete']))
    	{
    	echo'<h3>Р—Р°РїРёСЃСЊ СѓСЃРїРµС€РЅРѕ СѓРґР°Р»РµРЅР°.</h3>';
    	}
}
 //---------------------------------------------Р РµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ Рё СѓРґР°Р»РµРЅРёРµ------------------------
if (isset($_GET['update3']))
{
echo '<form method=POST action="?id_razdel3&id_kadri='.$_GET["id_kadri"].'&id_year='.$_GET["id_year"].'">
<table border=1 class="cent">';
$spravochnik="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=3 ORDER BY id ";
$spr_vid=mysql_query($spravochnik);
echo '<input type="hidden" name="a" value="'.$line[0].'"><tr><td colspan=2><table border=1 class=indplan><caption><h4>Р’С‹Р±РµСЂРёС‚Рµ СЂР°Р±РѕС‚Сѓ</h4></caption><tr><th>РЎРїРёСЃРѕРє РЅР°СѓС‡РЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРѕР№ Рё РіРѕСЃР±СЋРґР¶РµС‚РЅРѕ РЅР°СѓС‡РЅРѕ-РёСЃСЃР»РµРґРѕРІР°С‚РµР»СЊСЃРєРѕР№ СЂР°Р±РѕС‚С‹</td><th>РќРѕСЂРјС‹ РІСЂРµРјРµРЅРё РІ С‡Р°СЃР°С… </td><th></td></tr>';
 while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type=radio name="delete_rab" value="'.$name['id'].'" ';
  if ($name['id']==$line['id_vidov_rabot']) echo 'checked';
  echo'></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>Р’РІРµРґРёС‚Рµ РїР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ</td></tr><tr><td><input type="text" name="plan" value="'.$line[8].'" size="15"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РїР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ С‡Р°СЃРѕРІ</td></tr><tr><td><input type="text" value="'.$line[6].'" name="timeplan" size="15"></td></tr>
<tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td>Р’РІРµРґРёС‚Рµ СЃСЂРѕРє РІС‹РїРѕР»РЅРµРЅРёСЏ</td></tr><tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td><input type="text" id=date_act name="srok" value="'.f_ri(DateTimeCustomConvert($line[5],'d','mysql2rus')).'" size="30">
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РІРёРґ РѕС‚С‡С‘С‚РЅРѕСЃС‚Рё</td></tr><tr><td><input type="text" name="otch" value="'.$line[7].'" size="30"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РїСЂРёРјРµС‡Р°РЅРёРµ</td></tr><tr><td><input type="text" name="pri" value="'.$line[4].'" size="40"</td></tr>';

echo '</table><input type=submit name="update3" value="РР·РјРµРЅРёС‚СЊ"></form>';


   }
//---------------------------------------------Р’РІРѕРґ РґР°РЅРЅС‹С…--------------------------------------
if (isset($_GET['add3']))
{

$sql="SELECT * FROM nauch_met_rab WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
$nauch_met_rab=mysql_query($sql);
echo '<table border=1 class="cent">';
$line=mysql_fetch_row($nauch_met_rab);
$sql4="SELECT id,name FROM spravochnik_vidov_rabot WHERE id_razdel=3";
$mysql4=mysql_query($sql4);
echo '<form method=POST action="?id_kadri='.$_GET["id_kadri"].'&id_year='.$_GET["id_year"].'&id_razdel3">
<tr><td colspan=2><table border=1 class=indplan><caption><h4>Р’С‹Р±РµСЂРёС‚Рµ СЂР°Р±РѕС‚Сѓ</h4></caption><tr><th>РЎРїРёСЃРѕРє СѓС‡РµР±РЅРѕ- Рё РѕСЂРіР°РЅРёР·Р°С†РёРѕРЅРЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРёС… СЂР°Р±РѕС‚</td><th>РќРѕСЂРјС‹ РІСЂРµРјРµРЅРё РІ С‡Р°СЃР°С… </td><th></td></tr>';
while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type=radio name="delete_rab" value="'.$name['id'].'"></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>Р’РІРµРґРёС‚Рµ РїР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ</td></tr><tr><td><input type="text" name="plan"  size="15"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РїР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ С‡Р°СЃРѕРІ</td></tr><tr><td><input type="text"  name="timeplan" size="15"></td></tr>
<tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td>Р’РІРµРґРёС‚Рµ СЃСЂРѕРє РІС‹РїРѕР»РЅРµРЅРёСЏ</td></tr><tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td><input type="text" name="srok"  id=date_act size="30">
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РІРёРґ РѕС‚С‡С‘С‚РЅРѕСЃС‚Рё</td></tr><tr><td><input type="text" name="otch"  size="30"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РїСЂРёРјРµС‡Р°РЅРёРµ</td></tr><tr><td><input type="text" name="pri"  size="40"</td></tr>';

echo '</table><input type=submit name="add3" value="РЎРѕС…СЂР°РЅРёС‚СЊ"></form>';


}
//---------------------------------------------Р Р°Р·РґРµР» 4--------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id_razdel4=4">4. РЈС‡РµР±РЅРѕ-РІРѕСЃРїРёС‚Р°С‚РµР»СЊРЅР°СЏ СЂР°Р±РѕС‚Р°</a>';}
if  (isset($_GET['id_razdel4']))
{
//---------------------------------------РІС‹РІРѕРґ С‚Р°Р±Р»РёС†С‹----------------------------------------
if (!$check)
echo '<h3>РќРµС‚ РґР°РЅРЅС‹С…</h3>';
else
 {
echo '<table border=1 class=indplan>
<tr class=indplan><th>в„–</td><th>Р’РёРґС‹ СЂР°Р±РѕС‚</td><th>РџР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ С‡Р°СЃРѕРІ</td><th>РЎСЂРѕРє РІС‹РїРѕР»РЅРµРЅРёСЏ</td>
<th>РћС‚РјРµС‚РєР° Рѕ РІС‹РїРѕР»РЅРµРЅРёРё</td><th>РџСЂРёРјРµС‡Р°РЅРёРµ</td><th>РќРѕРјРµСЂ РіСЂСѓРїРїС‹</td><th></td></tr>';
$i=1;
while ($line=mysql_fetch_array($uch_vosp_rab))
{
$sql2="SELECT name FROM spravochnik_vidov_rabot WHERE id=".$line['id_vidov_rabot']."";
$mysql2=mysql_query($sql2);
$res1=mysql_fetch_array($mysql2);
if ($line['otm']=='РЅРµС‚')
{
if($line['srok_vipolneniya']<date('Y-m-d'))
echo '<tr onmouseover=this.style.background="tomato" onmouseout=this.style.background="red" bgcolor=red title="Р”Р°РЅРЅР°СЏ СЂР°Р±РѕС‚Р° Р±С‹Р»Р° РїСЂРѕСЃСЂРѕС‡РµРЅР°">';
else
echo '<tr>';
}
else
echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF">';
echo '<td>'.$i.'</td><td>'.$res1['name'].'</td><td>'.$line['kol_vo_plan'].'</td><td>'.f_ri(DateTimeCustomConvert($line['srok_vipolneniya'],'d','mysql2rus')).'</td>
<td>'.$line['otm'].'</td><td>'.$line['prim'].'</td><td>'.$line['st_group'].'</td><td>'; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo'<a href="javascript:del_confirm_act(\''.f_ro($line['id']).'\',\''.str_replace(" ","_",f_ro($res1['name'])).'\');"><img src="'.$files_path.'images/todelete.png" alt="РЈРґР°Р»РёС‚СЊ" title="РЈРґР°Р»РёС‚СЊ"></a><p><a href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id='.$line['id'].'&update4"><img src="'.$files_path.'images/toupdate.png" alt="Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ" title="Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ"></a></td></tr>';}
	$i++;
	}
echo '</table>';
   }
//------------------------------РџСЂРѕРІРµСЂРєР° РЅР° РїСѓСЃС‚С‹Рµ РїРѕР»СЏ РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё----------------
if ((isset($_POST['update4'])) and !($empt))
 {
 echo'<h3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ РёР·РјРµРЅРµРЅС‹.</h3>';
  }

    if ($empt)
    {
    echo '<h3>Р’С‹ Р·Р°РїРѕР»РЅРёР»Рё РЅРµ РІСЃРµ РїРѕР»СЏ</h3>';
     }

//------------------------------РџСЂРѕРІРµСЂРєР° РЅР° РїСѓСЃС‚С‹Рµ РїРѕР»СЏ РїСЂРё Р”РѕР±Р°РІР»РµРЅРёРё----------------
if ((isset($_POST['add4'])) and !($empt_add))
 {
 echo'<h3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ РґРѕР±Р°РІР»РµРЅС‹.</h3>';
  }

    if ($empt_add)
    {
    echo '<h3>Р’С‹ Р·Р°РїРѕР»РЅРёР»Рё РЅРµ РІСЃРµ РїРѕР»СЏ</h3>';
     }
       if ($duplicat)
    {
    echo '<h3>РЈС‡РµР±РЅРѕ-РІРѕСЃРїРёС‚Р°С‚РµР»СЊРЅР°СЏ СЂР°Р±РѕС‚Р° СЃ С‚Р°РєРёРј РЅР°Р·РІР°РЅРёРµРј СѓР¶Рµ СЃСѓС‰РµСЃС‚РІСѓРµС‚,
     РїСЂРѕРІРµСЂСЊС‚Рµ С†РµР»РµСЃРѕРѕР±СЂР°Р·РЅРѕСЃС‚СЊ РІРІРµРґРµРЅРёСЏ РґР°РЅРЅС‹С….</h3>';
     }
     if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&add4">Р”РѕР±Р°РІРёС‚СЊ СЂР°Р±РѕС‚Сѓ</a>';}
   	if(isset($_GET['delete']))
    	{
    	echo'<h3>Р—Р°РїРёСЃСЊ СѓСЃРїРµС€РЅРѕ СѓРґР°Р»РµРЅР°.</h3>';
    	}
   }
//---------------------------------------------Р РµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ РЈС‡РµР±РЅРѕ-РІРѕСЃРїРёС‚Р°С‚РµР»СЊРЅРѕР№ СЂР°Р±РѕС‚С‹ ------------------------
if (isset($_GET['update4']))
{
echo '<form method=POST action="?id_razdel4&id_kadri='.$_GET["id_kadri"].'&id_year='.$_GET["id_year"].'">
<table border=1 class="cent">';
$otmetka="SELECT id,name FROM otmetka";
$otm_sel=mysql_query($otmetka);
$study="SELECT id,name FROM study_groups";
$study_groups=mysql_query($study);
$spravochnik="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=4 ORDER BY id ";
$spr_vid=mysql_query($spravochnik);
echo '<input type="hidden" name="a" value="'.$line['id'].'"><tr><td colspan=2><table border=1 class=indplan><caption><h4>Р’С‹Р±РµСЂРёС‚Рµ СЂР°Р±РѕС‚Сѓ</h4></caption><tr><th>РЎРїРёСЃРѕРє СѓС‡РµР±РЅРѕ- Рё РѕСЂРіР°РЅРёР·Р°С†РёРѕРЅРЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРёС… СЂР°Р±РѕС‚</td><th>РќРѕСЂРјС‹ РІСЂРµРјРµРЅРё РІ С‡Р°СЃР°С… </td><th></td></tr>';
 while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type=radio name="delete_rab" value="'.$name['id'].'" ';
  if ($name['id']==$line['id_vidov_rabot']) echo 'checked';
  echo'></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>РџР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ С‡Р°СЃРѕРІ</td></tr><tr><td><input type="text" name="plan" value="'.$line['kol_vo_plan'].'" size="15"></td></tr>
<tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td>Р’РІРµРґРёС‚Рµ СЃСЂРѕРє РІС‹РїРѕР»РЅРµРЅРёСЏ</td></tr><tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td><input type="text" value="'.f_ri(DateTimeCustomConvert($line['srok_vipolneniya'],'d','mysql2rus')).'" id=date_act name="srok">
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РїСЂРёРјРµС‡Р°РЅРёРµ</td></tr><tr><td><input type="text" name="prim" value="'.$line['prim'].'" size="30"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РѕС‚РјРµС‚РєСѓ Рѕ РІС‹РїРѕР»РЅРµРЅРёРё СЂР°Р±РѕС‚С‹</td></tr><tr><td><select name="danet">';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'" ';
if ($line['id_otmetka']==$otm_selt['id']){echo'selected';}
echo'>'.$otm_selt['name'].'</option>';
}
echo'</select></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РЅРѕРјРµСЂ РіСЂСѓРїРїС‹</td></tr><tr><td><select name="group">';
while($st_gr=mysql_fetch_array($study_groups))
{
echo'
<option value="'.$st_gr['id'].'"';
if($line['id_study_groups']==$st_gr['id']){echo' selected';}
echo '>'.$st_gr['name'].'</option>';
}
echo '</select></td></tr>';

echo '</table><input type=submit name="update4" value="РР·РјРµРЅРёС‚СЊ"></form>';
   }
//---------------------------------------------Р’РІРѕРґ РґР°РЅРЅС‹С…--------------------------------------
if (isset($_GET['add4']))
{
$otmetka="SELECT id,name FROM otmetka";
$otm_sel=mysql_query($otmetka);
$study="SELECT id,name FROM study_groups";
$study_groups=mysql_query($study);
echo '<table border=1 class="cent">';
$line=mysql_fetch_row($uch_vosp_rab);
$sql4="SELECT id,name FROM spravochnik_vidov_rabot WHERE id_razdel=4";
$mysql4=mysql_query($sql4);
echo '<form method=POST action="?id_kadri='.$_GET["id_kadri"].'&id_year='.$_GET["id_year"].'&id_razdel4">
<tr><td colspan=2><table border=1 class=indplan><caption><h4>Р’С‹Р±РµСЂРёС‚Рµ СЂР°Р±РѕС‚Сѓ</h4></caption><tr><th>РЎРїРёСЃРѕРє СѓС‡РµР±РЅРѕ-РІРѕСЃРїРёС‚Р°С‚РµР»СЊРЅС‹С… СЂР°Р±РѕС‚</td><th>РќРѕСЂРјС‹ РІСЂРµРјРµРЅРё РІ С‡Р°СЃР°С… </td><th></td></tr>';
while($name=mysql_fetch_array($spr_vid))
 {
 echo '<tr><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type=radio name="delete_rab" value="'.$name['id'].'"></td></tr>';
 }
 echo '</table></td></tr>';
 echo '
<tr><td>РџР»Р°РЅРёСЂСѓРµРјРѕРµ РєРѕР»РёС‡РµСЃС‚РІРѕ С‡Р°СЃРѕРІ</td></tr><tr><td><input type="text" name="plan"  size="15"></td></tr>
<tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td>Р’РІРµРґРёС‚Рµ СЃСЂРѕРє РІС‹РїРѕР»РЅРµРЅРёСЏ</td></tr><tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td><input type="text" id=date_act name="srok">
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РїСЂРёРјРµС‡Р°РЅРёРµ</td></tr><tr><td><input type="text" name="prim" size="30"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РѕС‚РјРµС‚РєСѓ Рѕ РІС‹РїРѕР»РЅРµРЅРёРё СЂР°Р±РѕС‚С‹</td></tr><tr><td><select name="danet">';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'">'.$otm_selt['name'].'</option>';
}
echo'</select></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РЅРѕРјРµСЂ РіСЂСѓРїРїС‹</td></tr><tr><td><select name="group">';
while($st_gr=mysql_fetch_array($study_groups))
{
echo'
<option value="'.$st_gr['id'].'">'.$st_gr['name'].'</option>';
}
echo '</select>
</td></tr>';
echo '</table><input type=submit name="add4" value="РЎРѕС…СЂР°РЅРёС‚СЊ"></form>';
}
//---------------------------------------------Р Р°Р·РґРµР» 5 РџРµСЂРµС‡РµРЅСЊ РЅР°СѓС‡РЅС‹С… Рё РЅР°СѓС‡РЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРёС… СЂР°Р±РѕС‚ --------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id_razdel5">5. РџРµСЂРµС‡РµРЅСЊ РЅР°СѓС‡РЅС‹С… Рё РЅР°СѓС‡РЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРёС… СЂР°Р±РѕС‚, РІС‹РїРѕР»РЅРµРЅРЅС‹С… РїСЂРµРїРѕРґР°РІР°С‚РµР»РµРј</a>';
}
if  (isset($_GET['id_razdel5']))
{
if (!$check1 and !$check2)
echo '<h3>РќРµС‚ РґР°РЅРЅС‹С…</h3>';
else
 {
 echo '<table border=1 class=indplan>
<tr class=indplan><th>РќР°РёРјРµРЅРѕРІР°РЅРёРµ СЂР°Р±РѕС‚</td><th>РћР±СЉС‘Рј РїРµС‡Р°С‚РЅС‹С… Р»РёСЃС‚РѕРІ Рё РёР·РґР°С‚РµР»СЊСЃС‚РІРѕ</td><th></td></tr><tr><th>Рђ) РџРµС‡Р°С‚РЅС‹С…</td><th>&nbsp;</td><th>&nbsp;</td></tr>';
while ($line=mysql_fetch_array($perechen_nauch_rab_p))
{
echo "<tr onmouseover=this.style.background='#fffafa' onmouseout=this.style.background='#DFEFFF'><td>".$line['name']."</td><td>".$line['volume']."</td><td>"; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo "<a href='javascript:del_confirm_act(\"".f_ro($line['id'])."\",\"".str_replace(' ','_',f_ro($line[3]))."\");'><img src='".$files_path."images/todelete.png' alt='РЈРґР°Р»РёС‚СЊ' title='РЈРґР°Р»РёС‚СЊ'></a><p><a href='?id_kadri=".$_GET['id_kadri']."&id_year=".$_GET['id_year']."&id=".$line[0]."&update5'><img src='".$files_path."images/toupdate.png' alt='Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ' title='Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ'></a></td></tr>";}
	}
echo '<tr><th>Р‘) Р СѓРєРѕРїРёСЃРЅС‹С…</td><th>&nbsp;</td><th>&nbsp;</td></tr>';
while ($line=mysql_fetch_array($perechen_nauch_rab_r))
{
echo "<tr onmouseover=this.style.background='#fffafa' onmouseout=this.style.background='#DFEFFF'><td>".$line['name']."</td><td>".$line['volume']."</td><td>"; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo "<a href='javascript:del_confirm_act(\"".f_ro($line['id'])."\",\"".str_replace(' ','_',f_ro($line[3]))."\");'><img src='".$files_path."images/todelete.png' alt='РЈРґР°Р»РёС‚СЊ' title='РЈРґР°Р»РёС‚СЊ'></a><p><a href='?id_kadri=".$_GET['id_kadri']."&id_year=".$_GET['id_year']."&id=".$line[0]."&update5'><img src='".$files_path."images/toupdate.png' alt='Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ' title='Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ'></a></td></tr>";}
	}
echo '</table>';
}
 //------------------------------РџСЂРѕРІРµСЂРєР° РЅР° РїСѓСЃС‚С‹Рµ РїРѕР»СЏ РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё РџРµСЂРµС‡РµРЅСЊ РЅР°СѓС‡РЅС‹С… Рё РЅР°СѓС‡РЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРёС… СЂР°Р±РѕС‚ -----------------
if ((isset($_POST['update5'])) and !($empt))
 {
 echo'<h3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ РёР·РјРµРЅРµРЅС‹.</h3>';
  }
    if ($empt)
    {
    echo '<h3>Р’С‹ Р·Р°РїРѕР»РЅРёР»Рё РЅРµ РІСЃРµ РїРѕР»СЏ</h3>';
     }
     if ($duplicat_apd)
    {
    echo '<h3>РџСЂРѕРІРµСЂСЊС‚Рµ РєРѕСЂСЂРµРєС‚РЅРѕСЃС‚СЊ РІРІРµРґС‘РЅРЅС‹С… РґР°РЅРЅС‹С…, РІРѕР·РјРѕР¶РЅРѕ СЂР°Р±РѕС‚Р° СЃ С‚Р°РєРёРј РЅР°Р·РІР°РЅРёРµРј СѓР¶Рµ СЃСѓС‰РµСЃС‚РІСѓРµС‚.</h3>';
     }
//------------------------------РџСЂРѕРІРµСЂРєР° РЅР° РїСѓСЃС‚С‹Рµ РїРѕР»СЏ РїСЂРё Р”РѕР±Р°РІР»РµРЅРёРё РџРµСЂРµС‡РµРЅСЊ РЅР°СѓС‡РЅС‹С… Рё РЅР°СѓС‡РЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРёС… СЂР°Р±РѕС‚ -----------------
if ((isset($_POST['add5'])) and !($empt_add) and !($duplicat))
 {
 echo'<h3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ РґРѕР±Р°РІР»РµРЅС‹.</h3>';
  }
    if ($empt_add)
    {
    echo '<h3>Р’С‹ Р·Р°РїРѕР»РЅРёР»Рё РЅРµ РІСЃРµ РїРѕР»СЏ</h3>';
     }
     if ($duplicat)
    {
    echo '<h3>Р”Р°РЅРЅС‹Рµ РЅРµ РґРѕР±Р°РІР»РµРЅС‹, РЅР°СѓС‡РЅР°СЏ СЂР°Р±РѕС‚Р° СѓР¶Рµ СЃСѓС‰РµСЃС‚РІСѓРµС‚.</h3>';
     }
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<a href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&add5">Р”РѕР±Р°РІРёС‚СЊ СЂР°Р±РѕС‚Сѓ</a>';
}

     	if(isset($_GET['delete']))
    	{
    	echo'<h3>Р—Р°РїРёСЃСЊ СѓСЃРїРµС€РЅРѕ СѓРґР°Р»РµРЅР°.</h3>';
    	}
    }
 //---------------------------------------------Р РµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ РџРµСЂРµС‡РµРЅСЊ РЅР°СѓС‡РЅС‹С… Рё РЅР°СѓС‡РЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРёС… СЂР°Р±РѕС‚ - ------------------------
if (isset($_GET['update5']))
{
$type_nauch_rab="SELECT id,name FROM type_nauch_rab";
$type_rab=mysql_query($type_nauch_rab);
echo '<form method=POST action="?id_razdel5&id_kadri='.$_GET["id_kadri"].'&id_year='.$_GET["id_year"].'">
<table border=1 class="cent">';
echo '<input type="hidden" name="a" value="'.$line['id'].'">
<tr><td>Р’РІРµРґРёС‚Рµ РЅР°Р·РІР°РЅРёРµ СЂР°Р±РѕС‚С‹</td></tr><tr><td><input type="text" name="rab_name" value="'.$line['name'].'" size="40"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РєРѕР»РёС‡РµСЃС‚РІРѕ РїРµС‡Р°С‚РЅС‹С… Р»РёСЃС‚РѕРІ Рё РёР·РґР°С‚РµР»СЊСЃС‚РІРѕ</td></tr><tr><td><input type="text" value="'.$line['volume'].'" name="kol"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РІРёРґ СЂР°Р±РѕС‚С‹</td></tr><tr><td><select name="rab_vid">';
while($type=mysql_fetch_array($type_rab))
{
echo '<option value="'.$type['id'].'" ';
if ($line['id_type_nauch_rab']==$type['id']){echo'selected';}
echo'>'.$type['name'].'</option>';
}
echo '</select></td></tr>
</table><input type=submit name="update5" value="РР·РјРµРЅРёС‚СЊ"></form>';


   }
//---------------------------------------------Р’РІРѕРґ РґР°РЅРЅС‹С… РџРµСЂРµС‡РµРЅСЊ РЅР°СѓС‡РЅС‹С… Рё РЅР°СѓС‡РЅРѕ-РјРµС‚РѕРґРёС‡РµСЃРєРёС… СЂР°Р±РѕС‚ ---------------------------------------
if (isset($_GET['add5']))
{
$type_nauch_rab="SELECT id,name FROM type_nauch_rab";
$type_rab=mysql_query($type_nauch_rab);
echo '<table border=1 class="cent"><form method=POST action="?id_kadri='.$_GET["id_kadri"].'&id_year='.$_GET["id_year"].'&id_razdel5">
<tr><td>Р’РІРµРґРёС‚Рµ РЅР°Р·РІР°РЅРёРµ СЂР°Р±РѕС‚С‹</td></tr><tr><td><input type="text" name="rab_name"  size="40"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РєРѕР»РёС‡РµСЃС‚РІРѕ РїРµС‡Р°С‚РЅС‹С… Р»РёСЃС‚РѕРІ Рё РёР·РґР°С‚РµР»СЊСЃС‚РІРѕ</td></tr><tr><td><input type="text"  name="kol"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РІРёРґ СЂР°Р±РѕС‚С‹</td></tr><tr><td><select name="rab_vid">';
while($type=mysql_fetch_array($type_rab))
{
echo '<option value="'.$type['id'].'">'.$type['name'].'</option>';
}
echo '</select></td></tr>';

echo '</table><input type=submit name="add5" value="РЎРѕС…СЂР°РЅРёС‚СЊ"></form>';

}
//---------------------------------------------Р Р°Р·РґРµР» 6 Р—Р°РїРёСЃРё РѕР± РёР·РјРµРЅРµРЅРёСЏС… РІ РіРѕРґРѕРІРѕРј РёРЅРґРёРІРёРґСѓР°Р»СЊРЅРѕРј РїР»Р°РЅРµ-------------------------
//---------------------------------------------Р’С‹РІРѕРґ С‚Р°Р±Р»РёС†С‹-------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id_razdel6=6">6. Р—Р°РїРёСЃРё РѕР± РёР·РјРµРЅРµРЅРёСЏС… РІ Р“РѕРґРѕРІРѕРј РёРЅРґРёРІРёРґСѓР°Р»СЊРЅРѕРј РїР»Р°РЅРµ</a>';
}
if  (isset($_GET['id_razdel6']))
{
$i=1;
if (!$check)
echo '<h3>РќРµС‚ РґР°РЅРЅС‹С…</h3>';
else {
	echo '<table border=1 class=indplan>
<tr class=indplan><th>в„–</td><th>Р Р°Р·РґРµР» Рё РїСѓРЅРєС‚</td><th>РР·РјРµРЅРµРЅРёСЏ(РџСЂРёС‡РёРЅС‹)</td><th>Р”Р°С‚Р° (Р·Р°РІ.РєР°С„.)</td>
<th>Р”Р°С‚Р° (РїСЂРµРїРѕРґР°РІР°С‚РµР»СЊ)</td><th>РћС‚РјРµС‚РєР° Рѕ РІС‹РїРѕР»РЅРµРЅРёРё</td><th></td></tr>';
while ($line=mysql_fetch_array($izmen))
{
echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF"><td>'.$i.'</td><td>'.$line['razdel'].'</td><td>'.$line['izmenenie'].'</td><td>'.f_ri(DateTimeCustomConvert($line['zav'],'d','mysql2rus')).'</td>
<td>'.f_ri(DateTimeCustomConvert($line['prep'],'d','mysql2rus')).'</td><td>'.$line['otm'].'</td><td>'; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo '<a href="javascript:del_confirm_act(\''.f_ro($line['id']).'\',\''.str_replace(" ","_",f_ro($line['izmenenie'])).'\');"><img src="'.$files_path.'images/todelete.png" alt="РЈРґР°Р»РёС‚СЊ" title="РЈРґР°Р»РёС‚СЊ"></a><p><a href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id='.$line[0].'&update6"><img src="'.$files_path.'images/toupdate.png" alt="Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ" title="Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ"></a></td></tr>';}
	$i++;   // СѓРІРµР»РµС‡РµРЅРёРµ РїРµСЂРµРјРµРЅРЅРѕР№ РЅР° 1;
	}
echo '</table>';
}
//------------------------------РџСЂРѕРІРµСЂРєР° РЅР° РїСѓСЃС‚С‹Рµ РїРѕР»СЏ РїСЂРё СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёРё Р—Р°РїРёСЃРё РѕР± РёР·РјРµРЅРµРЅРёСЏС… РІ РіРѕРґРѕРІРѕРј РёРЅРґРёРІРёРґСѓР°Р»СЊРЅРѕРј РїР»Р°РЅРµ----------------
if ((isset($_POST['update6'])) and !($empt))
 {
 echo'<h3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ РёР·РјРµРЅРµРЅС‹.</h3>';
  }
    if ($empt)
    {
    echo '<h3>Р’С‹ Р·Р°РїРѕР»РЅРёР»Рё РЅРµ РІСЃРµ РїРѕР»СЏ</h3>';
     }
//------------------------------РџСЂРѕРІРµСЂРєР° РЅР° РїСѓСЃС‚С‹Рµ РїРѕР»СЏ РїСЂРё Р”РѕР±Р°РІР»РµРЅРёРё Р—Р°РїРёСЃРё РѕР± РёР·РјРµРЅРµРЅРёСЏС… РІ РіРѕРґРѕРІРѕРј РёРЅРґРёРІРёРґСѓР°Р»СЊРЅРѕРј РїР»Р°РЅРµ----------------
if ((isset($_POST['add6'])) and !($empt_add) and !($duplicat))
 {
 echo'<h3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ РґРѕР±Р°РІР»РµРЅС‹.</h3>';
  }
    if ($empt_add)
    {
    echo '<h3>Р’С‹ Р·Р°РїРѕР»РЅРёР»Рё РЅРµ РІСЃРµ РїРѕР»СЏ</h3>';
     }
      if ($duplicat)
    {
    echo '<h3>Р”Р°РЅРЅС‹Рµ РЅРµ РґРѕР±Р°РІР»РµРЅС‹, Р·Р°РїРёСЃСЊ СѓР¶Рµ СЃСѓС‰РµСЃС‚РІСѓРµС‚.</h3>';
     }
     if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&add6">Р”РѕР±Р°РІРёС‚СЊ Р·Р°РїРёСЃСЊ</a>';
 }
      	if(isset($_GET['delete']))
    	{
    	echo'<h3>Р—Р°РїРёСЃСЊ СѓСЃРїРµС€РЅРѕ СѓРґР°Р»РµРЅР°.</h3>';
    	}
   }
//---------------------------------------------Р РµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ Р—Р°РїРёСЃРё РѕР± РёР·РјРµРЅРµРЅРёСЏС… РІ РіРѕРґРѕРІРѕРј РёРЅРґРёРІРёРґСѓР°Р»СЊРЅРѕРј РїР»Р°РЅРµ  -------------------------

if (isset($_GET['update6']))
{
echo '<form method=POST action="?id_razdel6&id_kadri='.$_GET["id_kadri"].'&id_year='.$_GET["id_year"].'">
<table border=1 class="cent">';
$otmetka="SELECT id,name FROM otmetka";
$otm_sel=mysql_query($otmetka);
echo '<input type="hidden" name="a" value="'.$line['id'].'">
<tr><td>Р’РІРµРґРёС‚Рµ СЂР°Р·РґРµР» Рё РїСѓРЅРєС‚</td></tr><tr><td><input type="text" name="razdel" value="'.$line['razdel'].'" size="15"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РѕРїРёСЃР°РЅРёРµ Рё РїСЂРёС‡РёРЅС‹ РёР·РјРµРЅРµРЅРёСЏ </td></tr><tr><td><textarea rows=5 cols=30 name="izmenenie">'.$line['izmenenie'].'</textarea></td></tr>
<tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td>Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ (Р·Р°РІ.РєР°С„.)</td></tr><tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td><input type="text" id=date_act name="zav" value="'.f_ri(DateTimeCustomConvert($line['zav'],'d','mysql2rus')).'" size="15">
<button  type="reset" id="f_trigger_date_act">...</button>
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td>Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ (РїСЂРµРїРѕРґР°РІР°С‚РµР»СЊ)</td></tr><tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td><input type="text" id=date_act1 name="prep" value="'.f_ri(DateTimeCustomConvert($line['prep'],'d','mysql2rus')).'" size="15"
<button  type="reset" id="f_trigger_date_act1">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act1",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act1",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РѕС‚РјРµС‚РєСѓ Рѕ РІС‹РїРѕР»РЅРµРЅРёРё</td></tr><tr><td><select name="danet">';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'" ';
if ($line['id_otmetka']==$otm_selt['id']){echo'selected';}
echo'>'.$otm_selt['name'].'</option>';
}
echo '</select></td></tr>';

echo '</table><input type=submit name="update6" value="РР·РјРµРЅРёС‚СЊ"></form>';
   }
//---------------------------------------------Р’РІРѕРґ РґР°РЅРЅС‹С… Р—Р°РїРёСЃРё РѕР± РёР·РјРµРЅРµРЅРёСЏС… РІ РіРѕРґРѕРІРѕРј РёРЅРґРёРІРёРґСѓР°Р»СЊРЅРѕРј РїР»Р°РЅРµ--------------------------------------
if (isset($_GET['add6']))
{
$otmetka="SELECT id,name FROM otmetka";
$otm_sel=mysql_query($otmetka);
$sql="SELECT * FROM izmen WHERE id_kadri=".$_GET['id_kadri']." and id_year=".$_GET['id_year']."";
$izmen=mysql_query($sql);
echo '<table border=1 class="cent">';
$line=mysql_fetch_row($izmen);
echo '<form method=POST action="?id_kadri='.$_GET["id_kadri"].'&id_year='.$_GET["id_year"].'&id_razdel6">
<tr><td>Р’РІРµРґРёС‚Рµ СЂР°Р·РґРµР» Рё РїСѓРЅРєС‚</td></tr><tr><td><input type="text" name="razdel"  size="15"></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РѕРїРёСЃР°РЅРёРµ Рё РїСЂРёС‡РёРЅС‹ РёР·РјРµРЅРµРЅРёСЏ </td></tr><tr><td><textarea rows=5 cols=30 name="izmenenie"></textarea></td></tr>
<tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td>Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ (Р·Р°РІ.РєР°С„.)</td></tr><tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td><input type="text" name="zav" id=date_act size="15">
<button  type="reset" id="f_trigger_date_act">...</button><script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
</td></tr>
<tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td>Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ (РїСЂРµРїРѕРґР°РІС‚РµР»СЊ)</td></tr><tr title="Р’РІРµРґРёС‚Рµ РґР°С‚Сѓ РІ С„РѕСЂРјР°С‚Рµ РґРґ.РјРј.РіРіРіРі"><td><input type="text" name="prep" id=date_act1 size="15"
<button  type="reset" id="f_trigger_date_act1">...</button>
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "date_act1",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_date_act1",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script></td></tr>
<tr><td>Р’РІРµРґРёС‚Рµ РѕС‚РјРµС‚РєСѓ Рѕ РІС‹РїРѕР»РЅРµРЅРёРё</td></tr><tr><td><select name="danet">';
while($otm_selt=mysql_fetch_array($otm_sel))
{
echo '<option value="'.$otm_selt['id'].'">'.$otm_selt['name'].'</option>';
}
echo '</select></td></tr>';

echo '</table><input type=submit name="add6" value="РЎРѕС…СЂР°РЅРёС‚СЊ"></form>';
}
//---------------------------------------------Р Р°Р·РґРµР» 7 Р—Р°РєР»СЋС‡РµРЅРёРµ Рё РїСЂРµРґР»РѕР¶РµРЅРёСЏ Р·Р°РІРµРґСѓСЋС‰РµРіРѕ РєР°С„РµРґСЂРѕР№-------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {
echo '<p><a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id_razdel7">7. Р—Р°РєР»СЋС‡РµРЅРёРµ Рё РїСЂРµРґР»РѕР¶РµРЅРёСЏ Р·Р°РІРµРґСѓСЋС‰РµРіРѕ РєР°С„РµРґСЂРѕР№</a>';
}
if  (isset($_GET['id_razdel7']))
{

echo '<br><textarea name="msg" rows=5 cols=30 readonly>'.$msg[1].'</textarea>';
echo '<br>'; if (!isset($_GET['save']) && !isset($_GET['print'])) {echo '<a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&izmen">РР·РјРµРЅРёС‚СЊ Р·Р°РїРёСЃСЊ</a>';}
if (isset($_POST['msg']))
     {
     	echo '<H3>Р”Р°РЅРЅС‹Рµ СѓСЃРїРµС€РЅРѕ РґРѕР±Р°РІР»РµРЅС‹</H3>';
     }
}
if  (isset($_GET['izmen']))
{
echo'
<form name="" method="POST" action=?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id_razdel7>
<textarea cols=30 rows=5 name="msg">'.$msg[1].'</textarea>
<br><input type="submit" value="Р’РІРѕРґ">
<input type="hidden" name="id" value="'.$msg[0].'">
</form>';
}

//---------------------------------------------Р Р°Р·РґРµР» 8 РЈС‡РµР±РЅР°СЏ РЅР°РіСЂСѓР·РєР°-------------------------
if (!isset($_GET['save']) && !isset($_GET['print'])) {    // РїСЂРѕРІРµСЂРєР° РґР°РЅРЅС‹С… РЅР° СЃРѕРѕС‚РІРµС‚СЃС‚РІРёРµ;
echo '<p><a class="notinfo" href="?id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&id_razdel8">8. РЈС‡РµР±РЅР°СЏ РЅР°РіСЂСѓР·РєР°</a>';
}
if  (isset($_GET['id_razdel8'])) {
	$sql	= "select id, title from specialites where pub = 1  
	";// Р·Р°РїСЂРѕСЃ РїРѕ РЅРѕРјРµСЂСѓ СЃРїРµС†РёР°Р»СЊРЅРѕСЃС‚Рё  Рё РїСЂРёСЃРІР°РёРІР°РµРј 1;
	$res	= mysql_query($sql) or die (mysql_error());  // РµСЃР»Рё РґР°РЅРЅС‹Рµ РѕСЃС‚СЃСѓС‚СЃС‚РІСѓСЋС‚ Р·Р°РїСЂРѕСЃ РЅРµ РІС‹РїРѕР»РЅРµРЅ;
	//$res	= mysql_fetch_array($res);	
	echo '<ul>';	
	for ($i=0;$i<mysql_num_rows($res);$i++) {     // СѓРІРµР»РµС‡РµРЅРёРµ РїРµСЂРµРјРµРЅРЅРѕР№ РЅР° 1;
		echo '<li><a href="'.$corriculum_address.'corriculums/view/'.mysql_result($res, $i, 'id').'/1/'.$_GET['id_kadri'].'/'.$_GET['id_year'].'" target="_blank">'.mysql_result($res, $i, 'title').'</li></li>'; // СЃСЃС‹Р»РєР° РЅР° СѓС‡РµР±РЅС‹Р№ РїР»Р°РЅ;
	}
	echo '</ul>';
}
if (!isset($_GET['save']) && !isset($_GET['print'])) {     // РїСЂРѕРІРµСЂРєР° РґР°РЅРЅС‹С… РЅР° СЃРѕРѕС‚РІРµС‚СЃС‚РІРёРµ;
echo '<p><a class="notinfo" href="ind_index.php">РќР°Р·Р°Рґ</a>';}
mysql_close();
}
else
{
if ($_GET['id_kadri']==0 && $_GET['id_year']==0)echo '<p align=center><h3>Р’С‹Р±РµСЂРёС‚Рµ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЏ Рё СѓС‡РµР±РЅС‹Р№ РіРѕРґ.</h3><br><a href="ind_index.php" class="notinfo">РќР°Р·Р°Рґ</a></p>';
else
	{
	if ($_GET['id_year']==0)echo '<p align=center><h3>Р’С‹Р±РµСЂРёС‚Рµ СѓС‡РµР±РЅС‹Р№ РіРѕРґ.</h3><br><a href="ind_index.php" class="notinfo">РќР°Р·Р°Рґ</a></p>';
	if ($_GET['id_kadri']==0)echo '<p align=center><h3>Р’С‹Р±РµСЂРёС‚Рµ РїСЂРµРїРѕРґР°РІР°С‚РµР»СЏ.</h3><br><a href="ind_index.php" class="notinfo">РќР°Р·Р°Рґ</a></p>';
    }

}
?>