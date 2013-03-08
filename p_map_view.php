<?php
$files_path='';

//include $files_path."sql_connect.php";
include $files_path.'sql_connect.php';
//include $files_path."authorisation.php";
//include $files_path."master_page_short.php";
$pg_title='Карта сайта';
include $files_path.'header.php';

if (!isset($_GET['wap'])) { if (!isset($_GET['id']))	{echo $head;} }
else { echo $head_wap;}

$filename=$files_path.'_modules/left_menu/menu.xml';
$xml_str=file_get_contents($filename);

//отсекаем служебные аттрибуты структуры
$xml_str=preg_replace('/^.*<root>(.*)<\/root>/i','$1',$xml_str);
$xml_str=iconv("utf-8", "windows-1251", $xml_str);
$xml_str=addslashes($xml_str);

    //формирование подраздела карты сайта с учетом подзадач файла /index.php, группу меню Сервисы+Моодле
    $menu_str='';    
    for ($i=0;$i<count($tasks_secure);$i++)
    {
    if ((stristr($_SERVER['SERVER_NAME'],$inner_url_name) && $tasks_secure[$i][3]!=0) ||
        (isset($_SESSION['auth']) &&  $_SESSION['auth']==1 && $tasks_secure[$i][4]!=0)   ) 
    {    
        if (is_array($tasks_secure[$i][2])) {//элемент в виде массива-группы            
            $menu_str.= "<item name=\"{$tasks_secure[$i][0]}\" title=\"{$tasks_secure[$i][1]}\">";
	    $menu_str.= "<content><name><![CDATA[{$tasks_secure[$i][0]}]]></name></content>";
                $subItems=$tasks_secure[$i][2];
                // вывод потомков
                for ($j=0;$j<count($subItems);$j++)
                {                    
                    if ((stristr($_SERVER['SERVER_NAME'],$inner_url_name) && $subItems[$j][3]!=0) ||
                        (isset($_SESSION['auth']) &&  $_SESSION['auth']==1 && $subItems[$j][4]!=0)   )
                    {
		$menu_str.="<item name=\"{$subItems[$j][0]}\" href=\"{$subItems[$j][2]}\" title=\"{$subItems[$j][1]}\">";
		$menu_str.="<content><name><![CDATA[{$subItems[$j][0]}]]></name></content></item>";
		    }
                }
        }
        else {
		$menu_str.="<item name=\"{$tasks_secure[$i][0]}\" href=\"{$tasks_secure[$i][2]}\" title=\"{$tasks_secure[$i][1]}\">";
		$menu_str.="<content><name><![CDATA[{$tasks_secure[$i][0]}]]></name></content>";
        }
        
        $menu_str.="</item>";
    }   
    }    
//echo '<textarea cols=60 rows=8>'.addslashes($menu_str).'</textarea>';
$xml_str.=addslashes($menu_str);

