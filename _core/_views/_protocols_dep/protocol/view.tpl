{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Данные о протоколе для печати</h2>
    {CHtml::helpForCurrentPage()}
    
	<h3><div align=center><b>Протокол № {$protocol->num} от {$protocol->date_text}</b></div></h3><br>
	
	<h4><div align=center><b>ПОВЕСТКА ДНЯ</b></div></h4>
	
	<br><pre><big>{$protocol->program_content}</big></pre><br>
	
    {include file="_protocols_dep/protocol/subform.points.tpl"}
    {include file="_protocols_dep/protocol/subform.visit.tpl"}
{/block}

{block name="asu_right"}
    {include file="_protocols_dep/protocol/common.right.tpl"}
{/block}