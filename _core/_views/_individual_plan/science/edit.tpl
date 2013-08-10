{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование научно-исследовательской работы</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/science/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/science/edit.right.tpl"}
{/block}