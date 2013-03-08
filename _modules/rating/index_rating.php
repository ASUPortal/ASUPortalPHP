<?php
include "config.php";

include $portal_path."authorisation.php";
include $portal_path."master_page_short.php";

//Рейтинг
?>
<LINK REL="STYLESHEET" TYPE="text/css" HREF="rating_style.css">
<script type="text/javascript"  src="rating_javascript.js"></script>

<?php
//--------------------------Заполнение-------------------
if (isset($_GET['zapolnenie']))
{
  $select=mysql_query('SELECT id, fio FROM kadri ORDER BY fio') or die ("Возникла ошибка  ");
  
$select1=mysql_query('SELECT id, name,(SELECT count(id_kadri) FROM summa_ballov  WHERE id_year=time_intervals.id) AS count FROM time_intervals  ORDER BY name desc') or die ("Возникла ошибка  ");

	//----Заголовок--------

echo '<table><tr><td valign=top><b>Преподаватель: </b>';
		echo '<Select   name="id_kadri" ONCHANGE="top.location.href=this.options[this.selectedIndex].value"><option value="?zapolnenie&id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&razdel='.$_GET['razdel'].'"></option>';
  	while ($fio=mysql_fetch_array($select))
   	{echo'<Option Value="?zapolnenie&id_kadri='.$fio['id'].'&id_year='.$_GET['id_year'].'&razdel='.$_GET['razdel'].'"  '; if ($_GET['id_kadri']==$fio['id'])echo'selected'; echo'>'.$fio['fio'].'</Option>';}
    echo'</Select>
    	   		</td>
    	   		<td>
                <b>&nbsp;&nbsp;&nbsp; учебный год: </b><select name="id_year" ONCHANGE="top.location.href=this.options[this.selectedIndex].value"><option value="?zapolnenie&id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&razdel='.$_GET['razdel'].'"></option>';
    while ($year=mysql_fetch_array($select1))
    {echo'<Option Value="?zapolnenie&id_kadri='.$_GET['id_kadri'].'&id_year='.$year['id'].'&razdel='.$_GET['razdel'].'"  '; if ($_GET['id_year']==$year['id'])echo'selected'; echo'>'.$year['name'].'</Option>';}
 echo'</select></td>
    	    </tr>
     </table><br>';

//-----------------Выводим ссылки на разделы-----------------------------------

echo '<table>
		<tr>
<td><a href="index_rating.php" class="notinfo">Назад</a></td>
        <td>&nbsp;&nbsp;</td>
<td><a href="?zapolnenie&id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&razdel=1" class="href_menu_rating">1. Звание</a></td>
		<td>&nbsp;&nbsp;</td>
<td><a href="?zapolnenie&id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&razdel=2" class="href_menu_rating">2. Должность</a></td>
		<td>&nbsp;&nbsp;</td>
<td><a href="?zapolnenie&id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&razdel=3" class="href_menu_rating">3. Научно-методическая и учебная работа</a></td>
		<td>&nbsp;&nbsp;</td>
<td><a href="?zapolnenie&id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'&razdel=4" class="href_menu_rating">4. Вычеты</a></td>
		</tr>
	  </table>';
if ($_GET['id_kadri']!=0 && $_GET['id_year']!=0)
	{
//------------------Выводим  разделы -------------------------
if (isset($_GET['razdel']))
		{
switch ($_GET['razdel'])
{
case 1:
$spr_name='spr_zvanie';
$spr_id='zvanie';
$title='Звание';
$altertable_with_id='zvanie_rate';
$id_spr_name='id_zvanie';
$summaballov_name='zvanie';
break;

case 2:
$spr_name='spr_dolzhnost';// основная таблица справочник
$spr_id='dolgnost';// справочник с названиями
$title='Должность';// заголовок таблицы
$altertable_with_id='dolzhnost';// таблица с id из табл. справочника (для удаления из неё инфы по id основной таблицы)
$id_spr_name='id_dolzhnost';//поле таблицы по которому осуществляется удаление
$summaballov_name='dolzhnost';//поле в табл. сумма_баллов
break;

case 3:
$spr_name='spr_nauch_met_uch_rab ';
$title='Научно-методическая и учебная работа';
$altertable_with_id='nauch_met_uch_rab';
$id_spr_name='id_rab';
$summaballov_name='nauch_met_uch_rab';
break;

case 4:
$spr_name='spr_vichet';
$title='Вычеты';
$altertable_with_id='vichet';
$id_spr_name='id_vichet';
$summaballov_name='vichet';
break;
}

//---------------------------Добавление----------------------------------------
         $mysql_add=false;
         $mysql_del=false;
if ((isset($_POST['add'])) && (isset($_POST['checkbox_array'])))
				{

                    $sql="SELECT id FROM summa_ballov WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year']."";
                    $mysql=mysql_query($sql) or die("Возникла ошибка добавления#0 в  таблицу summa_ballov ");
                    //если записей нет, то вставляем  в summa_ballov пустые значения и id преподавателя
                    if (!mysql_num_rows($mysql))
		    {
                    	$sql="INSERT INTO summa_ballov (id_kadri,id_year) VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."')";
                    	$mysql=mysql_query($sql) or die("Возникла ошибка добавления#1 в  таблицу summa_ballov");
			
                    }


				foreach ($_POST['checkbox_array'] as $i)
							{

     					//проверка на дубликаты
     				$sql="SELECT * FROM ".$altertable_with_id." WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year']." AND ".$id_spr_name."=".$i."";
     				$mysql=mysql_query($sql) or die("Возникла ошибка");
                    if(!mysql_num_rows($mysql))
     					{
     					if ($_GET['razdel']!=1)//проверка на звание
     						{
                    $sql="INSERT INTO ".$altertable_with_id." (id_kadri,id_year,".$id_spr_name.") VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$i."')";
                    $mysql_add=mysql_query($sql) or die("Возникла ошибка ");
                    	    }
                    	    else
                    	    {
                    	    	$sql=mysql_query("SELECT * FROM zvanie_rate WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year']."") or die("Возникла ошибка ");
                    	    	if(mysql_num_rows($sql)==0)
                    	    	{
                    	    		$sql="INSERT INTO ".$altertable_with_id." (id_kadri,id_year,".$id_spr_name.") VALUES ('".$_GET['id_kadri']."','".$_GET['id_year']."','".$i."')";
                    $mysql_add=mysql_query($sql) or die("Возникла ошибка ");
                    	    	}
                    	    	else
                    	    	{
                    	    		$sql="UPDATE zvanie_rate  SET id_zvanie=".$i." WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year']."";
                    $mysql_add=mysql_query($sql) or die("Возникла ошибка ");
                    	    	}
                    	    }
                    	}

							}
					//апдейт таблицы summa_ballov

					$sql="UPDATE summa_ballov SET ".$summaballov_name."=(SELECT SUM(round(rate,3)) FROM ".$spr_name."  WHERE id IN (SELECT ".$id_spr_name." FROM ".$altertable_with_id." WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")) WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year']."";
                    $mysql=mysql_query($sql) or  die("Возникла ошибка обновления#1 таблицы summa_ballov: ");

                 }
//------------------------------Удаление-------------------------------------

if ((isset($_POST['checkbox_array_del'])) && (isset($_POST['delete'])))
	{
	foreach ($_POST['checkbox_array_del'] as $i)
			{

     				$sql="DELETE FROM ".$altertable_with_id." WHERE ".$id_spr_name."='$i' AND id_kadri='".$_GET['id_kadri']."' AND id_year='".$_GET['id_year']."'";
                   $mysql_del=mysql_query($sql) or die("Возникла ошибка");

			}
			//апдейт таблицы summa_ballov

			$del_summa_ballov="UPDATE summa_ballov SET ".$summaballov_name."=(SELECT SUM(round(rate,3)) FROM ".$spr_name."  WHERE id IN (SELECT ".$id_spr_name." FROM ".$altertable_with_id." WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")) WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year']."";
            if($del_mysql=mysql_query($del_summa_ballov)){}
	        else $del_mysql=mysql_query("UPDATE summa_ballov SET ".$summaballov_name."='0' WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year']."")or die("Возникла ошибка обновления#3 таблицы summa_ballov ");
	}


//----------------Форма вывода----------------------------------
        if($_GET['razdel']==1 or $_GET['razdel']==2)
        {
$sql="SELECT ".$spr_name.".*,".$spr_id.".name AS name FROM ".$spr_name." INNER JOIN ".$spr_id." ON ".$spr_name.".id=".$spr_id.".id  WHERE ".$spr_name.".id NOT IN (SELECT ".$id_spr_name." FROM ".$altertable_with_id." WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].") ORDER BY rate DESC,name";
        }
        else
        {
$sql="SELECT * FROM ".$spr_name."  WHERE id NOT IN (SELECT ".$id_spr_name." FROM ".$altertable_with_id." WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].") ORDER BY rate DESC,name";
		}
		$mysql=mysql_query($sql);
		   if (!mysql_num_rows($mysql))
      		{
  echo "<table width=100% border=0><tr><td width=50% ><table width=100%><td align=center><b>Нет данных.</b></td></td></tr>";
     	 	}
     	else
     		{
     		//------------Вывод данных-----------------
     		echo "<table width=100% border=0 ><tr>
     			<td width=50%><br><table border=0 width=100%>
     			            <tr>
     				<td align=center width=85%><b>".$title."</b></td>
     		        <td align=left width=15%><b>Балл</b></td>
     			            </tr><FORM method='POST'>
     					   <tr>
     					   <td colspan=2><table width=100% border=1 class=indplan>";

     					   $i=1;
			while ($line=mysql_fetch_array($mysql))
					{$id=$line['id'];
     				echo "<tr onmouseover=this.style.background='Turquoise' onmouseout=this.style.background='#DFEFFF' ><td  width=5%>$i</td>
     						  <td width=85%>".$line['name']."</td>
     					      <td width=10%>".$line['rate']."</td>
     						<td>";
     						//для 1го раздела переключатель, для остальных флажки
     						if ($_GET['razdel']==1)echo"<input type='radio' name=checkbox_array[0] value=".$id.">"; else echo"<input type='checkbox' name=checkbox_array[".$id."] value=".$id."></td>
     					</tr>";
     				++$i;
					}
			echo '</table></td>
			              </tr>
			       <tr><td align=left>
			<input type="submit" value="Назначить>>" name="add" title="Добавить">
			</FORM>
			<br><a href="index_rating.php" class="notinfo">Назад</a>
				     </td>
				  </tr>';
			}
				   //----------Форма для уже добавленных данных-------------
        if($_GET['razdel']==1 or $_GET['razdel']==2)
        {
		$sql="SELECT ".$spr_name.".*,".$spr_id.".name AS name FROM ".$spr_name." INNER JOIN ".$spr_id." ON ".$spr_name.".id=".$spr_id.".id  WHERE ".$spr_name.".id IN (SELECT ".$id_spr_name." FROM ".$altertable_with_id." WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].") ORDER BY rate DESC,name";
        }
        else
        {
        $sql="SELECT * FROM ".$spr_name." WHERE id IN (SELECT ".$id_spr_name." FROM ".$altertable_with_id." WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].") ORDER BY rate DESC";
		}
				   $mysql=mysql_query($sql);
				   if (mysql_num_rows($mysql))
				   {
     		echo"</table>
     			</td>
     				<td width=50% valign=top><fieldset><legend>Данные преподавателя</legend>
     				<table border=0 width=100%>
     						<tr>
     							<td align=center width=85%><b>".$title."</b></td>
     							<td align=left width=15%><b>Балл</b></td>
     				        </tr>
					<FORM method=POST>
							<tr>
					   <td colspan=2><table width=100% border=1 class=indplan>";
	$summa=0;
	while ($line=mysql_fetch_array($mysql))
						{

						echo"
						<tr onmouseover=this.style.background='Turquoise' onmouseout=this.style.background='#DFEFFF' >
						   <td width=85% align=left>".$line['name']."</td>
						   <td width=10% align=left>".$line['rate']."</td>
                           <td width=5%><input type='checkbox' name='checkbox_array_del[]' value=$line[0]"; if ($_GET['razdel']==1)echo " checked"; echo"></td>
	                    </tr>";
	                    $summa=$summa+$line['rate'];
	                    }
	                    echo"<tr><td align=left><b>Итого :</b></td><td align=left  colspan=2><b>".$summa."</b></td></tr>
	                    </table></td>
	                    	 </tr>
	                    	 <tr>
	                    <td align=right colspan=2><input type='submit' name='delete' value='<<Убрать' title='Убрать из списка выбранных'></FORM></td>
	                    	 </tr>";
	                    	 if ($mysql_add)
          					{
          				echo "<tr>
          				<td><h3 align=center>Данные преподавателю добавлены.</h3></td>
          					 </tr>";
          	       			}
          	       			if ($mysql_del)
          					{
          				echo "<tr>
          	<td><h3 align=center>Данные преподавателя отредактированы.</h3></td>
          					</tr>";
          					}
	                    	 echo"</table></fieldset></td>
	                    	 </tr>
                        </table>";



     			}
     			else
     			{
     				echo"</table>
     			</td><td width=50% align=center valign=top><br><fieldset><legend>Данные преподавателя</legend><b>Нет данных.</b></fieldset></td>
     			</tr></table>";
     			}






		}// if (isset($_GET['razdel']))
	} //if ($_GET['id_kadri']!=0)
