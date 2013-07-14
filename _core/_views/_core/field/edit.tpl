{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование поля модели</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core/field/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_core/field/edit.right.tpl"}
{/block}