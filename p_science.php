<?php
	$pg_title="Наука";
	include "sql_connect.php";
	include "header.php";
	if (!isset($_GET["wap"])) {	echo $head;}
	else { echo $head_wap;}
	?>

<p>&nbsp;Страница в стадии наполнения</p>

<?php
	if (!isset($_GET["wap"])) {
	  echo $end1;
	  include "display_voting.php";
	  }
	echo $end2; include("footer.php"); 
	?>