else
{
if ($_GET['id_kadri']==0 && $_GET['id_year']==0)echo '<p align=center><h3>Выберите преподавателя и учебный год.</h3><br><a href="index_rating.php" class="notinfo">Назад</a></p>';
else
	{
	if ($_GET['id_year']==0)echo '<p align=center><h3>Выберите учебный год.</h3><br><a href="index_rating.php" class="notinfo">Назад</a></p>';
	if ($_GET['id_kadri']==0)echo '<p align=center><h3>Выберите преподавателя.</h3><br><a href="index_rating.php" class="notinfo">Назад</a></p>';
    }
}


}// if (isset($_GET['zapolnenie']))
else
{
//==================================Просмотр====================================
if (isset($_GET['prosmotr']))
{
  $select=mysql_query('SELECT k.id, concat(k.fio," (",count(*),")") as fio FROM kadri k
		      INNER JOIN summa_ballov sb ON k.id=sb.id_kadri
		      GROUP BY k.id,k.fio
		      ORDER BY k.fio') or die ("Возникла ошибка  ");
  $select1=mysql_query('SELECT ti.id, ti.name, count(*) as count FROM time_intervals ti
		       INNER JOIN summa_ballov sb ON ti.id=sb.id_year 
			GROUP BY ti.id, ti.name 
			ORDER BY ti.name desc') or die ("Возникла ошибка  ");

echo'<table  border=0><tr>
		<td ><b>Преподаватель: </b>';
		if (!isset($_GET['save']) && !isset($_GET['print']))
		{echo '<Select   name="id_kadri" ONCHANGE="top.location.href=this.options[this.selectedIndex].value"><option value="?prosmotr&id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'"></option>';
  	while ($fio=mysql_fetch_array($select))
   	{echo'<Option Value="?prosmotr&id_kadri='.$fio['id'].'&id_year='.$_GET['id_year'].'"'; if ($_GET['id_kadri']==$fio['id'])echo'selected'; echo'>'.$fio['fio'].'</Option>';}
    echo'</Select>';
    	}else while ($fio=mysql_fetch_array($select)){if ($_GET['id_kadri']==$fio['id'])echo $fio['fio'];}
    	echo'</td>
    	<td><b> &nbsp;&nbsp;&nbsp;учебный год: </b>';
    	if (!isset($_GET['save']) && !isset($_GET['print']))
    	{echo'<select name="id_year" ONCHANGE="top.location.href=this.options[this.selectedIndex].value"><option value="?prosmotr&id_kadri='.$_GET['id_kadri'].'&id_year='.$_GET['id_year'].'"></option>';
    while ($year=mysql_fetch_array($select1))
   	{echo'<Option Value="?prosmotr&id_kadri='.$_GET['id_kadri'].'&id_year='.$year['id'].'"'; if ($_GET['id_year']==$year['id'])echo'selected'; echo'>'.$year['name'].'('.$year['count'].')</Option>';}
   	echo'</select>';
   		}else while ($year=mysql_fetch_array($select1)){if ($_GET['id_year']==$year['id'])echo $year['name'];}

   	   echo'</td>
   	   <td align=right>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
       if (!isset($_GET['save']) && !isset($_GET['print']))
{echo '<a class=text href="?'.$_SERVER['QUERY_STRING'].'&save&attach=doc">Выгрузить в MS Word</a><br>';
echo '<a class=text href="?'.$_SERVER['QUERY_STRING'].'&print">Распечатать</a>';}
 echo'</td>
    				</tr></table>';

if ($_GET['id_kadri']!=0 && $_GET['id_year']!=0)
{
//--------------------Вывод показателей преподавателя---------------------------
if (!isset($_GET['save']) && !isset($_GET['print']))
{echo "<br><a href='index_rating.php' class='notinfo'>Назад</a><br><br>";}
//------------------------------Звание

			$sql="SELECT kadri.fio AS name,summa_ballov.* FROM summa_ballov INNER JOIN kadri ON summa_ballov.id_kadri=kadri.id WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year']."";
    		$mysql=mysql_query($sql) or die("Ошибка");
    		$summa=mysql_fetch_array($mysql);

            if ($summa)
            	{
    		$sql="SELECT id FROM summa_ballov WHERE id_year=".$_GET['id_year']." AND (zvanie+dolzhnost+nauch_met_uch_rab+vichet)>(SELECT  (zvanie+dolzhnost+nauch_met_uch_rab+vichet) FROM summa_ballov WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")";
	$rating=mysql_query($sql) or die("Ошибка ");
	$rating_total=mysql_num_rows($rating);
                 }
                 else
                 $rating_total="-1";
    		echo"<table border=1 align=center class=indplan><tr class=indplan><td>&nbsp;</td><th>Имя</th><th>Звание</th><th>Должность</th><th>Работа</th><th>Вычеты</th><th>Сумма</th></tr>";

    		echo"<tr><td width=5%>".($rating_total+1)."</td><td width=30%>".$summa['name']."</td><td width=10%>".str_replace('.',',',$summa['zvanie'])."</td><td width=10%>".str_replace('.',',',$summa['dolzhnost'])."</td><td width=10%>".str_replace('.',',',$summa['nauch_met_uch_rab'])."</td><td width=10%>".str_replace('.',',',$summa['vichet'])."</td><td width=10%>".str_replace('.',',',($summa['zvanie']+$summa['dolzhnost']+$summa['nauch_met_uch_rab']+$summa['vichet']))."</td></tr>";

		    echo"</table>";

$sql="SELECT zvanie.name AS name,spr_zvanie.rate FROM spr_zvanie INNER JOIN zvanie ON spr_zvanie.id=zvanie.id WHERE spr_zvanie.id IN (SELECT id_zvanie FROM zvanie_rate WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")";
$mysql=mysql_query($sql) or die("Ошибка ");
$zvanie=mysql_fetch_row($mysql);

$sql="SELECT MAX(round(rate,3)) FROM spr_zvanie";
	$mysql=mysql_query($sql) or die("Ошибка ");
	$max_zvanie=mysql_fetch_array($mysql);


if ($zvanie)
	    {

$sql="SELECT id FROM summa_ballov WHERE id_year=".$_GET['id_year']." AND zvanie>(SELECT  zvanie FROM summa_ballov WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")";
	$mysql=mysql_query($sql) or die("Ошибка ");
	$rating_zvanie=mysql_num_rows($mysql);

$sql="SELECT ((SELECT zvanie FROM summa_ballov WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year']." )/(SELECT SUM(round(zvanie,3)) FROM summa_ballov WHERE id_year=".$_GET['id_year']."))*100 FROM summa_ballov";
$mysql=mysql_query($sql) or die("Ошибка : ");
$prepod_percent=mysql_fetch_array($mysql);

echo "<table width=30% border=0 align=center>
				<tr>
	<td width=100%><fieldset ><legend>Звание</legend>
		<table border=0 width=100%>
			<tr>
		<td width=85%><b>Звание</b></td>
		<td><b>Балл</b></td>
			</tr>
			<tr>
		<td>".$zvanie[0]."</td>
		<td>".str_replace('.',',',$zvanie[1])."</td>
			</tr>";
			$percent_zvanie=($zvanie[1]/$max_zvanie[0])*100;
			echo "
       			<tr>
       	   <td><hr><b>Итого</b></td>
       	   <td><hr><b>".str_replace('.',',',$zvanie[1])."</b></td>
       			</tr>

			<tr>
		<td title='Процент от максимального звания'><b>%</b></td>
		<td title='Процент от максимального звания'><b>".str_replace('.',',',round($percent_zvanie,3))."</b></td>
			</tr>
			<tr>
       	   <td><b>Рейтинг</b></td>
       	   <td><b>".($rating_zvanie+1)."</b></td>
       		</tr>
       		<tr>
       	   <td title='Процент от набранного всеми преподавателями'><b>% от всех преподавателей</b></td>
       	   <td title='Процент от набранного всеми преподавателями'><b>".str_replace('.',',',round($prepod_percent[0],3))."</b></td>
       		</tr>
		</table></fieldset>
	</td>

				</tr>

		</table><br>";
		}
