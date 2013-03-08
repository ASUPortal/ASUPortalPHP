function saveCookie(form_name,pg_name)	//сохранение формы в куки с именем формы
{	
	eval('var formElemCnt=document.'+form_name+'.elements.length;');
	if (formElemCnt>0) {
		var str='';
		for (var i=0;i<formElemCnt;i++)
		{	
		 	eval('var MyElem=document.'+form_name+'.elements[i]');
		 	if (MyElem.type=='text' || MyElem.type=='select-one') 
			 {str+='new Array(\''+MyElem.id+'\',\''+MyElem.value+'\',\''+MyElem.type+'\'),';}
		} 
		str=str.replace(/,$/,'');
		//alert(str);
		document.cookie=form_name+'_'+pg_name+"="+str;
		document.cookie=form_name+'_'+pg_name+"="+str;
		//alert(document.cookie);
	}
}
function getCookie(cName)	//получение значения Куки из всей строки Куки
{
var cVal=eval('document.cookie.replace(/.*'+cName+'=([^;]*)(.*)/,"$1")'); 	
if (document.cookie==cVal)	return ""
else return  cVal;
} 
//-------------------------
function loadCookie(form_name,pg_name)	//загрузка формы из куки
{	
	//формируем значение строки массива
	var cVal=getCookie(form_name+'_'+pg_name);

	if (cVal!="") {
		//cVal=cVal.replace(/,$/,'');	
		
		//формируем массив cArray
		eval("var cArray=new Array("+cVal+")");
		var str='';
		for (var i=0;i<cArray.length;i++)
		{
		str+='\''+cArray[i][0]+'\',\''+cArray[i][1]+'\',\''+cArray[i][2]+'\',\n';		
		}
	//alert(str);
	//alert(document.cookie);
	restoreForm(cArray);	
	return cArray;
	}
	else return 0;
}
function deleteCookie(form_name,pg_name)
{
	//document.cookie += "; expires=Sat, 01-Jan-2005 00:00:01 GMT";
	document.cookie=form_name+'_'+pg_name+"=; expires=Sat, 01-Jan-2005 00:00:01 GMT";
	document.cookie=form_name+'_'+pg_name+"=; expires=Sat, 01-Jan-2005 00:00:01 GMT";

	alert(document.cookie); 
} 
function restoreForm(cArray)	//пытаемся вернуть значения в форму из Куки
{
 if (cArray.length>0)
 {
		for (var i=0;i<cArray.length;i++)
		{
		var elem=document.getElementById(cArray[i][0]);
		if (elem!=null) {
			if (elem.type==cArray[i][2] && elem.type=='text') {elem.value=cArray[i][1];}
			if (elem.type==cArray[i][2] && elem.type=='select-one') {	//алгоритм поиска значения в списке Select
			 //alert('select-one='+cArray[i][1]);
			 if (elem.selectedIndex<=0)
			 	 for (var j=0;j<elem.length;j++)
			 	 {
			 	  if (elem.options[j].value==cArray[i][1]) elem.options[j].selected=true;
			 	  else elem.options[j].selected=false;
			 	 }	
			//else alert('элемент уже выбран:'+elem.selectedIndex);			 
			 }
		}
		} 
 } 
} 
function viewCookie()
{
 alert(document.cookie);
} 