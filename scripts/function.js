//<!--
function setCenterWinPos(layerID)	//центрируем окно 
{
	var midPos=0;
	var div_width=300;
	
	try {div_width=parseInt(document.getElementById(layerID).style.width);}
	catch (e) {div_width=parseInt(document.all[layerID].style.width);}
	
	midPos=Math.ceil((screen.width-div_width)/2);
	
	try {document.getElementById(layerID).style.left=midPos;}
	catch (e) {document.all[layerID].style.left=midPos;}	
}
//--------------------------------------работаем с Cookies
function saveCook(cName,cVal)	//сохраняем куку
{	
		document.cookie=cName+"="+cVal;
		document.cookie=cName+"="+cVal;
}
function delCook(cName)		//удаляем куку
{	
	document.cookie=cName+"=; expires=Sat, 01-Jan-2005 00:00:01 GMT";
	document.cookie=cName+"=; expires=Sat, 01-Jan-2005 00:00:01 GMT";

	//alert(document.cookie); 
}
function getCook(cName)	//получение значения Куки
{
var cVal=eval('document.cookie.replace(/.*'+cName+'=([^;]*)(.*)/,"$1")'); 	
if (document.cookie==cVal)	return ""
else return  cVal;
} 

//--------------------------------------
function pgValsCh(location)	//изменение числа записей на странице
{ 
 pgVal=document.getElementById('pgVals');
 pageVal_=parseInt(pgVal.value);
 if (isNaN(pageVal_)) pageVal_=0;
 
 if (pageVal_>0 && pageVal_<999) window.location.href='?'+location+'&pgVals='+pageVal_;
 else alert('необходимо: '+pgVal.title);
					       
 } 
//---------checkbox операции- начало-------------------

function mark_all_checkbox(click_name,form_name,mask_name,ignore_items_cnt)	//выделение\отмена всех галочек в форме с учетом пропуска элементов
{ 
 	//mask_name	маска элемента, например item_ch
	//ignore_items_cnt	число пропускаемых от начала элементов формы
	//click_name	имя 
	var item_cnt=0;
	var chech_val='';
	var mark_val;
	
	if (ignore_items_cnt==null) ignore_items_cnt=0;	
	if (mask_name==null) mask_name='';
		
	item_cnt=document.forms[form_name].elements.length;	
	
	try { mark_val=document.getElementById(click_name).checked;}
	catch (e) { mark_val=document.forms[form_name].elements[click_name].checked;  }
  
	for (i=ignore_items_cnt;i<item_cnt;i++) {
		var check_item=document.forms[form_name].elements[i];
		var digital_id=0;
		var row_item;
		if (check_item!=null && check_item.type=='checkbox')
			if (mask_name=='' || check_item.id.indexOf(mask_name)!=-1 || check_item.name.indexOf(mask_name)!=-1)	//если не указана маска или она совпадает
				{
					check_item.checked=mark_val;
					digital_id=parseInt(check_item.id.replace(/\D/gi,''),10);
					if (isNaN(digital_id)) digital_id=0;
					else {
						row_item=document.getElementById('row'+digital_id);
						if (row_item!=null) {
							if (check_item.checked) row_item.className='row_dark';
							else row_item.className='row_light';						
						}
					}
				}			
	}  
 }
function check_cnt(form_name,mask_name,ignore_items_cnt)	//подсчет числа отмеченных галочек, например при отправке формы
{
 	//mask_name	маска элемента, например item_ch
	//ignore_items_cnt	число пропускаемых от начала элементов формы

	var item_cnt=document.forms[form_name].elements.length;
	var err=true;
	if (ignore_items_cnt==null) ignore_items_cnt=0;	
	if (mask_name==null) mask_name='';

	for (var i=ignore_items_cnt;i<item_cnt;i++)
	{
		var check_item=document.forms[form_name].elements[i];
		
		if (check_item!=null && check_item.type=='checkbox' && check_item.checked)
			if (mask_name=='' || check_item.id.indexOf(mask_name)!=-1 || check_item.name.indexOf(mask_name)!=-1)	//если не указана маска или она совпадает
				{err=false; break;}
	}
	return !err;
	//if (err) {alert('Не введено ни одной оценки у студентов.');}	
}
function mark_row(row_element)	//выделение строки таблицы
{
	var checkbox = row_element.getElementsByTagName( 'input' )[0];
	if (checkbox!=null) {		
		if (checkbox.checked) row_element.className='row_light';
		else row_element.className='row_dark';		
		checkbox.checked=!checkbox.checked;
	}			
}

