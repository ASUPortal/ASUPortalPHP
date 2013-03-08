<?php
include "config.php";

include $portal_path."authorisation.php";
include $portal_path."master_page_short.php";

//Добавление,удаление и редактирование данных.
?>

<LINK REL="STYLESHEET" TYPE="text/css" HREF="rating_style.css">
<script type="text/javascript"  src="rating_javascript.js"></script>
<?php
//=======================Ссылки===================================================

//--------------------------На главную-----------------------------

echo"<table><tr><td><p><a href='index_rating.php' class='notinfo'>Назад</a></td>";
//--------------------------1ый раздел-----------------------------




//--------------------------2ой раздел-----------------------------

echo"<td>&nbsp;&nbsp;</td><td><p><a href='?razdel=2' class='href_menu_rating'>1. Звания</a></td>";


//--------------------------3ий раздел-----------------------------

echo"<td>&nbsp;&nbsp;</td><td><p><a href='?razdel=3' class='href_menu_rating'>2. Должности</a></td>";


//--------------------------4ый раздел-----------------------------

echo"<td>&nbsp;&nbsp;</td><td><p><a href='?razdel=4' class='href_menu_rating'>3. Научно-методическая и учебная работа</a></td>";

//--------------------------5ый раздел-----------------------------

echo"<td>&nbsp;&nbsp;</td><td><p><a href='?razdel=5' class='href_menu_rating'>4. Вычеты</a></td></tr></table>";




//================================Обработка данных ===================================

//------------------------------1ый раздел ------------------------------------------

