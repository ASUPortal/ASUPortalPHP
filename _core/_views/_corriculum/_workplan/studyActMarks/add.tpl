{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление описания и количества баллов за учебную деятельность</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/studyActMarks/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/studyActMarks/common.right.tpl"}
{/block}