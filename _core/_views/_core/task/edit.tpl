{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование задачи модели</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core/task//form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_core/task//edit.right.tpl"}
{/block}