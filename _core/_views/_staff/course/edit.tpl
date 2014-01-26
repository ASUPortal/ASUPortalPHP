{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование курса повышения квалификации</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_staff/course/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_staff/course/edit.right.tpl"}
{/block}