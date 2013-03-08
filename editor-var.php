<?php
$edit_table='
<script language="javascript" type="text/javascript">
<!--
// Define the bbCode tags
bbcode = new Array();
bblast = new Array();
bbtags = new Array(\'[b]\',\'[/b]\',\'[i]\',\'[/i]\',\'[u]\',\'[/u]\',\'[quote]\',\'[/quote]\',\'[code]\',\'[/code]\',\'[img]\',\'[/img]\',\'[url]\',\'[/url]\');
imageTag = false;
//-->
</script>
<script language="javascript" type="text/javascript" src="scripts/editor_func.js"></script>



<table cellspacing="0" cellpadding="0" border="0" align="">
  <tr align="center" valign="middle">
    <td>
<img src="images/editor/bold.gif"  title="Жирный/Bold" accesskey="b" name="addbbcode0" value="B" style="font-weight:bold;" onclick="bbstyle(document.forms[\'form1\'].elements[\'file\'], 0, \'form1.file\')"/>
<img src="images/editor/italic.gif" title="Наклонный/Italic"  accesskey="i" name="addbbcode2" value="i" style="font-style:italic;" onclick="bbstyle(document.forms[\'form1\'].elements[\'file\'], 2, \'form1.file\')" />
<img src="images/editor/underline.gif" title="Подчёркнутый/Underline" accesskey="u" name="addbbcode4" value="u" style="text-decoration: underline;" onclick="bbstyle(document.forms[\'form1\'].elements[\'file\'], 4, \'form1.file\')" />
<img src="images/editor/quote.gif" title="Цитата/Quote" accesskey="q" name="addbbcode6" value="Цитата" onclick="bbstyle(document.forms[\'form1\'].elements[\'file\'], 6, \'form1.file\')" />
<img src="images/editor/quote.gif" title="Цицитировать выделенное/Quote selection" accesskey="Q" name="addbbcode6" onmouseover=\'checkselection()\' value="Цитировать выделенное" onclick="addquote(document.forms[\'form1\'].elements[\'file\'])" />
<img src="images/editor/createlink.gif" title="Ссылка/URL" accesskey="w" name="addbbcode12" value="URL" style="text-decoration: underline;" onclick="bbstyle(document.forms[\'form1\'].elements[\'file\'], 12, \'form1.file\')" />
<img src="images/editor/redo.gif" title="Закрыть все теги/Close tags" onclick="javascript:bbstyle(document.forms[\'form1\'].elements[\'file\'], -1, \'form1.file\')">закрыть все теги
	</td></tr></table>';
?>