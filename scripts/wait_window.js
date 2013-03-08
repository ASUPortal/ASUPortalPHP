//alert(window.location.href);
//var main_path='';
if (window.location.href.indexOf("olap_file")>0) {main_path='../';}//для olap-отчетов меняем путь для вывода картинки
document.write('\
<div name="wait_layer" id="wait_layer" style="position:absolute; align:center; top: 50px; width:250; display:; background-color:#FFFFFF;" >\
<table width="250" border="1" class=light_color_max cellspasing="10" cellpadding="10" align="center"> <tr><td>\
		<p align="center">Подождите... <br>Идет выполнение запроса<br><br>\
		<img src="'+main_path+'images/design/load.gif"><br><br>\
		<input type=button value="Отмена" onClick="javascript:history.back();"> &nbsp;\
		<input type=button value="Закрыть" onClick="javascript:hide_show(\'wait_layer\');"></p>\
  	</td></tr></table>\
</div>\
');

var midPos=0;
var div_width=300;

//alert(midPos-document.getElementById("wait_layer").style.width);
try {div_width=parseInt(document.getElementById("wait_layer").style.width);}
catch (e) {div_width=parseInt(document.all["wait_layer"].style.width);}
//alert(div_width);
midPos=Math.ceil((screen.width-div_width)/2);
//alert("midPos"+midPos);
try {document.getElementById("wait_layer").style.left=midPos;}
catch (e) {document.all["wait_layer"].style.left=midPos;}