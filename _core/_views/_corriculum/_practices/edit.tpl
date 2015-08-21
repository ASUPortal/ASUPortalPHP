{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование практики</h2>
{CHtml::helpForCurrentPage()}
{include file="_corriculum/_practices/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_corriculum/_practices/edit.right.tpl"}
{/block}