else
echo "<br><table width=30% align=center><tr><td><fieldset><legend>Звание</legend><b>Звание не выбрано.</b></fieldset></td></tr></table><br>";

//----------------------------------Должность
$sql="SELECT dolgnost.name AS name,spr_dolzhnost.rate FROM spr_dolzhnost INNER JOIN dolgnost ON spr_dolzhnost.id=dolgnost.id WHERE spr_dolzhnost.id IN(SELECT id_dolzhnost FROM dolzhnost WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")";
$mysql=mysql_query($sql) or die("Ошибка");
		$summa_dolzhnost=0;
		$sql="SELECT SUM(round(rate,3)) FROM spr_dolzhnost WHERE rate>0";
		$result=mysql_query($sql) or die("Ошибка");
		$sum_rate_dolzhnost=mysql_fetch_row($result);
if (mysql_num_rows($mysql))
	{
$sql="SELECT id FROM summa_ballov WHERE id_year=".$_GET['id_year']." AND dolzhnost>(SELECT  dolzhnost FROM summa_ballov WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")";
	$rating=mysql_query($sql) or die("Ошибка ");
	$rating_dolzhnost=mysql_num_rows($rating);

	$sql="SELECT ((SELECT dolzhnost FROM summa_ballov WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")/(SELECT SUM(round(dolzhnost,3)) FROM summa_ballov WHERE id_year=".$_GET['id_year']."))*100 FROM summa_ballov";
