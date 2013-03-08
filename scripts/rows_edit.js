var c=0; //счётчик количества строк
//var c_send=0; //счётчик количества строк для рассылки
function addline(tab_name)
{
	c=document.getElementById('max_'+tab_name).value;	
	c++; // увеличиваем счётчик строк
	s=document.getElementById(tab_name).innerHTML; // получаем HTML-код таблицы
	s=s.replace(/[\r\n]/g,''); // вырезаем все символы перевода строк
	//re=/((.|\n)*)(<tr id=(.|\n)*>)(<\/table>)/gi;
	re=/(.*)(<tr id=.*>)(<\/table>)/gi; 
                // это регулярное выражение позволяет выделить последнюю строку таблицы
	s1=s.replace(re,'$2'); // получаем HTML-код последней строки таблицы
//alert(s1);
	s2=s1.replace(/\_\d+/gi,'_'+c+''); // заменяем все цифры к квадратных скобках
                
		// на номер новой строки
//-----------------------------------------------------------------------------
var myExp = new RegExp("(rmline\\()\\d+\\,'"+tab_name+"'","gi"); //    формируем рег_выражения с учетом области для замены=tab_name
s2=s2.replace(myExp,'$1'+c+',\''+tab_name+'\'');
//-----------------------------------------------------------------------------
                // заменяем аргумент функции rmline на номер новой строки
	s=s.replace(re,'$1$2'+s2+'$3');
                // создаём HTML-код с добавленным кодом новой строки
	document.getElementById(tab_name).innerHTML=s;
	//alert(s);
	document.getElementById('max_'+tab_name).value=c;
	
	                // возвращаем результат на место исходной таблицы
//	alert(s);
	return false; // чтобы не происходил переход по ссылке
}
function rmline(q,tab_name)
{
                c=document.getElementById('max_'+tab_name).value;
		
		if (q==0)return false;
                if (c==0) return false; else c--;
                // если раскомментировать предыдущую строчку, то последний (единственный) 
                // элемент удалить будет нельзя.
           
	s=document.getElementById(tab_name).innerHTML;
	s=s.replace(/[\r\n]/g,'');
	re=new RegExp('<tr id="?newline"? nomer="?_'+q+'.*?<\\/tr>','gi');
                // это регулярное выражение позволяет выделить строку таблицы с заданным номером
	s=s.replace(re,'');
                // заменяем её на пустое место
	
	document.getElementById(tab_name).innerHTML=s;
	document.getElementById('max_'+tab_name).value=c;
	
	return false;
}