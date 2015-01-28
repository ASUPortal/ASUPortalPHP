<?php
//$head_title='ТВ-сервер-администрирование';

include 'sql_connect.php';
include 'header.php';

if (!isset($_GET['wap'])) {	echo $head;}
else { echo $head_wap;}

?>
<h4>ТВ-сервер-администрирование<h4>
<iframe src="http://10.61.2.223/administration.php" width="100%" height="800" allowtransparency="true" >
</iframe>
<?php

if (!isset($_GET['wap'])) {
  echo $end1;
  include "display_voting.php";
  }
define("CORRECT_FOOTER", true);
echo $end2; include('footer.php'); 
?>