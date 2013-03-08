{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление вопроса</h2>

    {include file="_state_exam/_questions/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_state_exam/_questions/add.right.tpl"}
{/block}