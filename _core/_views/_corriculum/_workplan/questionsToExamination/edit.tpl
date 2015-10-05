{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование вопроса к экзамену (зачёту)</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/questionsToExamination/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/questionsToExamination/common.right.tpl"}
{/block}