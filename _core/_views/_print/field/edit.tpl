{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование описателя поля</h2>
{CHtml::helpForCurrentPage()}

    {include file="_print/field/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_print/field/edit.right.tpl"}
{/block}