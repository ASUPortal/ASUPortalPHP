{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление задачи</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_users/tasks/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_users/tasks/add.right.tpl"}
{/block}