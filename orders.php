<?php
include ('authorisation.php');

$err_del=false;
if ($_SESSION['task_rights_id']==4 && isset($_GET['type']) && isset($_GET['item_id']) && $_GET['type']=='del' && intval($_GET['item_id'])>0) {
	$query_string=$_SERVER['QUERY_STRING'];
	$query_string=reset_param_name ($query_string,'type');
	$query_string=reset_param_name ($query_string,'item_id');
	$query='delete from orders where id="'.intval($_GET['item_id']).'" limit 1';
	//echo $query;
	if (mysql_query($query))
	{
	 header('Location:?'.$query_string);
	 }
	else {$err_del=true;}
}

include ('master_page_short.php');

?>

<script type="text/javascript" src="scripts/calendar_init.js"></script>
<script language="JavaScript">
function help_msg()
{ alert('Эта главная форма, предназначена для ввода и правки данных по приказам преподавателей.\n'+'');
}
</script>

<?php
  $days4stat=0;
  $date_from=date('Y.m.d',mktime(0,0,0, date("m"),date("d")-$days4stat,  date("Y"))  );
	$kadri_id=0;
	if (isset($_GET['kadri_id']) && intval($_GET['kadri_id'])>0) $kadri_id=intval($_GET['kadri_id']);
	$item_id=0;
	if (isset($_GET['item_id']) && intval($_GET['item_id'])>0) $item_id=intval($_GET['item_id']);
	
      function order_exist($kadri_id,$type_money,$type_order)
	  {
      global $date_from;
	  $exist='';
	  if ($kadri_id>0 && $type_money>0 && $type_order>0) {
		  $query_all_='select id from orders od where kadri_id='.$kadri_id. ' and type_money='.$type_money.' and type_order='.$type_order.' and 
			(cast(concat(substring(od.date_end,7,4),".",substring(od.date_end,4,2),".",substring(od.date_end,1,2)) as datetime)>=now() || od.date_end="" || od.date_end is NULL ) limit 0,1';
	      
		
		if ($type_money==$_GET['type_money'] && $type_order==$_GET['type_order']) {$exist.=' class=round_table';}
		if ($res_all=mysql_query($query_all_) and mysql_numrows($res_all)>0) {$exist.=' style="font-weight:bold;"';}	
		
	  }
	  return $exist;	   
	  } 



//include ('sql_connect.php');

echo '<h4>'.$pg_title.'</h4>';
if ($kadri_id==0)
{
	persons_select('type_money=2&kadri_id');	//вывести выбор преподавателя
	echo "<p><a href='p_administration.php'>К списку задач.</a></p>";
	exit;
}

$disabled_val="";   //для вставки
$fio="";            //
$type="";           //

$fio_res=mysql_query("select fio from kadri where id=".$kadri_id." limit 0,1");
//echo "select fio from kadri where id=".$_GET['kadri_id'];
$a=mysql_fetch_array($fio_res);

$fio=$a['fio'];
//определяем следующий номер записи сотрудника в БД
    $tab_name='orders';
    $res=mysql_query('select id from '.$tab_name.' order by id DESC limit 0,1');
    $a=mysql_fetch_array($res);

          //$disabled_val="disabled";
//----------------------------end update------------------------------------------------------------------------
if ($err_del) {echo '<div class=warning> Ошибка удаления приказа. Попробуйте повторить действие.</div>';}

