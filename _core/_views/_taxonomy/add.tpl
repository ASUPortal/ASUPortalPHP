{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление термина таксономии</h2>
{CHtml::helpForCurrentPage()}

    {include file="_taxonomy/form.term.tpl"}
{/block}

{block name="asu_right"}
{include file="_taxonomy/add.right.tpl"}
{/block}