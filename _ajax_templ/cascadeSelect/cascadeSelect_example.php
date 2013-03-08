<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<script src="../../scripts/jquery-1.3.2.min.js" type="text/javascript"></script>
<!--script src="../../scripts/jquery-1.2.3.pack.js" type="text/javascript"></script-->
<script src="js/cascadeSelect.js" type="text/javascript"></script>
<style>
.cascadeSelect_loading {
	background : Window url('../../images/autocomplete_indicator.gif') right center no-repeat; 
	height:16px; width:16px; position:absolute;	}
</style>
<script type="text/javascript">
$(document).ready(function(){  

  $('#list1').change(function(){  	
	  //главный список, фильтруемый список, тип_запроса, разворачивать_при_пустом_главном
	  adjustList2('list1','list2','StGroup2Students','allowMainIsNull');	
  }).change();

});
</script>
</head>
<body>
<?php
$files_path='../../';
//include '../asu/sql_connect.php';
include $files_path.'sql_connect.php';

?>  
  <div class="border">
  <label>Страна</label><br />
  <select id="list1">
	<?php
	$listQuery="SELECT id,
	  concat(name,' (',(
	    select count(*) from students s where s.group_id=sg.id
	    ),')') as name
	  FROM study_groups sg WHERE 1 order by sg.name";
	echo getFrom_ListItemValue($listQuery,'id','name','list1');
	?>
  </select>
  </div>
  <div class="border">
  <label>Автомобиль</label> <span id="ac_loading" class="cascadeSelect_loading" style="display:none";> </span> <br />
  <select id="list2" disabled="disabled"></select> 
  </div>
</body>
</html>