if (isset($_POST['type']) && $_SESSION['task_rights_id']==4)        //идем на добавление/обновление приказа по сотруднику
{


 if ($_POST['type']=="insert" )
    {echo "Добавление нового приказа. ";
$insert_string="";
for ($i=4;$i<13;$i++) {
 $name_elem="elem".$i;
 
 //приводим к числовому формату MySQL 
 if ($i==11) {$tmp=str_replace(',','.',$_POST[$name_elem]);}	
 else {$tmp=$_POST[$name_elem];}
 
  $insert_string=$insert_string.", '".$tmp."'";
 // $name_elem="elem".$i; $insert_string=$insert_string.", '".$_POST[$name_elem]."'";
 }
    $query="insert into orders(kadri_id,type_order,num_order,date_order,date_begin,date_end,
        main_work_place,prev_order,rank_ets,rate,conditions,type_money)
           values(".$_POST['elem0'].",".$_GET['type_order'].$insert_string.",".$_GET['type_money'].")";
           //echo "<br>".$query;
           if (mysql_query($query)) {echo "Приказ по сотруднику: <b>".$fio."</b> <span class='success'> успешно добавлен </span>";}
           else {echo "Приказ не добавлен...";}
    }
 if ($_POST['type']=="update" )
    {echo "Изменение существующего приказа";
    $query="update orders set num_order='".$_POST['elem4']."',date_order='".$_POST['elem5']."',
                   type_order='".$_GET['type_order']."',prev_order='".$_POST['elem9']."',rank_ets='".$_POST['elem10']."',
                   rate='".str_replace(',','.',$_POST['elem11'])."',
                   date_begin='".$_POST['elem6']."',date_end='".$_POST['elem7']."',conditions='".$_POST['elem12']."',
                   main_work_place='".$_POST['elem8']."' where kadri_id=".$kadri_id.' and type_money='.$_GET['type_money'];
          
    if ($item_id>0)
    {$query=$query.' and id='.$item_id.'';}
    //else {}
    // echo "<br>".$query;
           if (mysql_query($query)) {echo "<br>Приказ по сотруднику: <b>".$fio."</b> <span class='success'>успешно обновлен </span> ";}
           else {echo "<br><font color=red>Приказ не обновлен...</font>";}
    }
}
//--------------------------------------------------------------
          $query_all='SELECT id , type_money , kadri_id , num_order , date_order , type_order , prev_order , rank_ets ,
          date_begin , date_end , conditions, rate , main_work_place FROM orders
          where kadri_id='.$kadri_id.' and type_money='.$_GET['type_money'].' and type_order='.$_GET['type_order'].'';
		  
		  if ($item_id>0) {$query_all=$query_all.' and id='.$item_id.'';}
		  else { 
		  	$query_all=$query_all.' and 
			concat(substring(date_end,7,4),".",substring(date_end,4,2),".",substring(date_end,1,2))>="'.$date_from.'"  limit 0,1';}
       //echo $query_all;
          if ( $res_all=mysql_query($query_all) and mysql_numrows($res_all)>0) {
          $tmpval=mysql_fetch_array($res_all);$type="update";

      }    //выборка не пустая
          else {echo "Ранее приказ по сотруднику: <b>".$fio."</b> не существовал. Вводится новый приказ.";$type="insert";}

?>
<form name=orders_form action="" method="post">
<table>
<tr valign="middle" height="44"><td width=300>
<input type=hidden name=type value="<?php echo $type; ?>">
<?php if ($_SESSION['task_rights_id']==4) { ?>
	<input type="submit" value="<?php
	if ($type=="insert") {echo "Добавить";} else {echo "Изменить";}?>"
	 onclick="javascipt:on_click();">&nbsp;&nbsp;&nbsp;&nbsp;
	 
	 <input type="button" value="Удалить" <?php if ($type=="insert") {echo " disabled"." title=\"Выберите приказ для удаления\"";} ?> 
		onclick="javascipt:del_confirm_act('текущий приказ','<?php echo '?'.$_SERVER['QUERY_STRING'].'&type=del&item_id='.$tmpval['id'];?>');">
 <?php } ?>
 </td> <td><input type="reset" value="Очистить">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 
 <input type="button" value="Справка" onclick="javascript:help_msg();"></td>  </tr>
</table>

<table name=tab1 cellpadding="0" cellspacing="10" class=forms_under_border width="777" border=0>
    <tr>
    <td width="275" colspan="2">
      <input name="elem0" type="hidden" value="<?php if ($kadri_id>0) echo $kadri_id; ?>">
      <input name="elem2" type="hidden" value="<?php if (!isset($tmpval['id']) or $tmpval['id']=="")
             {echo trim($a['id']+1);} else {echo $tmpval['id'];} echo '" '.$disabled_val; ?>>
    </td>
  </tr>
    <tr>
      <td> Общая ставка: <?php
	if ($kadri_id>0) {
		  $query_rate='SELECT round(sum(od.rate),2) as rate_sum,count(od.rate) as ord_cnt
	  FROM `orders` od
	  WHERE (cast(concat(substring(od.date_end,7,4),".",substring(od.date_end,4,2),".",substring(od.date_end,1,2)) as datetime)>=now() || od.date_end="" || od.date_end is NULL ) and kadri_id='.$kadri_id;
	  $rate_stat=getRowSqlVar($query_rate);
	  if ($rate_stat[0]['rate_sum']>0)
		  echo '<b>'.$rate_stat[0]['rate_sum'].'<sup>'.$rate_stat[0]['ord_cnt'].'</sup></b>';
	}      
      ?><br><small>по активным приказам<small></td><td>   
<?php 

	//kadri_id=29&type_money=4;
	
	//$kadri_id=$_GET['kadri_id'];
   	echo 'Сотрудник: <!--<b>'.$fio.'</b>-->';
	echo'<select name="teach_name" style="width:300;" 
		onChange="javascript:window.location.href=\'?type_money=2&kadri_id=\'+this.options[this.selectedIndex].value;"> ';
		
		$query='select id,concat(fio," (",(
			select count(*) from orders od where od.kadri_id=kadri.id and  (cast(concat(substring(od.date_end,7,4),".",substring(od.date_end,4,2),".",substring(od.date_end,1,2)) as datetime)>=now() || od.date_end="" || od.date_end is NULL)
			),")") as fio from kadri order by 2 ';
		if ($_SESSION['task_rights_id']==4) {		 
		 echo '<option value="0">...выберите преподавателя ...</option>';
		 }
		else {	//для преподавателя только просмотр своих приказов
		 $query.=' where kadri.id="'.$kadri_id.'" order by kadri.fio ';};
		
		$res=mysql_query($query);
		while ($a=mysql_fetch_array($res)) 	{
		 	$select_val='';
			 if ($kadri_id==$a['id']) {$select_val=' selected';} 
			echo '<option value="'.$a['id'].'"'.$select_val.'>'.$a['fio'].'</option>';
			}

