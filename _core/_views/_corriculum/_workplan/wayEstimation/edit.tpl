{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование способа оценивания</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/wayEstimation/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/wayEstimation/common.right.tpl"}
{/block}