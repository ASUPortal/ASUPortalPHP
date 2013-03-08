<?php
//error_reporting(E_ERROR);
//$menu_xml_f_name='_modules/xml_menu111.xml';

//---------------------------
if (!isset($menu_xml_f_name)) $file = "_modules/left_menu/menu.xml";
else $file=$menu_xml_f_name;

$file=$files_path.$file;
$depth = array();
//$group_delimiter=1;


function startElement($parser, $name, $attrs) 
{
    global $depth,$files_path;
	global $menu_str;
	$out='';
    if (!isset($depth[$parser])) $depth[$parser]=0;
    if (count($attrs)) {
        if (!isset($attrs['HREF'])) $attrs['HREF']='';
	if (!isset($attrs['TITLE'])) $attrs['TITLE']='';
	
	if ($depth[$parser]==1) {
			$out.="\t<div class=collapsed>\n\t";
			if ($attrs['HREF']!='') {				
				$out.="\t<span class=noChild><a class=noChild href=\"$files_path{$attrs['HREF']}\" title=\"{$attrs['TITLE']}\">{$attrs['NAME']}</a></span>";}
			else 
				{$out.="<span class=hasChild title=\"{$attrs['TITLE']}\">{$attrs['NAME']}</span>";}
			$out.="\n"; }    //$name
        else
            {if ($depth[$parser]==2) $out.="\t\t<a class=hasChild href=\"$files_path{$attrs['HREF']}\" title=\"{$attrs['TITLE']}\">{$attrs['NAME']}</a>\n";     }
    }       
    $depth[$parser]++;
	$out=iconv("utf-8", "windows-1251", $out);
	$menu_str.=$out;
}

function endElement($parser, $name) 
{
    global $depth;
	global $menu_str;
    
    $depth[$parser]--;
    if ($depth[$parser]==1) $menu_str.="\t</div>\n";//echo "\t</div>\n";    
}

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
if (!($fp = fopen($file, "r"))) {
    $menu_str.="could not open XML input";
}
//xml_set_character_data_handler($xml_parser,"US-ASCII");

while ($data = fread($fp, 4096)) {
    if (!xml_parse($xml_parser, $data, feof($fp))) {
        $menu_str.="<span class=warning>\nошибка XML-пункта меню ".
			xml_error_string(xml_get_error_code($xml_parser)).
			" at line ".
			xml_get_current_line_number($xml_parser).
			"</span>";
    }
}
xml_parser_free($xml_parser);
?>