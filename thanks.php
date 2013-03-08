<?php
//include "header.php";
include 'authorisation.php';

//$head_title='Благодарности. '.$comp_title;

include 'master_page_short.php';

echo '<div class="main">'.$pg_title.'</div>';
?>
<table border=0 width=95%>
	<tr><td colspan=4 class=middle_lite style="text-align:justify;">
Огромную признательность выражаю: 
<p> <b>Смирновой Алевтине Павловне</b>, 
	которая помогла оформить документы для трудоустройства на кафедре,
<p><b>Куликову Геннадию Григорьевичу</b>, заведующему кафедрой АСУ, 
	за терпение на стадии внедрения портала и консультации по информационному наполнению портала,
<p><b>Дубинину Николаю Михайловичу</b>, доценту кафедры и моему научному руководителю, 
	за идеи по функционированию портала,
<p><b>Старцеву Геннадию Владимировичу</b>, к.т.н., преподавателю,
	за формализацию пожеланий пользователей и постоянную поддержку в обновления портала.
	
	</td></tr>
	<tr>
		<td><img src="images/thanks/smirnova.jpg" height="167"></td>
		<td><img src="images/thanks/kulikov.jpg" height="167"></td>
		<td><img src="images/thanks/dubinin.jpg" height="167"></td>
		<td><img src="images/thanks/starcev.jpg" height="167"></td>
	</tr>
</table>
<?php
  echo $end1;
  include "display_voting.php";
  echo $end2; include('footer.php'); 
?>	
<?php include('footer.php'); ?>
