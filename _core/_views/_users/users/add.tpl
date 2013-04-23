{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление пользователя</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_users/users/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_users/users/add.right.tpl"}
{/block}