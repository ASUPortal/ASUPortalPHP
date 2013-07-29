{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование валидатора</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core/validator/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_core/validator/add.right.tpl"}
{/block}