//----------------------------------------------
    //списки задач пользователя после авторизации
	function getArrIndex($sVal,$sArr,$sArrColumn)	// поиск вхождения в многомерном массиве
	{
	    $find=false;
	    if ($sVal!=='' && is_array($sArr) && isset($sArrColumn))
	    {
		for ($i=0;$i<count($sArr);$i++)
		{
		    if ($sArr[$i][$sArrColumn]==$sVal) {$find=true;  break;    }            
		}
	    }
	    else return 'err';
	    
	    if ($find) return $i;
	    else return -1;    
	}
	function rightArrStr($val)	//подготовка массива перед выводом в меню
	{
	    return str_replace('"','\'',$val);    
	}
	$menu_str='';  
	$query_tasks_users='';
	if (isset($_SESSION['id']) && intval($_SESSION['id'])>0 && $_SESSION['group_blocked']!=1) {
		$query_tasks_users="SELECT distinct t.name as pg_name,t.comment, t.url, t.menu_name_id, t.id 
			FROM task_in_group tig inner join tasks t on t.id=tig.task_id 
			WHERE tig.user_group_id in (
			  SELECT group_id
				FROM user_in_group
				WHERE user_id ='".$_SESSION['id']."') and hidden=0 ";

		//введение персональных задач пользователя
		$query_tasks_users.="union 
			SELECT distinct t.name as pg_name, t.comment, t.url, t.menu_name_id, t.id 
			FROM task_in_user tiu inner join tasks t on t.id=tiu.task_id 
			WHERE user_id ='".$_SESSION['id']." and hidden=0' 
			order by 1";
	
	$tasks_users=getRowSqlVar($query_tasks_users);
	$tasks_users=array_map('rightArrStr',$tasks_users);
	//print_r($tasks_users);
	
    //списки групп задач пользователя после авторизации
		$query_tasks_gr_users="SELECT distinct  tmn.name, tmn.comment, tmn.id
		FROM task_menu_names tmn where tmn.id in (
			select t.menu_name_id	 
			FROM task_in_group tig inner join tasks t on t.id=tig.task_id 
			WHERE tig.user_group_id in (
			  SELECT group_id
				FROM user_in_group
				WHERE user_id ='".$_SESSION['id']."')
			) or 
		    tmn.id in (
			select t.menu_name_id 
			FROM task_in_user tiu inner join tasks t on t.id=tiu.task_id 
			WHERE user_id ='".$_SESSION['id']."'
			)
		    union select 'прочие','',0 
		order by 1 DESC";
	
	$tasks_gr_users=getRowSqlVar($query_tasks_gr_users);
	$tasks_gr_users=array_map('rightArrStr',$tasks_gr_users);
	//echo '<hr>';
	//print_r($tasks_gr_users);
    //формирование подраздела карты сайта с учетом подзадач файла /index.php-----------------------------------------
	
	
	  	//подготовка переменной	 для хранения нового меню
	for ($i=0;$i<count($tasks_gr_users);$i++)
	{
	    //{//элемент в виде массива-группы            
		$menu_str.= "<item id=\"tgu{$tasks_gr_users[$i]['id']}\" name=\"{$tasks_gr_users[$i]['name']}\" title=\"{$tasks_gr_users[$i]['comment']}\">";
		$menu_str.= "<content><name><![CDATA[{$tasks_gr_users[$i]['name']}]]></name></content>";
		    // вывод потомков- задачи в пункте меню
		    for ($j=0;$j<count($tasks_users);$j++)
		    {                  
			if ($tasks_users[$j]['menu_name_id']==$tasks_gr_users[$i]['id'])
			{
			$menu_str.="<item id=\"tu{$tasks_users[$j]['id']}\" name=\"{$tasks_users[$j]['pg_name']}\" href=\"{$tasks_users[$j]['url']}\" title=\"{$tasks_users[$j]['comment']}\">";
			$menu_str.="<content><name><![CDATA[{$tasks_users[$j]['pg_name']}]]></name></content></item>";
			}
		    }
	    $menu_str.="</item>";	   
	}
	
    }
    //$xml_str="<item name=\"Администрация\" title=\"{$tasks_gr_users[$i]['comment']}\">".$xml_str."</item>"
    $xml_str.=addslashes($menu_str);
    
    //echo '<textarea cols=60 rows=8>'.addslashes($menu_str).'</textarea>';
    //print_r($tasks_users);
    //echo $query_tasks_users;// '$tasks_users='.$tasks_users;
