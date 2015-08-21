{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование вопроса</h2>
    {CHtml::helpForCurrentPage()}

    {include file="_question_answ/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_question_answ/edit.right.tpl"}
{/block}