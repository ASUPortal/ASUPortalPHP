{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление таксономии</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_taxonomy/form.legacy.tpl"}
{/block}

{block name="asu_right"}
    {include file="_taxonomy/legacy.taxonomy.add.right.tpl"}
{/block}