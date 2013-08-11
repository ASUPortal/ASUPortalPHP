<?php
	$pg_title="Ссылки";
	include "sql_connect.php";
	include "header.php";
	if (!isset($_GET["wap"])) {	echo $head;}
	else { echo $head_wap;}
	?>

<p class="main">&nbsp;</p>
<table class="text" border="0" cellpadding="5" width="99%" align="center">
<tbody>
<tr class="light_color_min" height="40" align="left">
<td width="150"><span style="text-decoration: underline;"><span style="color: #0000ff;"><a href="http://asu.ugatu.ac.ru/">http://asu.ugatu.ac.ru/</a></span></span><a href="http://www.asu-ugatu.ueuo.com" target="_blank"></a></td>
<td>Внешний портал&nbsp;АСУ УГАТУ</td>
</tr>
<tr class="light_color_max" height="40" align="left">
<td width="150"><a href="http://vkontakte.ru/asu_ugatu" target="_blank">vkontakte.ru/asu_ugatu</a></td>
<td>Официальная страница в социальной сети "ВКонтакте"</td>
</tr>
<tr class="light_color_max" height="40" align="left">
<td width="150"><a href="http://www.ugatu.ac.ru" target="_blank">www.ugatu.ac.ru</a></td>
<td>Официальный сайт УГАТУ</td>
</tr>
<tr class="light_color_min" height="40" align="left">
<td width="150"><a href="http://www.aspirantura.spb.ru" target="_blank">www.aspirantura.spb.ru</a></td>
<td>Портал для аспирантов и научных работников</td>
</tr>
<tr class="light_color_max" height="40" align="left">
<td width="150"><a href="http://vak.ed.gov.ru" target="_blank">www.vak.ed.gov.ru</a></td>
<td>Официальный сайт Высшей аттестационной комиссии (ВАК) Министерства образования РФ</td>
</tr>
<tr class="light_color_min" height="40" align="left">
<td width="150"><a href="http://www.library.ugatu.ac.ru" target="_blank">www.library.ugatu.ac.ru</a></td>
<td>Научно-техническая библиотека УГАТУ</td>
</tr>
<tr class="light_color_max" height="40" align="left">
<td width="150"><a href="http://www.glossary.ru" target="_blank">www.glossary.ru</a></td>
<td>Толковые словари разных тематик</td>
</tr>
<tr class="light_color_min" height="40" align="left">
<td width="150"><a href="http://www.apkit.ru/committees/education/meetings/standarts.php" target="_blank">http://www.apkit.ru/committees/education/meetings/standarts.php</a></td>
<td>Квалификационные требования (профессиональный стандарт) в области информационных технологий</td>
</tr>
<tr class="light_color_max" height="40" align="left">
<td width="150"><a href="http://kumertau.ugatu.su" target="_blank">kumertau.ugatu.su</a></td>
<td>Сайт Кумертауского филиала УГАТУ</td>
</tr>
<tr>
<td><a href="http://vtizi.ugatu.ac.ru">http://vtizi.ugatu.ac.ru</a></td>
<td>Кафедра вычислительной техники и защиты информации (ВТ и ЗИ)</td>
</tr>
<tr class="light_color_max" height="40" align="left">
<td><a href="http://www.ugatu.ac.ru/EC_INF/index.html">http://www.ugatu.ac.ru/EC_INF/index.html</a></td>
<td>Кафедра Экономической информатики</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<div class="text" style="text-align:center;">Раздел наполняется, будем рады интересным ссылкам. <br />почтовый адрес администратора портала</div>

<?php
	if (!isset($_GET["wap"])) {
	  echo $end1;
	  include "display_voting.php";
	  }
define("CORRECT_FOOTER", true);
	echo $end2; include("footer.php"); 
	?>