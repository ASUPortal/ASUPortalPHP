{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление оценочных материалов</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/evaluationMaterials/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/evaluationMaterials/common.right.tpl"}
{/block}