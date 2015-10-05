{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование оценочных материалов</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/estimatedMaterials/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/estimatedMaterials/common.right.tpl"}
{/block}