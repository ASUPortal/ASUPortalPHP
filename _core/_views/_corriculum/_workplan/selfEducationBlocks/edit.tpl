{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование вопроса для самостоятельного изучения</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_workplan/selfEducationBlocks/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/selfEducationBlocks/common.right.tpl"}
{/block}