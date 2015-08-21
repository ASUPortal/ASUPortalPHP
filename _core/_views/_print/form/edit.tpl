{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование шаблона документа</h2>
{CHtml::helpForCurrentPage()}

    {include file="_print/form/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_print/form/edit.right.tpl"}
{/block}