//var autocompleteItem="#recenz";
//if (autocompleteItem=="") {autocompleteItem="#recenz";}

$(document).ready(function(){
// --- Автозаполнение ---

function liFormat (row, i, num) {
	var result = row[0] + '&nbsp;&nbsp;<span class=qnt>' + row[1] + ' шт.</span>';
	return result;
}
function selectItem(li) {
/*
	if( li == null ) var sValue = 'А ничего не выбрано!';
	if( !!li.extra ) var sValue = li.extra[2];
	else var sValue = li.selectValue;
	alert("Выбрана запись с ID: " + sValue);
*/	
}

for (i=0;i<fieldsArr.length;i++)
{
	$(fieldsArr[i][0]).autocomplete("autocomplete.php", {
		delay:10,
		minChars:2,
		matchSubset:1,
		autoFill:true,
		matchContains:1,
		cacheLength:10,
		selectFirst:true,
		formatItem:liFormat,
		maxItemsToShow:10,
		onItemSelect:selectItem,
		DBqueryText:fieldsArr[i][1]
	});  
}
});