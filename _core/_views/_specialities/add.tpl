{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление специальности</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_specialities/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_specialities/add.right.tpl"}
{/block}