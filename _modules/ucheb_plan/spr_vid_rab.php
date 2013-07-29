<?php
include "config.php";

include $portal_path."authorisation.php";
include $portal_path."master_page_short.php";

?>
<LINK REL="STYLESHEET" TYPE="text/css" HREF="indplan.css">
<script type="text/javascript"  src="indplan.js"></script>

<p class="main"><?php echo $head_title;?></p>
<?php
//---------------------------------------------Раздел 2--------------------------------------
echo '<p><a class="notinfo" href="?id_razdel2">1. Справочник учебно- и организационно-методическая работа</a><br>';
if (isset($_GET['id_razdel2']))
 {
//---------------------------------------------Добавление в базу данных----------------------
 if (isset($_POST['save']))
 {
 	 if (!($_POST['work']!='' && $_POST['time']!=''))
    {
    	echo '<h3 class=warning>Были обнаружены записи с пустыми полями</h3>';
 	}
 	else
 	{
 	$sql="INSERT INTO spravochnik_vidov_rabot (id,id_razdel,name,time_norm) VALUES ('".$_POST['max']."','2','".$_POST['work']."','".$_POST['time']."')";
 	mysql_query($sql) or die("Возникла ошибка добавление : ".mysql_error());

  echo '<h3 class=success>Данные успешно добавлены</h3>';
  }
 }
 //---------------------------------------------Удаление из базы данных----------------------
 if ((isset($_POST['delete']))&& (isset($_POST['delete_rab'])))
 {
 foreach ($_POST['delete_rab'] as $i)
  {
  $sql="DELETE FROM spravochnik_vidov_rabot WHERE id='$i'";
  $sql1="DELETE FROM uch_org_rab WHERE id_vidov_rabot='$i'";
  mysql_query($sql1) or die("Возникла ошибка удаления : ".mysql_error());
  mysql_query($sql) or die("Возникла ошибка удаления : ".mysql_error());
  echo'<h3 class=success>Данные успешно удалены</h3>';
  }
 }

 //---------------------------------------Редактирование в базе данных----------------------
 if((isset($_POST['edit']))and (isset($_POST['delete_rab'])))
			{

					echo "<table class=indplan border=1 width=65%><tr class=indplan><th>Список учебно- и организационно-методических работ</td><th>Нормы времени в час</td></tr><br>";
					echo "<FORM method=POST>";
			foreach ($_POST['delete_rab'] as $i)
				{
 					echo "<input name=delete_rab[".$i."] type='hidden' value=".$i.">";

 					$sql="SELECT * FROM spravochnik_vidov_rabot WHERE id=".$i."";
 					$mysql=mysql_query($sql);
 					$name=mysql_fetch_row($mysql);
 					echo "<tr>
					<td><textarea name='name".$i."' rows=4 cols=38 maxlength='200'>$name[2]</textarea></td>
 					<td><textarea name='time_norm".$i."' rows=4 cols=38 maxlength='300'>$name[3]</textarea></td>
					</tr>
					";
				}
			echo "</table><input type='submit' name='izmen' value='Изменить'></FORM>";

			}
		else
			{
           //---------------Изменение-----------------------
           $check=true;
			if (isset($_POST['izmen']))
				{

 				foreach ($_POST['delete_rab'] as $i)
					{


	 					$name=mysql_escape_string($_POST['name'.$i.'']);
	 					$time_norm=mysql_escape_string($_POST['time_norm'.$i.'']);
                         if ($name!=''&& $time_norm!='') //проверка на пустые значения
                               {
							$sql="UPDATE spravochnik_vidov_rabot SET name='$name',time_norm='$time_norm' WHERE id=$i";
							$mysql=mysql_query($sql) or die("Возникла ошибка: ".mysql_error());
                                }
                                else
                                {$mysql=false; $unchanged[]=$i;}
                        		 $check=($check && $mysql);
					}
					if ($check)
					{
                 echo "<h3 align=center>Данные изменены.</h3>";
				    }
				    else // если были пустые поля ещё раз выводим данные для изменения
				    {
				    	echo"<h3 align=center>Были обнаружены записи с пустыми полями</h3>";
				    	echo "<table class=indplan border=1 width=65%>
					<tr>
				<th>Список учебно- и организационно-методических работ</td>
				<th>Нормы времени в часах</td>
	                </tr><br>
			       <FORM method=POST>";

				    		foreach ($unchanged as $i)
				    		{
				    echo "<input name=delete_rab[".$i."] type='hidden' value=".$i.">";

 					$sql="SELECT * FROM spravochnik_vidov_rabot WHERE id=".$i."";
 					$mysql=mysql_query($sql);
 					$name=mysql_fetch_row($mysql);
 					echo "<tr>
 					<td><textarea name='name".$i."' rows=4 cols=38 maxlength='200'>$name[2]</textarea></td>
 					<td><textarea name='time_norm".$i."' rows=4 cols=38 maxlength='300'>$name[3]</textarea></td>
                          </tr>
					";
				    		}
				    		echo "</table><input type='submit' name='izmen' value='Изменить'></FORM>";
           				   }
                }
                }
  //------------------------------------------------------Добавление работы-------------------------------------
 if (isset ($_POST['add']))
 {
 $max=0;
 $sql="SELECT MAX(id) FROM spravochnik_vidov_rabot";
 $mysql=mysql_query($sql);
 $max=mysql_fetch_row($mysql);

 $max=1+$max[0];

 ?>
 <form method="POST" action="" name="form_id_razdel2" id="form_id_razdel2">
 <input type="hidden" name="max" value="<?php echo $max?>">
 <div class=text style="float: left;">
 Название работы<br>
 <textarea name="work" id="work" rows=4 cols=30 maxlength="200" title="Название работы"></textarea>
 <span class=warning>*</span>
 </div>
 <div class=text style="float: left;">
 Норма времени<br>
 <textarea name="time" id="time" rows=4 cols=30 maxlength="300" title="Норма времени"></textarea>
 <span class=warning>*</span>
 </div>
 <br style="clear: both;">
    <input type="submit" name="save" value="Добавить"
	onclick="return requireFieldCheck(new Array(new Array('work',''),new Array('time','')));">    
 </form>
 <?php

 }
 //-------------------------------------------Перевод работ в другие разделы----------------------
if((isset($_POST['delete_rab']))and (isset($_POST['change'])))
 {
 foreach($_POST['delete_rab'] as $i)
 {
 $sql="UPDATE spravochnik_vidov_rabot SET id_razdel=".$_POST['razdel']." WHERE id=$i";
 $mysql=mysql_query($sql) or die("Возникла ошибка: ".mysql_error());

 }
 echo '<h3 class=success>Данные успешно изменены</h3>';
 }
//---------------------------------------------Вывод-----------------------------------------
 $sql="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=2 ORDER BY name ";
 $mysql=mysql_query($sql);
 $check=mysql_num_rows($mysql);
 if (!$check)
 echo'<h3>Справочник учебно- и организационно-методических работ пуст</h3>
 <form method="POST" action=""><input type="submit" name="add" value="Добавить работу"></form>';
 else
 {
 echo '<form method="POST" action="">
 <input type="submit" name="delete" value="Удалить работу" onclick="confirm_delete()" id="delete">
 <input type="submit" name="edit" value="Редактировать работу">
 <input type="submit" name="add" value="Добавить работу">
 <p><b>выбранные работы перевести в раздел:</b><select size="1" name="razdel" ><option value=3>научно-методических и госбюджетных научно-исследовательских</option>
 <option value=4>учебно-воспитательных</option></select>
 <input type="submit" name="change" value="Ввод">
 <table border=1 class=indplan><tr class=indplan><th>Список учебно- и организационно-методических работ</td><th>Нормы времени в часах </td><th></td></tr>';
 while($name=mysql_fetch_array($mysql))
 {
 echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF"><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type="checkbox" name="delete_rab[]" value="'.$name['id'].'"></td></tr>';
 }
 echo '</table>';
 echo '</form>';

 }

 }
 //---------------------------------------------Раздел 3--------------------------------------
