<?php
include ('authorisation.php');
include ('master_page_short.php');

$multy_ptypes=true;	//множественные "тип участия на каф." у сотрудника

?>
<script type="text/javascript" src="scripts/jquery.autocomplete.js"></script>
<LINK href="css/autocomplete.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
	//массив полей автозаполнения: имя поля (#id), тип запроса к БД для выборки
	var fieldsArr=new Array(
		new Array("#q ","kadriFio")
	);
</script>
<script type="text/javascript" src="scripts/autocomplete_custom.js"></script>

<?php

$person_type=0;
$q='';
if (isset($_GET['person_type']) && intval($_GET['person_type'])>0)
{$person_type=intval($_GET['person_type']);}
if (isset($_GET['q']) && $_GET['q']!='')
{$q=$_GET['q'];}


?>
<h4><?php echo $pg_title;?>
	<span class=text>( всего записей: <?php
	$stat_kadri=getRowSqlVar('select (select count(*) from kadri) as cnt,(select count(*) from kadri where photo!="" and photo is not null) as cnt_ph ');
	echo "<b>{$stat_kadri[0]['cnt']}</b>, из них с фото: <b>{$stat_kadri[0]['cnt_ph']}</b>";
	?>)</span>
</h4>
<form name=search_form method=get action=''>
<input type=text name=q id=q value="<?php echo $q;?>" size=30 title="Введите часть ФИО преподавателя для поиска ">
<span class=text>фильтр по ФИО (автозаполнение) </span>
<input type=submit value=Отобрать> &nbsp;
<input type=button onclick="javascript:window.location.href='?'" value=Сбросить <?php echo echoIf($q=='' && $person_type=='','title="введите текст запроса" disabled',''); ?> > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type=button value=Справка onclick='javascript:help_msg();'>
</form><p>

<?php

$tab_space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

if ($multy_ptypes) {
	$query='SELECT count(*) AS type_cnt, kpt.person_type_id as person_type, p.name AS type_name
              FROM kadri_in_ptypes kpt 
		LEFT JOIN person_types p ON p.id = kpt.person_type_id
		left join kadri k on k.id=kpt.kadri_id ';
	if ($person_type>0) {$query.=' where kpt.person_type_id='.$person_type; }
	if ($q!='') {$query.=' where lower(k.fio) LIKE "%'.strtolower($q).'%" '; }
	$query.=' group by kpt.person_type_id,p.name';
	
	//считаем сотрудников, у кот.нет категории
	$query.=' UNION select (select count(*) from kadri k where '.echoIf($q!='',' lower(k.fio) LIKE "%'.strtolower($q).'%" and','').'
		k.id not in (select kadri_id from kadri_in_ptypes)), 0, NULL ';
}
else {
	$query='select count(*) as type_cnt,k.person_type,p.name as type_name 
		from kadri k left join person_types p on p.id=k.person_type '; 
	if ($person_type>0) {$query.=' where k.person_type='.$person_type; }
	if ($q!='') {$query.=' where lower(k.fio) LIKE "%'.strtolower($q).'%" '; }
	$query.=' group by k.person_type,p.name';
}

$query='select type_cnt,person_type,type_name from ('.
		$query.' union select 0,id,name from person_types)T group by person_type';
//echo $query;
$res_types=mysql_query($query);

$style_addBtn='';$style_addBtn_='';	//скрываем возм.добавления для НЕ админов (кроме доб-я Рецензент)
if ($_SESSION['group_id']!=2 && $_SESSION['role']!='kadri') {$style_addBtn='display:none;';}

while ($a_types=mysql_fetch_array($res_types))
{
	$person_type=intval($a_types['person_type']);
	
	if ($multy_ptypes) {
		if ($person_type==0 || $person_type=='')
			$query='select id,fio,fio_short,photo from kadri k where k.id not in
				(select kadri_id from kadri_in_ptypes) ';
		else $query='select id,fio,fio_short,photo from kadri k where k.id in
				(select kadri_id from kadri_in_ptypes where person_type_id='.$person_type.') ';
			}
	else $query='select id,fio,fio_short,photo from kadri k where and k.person_type='.$person_type.' ';
	
	
	if ($q!='') {$query.=' and lower(k.fio) LIKE "%'.strtolower($q).'%" ';}
	
	$query.='order by k.fio,k.fio_short';
	
	//echo $query;
	$res_kadri=mysql_query($query);	
	
	$list_state2='none';$list_state1='checked';
	if ($a_types['type_name']=='') {$a_types['type_name']=' <b style="color:#999999;">тип участия не указан</b> ';}
	
	echo "<br/><br/>";
	
	if ($person_type==2) //рецензента не скрываем кнопку добав-я
	{$style_addBtn_='';}
	else {$style_addBtn_=$style_addBtn;}
	
	if (intval($a_types['type_cnt'])>0) {
		 echo "<a href=javascript:hide_show('pType".$person_type."'); title='Кликните мышкой для раскрытия списка '>
	          <b>".$a_types['type_name']." (".$a_types['type_cnt'].")</b>...</a>";
		 
		 echo " &nbsp; <a href='lect_anketa.php?person_type=".$person_type."' style='$style_addBtn_'>добавить</a>";	 	
	     
		 echo "<div id=pType$person_type style='display:$list_state2;'>";$i=0;
	     /*Не сворачивать список при следующей загрузке</form>";*/
	     $row_cnt=25; //число ФИО в одном столбце
	     echo "<table border=1 class=forms_under_border><tr valign=top align=left><td width=200> ";
	     while ($a=mysql_fetch_array($res_kadri))
	           {$i++;
	           echo "<a href='lect_anketa.php?kadri_id=".$a['id']."&action=update' title='".$a['fio']."'>
			".$i.' &nbsp; '.$a['fio_short'].echoIf(trim($a['photo'])!='',"
			<img style='position:absolute;z-index:0;' src='images/lects/small/sm_{$a['photo']}' border=0 title='{$a['fio_short']}' height=20 onMouseOver=\"javascript:this.height='120';this.style.zIndex=1;\" onMouseOut=\"javascript:this.height='20';this.style.zIndex=0;\">","")."</a><br>\n";
	           if (round($i/$row_cnt)==($i/$row_cnt)) {echo "</td><td width=200>";}
	           }
	     echo "</td></tr></table>";
	     echo "</div>";
	 }
	 else {echo " <b>".$a_types['type_name']." (".$a_types['type_cnt'].")</b>...";
	 	echo " &nbsp; <a href='lect_anketa.php?person_type=".$person_type."' style='$style_addBtn_'>добавить</a>";}
	     
}
?>	<p>
	<div class=text>
		<b>Примечание:</b>
		<ul>
		<li>для выбора сотрудника сначала выберите раздел или воспользуйтесь поиском; </li>
		<li>фото отражается (при наличии) рядом с ФИО сотрудника; </li>
		<li>фото увеличивается, если к нему подвести мышь; </li>
		<li>при наборе поискового выражения используется автозаполнение и Вам достаточно набрать часть ФИО сотрудника. </li>
		</ul>	
	</div>

<?php
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';} s	     
?>
<?php include('footer.php'); ?>