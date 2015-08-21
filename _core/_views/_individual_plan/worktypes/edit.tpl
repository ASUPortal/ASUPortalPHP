{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование вида работ</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/worktypes/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/worktypes/edit.right.tpl"}
{/block}