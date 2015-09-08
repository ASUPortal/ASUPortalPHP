<?php
include ('../sql_connect.php');

$files_path='http://'.$_SERVER['SERVER_NAME'].'/'.$root_folder;
include ('../header.php');

echo $head;
?>

<div class="main">Ошибка 403.</div><br>
<h3 align="center">Доступ к разделу ограничен.<h4>


<?php
$files_path='../';
  echo $end1;
  //include ('../display_voting.php');
  echo $end2;
?>