$percent=mysql_query($sql) or die("Ошибка ");
$prepod_percent=mysql_fetch_array($percent);

echo"<table width=40% border=0 align=center>
				<tr>
	<td width=100%><fieldset ><legend>Должность</legend>
		<table border=0 width=100%>
			<tr>
		<td width=85% ><b>Должность</b></td>
		<td ><b>Балл</b></td>
			</tr>";

		while($dolzhnost=mysql_fetch_array($mysql))
		{
           echo"<tr>
		<td>".$dolzhnost['name']."</td>
		<td>".str_replace('.',',',$dolzhnost['rate'])."</td>
			</tr>";
			$summa_dolzhnost=$summa_dolzhnost+$dolzhnost['rate'];
		}
           echo "
       			<tr>
       	   <td><hr><b>Итого</b></td>
       	   <td><hr><b>".str_replace('.',',',$summa_dolzhnost)."</b></td>
       			</tr>
       			";
       		$percent_dolzhnost=($summa_dolzhnost/$sum_rate_dolzhnost[0])*100;
       	    echo"
       	        <tr>
       		<td title='Процент от максимального набора должностей'><b>% от всех должностей</b></td>
       		<td title='Процент от максимального набора должностей'><b>".str_replace('.',',',round($percent_dolzhnost,3))."</b></td>
       			</tr>
       			<tr>
       	   <td><b>Рейтинг</b></td>
       	   <td><b>".($rating_dolzhnost+1)."</b></td>
       		    </tr>
       		    <tr>
       	   <td title='Процент от набранного всеми преподавателями'><b>% от всех преподавателей</b></td>
       	   <td title='Процент от набранного всеми преподавателями'><b>".str_replace('.',',',round($prepod_percent[0],3))."</b></td>
       		</tr>
       </table></fieldset>
	</td>
				</tr>
		</table><br>";

	}
    else
    echo "<br><table width=40% align=center><tr><td><fieldset><legend>Должность</legend><b>Должность не выбрана.</b></fieldset></td></tr></table><br>";
