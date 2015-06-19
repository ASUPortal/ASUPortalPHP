{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование оценочного средства</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/markTypes/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/markTypes/common.right.tpl"}
{/block}