<?php

    // <abarmin date="07.10.2012">
if (!isset($files_path)) {$files_path='';}
    require_once($files_path."core.php");

    $menu_str = '';
    $menu_str .= '<link href="'.WEB_ROOT.'css/_modules/_sdmenu/sdmenu.css" rel="stylesheet" type="text/css">';
    $menu_str .= '<script src="'.WEB_ROOT.'scripts/_modules/_sdmenu/sdmenu.js"></script>';
    $menu_str .= '<script>
                            $(document).ready(function(){
                                if ($("#mainMenu").length) {
                                    myMenu = new SDMenu("mainMenu");
                                    myMenu.oneSmOnly = true;
                                    myMenu.init();
                                }
                            });
                    </script>';

    $menu_str .= '<div id="mainMenu" class="sdmenu">';
    foreach (CMenuManager::getMenu("main_menu")->getMenuPublishedItemsInHierarchy()->getItems() as $item) {
        $menu_str .= '<div class="collapsed">';
        if ($item->getChilds()->getCount() > 0) {
            $menu_str .= '<span class="hasChild" title="Выберите подраздел">'.htmlspecialchars($item->getName()).'</span>';
            foreach ($item->getChilds()->getItems() as $child) {
                $menu_str .= '<a class="hasChild" href="'.htmlspecialchars($child->getLink()).'" title="'.htmlspecialchars($child->getName()).'">'.htmlspecialchars($child->getName()).'</a>';
            }
        } else {
            $menu_str .= '<span class="noChild">';
            $menu_str .= '<a class="noChild" href="'.htmlspecialchars($item->getLink()).'" title="'.htmlspecialchars($item->getName()).'">'.htmlspecialchars($item->getName()).'</a>';
            $menu_str .= '</span>';
        }
        $menu_str .= '</div>';
    }
    $menu_str .= '</div>';
/*

if (!isset($menu_str)) $menu_str='';
if (!isset($files_path)) $files_path='';


$menu_str='
	<link rel="stylesheet" type="text/css" href="'.$web_root.'_modules/left_menu/sdmenu/sdmenu.css" />
	<script type="text/javascript" src="'.$web_root.'_modules/left_menu/sdmenu/sdmenu.js"></script>
	<script type="text/javascript">
	// <![CDATA[
	var myMenu;
	window.onload = function() {
		myMenu = new SDMenu("my_menu");                
        myMenu.oneSmOnly = true;              // One expanded submenu at a time        
		myMenu.init();
	};
	// ]]>
        
	</script>

    <div style="float: left" id="my_menu" class="sdmenu">
';

//$xml_filename='menu.xml';
/*
$xsl_filename='menu.xsl';

$doc = new DOMDocument();
$xsl = new XSLTProcessor();

$doc->load($xsl_filename);
$xsl->importStyleSheet($doc);

$doc->load($xml_filename);
echo $xsl->transformToXML($doc);
*/

/*

include 'parsexml.php';

//  меню зависимых от условия пунктов меню\задач
$tasks_secure=array();
//'caption(заголовок)';  'href(адрес страницы)'  title(подсказка)  'inner_vew(просмотр только внутри ЛВС):0,1'    'authoriz_view(просмотр авторизованным):0,1'
array_push($tasks_secure, array('Сервисы', '', array(
                         array('ТВ-сервер','Просмотр трансляция телевидения','pg_view.php?pg_id=60',1,0),
                         array('OLAP-отчеты','OLAP-отчеты','olap/',1,0),
                         array('ИТПК','Информационно-технологическое пространство кафедры','ftp://10.61.2.62',1,0),
						array('Документооборот','Система электронного документооборота','http://10.61.2.64/Referent/br_switch.nsf',1,0),
						array('Отслеживания ошибок','Система отслеживания ошибок Портала АСУ','/mantisbt',1,0)
                         )
                         ,1,0)
           );
array_push($tasks_secure, 
                         array('СДО Moodle','система дистанционного обучения Moodle','http://moodle.ugatu.su',0,1)                         
                         );

    //формирование подраздела главного левого меню портала
    for ($i=0;$i<count($tasks_secure);$i++)
    {

    if ((stristr($_SERVER['SERVER_NAME'],$inner_url_name) && $tasks_secure[$i][3]!=0) ||
        (isset($_SESSION['auth']) &&  $_SESSION['auth']==1 && $tasks_secure[$i][4]!=0)   ) 
    {    
        $menu_str.= "<div class=collapsed>\n";
        
        $childClass='noChild';
        if (is_array($tasks_secure[$i][2])) {//элемент в виде массива-группы
            $childClass='hasChild';
            $menu_str.= "<span class=\"$childClass\" title=\"{$tasks_secure[$i][1]}\">{$tasks_secure[$i][0]}</span>\n";
                $subItems=$tasks_secure[$i][2];
                // вывод потомков
                for ($j=0;$j<count($subItems);$j++)
                {                    
                    if ((stristr($_SERVER['SERVER_NAME'],$inner_url_name) && $subItems[$j][3]!=0) ||
                        (isset($_SESSION['auth']) &&  $_SESSION['auth']==1 && $subItems[$j][4]!=0)   )
                    $menu_str.="\t<a class=\"$childClass\" href=\"$files_path{$subItems[$j][2]}\" title=\"{$subItems[$j][1]}\">{$subItems[$j][0]}</a>\n";
                }
        }
        else {
            $menu_str.= "<span class=\"$childClass\">\n
                \t<a class=\"$childClass\" href=\"$files_path{$tasks_secure[$i][2]}\" title=\"{$tasks_secure[$i][1]}\">{$tasks_secure[$i][0]}</a>\n
                </span>\n";
        }    
        $menu_str.="</div>\n";
    }   
    }

/*
//в доступе только для внутр.портала
if (stristr($_SERVER['SERVER_NAME'],$inner_url_name))
$menu_str.="
	<div class=collapsed>
        <span class=hasChild title=\"выберите подраздел\">Сервисы</span>
		<a class=hasChild href=\"pg_view.php?pg_id=60\" title=\"Просмотр трансляция телевидения\">ТВ-сервер</a>
		<a class=hasChild href=\"olap/index.php\" title=\"\">OLAP-отчеты</a>
		<a class=hasChild href=\"ftp://10.61.2.63\" title=\"Информационно-технологическое пространство кафедры\">ИТПК</a>
		<a class=hasChild href=\"http://10.61.2.64/Referent/br_switch.nsf\" title=\"Система электронного документооборота\">Документооборот</a>
		<a class=hasChild href=\"http://10.61.2.63/mantisbt\" title=\"Система отслеживания ошибок Портала АСУ\">Отслеживание ошибок</a>

        </span>
	</div>";


//формиривание ссылки на СДО
if (isset($_SESSION['auth']) &&  $_SESSION['auth']==1) {
$menu_str.="<div class=collapsed><span class=noChild><a class=noChild href=\"/moodle/\" title=\"система дистанционного обучения Moodle\">СДО Moodle</a></span></div>";
}
*/
/*
$menu_str.="</div>";
*/
?>