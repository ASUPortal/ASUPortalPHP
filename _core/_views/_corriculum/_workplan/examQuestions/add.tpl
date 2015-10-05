{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление вопроса</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/examQuestions/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/examQuestions/common.right.tpl"}
{/block}