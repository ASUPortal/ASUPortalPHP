{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование расписания</h2>

    {CHtml::helpForCurrentPage()}
    
    {CHtml::warningSummary($schedule)}

	{include file="_schedule/form.tpl"}
	
{/block}

{block name="asu_right"}
	{include file="_schedule/common.right.tpl"}
{/block}