//-----------------------------Работы
$sql="SELECT name,rate FROM spr_nauch_met_uch_rab WHERE id IN(SELECT id_rab FROM nauch_met_uch_rab WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")";
$mysql=mysql_query($sql) or die("Ошибка  ");

			$summa_rabot=0;
			$sql="SELECT SUM(round(rate,3)) FROM spr_nauch_met_uch_rab WHERE rate>0";
		$result=mysql_query($sql) or die("Ошибка  ");
		$sum_rate_rab=mysql_fetch_row($result);
if (mysql_num_rows($mysql))
	{
    $sql="SELECT id FROM summa_ballov WHERE id_year=".$_GET['id_year']." AND nauch_met_uch_rab>(SELECT  nauch_met_uch_rab FROM summa_ballov WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")";
	$rating=mysql_query($sql) or die("Ошибка  ");
	$rating_rab=mysql_num_rows($rating);

	$sql="SELECT ((SELECT nauch_met_uch_rab FROM summa_ballov WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")/(SELECT SUM(round(nauch_met_uch_rab,3)) FROM summa_ballov WHERE id_year=".$_GET['id_year']."))*100 FROM summa_ballov";
$percent=mysql_query($sql) or die("Ошибка  ");
$prepod_percent=mysql_fetch_array($percent);

