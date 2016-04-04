<?php
include 'authorisation.php';

$pg_title='Поиск по всему файловому хранилищу. '.$comp_title;

//session_start();
//include_once("setup.php");

$q='';
//$search_server_ip=$search_serv;//$_SERVER['SERVER_ADDR'];//172.16.4.77
//получаем из файла настроек
if (trim($search_server_ip)=='') {$search_server_ip=$_SERVER['SERVER_ADDR'];}

//echo ' SERVER_ADDR='.$server_ip;
//echo phpinfo();

//принимаем строку запроса и перенапрявляем на поисковый сервер...

if (isset($_POST['q']) && $_POST['q']!='')
{ $q=$_POST['q'];
  //echo '<br>q='.$q.'<p>';
  $q=mb_convert_encoding($q,'utf-8','windows-1251');
  //echo '<br>q='.$q.'<p>';
  //echo '<br>Location: http://'.$server_ip.':3333/search?q='.$q;
  Header('Location: http://'.$search_server_ip.':3330/search?q='.$q.'&z=0');
 }

include ('master_page_short.php');
?>

<div class="main"><?php echo $pg_title;?></div><br>
<div class="text" style="text-align:center;">в поисковую базу дополнительно включены тексты дипломных работ студентов </div>
<table width=95% border=0 cellpadding=0 cellspacing=0 bgcolor="#ccccff">
<tr><td>

  <form action="" name="search_form" method="post">
<center>
    <table border=0>
    	<tr><td align="right" class=middle>
		<br>Строка поиска:&nbsp; <input type="text" name="q" size="50">&nbsp;&nbsp;
      	<input type="submit" value="Найти">
		  </td></tr>
    </table>
</center>  </form>
&nbsp;
</td></tr>
</table>
<font color="Gray" size=-1>
<p align=center>
при поддержке поисковой системы Archivarius 3000 &copy; 2004 - 2007.
</p>
</font>

<?php
  echo $end1;
  //include "display_voting.php";
define("CORRECT_FOOTER", true);
  echo $end2; include('footer.php'); 
?>