//------------------------------------------------    
?>

	<script type="text/javascript" src="_modules/jstree/lib/jquery.js"></script>
	<script type="text/javascript" src="_modules/jstree/lib/sarissa.js"></script>
	<script type="text/javascript" src="_modules/jstree/jquery.tree.js"></script>
	<script type="text/javascript" src="_modules/jstree/plugins/jquery.tree.xml_nested.js"></script>

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
	<h1 class="title"><?php echo $pg_title; ?>
	<?php if (isset($_SESSION['auth']) &&  $_SESSION['auth']==1) {
		?>
		<span class=text style="padding-left:40px;">изменить меню
			<a class=button href="<?php echo $files_path;?>_modules/left_menu/admin_menu.php" title="изменить меню для неавторизованных пользователей" >Главное</a> &nbsp; &nbsp; 
			<a class=button href="<?php echo $files_path;?>user_access.php" title="изменить меню для авторизованных пользователей через настройку прав к задачам">Администрация</a>
		</span>
		<?php 
	}
	?></h1>

	<p>Здесь Вы можете ознакомиться с картой навигации.</p>
	    <a class=text href="#closeall" onclick="yourTree.close_all();" title="свернуть все узлы карты портала">скрыть все</a> &nbsp;
	    <a class=text href="#openall" onclick="yourTree.open_all();" title="развернуть все узлы карты портала">показать все</a>
	
	<script type="text/javascript" class="source">
	var yourTree= jQuery.tree.create();
	var files_path='<?php echo $files_path ?>';
	var opts = {}; 
	opts.outer_attrib = ["id", "rel", "class", "title", "href", "name"];	

	$(function () { 		
		yourTree.init("#xml_n",{
			data : { 
				type : "xml_nested",
				opts : { 
				static : '<root><?php echo $xml_str; ?></root>'
				}
			}
, 
       callback      : { 
            onselect    : function(NODE,TREE_OBJ) {
		var link=TREE_OBJ.selected.attr("href");
		if (link!=null && link!='')
		    {
		    link=link.toLowerCase();
		    if (link.indexOf('http://')==-1 && link.indexOf('ftp://')==-1)
			document.location.href=files_path+link;
		    else
			document.location.href=link;
		    }
	    },
	    onload	: function(TREE_OBJ) {
		$.tree.focused().open_all();
		
		//$.tree.focused().select_branch('#adm_group')
		
		<?php if (isset($_SESSION['auth']) &&  $_SESSION['auth']==1) { ?>
		//перемещение групп меню в раздел Администрация
		$.tree.focused().cut('#tgu3');
		$.tree.focused().paste('#adm_group', 'inside');

		$.tree.focused().cut('#tgu2');
		$.tree.focused().paste('#adm_group', 'inside');

		$.tree.focused().cut('#tgu1');
		$.tree.focused().paste('#adm_group', 'inside');

		$.tree.focused().cut('#tgu0');
		$.tree.focused().paste('#adm_group', 'inside');		
		<?php }?>
		//$.tree.focused().attr("link",'');
		//$('#adm_group').attr("link",'');
		}
	    //onselect    : function(NODE,TREE_OBJ) { getAttr(); }
	    },
                types : { 
                        // all node types inherit the "default" node type 
                        "default" : { 
                                deletable : false, 
                                draggable : false, 
                                renameable : false 
                        }, 
                        "root" : { 
                                draggable : false, 
                                renameable : false, 
                                valid_children : [ "folder" ], 
                                icon : { 
                                        image : "images/drive.png" 
                                } 
                        } 
                },
		ui : { 
		theme_name : "apple"
		}
		})
	});
	
	</script>
<div style="clear:both;">
	<!--input type=button value=свернуть onclick="yourTree.close_all();"> &nbsp; 
	<input type=button value=развернуть onclick="yourTree.open_all();"--> 	
</div>

<div class="demo" id="xml_n" style="height:100%; width:100%; border:none; "></div>



<div class=text style="clear:both;">
	<strong>Примечание</strong>
	<ul>
		<li>свернуть и развернуть узел, Вы можете выбрав стрелку рядом с наименованием узла;</li>
		<li>если узел включает переход по ссылке, кликните узел по наименованию;</li>
		<li>после авторизации список узла "Администрация" будет дополнен доступными задачами;</li>
		<li>Вы можете одновременно свернуть и развернуть все узлы карты, выбрав необходимые ссылки в верхней части карты.</li>
	</ul>
	
</div>
	
<?php
if (!isset($_GET['wap'])) {
  echo $end1;
  include $files_path."display_voting.php";
  
}
echo $end2;
?>
<?php include('footer.php'); ?>