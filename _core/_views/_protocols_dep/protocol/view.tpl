{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h3><div align=center>Протокол № {$protocol->num} от {$protocol->date_text}</div></h3><br>
	
	<h4><div align=center><b>ПОВЕСТКА ДНЯ</b></div></h4>
	
	<br>{str_replace("\r\n", "<br>", $protocol->program_content)}<br>
	
	<h4><div align=center>Пункты повестки</div></h4>
    {include file="_protocols_dep/protocol/subform.points.tpl"}
    
    <h4><div align=center>Посещаемость</div></h4>
    {include file="_protocols_dep/protocol/subform.visit.tpl"}
{/block}

{block name="asu_right"}
    {include file="_protocols_dep/protocol/common.right.tpl"}
{/block}