{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление билета</h2>

    {include file="_state_exam/_tickets/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_state_exam/_tickets/add.right.tpl"}
{/block}