echo"<table width=40% border=0 align=center>
				<tr>
	<td width=100%><fieldset ><legend>Научно-методическая и учебная работа</legend>
		<table border=0 width=100%>
			<tr>
		<td width=85%><b>Описание</b></td>
		<td><b>Балл</b></td>
			</tr>";

		while($rabota=mysql_fetch_row($mysql))
		{
           echo"<tr>
		<td>".$rabota[0]."</td>
		<td>".str_replace('.',',',$rabota[1])."</td>
			</tr>";
			$summa_rabot=$summa_rabot+$rabota[1];
		}
          echo "
       			<tr>
       	   <td><hr><b>Итого</b></td>
       	   <td><hr><b>".str_replace('.',',',$summa_rabot)."</b></td>
       			</tr>";
       			$percent_rab=($summa_rabot/$sum_rate_rab[0])*100;
       		echo"
       			<tr>
       		<td title='Процент от максимального набора работ'><b>% от всех работ</b></td>
       		<td title='Процент от максимального набора работ'><b>".str_replace('.',',',round($percent_rab,3))."</b></td>
       			</tr>
       			<tr>
       	   <td><b>Рейтинг</b></td>
       	   <td><b>".($rating_rab+1)."</b></td>
       		    </tr>
       		    <tr>
       	   <td title='Процент от набранного всеми преподавателями'><b>% от всех преподавателей</b></td>
       	   <td title='Процент от набранного всеми преподавателями'><b>".str_replace('.',',',round($prepod_percent[0],3))."</b></td>
       		    </tr>
       </table></fieldset>
	</td>
				</tr>
		</table><br>";
	 }
    else
    echo "<br><table width=40% align=center><tr><td><fieldset><legend>Научно-методическая и учебная работа</legend><b>Работа не выбрана.</b></fieldset></td></tr></table><br>";

    //----------------------------------Вычеты
