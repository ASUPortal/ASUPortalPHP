{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление вида работ</h2>

    {include file="_individual_plan/worktypes/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/worktypes/add.right.tpl"}
{/block}