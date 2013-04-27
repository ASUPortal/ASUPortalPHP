{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование ребенка</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_staff/child/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_staff/child/edit.right.tpl"}
{/block}