$sql="SELECT name,rate FROM spr_vichet WHERE id IN(SELECT id_vichet FROM vichet WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")";
$mysql=mysql_query($sql) or die("Ошибка  ");
		$summa_vichet=0;
		$sql="SELECT SUM(round(rate,3)) FROM spr_vichet";
		$result=mysql_query($sql) or die("Ошибка  ");
		$sum_rate_vichet=mysql_fetch_row($result);
if (mysql_num_rows($mysql))
	{
$sql="SELECT id FROM summa_ballov WHERE id_year=".$_GET['id_year']." AND vichet>(SELECT  vichet FROM summa_ballov WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")";
	$rating=mysql_query($sql) or die("Ошибка  ");
	$rating_vichet=mysql_num_rows($rating);

	$sql="SELECT ((SELECT vichet FROM summa_ballov WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")/(SELECT SUM(round(vichet,3)) FROM summa_ballov WHERE id_year=".$_GET['id_year']."))*100 FROM summa_ballov";
$percent=mysql_query($sql) or die("Ошибка  ");
$prepod_percent=mysql_fetch_array($percent);

echo"<table width=40% border=0 align=center>
				<tr>
	<td width=100%><fieldset ><legend>Вычеты</legend>
		<table border=0 width=100%>
			<tr>
		<td width=85% ><b>Вычеты</b></td>
		<td ><b>Балл</b></td>
			</tr>";

		while($vichet=mysql_fetch_row($mysql))
		{
           echo"<tr>
		<td>".$vichet[0]."</td>
		<td>".str_replace('.',',',$vichet[1])."</td>
			</tr>";
			$summa_vichet=$summa_vichet+$vichet[1];
		}
           echo "
       			<tr>
       	   <td><hr><b>Итого</b></td>
       	   <td><hr><b>".str_replace('.',',',$summa_vichet)."</b></td>
       			</tr>
       			";
       		$percent_vichet=($summa_vichet/$sum_rate_vichet[0])*100;
       	    echo"
       	        <tr>
       		<td title='Процент от максимального набора вычетов'><b>% от всех вычетов</b></td>
       		<td title='Процент от максимального набора вычетов'><b>".str_replace('.',',',round($percent_vichet,3))."</b></td>
       			</tr>
       			<tr>
       	   <td><b>Рейтинг</b></td>
       	   <td><b>".($rating_vichet+1)."</b></td>
       		    </tr>
       		    <tr>
       	   <td title='Процент от набранного всеми преподавателями'><b>% от всех преподавателей</b></td>
       	   <td title='Процент от набранного всеми преподавателями'><b>".str_replace('.',',',round($prepod_percent[0],3))."</b></td>
       		</tr>
       </table></fieldset>
	</td>
				</tr>
		</table><br>";

	}
    else
    echo "<br><table width=40% align=center><tr><td><fieldset><legend>Вычеты</legend><b>Вычетов нет.</b></fieldset></td></tr></table><br>";


    //------------------------------Итого
    $itog=$zvanie[1]+$summa_dolzhnost+$summa_rabot+$summa_vichet;

   $sql="SELECT ((SELECT (zvanie+dolzhnost+nauch_met_uch_rab+vichet) FROM summa_ballov WHERE id_kadri=".$_GET['id_kadri']." AND id_year=".$_GET['id_year'].")/(SELECT SUM(round(zvanie+dolzhnost+nauch_met_uch_rab+vichet,3)) FROM summa_ballov WHERE id_year=".$_GET['id_year']."))*100 FROM summa_ballov";
