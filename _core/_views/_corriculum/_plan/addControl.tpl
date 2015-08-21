{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление формы контроля в дисциплину {$discipline->discipline->name}</h2>
{CHtml::helpForCurrentPage()}

{include file="_corriculum/_plan/form.control.tpl"}
{/block}

{block name="asu_right"}
{include file="_corriculum/_plan/addLabor.right.tpl"}
{/block}