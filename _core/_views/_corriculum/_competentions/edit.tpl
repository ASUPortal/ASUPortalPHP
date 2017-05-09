{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование компетенции</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_corriculum/_competentions/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_competentions/common.right.tpl"}
{/block}