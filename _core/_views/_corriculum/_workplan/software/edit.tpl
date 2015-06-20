{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование ПО</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/software/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/software/common.right.tpl"}
{/block}