function markTableRowsInit(table_name) {
    // for every table row ...
    
    var rows = document.getElementById(table_name).getElementsByTagName('tr');
    for ( var i = 0; i < rows.length; i++ ) {
        // ... with the class 'odd' or 'even' ...
        if ( 'row_light' != rows[i].className.substr(0,9) && 'row_dark' != rows[i].className.substr(0,8) ) {
            continue;
        }
        // ... add event listeners ...
        // ... to highlight the row on mouseover ...
        if ( navigator.appName == 'Microsoft Internet Explorer' ) {
            // but only for IE, other browsers are handled by :hover in css
            rows[i].onmouseover = function() {
                this.className += ' hover';
            }
            rows[i].onmouseout = function() {
                this.className = this.className.replace( ' hover', '' );
            }
        }
	rows[i].onclick= function() {
		mark_row(this);
		}

        // .. and checkbox clicks
        var checkbox = rows[i].getElementsByTagName('input')[0];
        if ( checkbox ) {
            checkbox.onclick = function() {                
                this.checked = ! this.checked;
            }
        }
    	
    }
}    
//-------checkbox операции конец---------------------

function saveState(cookieName,cookieVal)
{
//alert("cookieName="+cookieName+", cookieVal="+cookieVal);
//document.cookie += "expires=" + cookieDate(time - 10);
if(cookieName!='')
     {
	  var display=document.getElementById(cookieName).style.display;	//получаем видимость элемента
	  document.cookie=cookieName+"="+display;document.cookie=cookieName+"="+display;}
//else {document.cookie="save_state=false";document.cookie="save_state=false";}

//alert('document.cookie='+document.cookie);
}


function confirm_url(url_addr)
{
 	if (confirm('Все несохраненные данные текущей страницы не будут сохранены (введенные Вами поля на текущей странице).\n\nДля сохранения изменений выберите Отмена, чтобы продолжить без сохранения - Ок'))
 	{window.location.href=url_addr;}
} 

function changeTextHide(name_form)
{
//отражение статуса ссылки при     скрыть/отразить
var msg_obj=document.getElementById(name_form);

if (msg_obj.innerHTML=='подробнее...') {
	msg_obj.innerHTML='кратко...';
} else {msg_obj.innerHTML='подробнее...';}
}

function requireFieldCheck(field_id_Array,form_name)
{//проверка обязательных полей формы и выдача списка незаполненных
//подсказка берется из передаваемого массива, если пусто - из title-проверяемого поля
//получаемый объект работает для Опера,ИЕ, Мозилла
/*  Пример
 	a = new Array(
	 	new Array('kadriFio',''),
		new Array('kandidWork_name','')
	);
requireFieldCheck(a,'order_form');
*/
err=false;
msg='';

for (i=0;i<field_id_Array.length;i++)
{ 
try {filedItem=document.getElementById(field_id_Array[i][0]);filedItem.value;}
catch (e) {
 	filedItem=eval("document.all."+field_id_Array[i][0]);
 }
//alert(filedItem.name+'='+filedItem.value);
if (filedItem.value=='' || filedItem.value==0) { 
   err=true;	
   if (field_id_Array[i][1]=='') {msg=msg+' - '+filedItem.title+';\n';}
   else {msg=msg+' - '+field_id_Array[i][1]+';\n';}
   }

}

if (err==true) {alert('Не заполнены обязательные поля:\n\n'+msg);return false;}
else {
  	try {
		if (form_name!=null && form_name!='') document.getElementById(form_name).submit();
		else return true;
	}
  	catch (e) {
		alert('Ошибка при отправке формы.\nПроверьте все обязательные поля и повторите отправку.');
		}
	}
	return false;
	 //alert(field_id_Array.length);
} 
function requireFieldCheck_mass_operation(field_id_Array,form_name)
{//аналогична функции requireFieldCheck для массовых операций с проверкой заполнения х-бы 1 реквизита
err=true;

for (i=0;i<field_id_Array.length;i++)
{ 
try {filedItem=document.getElementById(field_id_Array[i][0]);filedItem.value;}
catch (e) {
 	filedItem=eval("document.all."+field_id_Array[i][0]);
 }

if (filedItem.value!='' && filedItem.value!=0)  
 {err=false;break;}	//если х-бы 1 значение не пустое, разрешаем масс.операцию

}

if (err==true) {alert('Не заполнены поля массовой операции:\n\n Операция отменена.');}
else {
  	if (confirm('Произвести массовую операцию по изменению параметров записей ?'))
	{
	 try {document.getElementById(form_name).submit();}
  	 catch (e) {alert('Ошибка при отправке формы массовой операции.\nПроверьте заполнение полей операции и повторите отправку.');}
	}
	 }
} 
function hide_show(id_name,mode,debug)    
// id объекта, режим показа {null,'...'}
{   //показать-скрыть mode=show|hide
	
    var elem=document.getElementById(id_name);
    if (elem!=null) {
       if (mode!=null)
	   switch (mode) {
	   case 'show' :
	      elem.style.display='';
	      break;
	   case 'hide' :
	      elem.style.display='none';
	      break;
	   case '':
	       if (elem.style.display=='')  elem.style.display='none';
	       else elem.style.display=''; 
	       break;
	   default :
		//alert('ошибка вызова функции show_hide');
	    }
       else {
	       if (elem.style.display=='')  elem.style.display='none';
	       else elem.style.display='';
       }
       

	}
    else { if (debug!=null && debug) alert('элемент не найден');}   
}
function win_open(url,height,width)
{
if (window.location.search.indexOf('wap')>=0) {//в режиме wap-просмотра
	window.location.href=url;   }
else {
	mywindow=window.open(url, 'news_window', config='scrollbars=yes,resizable=true,screenX=200,screenY=400,height='+height+',width='+width+''); 
	if (parseInt(mywindow.screenX)<200) 
		{mywindow.screenX=200;mywindow.screenY=400;}
	//mywindow.screenX=200;
	//mywindow.screenY=400;
	}

}

