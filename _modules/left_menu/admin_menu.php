<?php
$files_path='../../';

//include $files_path."sql_connect.php";
include $files_path."authorisation.php";
include $files_path."master_page_short.php";

$file='menu.xml';
?>

	<script type="text/javascript" src="../jstree/lib/jquery.js"></script>
	<script type="text/javascript" src="../jstree/lib/jquery.cookie.js"></script>
	<script type="text/javascript" src="../jstree/lib/jquery.hotkeys.js"></script>
	<script type="text/javascript" src="../jstree/lib/jquery.metadata.js"></script>
	<script type="text/javascript" src="../jstree/lib/sarissa.js"></script>
	<script type="text/javascript" src="../jstree/jquery.tree.js"></script>
	<script type="text/javascript" src="../jstree/plugins/jquery.tree.checkbox.js"></script>
	<script type="text/javascript" src="../jstree/plugins/jquery.tree.contextmenu.js"></script>
	<script type="text/javascript" src="../jstree/plugins/jquery.tree.cookie.js"></script>
	<script type="text/javascript" src="../jstree/plugins/jquery.tree.hotkeys.js"></script>
	<script type="text/javascript" src="../jstree/plugins/jquery.tree.metadata.js"></script>
	<script type="text/javascript" src="../jstree/plugins/jquery.tree.themeroller.js"></script>
	<script type="text/javascript" src="../jstree/plugins/jquery.tree.xml_flat.js"></script>
	<script type="text/javascript" src="../jstree/plugins/jquery.tree.xml_nested.js"></script>

	<style type="text/css">
	html, body { margin:0; padding:0; }
	pre, code, select, option, input, textarea { font-family:"Trebuchet MS", Sans-serif; font-size:10pt; }
	#container { width:90%; margin:10px auto; overflow:hidden; }
	.demo { height:200px; width:300px; float:left; margin:0; border:1px solid gray; font-family:Verdana; font-size:10px; background:white; overflow:auto; }
	.code { width:490px; float:right; margin:0 0 10px 0; border:1px solid gray; }
	pre { display:block; }
	input { font-size:14px; }
	</style>

<div id="container" >
	<h1 class="title"><?php echo $head_title; ?></h1>

	<p>здесь Вы можете построить основное меню портала, расположенное слева.</p>
	<?php
	
$xml_parser = xml_parser_create();
$menu_str='';
//xml_set_element_handler($xml_parser, "startElement", "endElement");
if (!($fp = fopen($file, "r"))) {
    $menu_str.="could not open XML input";
}
//xml_set_character_data_handler($xml_parser,"US-ASCII");

while ($data = fread($fp, 4096)) {
    if (!xml_parse($xml_parser, $data, feof($fp))) {
        $menu_str.="<span class=warning>\nошибка XML-пункта меню \"<font size=+1>".
			xml_error_string(xml_get_error_code($xml_parser)).
			"</font>\" at line, at column: <font size=+1>".
			xml_get_current_line_number($xml_parser).
			", ".
			xml_get_current_byte_index($xml_parser).
			"</font></span>";
    }
}
xml_parser_free($xml_parser);

