{extends file="_core.3col.tpl"}

{block name="asu_center"}

<h2>Расписание занятий общее, {$yearPart->getValue()} семестр {$year->getValue()} года</h2><br>

	{include file="__public/_schedule/subform.showSchedules.tpl"}
	
	{include file="__public/_schedule/viewAll.form.tpl"}
{/block}

{block name="asu_right"}
	{include file="__public/_schedule/common.right.tpl"}
{/block}