function show_auth_form(){
document.getElementById('author_layer').style.display=''
}

function show_auth_form(div_name){

var centerPos=(screen.width-300)/2;	//центр экрана по ширине
if (centerPos<100) {centerPos=300}
//alert(screen.availWidth);
document.getElementById(div_name).style.left=centerPos;
document.getElementById(div_name).style.display='';

}

function del_confirm_act(del_title,loc_href)
{
if (confirm('Удалить: «'+del_title+'» ?'))
{window.location.href=loc_href;}

}

function date_check(date4check,len_control)
{//проверка корректности даты календаря (критично для Олап по дипломникам)
//len_control - контроль длины, по умолчанию =false 
var err=false;

if (len_control && date4check.length!=10) {err=true;}
	//получаем дату из поля формы и выделяем гггг мм дд
var dipdate=date4check;
var dY=dipdate.substr(6,4);
var dM=dipdate.substr(3,2);
var dD=dipdate.substr(0,2);

	//формируем на основе данных формы новую дату
var today_date=new Date(dY,dM-1,dD);	//получаем дату по календарю
var Tdate=today_date.getDate();
var Tmonth=today_date.getMonth()+1;
var Tyear=today_date.getFullYear();

 	//проверяем правильность даты

if (dY!=Tyear || dM!=Tmonth || dD!=Tdate) {err=true;}

return err; 
}

function day_now(elem_id)	//текущая дата
{
 var date_string=new Date().toLocaleString().substr(0,10);
 document.getElementById(elem_id).value=date_string;
}

function uploadList(listId,listType,selectId,param1,param2,rootPath) //обновление элементов списка
{
   var listObj=document.getElementById(listId);//options[this.selectedIndex].value
   if (typeof(rootPath)=="undefined" || rootPath==null) var rootPath="";

   var selectId=parseInt(selectId);
   if (isNaN(selectId)) selectId=0;
   
   //alert('listId='+listId+', listType='+listType+', param1='+param1+', rootPath='+rootPath);
   //return;

   if (listObj!=null) 
        {
        $('#'+listId+'_loading').attr("style","");
	$("select[name$='"+listId+"']").load(rootPath+'ajax_list_items.php?list_type='+listType+
					     (listObj!=null?'&listId='+listId:'')+
					     (selectId>0?'&selectId='+selectId:'')+
					     (param1!=null?'&param1='+param1:'')+
					     (param2!=null?'&param2='+param2:'')
					     );
        
        $("select[name$='"+listId+"']").addClass('field_update');
	$("select[name$='"+listId+"']").ajaxComplete(function(event,request, settings){   					
   					$('#'+listId+'_loading').attr("style","display:none;");
					$("select[name$='"+listId+"']").removeClass('field_update');					   
 				});        
        }
   else {alert('ошибка обновления списка.');}
}
//-->