echo '<p><a class="notinfo" href="?id_razdel3">2. Справочник научно-методическая и госбюджетная научно-исследовательская работа</a><br>';
if (isset($_GET['id_razdel3']))
 {
//---------------------------------------------Добавление в базу данных----------------------
 if (isset($_POST['save']))
 {
 	if (!($_POST['work']!='' && $_POST['time']!=''))
    {
    	echo '<h3>Были обнаружены записи с пустыми полями</h3>';
 	}
 	else
 	{
 	$sql="INSERT INTO spravochnik_vidov_rabot (id,id_razdel,name,time_norm) VALUES ('".$_POST['max']."','3','".$_POST['work']."','".$_POST['time']."')";
 	mysql_query($sql) or die("Возникла ошибка добавление : ".mysql_error());

  echo '<h3 class=success>Данные успешно добавлены</h3>';
    }
 }
 //---------------------------------------------Удаление из базы данных----------------------
 if ((isset($_POST['delete']))&& (isset($_POST['delete_rab'])))
 {
 foreach ($_POST['delete_rab'] as $i)
  {
  $sql="DELETE FROM spravochnik_vidov_rabot WHERE id='$i'";
  $sql1="DELETE FROM nauch_met_rab WHERE id_vidov_rabot='$i'";
  mysql_query($sql1) or die("Возникла ошибка удаления : ".mysql_error());
  mysql_query($sql) or die("Возникла ошибка удаления : ".mysql_error());
  echo'<h3 class=success>Данные успешно удалены</h3>';
  }
 }

 //---------------------------------------Редактирование в базе данных----------------------
 if((isset($_POST['edit']))and (isset($_POST['delete_rab'])))
			{

					echo "<table border=1 class=indplan width=65%><tr class=indplan><th>Список научно-методических и госбюджетно научно-исследовательских работ</td><th>Нормы времени в час</td></tr><br>";
					echo "<FORM method=POST>";
			foreach ($_POST['delete_rab'] as $i)
				{
 					echo "<input name=delete_rab[".$i."] type='hidden' value=".$i.">";

 					$sql="SELECT * FROM spravochnik_vidov_rabot WHERE id=".$i."";
 					$mysql=mysql_query($sql);
 					$name=mysql_fetch_row($mysql);
 					echo "<tr>
					<td><textarea name='name".$i."' rows=4 cols=38 maxlength='200'>$name[2]</textarea></td>
 					<td><textarea name='time_norm".$i."' rows=4 cols=38 maxlength='300'>$name[3]</textarea></td>
					</tr>
					";
				}
			echo "</table><input type='submit' name='izmen' value='Изменить'></FORM>";

			}
		else
			{
           //---------------Изменение-----------------------
           $check=true;
			if (isset($_POST['izmen']))
				{

 				foreach ($_POST['delete_rab'] as $i)
					{


	 					$name=mysql_escape_string($_POST['name'.$i.'']);
	 					$time_norm=mysql_escape_string($_POST['time_norm'.$i.'']);
                         if ($name!=''&& $time_norm!='') //проверка на пустые значения
                               {
							$sql="UPDATE spravochnik_vidov_rabot SET name='$name',time_norm='$time_norm' WHERE id=$i";
							$mysql=mysql_query($sql) or die("Возникла ошибка: ".mysql_error());
                                }
                                else
                                {$mysql=false; $unchanged[]=$i;}
                        		 $check=($check && $mysql);
					}
					if ($check)
					{
                 echo "<h3 align=center>Данные изменены.</h3>";
				    }
				    else // если были пустые поля ещё раз выводим данные для изменения
				    {
				    	echo"<h3 align=center>Были обнаружены записи с пустыми полями</h3>";
				    	echo "<table border=1 class=indplan width=65%>
					<tr>
				<th>Список научно-методических и госбюджетно научно-исследовательских работ</td>
				<th>Нормы времени в часах</td>
	                </tr><br>
			       <FORM method=POST>";

				    		foreach ($unchanged as $i)
				    		{
				    echo "<input name=delete_rab[".$i."] type='hidden' value=".$i.">";

 					$sql="SELECT * FROM spravochnik_vidov_rabot WHERE id=".$i."";
 					$mysql=mysql_query($sql);
 					$name=mysql_fetch_row($mysql);
 					echo "<tr>
 					<td><textarea name='name".$i."' rows=4 cols=38 maxlength='200'>$name[2]</textarea></td>
 					<td><textarea name='time_norm".$i."' rows=4 cols=38 maxlength='300'>$name[3]</textarea></td>
                          </tr>
					";
				    		}
				    		echo "</table><input type='submit' name='izmen' value='Изменить'></FORM>";
           				   }
                }
                }
  //------------------------------------------------------Добавление работы-------------------------------------
 if (isset ($_POST['add']))
 {
 $max=0;
 $sql="SELECT MAX(id) FROM spravochnik_vidov_rabot";
 $mysql=mysql_query($sql);
 $max=mysql_fetch_row($mysql);

 $max=1+$max[0];

 ?>
 <form method="POST" action="">
 <input type="hidden" name="max" value="<?php echo $max?>">
 <div class=text style="float: left;">
 Название работы<br>
 <textarea name="work" id="work" rows=4 cols=30 maxlength="200" title="Название работы"></textarea>
 <span class=warning>*</span>
 </div>
 <div class=text style="float: left;">
 Норма времени<br>
 <textarea name="time" id="time" rows=4 cols=30 maxlength="300" title="Норма времени"></textarea>
 <span class=warning>*</span>
 </div>
 <br style="clear: both;">
    <input type="submit" name="save" value="Добавить"
	onclick="return requireFieldCheck(new Array(new Array('work',''),new Array('time','')));">    
 </form>
 <?php

 }
//-------------------------------------------Перевод работ в другие разделы----------------------
if((isset($_POST['delete_rab']))and (isset($_POST['change'])))
 {
 foreach($_POST['delete_rab'] as $i)
 {
 $sql="UPDATE spravochnik_vidov_rabot SET id_razdel=".$_POST['razdel']." WHERE id=$i";
 $mysql=mysql_query($sql) or die("Возникла ошибка: ".mysql_error());

 }
 echo '<h3 class=success>Данные успешно изменены</h3>';
 }
//---------------------------------------------Вывод-----------------------------------------
 $sql="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=3 ORDER BY id ";
 $mysql=mysql_query($sql);
  $check=mysql_num_rows($mysql);
 if (!$check)
 echo'<h3>Справочник научно-методических и госбюджетно научно-исследовательских работ пуст</h3>
 <form method="POST" action=""><input type="submit" name="add" value="Добавить работу"></form>';
 else
 {
 echo '<form method="POST" action="">
 <input type="submit" name="delete" value="Удалить работу" onclick="confirm_delete()" id="delete">
 <input type="submit" name="edit" value="Редактировать работу">
 <input type="submit" name="add" value="Добавить работу">
 <p><b>выбранные работы перевести в раздел:</b><select size="1" name="razdel" ><option value=2>учебно- и организационно-методических</option>
 <option value=4>учебно-воспитательных</option></select>
 <input type="submit" name="change" value="Ввод">
 <table class=indplan border=1><tr class=indplan><th>Список научно-методических и госбюджетно научно-исследовательских работ</td><th>Нормы времени в часах </td><th></td></tr>';
 while($name=mysql_fetch_array($mysql))
 {
 echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF"><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type="checkbox" name="delete_rab[]" value="'.$name['id'].'"></td></tr>';
 }
 echo '</table>';
 echo '</form>';


 }
 }
 //---------------------------------------------Раздел 4--------------------------------------
