<?php
include "config.php";

include $portal_path."authorisation.php";
include $portal_path."master_page_short.php";

?>

<LINK REL="STYLESHEET" TYPE="text/css" HREF="indplan.css">
<script type="text/javascript"  src="indplan.js"></script>

<p class="main"><?php echo $head_title;?></p>

<?php

echo'<p><a id="ssilka1" class="notinfo"  href="prosmotr.php" >Индивидуальный план преподавателя</a>
<p ><br><a id="ssilka3" class="notinfo"  href="rab_prep.php">Работы, выполненные преподавателями</a></p>
<p><br><a class="notinfo" href="spr_vid_rab.php">Справочники видов работ</a></p>';
?>