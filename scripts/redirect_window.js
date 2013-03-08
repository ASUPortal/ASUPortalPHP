var path_redirect='http://asu.ugatu.ac.ru';

if (window.location.href.indexOf("olap_file")>0) {main_path='../';}//для olap-отчетов меняем путь для вывода картинки

//выводим окно перехода
document.write('\
<div name="redirect_layer" id="redirect_layer" style="position:absolute; align:center; top: 50px; width:250; display:none; background-color:#FFFFFF;" >\
<table width="400" border="1" class=light_color_max cellspasing="10" cellpadding="10" align="center"> <tr><td>\
		Переход на другой сайт... <hr><p align="center">Для получения более свежей информации рекомедуется перейти на <br><br><font size=+3><a href="'+path_redirect+'">asu.ugatu.ac.ru</a></font> <br><br>\
                 осталось до перехода : <span id=timeLeft name=timeLeft></span> сек.<br><br>\
		<input type=button value="Отменить переход" onClick="javascript:hide_show(\'redirect_layer\');stoptimer();saveCook(\'not_redirect\',\'1\');"></p>\
  	</td></tr></table>\
</div>\
');

setCenterWinPos("redirect_layer");  //центрируем окно перехода

//-------------------------------
var i=12;
var timerID = null
var timerRunning = false
var timeLeft=document.getElementById('timeLeft');
function goRedirect()
{
    window.location.href=path_redirect;
}

function stoptimer(){
   if(timerRunning)
      clearInterval(timerID)
   timerRunning = false;   
}
function startTimer(){
   stoptimer()
   timeLeft.innerHTML=i;
   timerID = setInterval("showtime()",1000)
   timerRunning = true
}

function showtime(){
   if (i>0) {   
        i--;
        timeLeft.innerHTML=i;
   }else
   {
    clearInterval(timerID);
    goRedirect();
   }
}