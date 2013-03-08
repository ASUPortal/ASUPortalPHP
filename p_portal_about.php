<?php
	$pg_title="О портале";
	include "sql_connect.php";
	include "header.php";
	if (!isset($_GET["wap"])) {	echo $head;}
	else { echo $head_wap;}
	?>

<div class="main">О кафедре</div>
<p>&nbsp;</p>
<table class="main" border="0" width="99%" align="center">
<tbody>
<tr>
<td><a href="p_asu_about.php">История кафедры </a></td>
<td>О портале</td>
<td><a href="p_ibm.php">Академические инициативы IBM в УГАТУ </a></td>
<td><!--<a href="asu_about_rules.php" mce_href="asu_about_rules.php">--><a href="http://www.ugatu.ac.ru/abiturient/entrance/rulez.htm" target="_blank">Правила приема</a></td>
<td><a style="color:#999999;">Учебный процесс </a></td>
</tr>
</tbody>
</table>
<p><br />&nbsp;</p>
<table border="0" cellspacing="0" cellpadding="0" width="95%" align="center">
<tbody>
<tr>
<td>
<div class="text"><strong>Разработчики:</strong> программирование: <a href="mailto:smart_newline@mail.ru">(с) Агапов Р.Н. </a>,<a href="mailto:skylinet@mail.ru">(с)Григорьев Г.Н. </a>дизайн: (с)<a href="mailto:smart_newline@mail.ru">Агапов Р.Н. </a>
<p><strong>Хронология работ</strong></p>
<table class="text" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td>дата начала работ по созданию: &nbsp;</td>
<td>02.2006</td>
</tr>
<tr>
<td>первый релиз: &nbsp;</td>
<td>06.2006</td>
</tr>
<tr>
<td>дата официального выхода: &nbsp;</td>
<td>09.2006</td>
</tr>
<tr>
<td>создание "зеркала": &nbsp;</td>
<td>11.2006</td>
</tr>
</tbody>
</table>
<p><strong>цель создания:</strong></p>
поддержка информационного взаимодействия сотрудников, преподавателей, студентов и других заинтересованных лиц кафедры.<br />
<p><strong>основные функции:</strong></p>
-поддержка учебного процесса (для всех участников портала)<br /><br />
<li>новости кафедры и объявления преподавателей</li>
<li>учебные материалы</li>
<li>нормативные документы</li>
<li>научные гранты и мероприятия</li>
<li>справочная информация по преподавателям и расписанию их занятий<br /></li>
<li>ссылки на сопутствующие ресурсы сети</li>
<li>реализация принципов многомерного анализа данных через Olap-технологию</li>
<br />-организационно-методическое обеспечение учебного процесса (для зарегистрированных учасников портала)<br /><br />
<li>выделение категорий доступа зарегистрированных пользователей</li>
<li>поддержка корпоративной служебной информации по кафедре</li>
<li>сбор и формирование отчетных показателей и информации для принятия решений</li>
<li>коммуникация участников портала через систему внутренних сообщений</li>
<li>система отслеживания изменений по порталу</li>
<br />В настоящее время портал позиционируется как внутренний (доступен только в локальной сети УГАТУ). <br />С целью поддержки "внешних" пользователей организовано "зеркало" портала, адрес которого указан в разделе "Ссылки" портала.<br /><br />Если Вы хотите зарегистрироваться на портале или у Вас возникли вопросы мы с радостью на них ответим.<br />Вы можете оставить заявку в приемной кафедры АСУ или отправить письмо на один из указанных ниже адресов.<br /><br />
<p><strong>Первые лица администрации портала:</strong></p>
<table class="text" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td>-технические аспекты работы портала: &nbsp;</td>
<td><a href="mailto:smart_newline@mail.ru">Агапов Руслан Николаевич </a></td>
</tr>
<tr>
<td>-вопросы содержания портала: &nbsp;</td>
<td>Старцев Геннадий Владимирович</td>
</tr>
<tr>
<td>-Olap-технология: &nbsp;</td>
<td>Яковлев Николай Николаевич</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
</tbody>
</table>

<?php
	if (!isset($_GET["wap"])) {
	  echo $end1;
	  include "display_voting.php";
	  }
	echo $end2; include('footer.php'); 
	?>