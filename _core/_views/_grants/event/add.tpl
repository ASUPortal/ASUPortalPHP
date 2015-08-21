{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление мероприятия</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_grants/event/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_grants/event/add.right.tpl"}
{/block}