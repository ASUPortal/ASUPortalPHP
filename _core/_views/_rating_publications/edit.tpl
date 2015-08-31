{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование типа издания</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_rating_publications/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_rating_publications/edit.right.tpl"}
{/block}