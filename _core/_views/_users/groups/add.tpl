{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление группы пользователей</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_users/groups/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_users/groups/add.right.tpl"}
{/block}