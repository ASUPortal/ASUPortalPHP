{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование таксономии</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_taxonomy/form.legacy.tpl"}
{/block}

{block name="asu_right"}
    {include file="_taxonomy/legacy.taxonomy.edit.right.tpl"}
{/block}