$percent=mysql_query($sql) or die("Ошибка  ");
$prepod_percent=mysql_fetch_array($percent);

    echo"<table width=40% border=0 align=center>
    	 <tr><td><fieldset><legend>Итого</legend>
    	 	<table width=100%>
    	 	<tr>
    <td width=85%><b>Общий балл : </b></td><td><b>".str_replace('.',',',$itog)."</b></td>
    		</tr>
    		<tr>
    		<td title='Процент от максимального набора по всем показателям'><b>%</b></td>
    		<td title='Процент от максимального набора по всем показателям'><b>".str_replace('.',',',round(($itog/($max_zvanie[0]+$sum_rate_dolzhnost[0]+$sum_rate_rab[0]))*100,3))."</b></td>
    		</tr>
    		<tr>
       	   <td><b>Рейтинг</b></td>
       	   <td><b>".($rating_total+1)."</b></td>
       		</tr>
       		<tr>
       	   <td title='Процент от набранного всеми преподавателями'><b>% от всех преподавателей</b></td>
       	   <td title='Процент от набранного всеми преподавателями'><b>".str_replace('.',',',round($prepod_percent[0],3))."</b></td>
       		 </tr>
          	</table></fieldset>
         </td></tr>
         </table>";

if (!isset($_GET['save']) && !isset($_GET['print']))

{echo "<br><br><a href='index_rating.php' class='notinfo'>Назад</a><br><br>";}
}// if ($_GET['id_kadri']!=0 && $_GET['id_year']!=0)
else
{
if ($_GET['id_kadri']==0 && $_GET['id_year']==0)echo '<p align=center><h3>Выберите преподавателя и учебный год.</h3><br><a href="index_rating.php" class="notinfo">Назад</a></p>';
else
	{
	if ($_GET['id_year']==0)echo '<p align=center><h3>Выберите учебный год.</h3><br><a href="index_rating.php" class="notinfo">Назад</a></p>';
	if ($_GET['id_kadri']==0)echo '<p align=center><h3>Выберите преподавателя.</h3><br><a href="index_rating.php" class="notinfo">Назад</a></p>';
    }
}
}//if (isset($_GET['prosmotr']))
else
{
//============================Ссылки=======================================



echo'<div><br>
<a  id="ssilka3"  href="rating_table.php?page_number=1&limit=20&id_year=0"  class="notinfo">Рейтинговая таблица</a>
<p><br><a id="ssilka1"  href="index_rating.php?prosmotr&id_kadri=0&id_year=0"  class="notinfo">Просмотреть</a>
<p><br> <a id="ssilka2"  href="index_rating.php?zapolnenie&id_kadri=0&id_year=0&razdel=1" class="notinfo">Заполнить</a>
<p><br><a id="ssilka" href="edit.php" class="notinfo">Редактировать</a></div>';
}//else if (isset($_GET['prosmotr']))
}//else if (isset($_GET['zapolnenie']))
?>

</body>

</html>