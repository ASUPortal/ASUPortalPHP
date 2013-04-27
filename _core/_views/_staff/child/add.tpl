{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление ребенка</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_staff/child/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_staff/child/add.right.tpl"}
{/block}