<?php

include ('authorisation.php');

// <abarmin date="08.05.2012">
require_once("core.php");
// </abarmin>

include ('master_page_short.php');
//$del_allow=false;		//разрешать удаление элементов справочников...
						//удаление разрешено только админам ($_SESSION['userType']=='администратор')

$tables=array();
for ($i=0;$i<3;$i++) {$tables[$i]=array(3);}
																			//для проверки перед удалением наличия в Анкете преподавателя

//если указан фильтр по типу усекаем список таблиц
$spr_type=-1;	//не выбран
if (isset($_GET['spr_type'])) $spr_type=intval($_GET['spr_type']);

echo '<h4 class="notinfo">'.$pg_title.'</h4>	';

//вывод категорий справочников
$query="SELECT cast(sm.name as char) as `name`,sm.id as `id`,(select count(*) from sprav_links where sprav_main_id=sm.id) as cnt FROM `sprav_main` sm
		union 
		select 'прочие' as `name`,0 as `id`,(select count(*) from sprav_links where sprav_main_id=0) as cnt";
$res=mysql_query($query);
if (mysql_num_rows($res)>0)
{
	echo '<div class=main style="text-align:left;">Фильтр по категории справочника: ';
	
	if ($spr_type==-1) {echo '<span class=success> все ('.getScalarVal('select count(*) from sprav_links').')</span> &nbsp;';}
	else {echo '<a href="?">все ('.getScalarVal('select count(*) from sprav_links').')</a> &nbsp;';}	
	 
	while ($a=mysql_fetch_assoc($res)) {
		if ($spr_type!=-1 && $spr_type==$a['id']) echo '<span class=success>'.$a['name'].' ('.$a['cnt'].') </span> &nbsp; ';
		else echo '<a href="?spr_type='.$a['id'].'">'.$a['name'].' ('.$a['cnt'].')</a> &nbsp; ';	
	}
	echo '</div>';
}
else {'<div class=warning>категорий не найдено</div>';}
//-----конец категорий

//вывод справочников указанной категории
$query='SELECT sl.id,
       sl.sprav_name,
       sl.sprav_main_id,
       sl.comment,
       t.name AS task_name,
       t.url AS task_url
  FROM   tasks t
       RIGHT OUTER JOIN
          sprav_links sl
       ON (t.id = sl.task_id)';
if ($spr_type>=0)
{
	$spr_type=intval($_GET['spr_type']);
	$query.=' where sl.sprav_main_id='.$spr_type;
}

	
$res=mysql_query($query);
if (mysql_num_rows($res)>0)
{
 echo '<p class=title>';
while ($a=mysql_fetch_assoc($res)) {  
  echo "\n<a name=".$a['sprav_name']."></a>&nbsp;&nbsp;-";
  		
  		$items_cnt=0;
		$items_cnt=getScalarVal('select count(*) from `'.$a['sprav_name'].'`');	//число записей в справочнике
		if ($a['task_url']!='' && $a['task_name']!='')
			echo '<a href="'.$a['task_url'].'" class=help style="" title="работа со справочником в расширенном режиме">
				'.$a['task_name'].'</a>';
			
  		else
			echo "<a href='spravochnik_edit.php?sprav_id=".$a['id']."'> ".$a['comment']."</a>";
			
		echo "<span class=text title=\"число записей\"> ({$items_cnt})</span>";
		echo "<br><br>\n";
                     }
}

// <abarmin date="08.05.2012">
// словари из таксономий
CTaxonomyManager::fullInit();
foreach (CTaxonomyManager::getTaxonomiesObjectList()->getItems() as $item) {
    echo '&nbsp;&nbsp;-&nbsp;<a class="" href="'.WEB_ROOT.'_modules/_taxonomy/?id='.$item->getId().'">'.$item->getName().'</a> <span class="text">('.$item->getTerms()->getCount().')</span></br></br>';
}
// </abarmin>

?>
<div class=text>
	<b>Примечание</b> <br>	
	<ul>
	  <li>в скобках указано число записей в справочнике</li>
	  <li>выделение  <span class=help style="color:blue;">имени справочника</span>
	  говорит о работе с мета-справочником, т.е. работе со справочником, который выделен в отдельную задачу </li>
	  <li>категория справочника присваивается администратором и служит для логической группировки</li>
	</ul>
</div>	
<?php
if (!isset($_GET['save']) && !isset($_GET['print'])) 
{echo '<div class="notinfo">';
	show_footer();
echo'</div>';}
                     
?>
<?php include('footer.php'); ?>