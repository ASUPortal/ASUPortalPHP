{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление оценки</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/brs/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/brs/common.right.tpl"}
{/block}