if ($menu_str!='') {echo $menu_str;?>
	<div class=warning>Работа возможна только после исправления ошибок в <a href="<?php echo $file ?>">xml-файле</a>,
	либо через загрузку образца (Восстановить) и сброса текущего меню</div>
<?php } ?>	
	<script type="text/javascript" class="source">
	var yourTree= jQuery.tree.create();
	var opts = {}; 
	opts.outer_attrib = ["id", "rel", "class", "title", "href", "name"];
	
	$(function () { 		
		yourTree.init("#xml_n",{
			data : { 
				type : "xml_nested",
				opts : {
					url : "menu.xml"					
				}
			},
			plugins : {
				contextmenu : {
				//---------------------------	
items : {
	create : {
		label	: "Создать", 
		icon	: "create",
		visible	: function (NODE, TREE_OBJ) { 
			if(NODE.length != 1) return 0; 
			return TREE_OBJ.check("creatable", NODE); 
		}, 
		action	: function (NODE, TREE_OBJ) { 
			TREE_OBJ.create(false, TREE_OBJ.get_node(NODE[0])); 
		},
		separator_after : true
	},
	rename : {
		label	: "Изменить", 
		icon	: "rename",
		visible	: function (NODE, TREE_OBJ) { 
			if(NODE.length != 1) return false; 
			return TREE_OBJ.check("renameable", NODE); 
		}, 
		action	: function (NODE, TREE_OBJ) { 
			TREE_OBJ.rename(NODE); 
		} 
	},
	remove : {
		label	: "Удалить",
		icon	: "remove",
		visible	: function (NODE, TREE_OBJ) { 
			var ok = true; 
			$.each(NODE, function () { 
				if(TREE_OBJ.check("deletable", this) == false) {
					ok = false; 
					return false; 
				}
			}); 
			return ok; 
		}, 
		action	: function (NODE, TREE_OBJ) { 
			$.each(NODE, function () { 
				TREE_OBJ.remove(this); 
			}); 
		} 
	}
}				
				//---------------------------
				}
			}
, 
       callback      : { 
            //onselect    : function(NODE,TREE_OBJ) { var link=TREE_OBJ.selected.attr("href"); if (link!=null && link!='') document.location.href=link; }
	    onselect    : function(NODE,TREE_OBJ) { getAttr(); }
	    },
	types : { 
		// all node types inherit the "default" node type 
		"default" : { 
			/*deletable : false, 
			renameable : false*/ 
		},                        
		"lock" : { /*	пункт меню Администрация закрыт от любых правок	*/
			draggable : false, 
			renameable : false,
			deletable : false,
			valid_children : "none"
		 } 
	},
	ui : { 
	theme_name : "apple"
	}		
		})
	});
	
	
	function get_xml()
	{
		//document.getElementById('xmlCode').value=yourTree.get(null,'html',opts);
		var xmlCode=document.getElementById('xmlCode');
		if (xmlCode!=null) {
			xmlCode.value=yourTree.get(null,'xml_nested',opts);
			xmlCode.style.display='';
		}
	}
	function saveAttr()	//сохранить данные текущего узла в дереве
	{
		if (yourTree.selected==null) {alert('Выберите узел дерева...'); return;}		
		
		var title=document.getElementById('title').value;
		var link=document.getElementById('link').value;
		var caption=document.getElementById('caption').value;		
		title=title.replace(/\"/g,"”");
		link=link.replace(/\"/g,"”");
		caption=caption.replace(/\"/g,"”");
		
		yourTree.selected.attr("title",title);
		yourTree.selected.attr("href",link);
		yourTree.selected.attr("name",caption);
		yourTree.rename(null,caption);		

		hide_show('editCurBtn','show');
		hide_show('saveCurBtn','hide');
		hide_show('cancelCurBtn','hide');
	}
	function getAttr()	//получить данные текущего узла в дереве
	{
		if (yourTree.selected==null) {alert('Выберите узел дерева...'); return;}	
		//alert(yourTree.selected.type);
		var nodeType=(yourTree.get_type(yourTree.selected));
		
		
		//блокировать кнопки правки элемента
		if (nodeType=='lock')	{
			lock_button('menu_items',true);
			lock_button('node_summary',true);
		}
		else
		{
			lock_button('menu_items',false);
			lock_button('node_summary',false);			
		}
		
		var title=yourTree.selected.attr("title");
		var link=yourTree.selected.attr("href");
		var caption=yourTree.get_text(yourTree.selected);
		
		if (title==null) title='';
		document.getElementById('title').value=title;
		
		if (link==null) link='';
		document.getElementById('link').value=link;
		
		if (caption==null) caption='';
		document.getElementById('caption').value=caption;
		
		hide_show('editCurBtn','hide');
		hide_show('saveCurBtn','show');
		hide_show('cancelCurBtn','show');
	}
	function lock_button(id_name,mode)
	{
		
	    var elem=document.getElementById(id_name);
	    if (elem!=null) {
	       if (mode!=null)
		   switch (mode) {
		   case true :
		      elem.disabled=true;
		      elem.title='выбранный узел не позволяет производить операции';
		      break;
		   case false :
		      elem.disabled=false;
		      elem.title='';
		      break;
		   case '':
		       elem.disabled=!elem.disabled;
		       elem.title='';
		       break;
		   default :
			//alert('ошибка вызова функции show_hide');
		    }
		}
	    else {alert('элемент не найден');}   
	}
	
	function hide_show(id_name,mode)    
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
	    else {alert('элемент не найден');}   
	}
	function cancelAttr()	//отмена сохранения
	{
		document.getElementById('title').value='';
		document.getElementById('link').value='';
		document.getElementById('caption').value='';
		
		hide_show('editCurBtn');
		hide_show('saveCurBtn');
		hide_show('cancelCurBtn');
	}
	function createChild()	//создание подпункта раздела
	{
		var title=document.getElementById('title').value;
		var link=document.getElementById('link').value;
		var caption=document.getElementById('caption').value;
		
		yourTree.create({ 	data : caption,
					icon: '../themes/default/throbber.gif', 
				attributes : {
					"href" : link,
					"title": title,
					"name" : caption
				} }, yourTree.selected, "inside");
		
	}
	function save_tree()
	{
		var xmlDocument = yourTree.get(null,'xml_nested',opts);
		
		$.ajax({
		   type: "POST",
		   url: "save_menu_xml.php",
		   data: 'xml='+xmlDocument,
		   cache: false,
		   dataType: "html",
		   success: function(msg){
		     alert( "Данные сохранены. ");
			 if (msg!='') alert('ошибка:'+msg+'!');
			 
		     //window.location.href="?";	//обновляем страницу
		   }
		 });
		
	}
	function load_tree()	//загрузка дерева из файла-примера
	{
		$("#xml_n").html('');
		$("#xml_n").tree({ 	
			data : { 	
				type : "xml_nested",
				opts : {
					url : "menu_example.xml"					
				}
			} 
		});		
	}	
	</script>
<div style="clear:both;">
<input type=button value=свернуть onclick="yourTree.close_all();"> &nbsp; 
<input type=button value=развернуть onclick="yourTree.open_all();"> 	
</div>
<div class="demo" id="xml_n" style="height:500px;">
</div>

<FIELDSET id=node_summary>
<LEGEND id=node_details>Данные узла</LEGEND>		
		<input type=text id="caption" value="" size=40> наименование* <br>
		<input type=text id="link" value="" size=40> ссылка  <br>
		<input type=text id="title" value="" size=40> комментарий	
</FIELDSET>

<p style="/*clear:both;*/"></p>	
<strong style="padding-left:40px;">Операции</strong><br>

<FIELDSET style="display:none;">
<LEGEND >Раздел</LEGEND>	
	<INPUT type="button" onclick='var t = $.tree.focused(); if(t.selected) t.create(); else alert("Выберите узел-родитель...");' value="Создать"> 
	<INPUT type="button" onclick="$.tree.focused().rename();" value="Переименовать"> 
	<INPUT type="button" onclick="if (confirm('Удалить '+yourTree.get_text(yourTree.selected))) $.tree.focused().remove();" value="Удалить">
</FIELDSET>	
	
<FIELDSET id=menu_items>
<LEGEND id=menu_items_details>Пункт меню</LEGEND>	
	<input id="editCurBtn" type=button value="Изменить" title="Изменить текущий узел" onclick="javascript:getAttr();">
	<input id="saveCurBtn" style="display:none;" type=button value="Сохранить" title="Сохранить текущий узел" onclick="javascript:saveAttr();">
	<input id="cancelCurBtn" style="display:none;" type=button value="Отменить" title="Отменить правку текущего узла" onclick="javascript:cancelAttr();">
	<input id="createBtn" type=button value="Создать" title="создать узел в текущем разделе" onclick="javascript:createChild();">
	<INPUT id="delBtn" type="button" onclick="if ($.tree.focused().selected && confirm('Удалить '+yourTree.get_text(yourTree.selected))) {$.tree.focused().remove();}" value="Удалить">
</FIELDSET>		
	<br>

<FIELDSET>
<LEGEND>Работа со списком</LEGEND>	


<input type=button value=Сохранить onclick="javascript:save_tree();" title="Сохранить дерево">
<input type=button value=Восстановить onclick="javascript:if (confirm('Текущее дерево будет утеряно и загружен образец. Продолжить ?')) load_tree();" title="Восстановить дерево из образца">

<input type=button value=xml onclick="javascript:get_xml();" title="Получить xml-код дерева">	
</FIELDSET>
	<br>
	<textarea id=xmlCode name=xmlCode cols=50 rows=8 style="display:none;"></textarea>
</div>
<div class=text style="clear:both;">
	<strong>Примечание</strong>
	<ul>
		<li>Вы можете визуально перемещать узлы удерживая кнопку мыши при перетаскивании;</li>
		<li>если Вы неудачно сгенерировали дерево, Вы сможете его восстановить из образца;</li>
		<li>для внесения правок в узлы дерева - выберите узел, внесите изменения в окне "Данные узла" и нажмите "Сохранить" раздела "Пункт меню";</li>
		<li>после внесения всех изменений в меню-дерево нажмите "Сохранить" раздела "Работа со списком".</li>
	</ul>
	
</div>
	
</body></html>