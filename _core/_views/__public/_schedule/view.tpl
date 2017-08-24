{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Расписание занятий, {$yearPart->getValue()} семестр {$year->getValue()} года{if (!is_null($name))}, 
	{if ($nameInCell == "lecturer")}
		<a href="{$web_root}_modules/_student_groups/public.php?action=view&id={$name->getId()}" title="Об учебной группе" style="color:#000000;">
	{elseif ($nameInCell == "studentGroup")}
		<a href="{$web_root}_modules/_lecturers/index.php?action=view&id={$name->getId()}" title="О преподавателе" style="color:#000000;">
	{/if}
	{$name->getName()}</a>
{/if}</h2><br>

	{include file="__public/_schedule/subform.showSchedules.tpl"}
	
	{include file="__public/_schedule/view.form.tpl"}
{/block}

{block name="asu_right"}
	{include file="__public/_schedule/common.right.tpl"}
{/block}