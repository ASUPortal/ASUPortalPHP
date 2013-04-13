{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование группы пользователей</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_users/groups/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_users/groups/edit.right.tpl"}
{/block}