//-------------------------------2,3,4 разделы------------------------------------------

     	if (isset($_GET['razdel']) )
   {
         if ( $_GET['razdel']==4 or $_GET['razdel']==5)
      {
     	//--------------присвоение переменных  в зависимости от выбранного раздела-----------------
     	switch ($_GET['razdel'])
     	{

     		case 4:
     		$spr_name='spr_nauch_met_uch_rab';// основная таблица справочник
     		$title='Описание';                 // заголовок таблицы
     		$altertable_with_id='nauch_met_uch_rab';// таблица с id из табл. справочника (для удаления из неё инфы по id основной таблицы)
     		$id_spr_name='id_rab';                //поле таблицы по которому осуществляется удаление
     		$summa_ballov_name='nauch_met_uch_rab'; //поле в таблице summa_ballov
     		break;

     		case 5:
     		$spr_name='spr_vichet';
     		$title='Вычеты';
     		$altertable_with_id='vichet';
     		$id_spr_name='id_vichet';
     		$summa_ballov_name='vichet';
     		break;

     	}



//---------------Добавление данных-----------------------
     		if ((isset($_POST['name'])) && (isset($_POST['rate'])))
     		        {
     		         if (($_POST['name']!='') && ($_POST['rate']!=''))
     		             {
 			                $name=mysql_escape_string($_POST['name']);
                            $rate=mysql_escape_string(str_replace(',','.',$_POST['rate']));
                            $sql="INSERT INTO ".$spr_name." (name,rate) VALUES ('$name','$rate')";
							$result=mysql_query($sql)or die("Возникла ошибка: ");

							echo "<p><H3 align=center>Данные добавлены.</H3></p>";

					     }
					}
                $check=true; $id_kadri=false;
          //---------------Изменение-----------------------
			if (isset($_POST['save']))
				{
				  //пересчёт summa_ballov

                  foreach ($_POST['checkbox_array'] as $i)
                  {
                  	  //получение id_kadri для пересчёта таблицы summa_ballov при изменении баллов
                                   	$sql="SELECT id_kadri,id_year FROM ".$altertable_with_id." WHERE ".$id_spr_name."=".$i."";
                   	   				$summa_ballov=mysql_query($sql) or die("Ошибка пересчёта#0 таблицы summa_ballov(произведите редактирование ещё раз!)");


                  }


                     //изменение
 				foreach ($_POST['checkbox_array'] as $i)
					{


	 					$name=mysql_escape_string($_POST['name'.$i.'']);
                        $rate=mysql_escape_string(str_replace(',','.',$_POST['rate'.$i.'']));
                        if ($name!='' && $rate!='') //проверка на пустые значения
                                {
							$sql="UPDATE ".$spr_name." SET name='$name',rate='$rate' WHERE id=$i";
							$mysql=mysql_query($sql) or die("Возникла ошибка (произведите редактирование ещё раз!) ");

                                 }
                         else {$mysql=false; $unchanged[]=$i;}
                         $check=($check && $mysql);

					}
                    //пересчёт таблицы summa_ballov для преподавателей, у которых изменился балл по званию,должности или работе

                     	While ($kadri=mysql_fetch_array($summa_ballov))
                    	 {
                         $sql="UPDATE summa_ballov SET ".$summa_ballov_name."=(SELECT SUM(round(rate,3)) FROM ".$spr_name."  WHERE id IN (SELECT ".$id_spr_name." FROM ".$altertable_with_id." WHERE id_kadri=".$kadri['id_kadri']." AND id_year=".$kadri['id_year'].")) WHERE id_kadri=".$kadri['id_kadri']." AND id_year=".$kadri['id_year']."";
                         $mysql=mysql_query($sql) or die("Ошибка пересчёта#1 таблицы summa_ballov(произведите редактирование ещё раз!):");
                     	 }


					if ($check)
					{
                 echo "<h3 align=center>Данные изменены.</h3>";
				    }
				    else // если были пустые поля ещё раз выводим данные для изменения
				    {
				    	echo"<h3 align=center>Записи с пустыми полями не были изменены:</h3>";
				    	echo "<table border=0 width=65% align=center>
					<tr>
				<td align=center><b>".$title."</b></td>
	            <td align=right><b>Балл</b></td>
			       </tr><br>
			       <FORM method=POST>";

				    		foreach ($unchanged as $i)
				    		{
				    echo "<input name=checkbox_array[] type='hidden' value=".$i.">";

 					$sql="SELECT * FROM ".$spr_name." WHERE id=".$i."";
 					$mysql=mysql_query($sql) or die("Возникла ошибка (произведите редактирование ещё раз!) ");
 					$name=mysql_fetch_row($mysql);
 					echo "<tr>
 					<td><input type='text' name='name".$i."' value='$name[1]' maxlength='100' size=90%></td>
                    <td><input type='text' name='rate".$i."' value='".str_replace('.',',',$name[2])."' maxlength='6' size=10%></td>
			             </tr>
					";
				    		}
				    		echo "</table><table  width=65% align=center><tr><td><input type='submit' name='save' value='Изменить'></td></tr></table></FORM>";
            echo "<br><br><a href='?razdel=".$_GET['razdel']."'>Отмена</a>";

				    	 }
               }

         //--------------------Редактирование---------------------------
         if( (isset($_POST['re']))and (isset($_POST['checkbox_array'])))
			{

					echo "<table border=0 width=65% align=center>
					<tr>
				<td align=center><b>".$title."</b></td>
	            <td align=right><b>Балл</b></td>
			       </tr><br>
			       <FORM method=POST>";

				foreach ($_POST['checkbox_array'] as $i)
				{
 					echo "<input name=checkbox_array[".$i."] type='hidden' value=".$i.">";

 					$sql="SELECT * FROM ".$spr_name." WHERE id=".$i."";
 					$mysql=mysql_query($sql) or die("Возникла ошибка (произведите редактирование ещё раз!) ");
 					$name=mysql_fetch_row($mysql);
 					echo "<tr>
 					<td><input type='text' name='name".$i."' value='$name[1]' maxlength='100' size=90%></td>
                    <td><input type='text' name='rate".$i."' value='".str_replace('.',',',$name[2])."' maxlength='6' size=10%></td>
			             </tr>
					";

				}

			echo "</table><table  width=65% align=center><tr><td><input type='submit' name='save' value='Изменить'></td></tr></table></FORM>";
            echo "<br><br><a href='?razdel=".$_GET['razdel']."'>Отмена</a>";
			}
		else
			{

		//----------------------------Удаление-----------------------------------

		if((isset($_POST['del'])) && (isset($_POST['checkbox_array'])))
	{                   //пересчёт summa_ballov и удаление из табл. $altertable_with_id
						foreach ($_POST['checkbox_array'] as $i)
	                     {
	                     //получение id_kadri для пересчёта таблицы summa_ballov при удалении
                                   	$sql="SELECT id_kadri,id_year FROM ".$altertable_with_id." WHERE ".$id_spr_name."=".$i."";
                   	   				$summa_ballov=mysql_query($sql) or die("Ошибка пересчёта#0 таблицы summa_ballov (произведите удаление ещё раз!) ");


	                     //удаление

	                     	 $sql="DELETE FROM ".$altertable_with_id." WHERE ".$id_spr_name."='$i'";

	                         $mysql=mysql_query($sql) or die("Возникла ошибка (произведите удаление ещё раз!)  ");

	                     }
                         //пересчёт таблицы summa_ballov для преподавателей, у которых изменился балл по званию,должности или работе

                     	While ($kadri=mysql_fetch_array($summa_ballov))
                    	 {
                         $sql="UPDATE summa_ballov SET ".$summa_ballov_name."=(SELECT SUM(round(rate,3)) FROM ".$spr_name."  WHERE id IN (SELECT ".$id_spr_name." FROM ".$altertable_with_id." WHERE id_kadri=".$kadri['id_kadri']." AND id_year=".$kadri['id_year'].")) WHERE id_kadri=".$kadri['id_kadri']." AND id_year=".$kadri['id_year']."";
                         $mysql=mysql_query($sql) or die("Ошибка пересчёта#1 таблицы summa_ballov (произведите удаление ещё раз!)");
                         }

        //удаление из таблицы справочника
		foreach ($_POST['checkbox_array'] as $i)
			{

				$sql="DELETE FROM ".$spr_name." WHERE id='$i'";
 				$mysql=mysql_query($sql) or die("Возникла ошибка (произведите удаление ещё раз!)");

			}



          if ($mysql)
          {
          	echo "<h3 align=center>Данные удалены.</h3>";
          }
	}

      if ($check)
      {
     	//------------------Форма вывода------------------------------------
     	$sql="SELECT * FROM ".$spr_name." ORDER BY rate DESC,name";
		$mysql=mysql_query($sql);
            if (mysql_num_rows($mysql)==0)
      		{
  echo "<table width=100% border=0><tr><td width=50%><table width=100%><td align=center><b>Нет данных.</b></td></td></tr>";
     	 	}
     		else
     		{
     		//------------Вывод данных-----------------
     		echo "<table width=100%><tr>
     			<td width=60%><table border=0 width=100%>
     			            <tr>
     				<td align=center width=80%><b>".$title."</b></td>
     		        <td align=left width=20%><b>Балл</b></td>
     			            </tr><FORM method='POST'>
     					   <tr>
     					   <td colspan=2><table width=100% border=1 class='indplan'>";

     					   $i=1;
			while ($line=mysql_fetch_row($mysql))
					{$id=$line[0];
     				echo "<tr onmouseover=this.style.background='Turquoise' onmouseout=this.style.background='#DFEFFF' ><td  width=5%>$i</td>
     						  <td width=80%>$line[1]</td>
     					      <td width=15%>".str_replace('.',',',$line[2])."</td>
     						<td><input type='checkbox' name=checkbox_array[] value=".$id."></td>
     					</tr>";
     				++$i;
					}
			echo '</table></td>
			              </tr>
			       <tr><td align=left><input type="submit" value="Удалить" id="del" name="del" title="Удаление" OnClick="Delete()">
			<input type="submit" value="Редактировать" name="re" title="Редактирование">
			</FORM>
				     </td>
				  </tr>';
          }
          //----------Форма для добавления данных-------------
     		echo"</table>
     			</td>
     				<td width=40% valign=top><fieldset><legend>Добавление</legend>
     				<table border=0 width=100%>
     						<tr>
     							<td align=center><b>".$title."</b></td><td align=center><b>Балл</b></td>
     				        </tr>
	<FORM method=POST>
						<tr>
						   <td align=center><input type='text' name='name'  value='' maxlength='100' size=45%></td>
						   <td align=right><input type='text' name='rate'  value='' maxlength='6' size=5%></td>

	                    </tr>
	                    <tr>
	                    <td align=right colspan=2><input type='submit' name='ok' value='Добавить'></FORM></td>
	                    </tr>";
                      //выведение предупреждения, если заполнены не все поля
                       if (isset($_POST['ok']))
            {
     		if (($_POST['name']=='') or ($_POST['rate']=='')){echo "<tr><td align=center><br><b>Вы ввели не все данные.</b></td></tr>";};
             }
                        echo"
	                </table></fieldset>
				    </td>
     						</tr></table>";
     	echo "<p><a href='index_rating.php'>Назад</a></td>";
      }
        }// end of else редактирование
   }
     if ($_GET['razdel']==3 or $_GET['razdel']==2 )
      {
      	  //--------------присвоение переменных  в зависимости от выбранного раздела-----------------
     	switch ($_GET['razdel'])
     	{

     		case 2:
     		$spr_name='zvanie';   // основная таблица справочник
     		$spr_id='spr_zvanie';     //подсправочник
     		$title='Звание';          // заголовок таблицы
     		$altertable_with_id='zvanie_rate'; // таблица с id из табл. справочника (для удаления из неё инфы по id основной таблицы)
     		$id_spr_name='id_zvanie';   //поле таблицы по которому осуществляется удаление
     		$summa_ballov_name='zvanie'; //поле в таблице summa_ballov
     		break;

     		case 3:
     		$spr_name='dolgnost';
     		$spr_id='spr_dolzhnost';
     		$title='Должность';
     		$altertable_with_id='dolzhnost';
     		$id_spr_name='id_dolzhnost';
     	    $summa_ballov_name='dolzhnost';
     		break;


     	}




                $check=true; $id_kadri=false;
          //---------------Изменение-----------------------
			if (isset($_POST['save']))
				{
				  //пересчёт summa_ballov

                  foreach ($_POST['checkbox_array'] as $i)
                  {
                  	  //получение id_kadri для пересчёта таблицы summa_ballov при изменении баллов
                                   	$sql="SELECT id_kadri,id_year FROM ".$altertable_with_id." WHERE ".$id_spr_name."=".$i."";
                   	   				$summa_ballov=mysql_query($sql) or die("Ошибка пересчёта#0 таблицы summa_ballov(произведите редактирование ещё раз!)");


                  }


                     //изменение
 				foreach ($_POST['checkbox_array'] as $i)
					{



                        $rate=mysql_escape_string(str_replace(',','.',$_POST['rate'.$i.'']));
                        if ($rate!='') //проверка на пустые значения
                                {
                                	$insert=mysql_query("SELECT * FROM ".$spr_id." WHERE id=".$i."") or die("Ошибка");
                                	if(mysql_num_rows($insert)!=0)
                                	{
							$sql="UPDATE ".$spr_id." SET rate='".floatval($rate)."' WHERE id=$i";
							$mysql=mysql_query($sql) or die("Возникла ошибка (произведите редактирование ещё раз!)");
                                     }
                                     else
                                     {
                                     $sql="INSERT INTO ".$spr_id." (id,rate) VALUES ('$i','".floatval($rate)."')";
									 $mysql=mysql_query($sql)or die("Возникла ошибка (произведите редактирование ещё раз!)");
                                     }
                                 }
                         else {$mysql=false; $unchanged[]=$i;}
                         $check=($check && $mysql);

					}
                    //пересчёт таблицы summa_ballov для преподавателей, у которых изменился балл по званию,должности или работе

                     	While ($kadri=mysql_fetch_array($summa_ballov))
                    	 {
                         $sql="UPDATE summa_ballov SET ".$summa_ballov_name."=(SELECT SUM(round(rate,3)) FROM ".$spr_id."  WHERE id IN (SELECT ".$id_spr_name." FROM ".$altertable_with_id." WHERE id_kadri=".$kadri['id_kadri']." AND id_year=".$kadri['id_year'].")) WHERE id_kadri=".$kadri['id_kadri']." AND id_year=".$kadri['id_year']."";
                         $mysql=mysql_query($sql) or die("Ошибка пересчёта#1 таблицы summa_ballov(произведите редактирование ещё раз!)");
                     	 }


					if ($check)
					{
                 echo "<h3 align=center>Данные изменены.</h3>";
				    }
				    else // если были пустые поля ещё раз выводим данные для изменения
				    {
				    	echo"<h3 align=center>Записи с пустыми полями не были изменены:</h3>";
				    	echo "<table border=1 width=65% align=center class='indplan'>
					<tr>
				<th align=center width=85%><b>".$title."</b></th>
	            <th align=center><b>Балл</b></th>
			       </tr><br>
			       <FORM method=POST>";

				    		foreach ($unchanged as $i)
				    		{
				    echo "<input name=checkbox_array[] type='hidden' value=".$i.">";

 					$sql="SELECT ".$spr_name.".*,(SELECT rate FROM ".$spr_id." WHERE ".$spr_name.".id=".$spr_id.".id) AS rate FROM ".$spr_name." WHERE id=".$i."";
 					$mysql=mysql_query($sql) or die("Возникла ошибка (произведите редактирование ещё раз!)");
 					$name=mysql_fetch_array($mysql);
 					echo "<tr>
 					<td>".$name['name']."</td>
                    <td><input type='text' name='rate".$i."' value='".str_replace('.',',',round($name['rate'],3))."' maxlength='6' size=10%></td>
			             </tr>
					";
				    		}
				    		echo "</table><table  width=65% align=center><tr><td><input type='submit' name='save' value='Изменить'></td></tr></table></FORM>";
            echo "<br><br><a href='?razdel=".$_GET['razdel']."'>Отмена</a>";

				    	 }
               }

         //--------------------Редактирование---------------------------
         if( (isset($_POST['re']))and (isset($_POST['checkbox_array'])))
			{

					echo "<table width=65% align=center border=1 class='indplan'>
					<tr>
				<th align=center width=85%>".$title."</th>
	            <th align=center>Балл</th>
			       </tr><br>
			       <FORM method=POST>";

				foreach ($_POST['checkbox_array'] as $i)
				{
 					echo "<input name=checkbox_array[".$i."] type='hidden' value=".$i.">";

 					$sql="SELECT ".$spr_name.".*,(SELECT rate FROM ".$spr_id." WHERE ".$spr_name.".id=".$spr_id.".id) AS rate FROM ".$spr_name." WHERE id=".$i."";
 					$mysql=mysql_query($sql) or die("Возникла ошибка (произведите редактирование ещё раз!)");
 					$name=mysql_fetch_array($mysql);
 					echo "<tr>
 					<td>".$name['name']."</td>
                    <td><input type='text' name='rate".$i."' value='".str_replace('.',',',round($name['rate'],3))."' maxlength='6' size=10%></td>
			             </tr>
					";

				}

			echo "</table><table  width=65% align=center><tr><td><input type='submit' name='save' value='Изменить'></td></tr></table></FORM>";
            echo "<br><br><a href='?razdel=".$_GET['razdel']."'>Отмена</a>";
			}
		else
			{

		//----------------------------Удаление-----------------------------------

		if((isset($_POST['del'])) && (isset($_POST['checkbox_array'])))
	{                   //пересчёт summa_ballov и удаление из табл. $altertable_with_id
						foreach ($_POST['checkbox_array'] as $i)
	                     {
	                     //получение id_kadri для пересчёта таблицы summa_ballov при удалении
                                   	$sql="SELECT id_kadri,id_year FROM ".$altertable_with_id." WHERE ".$id_spr_name."=".$i."";
                   	   				$summa_ballov=mysql_query($sql) or die("Ошибка пересчёта#0 таблицы summa_ballov (произведите удаление ещё раз!) ");


	                     //удаление

	                     	 $sql="DELETE FROM ".$altertable_with_id." WHERE ".$id_spr_name."='$i'";

	                         $mysql=mysql_query($sql) or die("Возникла ошибка (произведите удаление ещё раз!)");

	                     }
                         //пересчёт таблицы summa_ballov для преподавателей, у которых изменился балл по званию,должности или работе

                     	While ($kadri=mysql_fetch_array($summa_ballov))
                    	 {
                         $sql="UPDATE summa_ballov SET ".$summa_ballov_name."=(SELECT SUM(rate) FROM ".$spr_id."  WHERE id IN (SELECT ".$id_spr_name." FROM ".$altertable_with_id." WHERE id_kadri=".$kadri['id_kadri']." AND id_year=".$kadri['id_year'].")) WHERE id_kadri=".$kadri['id_kadri']." AND id_year=".$kadri['id_year']."";
                         $mysql=mysql_query($sql) or die("Ошибка пересчёта#1 таблицы summa_ballov (произведите удаление ещё раз!)");
                         }

        //удаление из таблицы подсправочника
		foreach ($_POST['checkbox_array'] as $i)
			{

				$sql="DELETE FROM ".$spr_id." WHERE id='$i'";
 				$mysql=mysql_query($sql) or die("Возникла ошибка (произведите удаление ещё раз!)");

			}



          if ($mysql)
          {
          	echo "<h3 align=center>Данные удалены.</h3>";
          }
	}

      if ($check)
 	{
     	//------------------Форма вывода------------------------------------


		$sql="SELECT ".$spr_name.".*, ".$spr_id.".rate AS rate FROM ".$spr_name." LEFT JOIN ".$spr_id." ON ".$spr_name.".id=".$spr_id.".id ORDER BY rate DESC";
		$mysql=mysql_query($sql);
            if (mysql_num_rows($mysql)==0)
      		{
  echo "<table width=100% border=0><tr><td width=50%><table width=100%><td align=center><b>Нет данных.</b></td></td></tr>";
     	 	}
     		else
     		{
     		//------------Вывод данных-----------------
     		echo "<table width=100%><tr>
     			<td width=60%><table border=0 width=100%>
     			            <tr>
     				<td align=center width=80%><b>".$title."</b></td>
     		        <td align=left width=20%><b>Балл</b></td>
     			            </tr><FORM method='POST'>
     					   <tr>
     					   <td colspan=2><table width=100% border=1 class='indplan'>";

     					   $i=1;
			while ($line=mysql_fetch_array($mysql))
					{$id=$line[0];
     				echo "<tr onmouseover=this.style.background='Turquoise' onmouseout=this.style.background='#DFEFFF' ><td  width=5%>$i</td>
     						  <td width=80%>".$line['name']."</td>
     					      <td width=15%>".str_replace('.',',',round($line['rate'],3))."</td>
     						<td><input type='checkbox' name=checkbox_array[] value=".$id."></td>
     					</tr>";
     				++$i;
					}
			echo '</table></td>
			              </tr>
			       <tr><td align=left><input type="submit" value="Удалить балл" id="del" name="del" title="Удаление" OnClick="Delete()">
			<input type="submit" value="Редактировать балл" name="re" title="Редактирование">
			</FORM>
				     </td>
				  </tr>';
          }
          //----------Форма для добавления данных-------------
     		echo"</table>
     			</td>
     				<td width=40% valign=top>
				    </td>
     						</tr></table>";
     	echo "<p><a href='index_rating.php'>Назад</a></td>";
 	}
        }// end of else редактирование
      }
 }
?>