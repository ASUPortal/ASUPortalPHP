<?php
include ('authorisation.php');
$pg_title='Контактная информация';
include ('master_page_short.php');
?>

<?php

//include ('menu.htm');
?>
<style>
td.send_form {font: 10pt Arial};

</style>

<body bgcolor="#FFFFFF" text="#000000">
<h3> <?php echo $pg_title;?></h3>
		
      <div style="font: 10pt Arial"> Консультант: <u>Старцев Геннадий </u><p> 
		Программирование и дизайн: <u> Агапов Руслан </u><p></div>
<table width=600 bgcolor="#E6E6FF">
  <tr><td> <div style="font: 10pt Arial">
		<h4 align=center>Сообщение автору</h4>
      <form name=post_form>
            <input type=text value="" name="user_name2" size=43 style="width:250; height:20">  Ваше имя <p> 
            <input type=text value="" name="user_name3" size=43 style="width:250; height:20">  адрес лектронной почты для ответа <p>
            <textarea name="user_name" rows="3" cols="40"  style="width:250; height:100" wrap="OFF"></textarea>    сообщение <p>
           	<select name="mark">          <option selected> хорошо</option>        </select>        оценка полезности ресурса <p>
          	<input type=submit value=Отправить onClick="javascript:alert('В настроящее время почтовый сервер не настроен на отправку сообщений. \nИзвините за неудобства');" name="submit">
      		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=reset value=Очистить name="reset">
        </form></div>
				
    <div align=right> <font size=-2>(с) <a href=mailto:smart_newline@mail.ru>Агапов Р.Н.</a> </font></div>
    
</td></tr></table>
<div align=left><p> <a href="javascript:history.back()">Вернуться...</a> </div>

<?php include('footer.php'); ?>