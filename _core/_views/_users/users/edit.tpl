{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование пользователя</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_users/users/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_users/users/edit.right.tpl"}
{/block}