echo '<p><a class="notinfo" href="?id_razdel4">3. Справочник учебно-воспитательная работа </a><br>';
if (isset($_GET['id_razdel4']))
 {
//---------------------------------------------Добавление в базу данных----------------------
 if (isset($_POST['save']))
 {
 	if (!($_POST['work']!='' && $_POST['time']!=''))
    {
    	echo '<h3>Были обнаружены записи с пустыми полями</h3>';
 	}
 	else
 	{
 	$sql="INSERT INTO spravochnik_vidov_rabot (id,id_razdel,name,time_norm) VALUES ('".$_POST['max']."','4','".$_POST['work']."','".$_POST['time']."')";
 	mysql_query($sql) or die("Возникла ошибка добавление : ".mysql_error());

  echo '<h3 class=success>Данные успешно добавлены</h3>';
    }
 }
 //---------------------------------------------Удаление из базы данных----------------------
 if ((isset($_POST['delete']))&& (isset($_POST['delete_rab'])))
 {
 foreach ($_POST['delete_rab'] as $i)
  {
  $sql="DELETE FROM spravochnik_vidov_rabot WHERE id='$i'";
  $sql1="DELETE FROM uch_vosp_rab WHERE id_vidov_rabot='$i'";
  mysql_query($sql1) or die("Возникла ошибка удаления : ".mysql_error());
  mysql_query($sql) or die("Возникла ошибка удаления : ".mysql_error());
  }
   echo'<h3 class=success>Данные успешно удалены</h3>';
 }

 //---------------------------------------Редактирование в базе данных----------------------
 if((isset($_POST['edit']))and (isset($_POST['delete_rab'])))
			{

					echo "<table border=1 class=indplan width=65%><tr class=indplan><th>Список учебно-воспитательных работ</td><th>Нормы времени в час</td></tr><br>";
					echo "<FORM method=POST>";
			foreach ($_POST['delete_rab'] as $i)
				{
 					echo "<input name=delete_rab[".$i."] type='hidden' value=".$i.">";

 					$sql="SELECT * FROM spravochnik_vidov_rabot WHERE id=".$i."";
 					$mysql=mysql_query($sql);
 					$name=mysql_fetch_row($mysql);
 					echo "<tr>
					<td><textarea name='name".$i."' rows=4 cols=38 maxlength='200'>$name[2]</textarea></td>
 					<td><textarea name='time_norm".$i."' rows=4 cols=38 maxlength='300'>$name[3]</textarea></td>
					</tr>
					";
				}
			echo "</table><input type='submit' name='izmen' value='Изменить'></FORM>";

			}
		else
			{
           //---------------Изменение-----------------------
           $check=true;
			if (isset($_POST['izmen']))
				{

 				foreach ($_POST['delete_rab'] as $i)
					{


	 					$name=mysql_escape_string($_POST['name'.$i.'']);
	 					$time_norm=mysql_escape_string($_POST['time_norm'.$i.'']);
                         if ($name!=''&& $time_norm!='') //проверка на пустые значения
                               {
							$sql="UPDATE spravochnik_vidov_rabot SET name='$name',time_norm='$time_norm' WHERE id=$i";
							$mysql=mysql_query($sql) or die("Возникла ошибка: ".mysql_error());
                                }
                                else
                                {$mysql=false; $unchanged[]=$i;}
                        		 $check=($check && $mysql);
					}
					if ($check)
					{
                 echo "<h3 align=center>Данные изменены.</h3>";
				    }
				    else // если были пустые поля ещё раз выводим данные для изменения
				    {
				    	echo"<h3 align=center>Были обнаружены записи с пустыми полями</h3>";
				    	echo "<table border=1 class=indplan width=65%>
					<tr>
				<th>Список учебно-воспитательных работ</td>
				<th>Нормы времени в часах</td>
	                </tr><br>
			       <FORM method=POST>";

				    		foreach ($unchanged as $i)
				    		{
				    echo "<input name=delete_rab[".$i."] type='hidden' value=".$i.">";

 					$sql="SELECT * FROM spravochnik_vidov_rabot WHERE id=".$i."";
 					$mysql=mysql_query($sql);
 					$name=mysql_fetch_row($mysql);
 					echo "<tr>
 					<td><textarea name='name".$i."' rows=4 cols=38 maxlength='200'>$name[2]</textarea></td>
 					<td><textarea name='time_norm".$i."' rows=4 cols=38 maxlength='300'>$name[3]</textarea></td>
                          </tr>
					";
				    		}
				    		echo "</table><input type='submit' name='izmen' value='Изменить'></FORM>";
           				   }
                }
                }
  //------------------------------------------------------Добавление работы-------------------------------------
 if (isset ($_POST['add']))
 {
 $max=0;
 $sql="SELECT MAX(id) FROM spravochnik_vidov_rabot";
 $mysql=mysql_query($sql);
 $max=mysql_fetch_row($mysql);

 $max=1+$max[0];

?>
 <form method="POST" action="" >
 <input type="hidden" name="max" value="<?php echo $max?>">
 <div class=text style="float: left;">
 Название работы<br>
 <textarea name="work" id="work" rows=4 cols=30 maxlength="200" title="Название работы"></textarea>
 <span class=warning>*</span>
 </div>
 <div class=text style="float: left;">
 Норма времени<br>
 <textarea name="time" id="time" rows=4 cols=30 maxlength="300" title="Норма времени"></textarea>
 <span class=warning>*</span>
 </div>
 <br style="clear: both;">
    <input type="submit" name="save" value="Добавить"
	onclick="return requireFieldCheck(new Array(new Array('work',''),new Array('time','')));">    
 </form>
 <?php

 }
 //-------------------------------------------Перевод работ в другие разделы----------------------
if((isset($_POST['delete_rab']))and (isset($_POST['change'])))
 {
 foreach($_POST['delete_rab'] as $i)
 {
 $sql="UPDATE spravochnik_vidov_rabot SET id_razdel=".$_POST['razdel']." WHERE id=$i";
 $mysql=mysql_query($sql) or die("Возникла ошибка: ".mysql_error());

 }
 echo '<h3 class=success>Данные успешно изменены</h3>';
 }
//---------------------------------------------Вывод-----------------------------------------
 $sql="SELECT id,name,time_norm FROM spravochnik_vidov_rabot WHERE id_razdel=4 ORDER BY id ";
 $mysql=mysql_query($sql);
  $check=mysql_num_rows($mysql);
 if (!$check)
 echo'<h3>Справочник учебно-воспитательных работ пуст</h3>
 <form method="POST" action=""><input type="submit" name="add" value="Добавить работу"></form>';
 else
 {
 echo '<form method="POST" action="">
 <br><input type="submit" name="delete" value="Удалить работу" onclick="confirm_delete()" id="delete">
 <input type="submit" name="edit" value="Редактировать работу">
 <input type="submit" name="add" value="Добавить работу">
 <p><b>выбранные работы перевести в раздел:</b><select size="1" name="razdel" ><option value=2>учебно- и организационно-методических</option>
 <option value=3>научно-методических и госбюджетных научно-исследовательских</option></select>
 <input type="submit" name="change" value="Ввод">
 <table border=1 class=indplan><tr class=indplan><th>Список учебно-воспитательных работ</td><th>Нормы времени в часах </td><th></td></tr>';
 while($name=mysql_fetch_array($mysql))
 {
 echo '<tr onmouseover=this.style.background="#fffafa" onmouseout=this.style.background="#DFEFFF"><td>'.$name['name'].'</td><td>'.$name['time_norm'].'</td><td><input type="checkbox" name="delete_rab[]" value="'.$name['id'].'"></td></tr>';
 }
 echo '</table>';
 echo '</form>';


 }
 }


echo '<p><a class="notinfo" href="ind_index.php">Назад</a>';
?>


</body>

</html>