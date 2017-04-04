{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование протокола кафедры</h2>
	{CHtml::helpForCurrentPage()}

    <div class="tab-content">
    	{include file="_protocols_dep/protocol/form.tpl"}
    	
    	<h3>Посещаемость</h3>
    	{CHtml::activeComponent("visit.php?protocol_id={$protocol->getId()}", $protocol)}
    	
    	<h3>Пункты повестки</h3>
    	{CHtml::activeComponent("point.php?protocol_id={$protocol->getId()}", $protocol)}
    	 
    </div>
{/block}

{block name="asu_right"}
	{include file="_protocols_dep/protocol/common.right.tpl"}
{/block}