echo '</select><p>';
?>
    </td>
  </tr>
    <tr>
      <td> Введенные приказы (тип средств):</td><td>
        <table border=0><tr valign=top align=left>
		<?php 
		//$type_money=;$type_order=;	//order_exist($_GET['kadri_id'],$type_money,$type_order)
		
		$query_cnt='select count(*) FROM `orders` od		
			where kadri_id='.$kadri_id.' and (cast(concat(substring(od.date_end,7,4),".",substring(od.date_end,4,2),".",substring(od.date_end,1,2)) as datetime)>=now() || od.date_end="" || od.date_end is NULL) ';
		//echo $query_cnt.' and type_order=2 and type_money=2';
		$str='Основной:<br>	<small>
		<a '.order_exist($kadri_id,2,2).' href="orders.php?kadri_id='.$kadri_id.'&type_order=2&type_money=2">-бюджет ('.getScalarVal($query_cnt.' and type_order=2 and type_money=2').');</a><br>
		<a '.order_exist($kadri_id,3,2).' href="orders.php?kadri_id='.$kadri_id.'&type_order=2&type_money=3">-внебюджет ('.getScalarVal($query_cnt.' and type_order=2 and type_money=3').').</a></small>';
		
		echo '<td valign=top align=left>'.$str.'</td><td width=20>&nbsp;</td>';
 //----------------		
		$str='Совместительство:<br>	<small>
		<a '.order_exist($kadri_id,2,3).' href="orders.php?kadri_id='.$kadri_id.'&type_order=3&type_money=2">-бюджет ('.getScalarVal($query_cnt.' and type_order=3 and type_money=2').');</a><br>
		<a '.order_exist($kadri_id,3,3).' href="orders.php?kadri_id='.$kadri_id.'&type_order=3&type_money=3">-внебюджет ('.getScalarVal($query_cnt.' and type_order=3 and type_money=3').').</a></small>';
		
		echo '<td valign=top align=left>'.$str.'</td><td width=20>&nbsp;</td>';
 //----------------		
		$str='Дополнительно:<br>	<small>
		<a '.order_exist($kadri_id,2,4).' href="orders.php?kadri_id='.$kadri_id.'&type_order=4&type_money=2">-бюджет;</a><br>
		<a '.order_exist($kadri_id,3,4).' href="orders.php?kadri_id='.$kadri_id.'&type_order=4&type_money=3">-внебюджет.</a></small>';
		
		echo '<td valign=top align=left>'.$str.'</td><td width=20>&nbsp;</td>';

		?>
		</tr></table>   
 </td>
  </tr>
  <?php 
  //выводим только после выбора типа приказа
  
  if (isset ($_GET['type_order']) && isset ($_GET['type_money'])) {
  //-----------------------------------------------------------------------------
  ?>

  <!--tr>
    <td width="275">Тип средств</td>
    <td width="472">
      <select name="elem13" title="Укажите тип средств ФОТ... " style="width:200" disabled>
      <?php
      $type_money="";
      $res=mysql_query("select id,name from order_type_money  order by id");

      if (isset($_GET['type_money'])) {
           // $type_money=$tmpval['type_money'];
            while ($a=mysql_fetch_array($res)) {
                if ($_GET['type_money']==$a['id']) {echo "<option value=".$a['id']." selected>".$a['name']."</option>\n";}
                else {echo "<option value=".$a['id'].">".$a['name']."</option>\n";}
                                  }
                           }
      ?>
      </select>
    </td>
  </tr-->
  <tr>
    <td width="275"> Приказ</td>
    <td width="472">номер
      <input name="elem4" type=text size=15 maxlength="10" class="tab_view" value="<?php if (isset($tmpval)) echo $tmpval['num_order']; ?>">
      , дата
      <input name="elem5" id=elem5 type=text size=15maxlength="10" class="tab_view" value="<?php if (isset($tmpval)) echo $tmpval['date_order']; ?>"> 
	<button type="reset" id="f_trigger_elem5">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem5",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem5",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>
    </td>
  </tr>
  <!--tr>
    <td width="275">Тип приказа</td>
    <td width="472">
      <select name="elem3" title="Укажите тип приказа... " style="width:200">
      <?php
      $type_ord="";
      $res=mysql_query("select id,name from order_type order by id");

      if (isset($tmpval)) {
            $type_ord=$tmpval['type_order'];
            while ($a=mysql_fetch_array($res)) {
                if ($type_ord==$a['id']) {echo "<option value=".$a['id']." selected>".$a['name']."</option>\n";}
                else {echo "<option value=".$a['id'].">".$a['name']."</option>\n";}
                                  }
                           }
      else {
            while ($a=mysql_fetch_array($res)) {echo "<option value=".$a['id'].">".$a['name']."</option>\n";}
           }


      ?>
      </select>
    </td>
  </tr-->
  <tr>
    <td width="275"> Период действия приказа</td>
    <td width="472">начало
      <input name="elem6" id=elem6 type=text size=15 maxlength="10" class="tab_view" value="<?php if (isset($tmpval)) echo $tmpval['date_begin']; ?>"> 
	<button type="reset" id="f_trigger_elem6">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem6",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem6",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>											
      , окончание
      <input name="elem7" id=elem7 type=text size=15maxlength="10" class="tab_view" value="<?php if(isset($tmpval)) echo $tmpval['date_end']; ?>" > 
	<button type="reset" id="f_trigger_elem7">...</button>
	<script type="text/javascript">
    Calendar.setup({
        inputField     :    "elem7",      // id of the input field
        ifFormat       :    "%d.%m.%Y",       // format of the input field "%m/%d/%Y %I:%M %p"
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_elem7",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode false
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
	</script>											
    </td>
  </tr>
  <tr>
    <td width="275"> Основное место работы (для совместителя)</td>
    <td width="472">
      <input name="elem8" type=text value="<?php if (isset($tmpval)) echo $tmpval['main_work_place']; ?>" size=60 class="tab_view" >
    </td>
  </tr>
  <tr>
    <td width="275"> Данные предыдущего приказа</td>
    <td width="472">
      <input name="elem9" type=text value="<?php if (isset($tmpval)) echo $tmpval['prev_order']; ?>" size=60 class="tab_view" >
    </td>
  </tr>
  <tr>
    <td width="275">Разряд ЕТС, размер ставки</td>
    <td width="472"> ЕТС
      <input name="elem10" type=text size=12 maxlength="10" class="tab_view" value="<?php if (isset($tmpval)) echo $tmpval['rank_ets']; ?>">
      , ставка
      <input name="elem11"  type=text size=12 maxlength="10" class="tab_view" value="<?php if (isset($tmpval))echo str_replace('.',',',$tmpval['rate']); ?>">
    </td>
  </tr>
  <tr>
    <td width="275"> Дополнительные условия работы</td>
    <td width="472">
      <input name="elem12" type=text size=60 class="tab_view" value="<?php if (isset($tmpval)) echo $tmpval['conditions']; ?>">
    </td>
  </tr>
  <tr>
    <td width="275"><span>Активные приказы <?php
    $query_act='select id as item_id,num_order,date_order,date_begin,date_end FROM `orders` od
	where kadri_id='.$kadri_id.' and type_money='.$_GET['type_money'].' and type_order='.$_GET['type_order'].'
	and (cast(concat(substring(od.date_end,7,4),".",substring(od.date_end,4,2),".",substring(od.date_end,1,2)) as datetime)>=now() || od.date_end="" || od.date_end is NULL ) ';    
    $a_act=getRowSqlVar($query_act);
    ?></span>
</td><td><?php
	if (count($a_act)>0)
	{
		$query_str=reset_param_name($_SERVER['QUERY_STRING'],'item_id');
	for ($i=0;$i<count($a_act);$i++) {
		echo '<a href="?'.$query_str.'&item_id='.$a_act[$i]['item_id'].'"'.echoIf($item_id==$a_act[$i]['item_id'],' style="font-weight:bold;"','') .'>
		приказ №'.$a_act[$i]['num_order'].' от '.$a_act[$i]['date_order'].' с '.$a_act[$i]['date_begin'].' по '.$a_act[$i]['date_end'].'</a><br>';
		}	
	}
	else {echo '<b><i> приказов нет</i></b>';}
?></td>
</tr>
	<tr>
    <td width="275"><span class=warning>Архивные приказы </span></td>
    <td width="472">
      список архивных приказов по дате приказа с убыванием <br>
      <?php if (isset($tmpval) || 1>0) {
	   		//поиск архивных приказов по тек_дате> дата_окончания_приказа
		//$days4stat=0;
		//$date_from=date('Y.m.d',mktime(0,0,0, date("m"),date("d")-$days4stat,  date("Y"))  );
		//echo ' date_from='.$date_from;
		$query_arh='select id,num_order,date_order,date_begin,date_end,
			concat(substring(date_order,7,4),".",substring(date_order,4,2),".",substring(date_order,1,2)) as date_order_sort,
			concat(substring(date_end,7,4),".",substring(date_end,4,2),".",substring(date_end,1,2)) as date_end_sort  
		from orders od 
		where kadri_id="'.$kadri_id.'" and 
			type_money='.$_GET['type_money'].' and type_order='.$_GET['type_order'].' and
			cast(concat(substring(od.date_end,7,4),".",substring(od.date_end,4,2),".",substring(od.date_end,1,2)) as datetime)<now()
			order by date_order_sort ';
		//echo ' query_arh='.$query_arh;
		$res_arh=mysql_query($query_arh);
		if (mysql_num_rows($res_arh)>0) {
			while ($a_arh=mysql_fetch_array($res_arh)) {
				//$strpos1=strpos($_SERVER['QUERY_STRING'],'&item_id=');
				//if ($strpos1<=0) {$strpos1=strlen($_SERVER['QUERY_STRING']); };
				
				//echo ' strpos1='.$strpos1;
				$query_str=reset_param_name($_SERVER['QUERY_STRING'],'item_id');
				//echo $query_str.'11111111';
				echo '<a  href="?'.$query_str.'&item_id='.$a_arh['id'].'" title="просмотреть/править приказ" '.echoIf($item_id==$a_arh['id'],' style="font-weight:bold;"','') .'>
				приказ №'.$a_arh['num_order'].' от '.$a_arh['date_order'].' с '.$a_arh['date_begin'].' по '.$a_arh['date_end'].'</a><br>';
				
			}  			   
		}
		else {echo '<b><i> приказов нет</i></b>';}
			   //echo $tmpval['num_order'];
		
		   						} 
	  ?>
    </td>
  </tr>
<?php
}
else {echo '<tr><td colspan=2> Выберите тип приказа, кликнув по ссылки выше </td></tr>';}
?>  
  </table>
  <div class=text>
  	<b>Примечание:</b><br>
  	<font class=round_table> цветом </font> указан текущий тип средств, <b>жирный</b> шрифт указывает на наличие приказа, обычный- на его отсутсвие.<br>
  	<font style="color:#ff0000;background-color:#FFFFFF;"> Архивные приказы </font> указывается при правке приказа, что текущий приказ <b>в архиве</b>
<table>
<tr valign="middle" height="44"><td width=300>
<?php if ($_SESSION['task_rights_id']==4) { ?>
<input type="submit" value="<?php if ($type=="insert") {echo "Добавить";} else {echo "Изменить";}//mysql_close(); ?>"
 onclick="javascipt:on_click();">&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="Удалить" <?php if ($type=="insert") {echo " disabled title=\"Выберите приказ для удаления\"";} ?> 
 	onclick="javascipt:del_confirm_act('текущий приказ','<?php echo '?'.$_SERVER['QUERY_STRING'].'&type=del&item_id='.$tmpval['id'];?>');">
<?php }?>
 </td> <td><input type="reset" value="Очистить">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <input type="button" value="Справка" onclick="javascript:help_msg();"></td>  </tr>
</table></form>
<p><a href="lect_anketa.php?kadri_id=<?php echo $kadri_id;?>&action=update">К анкете преподавателя</a></p>
<p><a href="p_administration.php">К списку задач.</a></p>
<?php include('footer.php'); ?>
